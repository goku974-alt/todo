<?php
/**
 * mistral_client.php - Client API Mistral AI
 * 
 * Classe d'interconnexion cURL avec l'API Mistral (Free Tier)
 * Inclut le protocole système éthique et la gestion des erreurs
 * 
 * Compatible Hostinger Mutualisé - PHP 8.3
 * Utilise exclusivement cURL (pas de file_get_contents pour HTTP)
 */

require_once __DIR__ . '/config.php';

class MistralClient {
    
    private $apiKey;
    private $model;
    private $endpoint;
    private $timeout;
    private $systemPrompt;
    
    /**
     * Constructeur du client Mistral
     * 
     * @param string|null $apiKey Clé API (utilise la rotation si null)
     * @param string|null $model Modèle à utiliser (défaut: DEFAULT_MISTRAL_MODEL)
     */
    public function __construct($apiKey = null, $model = null) {
        // Rotation automatique des clés API
        $this->apiKey = $apiKey ?? getRotatedApiKey();
        $this->model = $model ?? DEFAULT_MISTRAL_MODEL;
        $this->endpoint = MISTRAL_API_ENDPOINT;
        $this->timeout = API_TIMEOUT;
        
        global $ETHIC_CORE_PROMPT;
        $this->systemPrompt = $ETHIC_CORE_PROMPT;
    }
    
    /**
     * Appel principal à l'API Mistral
     * 
     * @param string $userPrompt Le prompt de l'utilisateur
     * @param float $temperature Température de créativité (0-1)
     * @param int $maxTokens Nombre maximum de tokens en réponse
     * @return array Réponse structurée avec contenu et métadonnées
     */
    public function call($userPrompt, $temperature = 0.7, $maxTokens = 2048) {
        // Structure des messages obligatoire selon spec
        $messages = [
            [
                'role' => 'system',
                'content' => $this->systemPrompt
            ],
            [
                'role' => 'user',
                'content' => $userPrompt
            ]
        ];
        
        // Payload JSON pour l'API
        $payload = json_encode([
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
            'top_p' => 1,
            'stream' => false
        ]);
        
        // Initialisation cURL avec configuration robuste pour Hostinger
        $ch = curl_init($this->endpoint);
        
        // Headers requis par Mistral API
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
            'User-Agent: EthicalPlatform/1.0 (PHP/' . PHP_VERSION . ')'
        ];
        
        // Options cURL optimisées
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; EthicalPlatform/1.0; PHP/' . PHP_VERSION . ')'
        ]);
        
        // Exécution de la requête
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        
        curl_close($ch);
        
        // Gestion des erreurs
        if ($errno !== 0) {
            error_log("cURL Error #$errno: $error");
            return [
                'success' => false,
                'error' => "Erreur de connexion: " . $error,
                'http_code' => $httpCode,
                'content' => null,
                'usage' => null
            ];
        }
        
        if ($httpCode !== 200) {
            error_log("Mistral API HTTP Error: $httpCode - Response: $response");
            
            // Décoder l'erreur de l'API
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['message'] ?? 'Erreur inconnue de l\'API Mistral';
            
            // En cas d'erreur 401/403, essayer une autre clé (rotation de secours)
            if (in_array($httpCode, [401, 403])) {
                return [
                    'success' => false,
                    'error' => "Clé API invalide ou expirée: $errorMessage",
                    'http_code' => $httpCode,
                    'content' => null,
                    'usage' => null,
                    'retry_with_rotation' => true
                ];
            }
            
            return [
                'success' => false,
                'error' => "Erreur API (HTTP $httpCode): $errorMessage",
                'http_code' => $httpCode,
                'content' => null,
                'usage' => null
            ];
        }
        
        // Parsing de la réponse
        $result = json_decode($response, true);
        
        if (!isset($result['choices']) || empty($result['choices'])) {
            return [
                'success' => false,
                'error' => 'Réponse invalide de l\'API Mistral',
                'http_code' => $httpCode,
                'content' => null,
                'usage' => null
            ];
        }
        
        // Extraction du contenu et des métadonnées
        $content = $result['choices'][0]['message']['content'] ?? '';
        $usage = $result['usage'] ?? null;
        
        return [
            'success' => true,
            'error' => null,
            'http_code' => $httpCode,
            'content' => $content,
            'usage' => $usage,
            'model' => $result['model'] ?? $this->model,
            'created' => $result['created'] ?? time()
        ];
    }
    
    /**
     * Appel avec prompt personnalisé (sans le system prompt éthique)
     * Utile pour certaines fonctionnalités spécifiques
     * 
     * @param array $messages Tableau de messages au format OpenAI/Mistral
     * @param float $temperature Température de créativité
     * @param int $maxTokens Nombre maximum de tokens
     * @return array Réponse structurée
     */
    public function callWithMessages($messages, $temperature = 0.7, $maxTokens = 2048) {
        $payload = json_encode([
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
            'top_p' => 1,
            'stream' => false
        ]);
        
        $ch = curl_init($this->endpoint);
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
            'User-Agent: EthicalPlatform/1.0 (PHP/' . PHP_VERSION . ')'
        ];
        
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; EthicalPlatform/1.0; PHP/' . PHP_VERSION . ')'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        
        curl_close($ch);
        
        if ($errno !== 0 || $httpCode !== 200) {
            return [
                'success' => false,
                'error' => "Erreur: " . ($error ?: "HTTP $httpCode"),
                'content' => null
            ];
        }
        
        $result = json_decode($response, true);
        
        return [
            'success' => true,
            'content' => $result['choices'][0]['message']['content'] ?? '',
            'usage' => $result['usage'] ?? null
        ];
    }
    
    /**
     * Test de connectivité à l'API
     * 
     * @return bool True si la connexion fonctionne
     */
    public function testConnection() {
        $result = $this->call("Réponds simplement 'OK' si tu es connecté.", 0.1, 10);
        return $result['success'] && stripos($result['content'], 'ok') !== false;
    }
    
    /**
     * Getter pour le modèle actuel
     */
    public function getModel() {
        return $this->model;
    }
    
    /**
     * Setter pour changer de modèle dynamiquement
     */
    public function setModel($model) {
        $this->model = $model;
    }
}

/**
 * Fonction helper pour un appel rapide à l'API
 * 
 * @param string $prompt Le prompt utilisateur
 * @param string|null $apiKey Clé API spécifique (optionnel)
 * @return string La réponse de l'IA ou message d'erreur
 */
function askMistral($prompt, $apiKey = null) {
    $client = new MistralClient($apiKey);
    $result = $client->call($prompt);
    
    if ($result['success']) {
        return $result['content'];
    } else {
        return "❌ Erreur: " . $result['error'];
    }
}

/**
 * Fonction pour appels parallèles multiples (multi-keys)
 * Permet de lancer plusieurs requêtes simultanément
 * 
 * @param array $prompts Tableau de prompts à envoyer
 * @param int $keyCount Nombre de clés à utiliser en parallèle
 * @return array Résultats de toutes les requêtes
 */
function parallelMistralCalls($prompts, $keyCount = 2) {
    $keys = getMultipleApiKeys(null, $keyCount);
    $results = [];
    
    // Utilisation de curl_multi pour le parallélisme
    $mh = curl_multi_init();
    $curlHandles = [];
    
    foreach ($prompts as $index => $prompt) {
        $apiKey = $keys[$index % count($keys)];
        $messages = [
            ['role' => 'system', 'content' => $GLOBALS['ETHIC_CORE_PROMPT']],
            ['role' => 'user', 'content' => $prompt]
        ];
        
        $payload = json_encode([
            'model' => DEFAULT_MISTRAL_MODEL,
            'messages' => $messages,
            'temperature' => 0.7,
            'max_tokens' => 2048
        ]);
        
        $ch = curl_init(MISTRAL_API_ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ],
            CURLOPT_TIMEOUT => API_TIMEOUT,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; EthicalPlatform/1.0)'
        ]);
        
        curl_multi_add_handle($mh, $ch);
        $curlHandles[$index] = $ch;
    }
    
    // Exécution parallèle
    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);
    
    // Récupération des résultats
    foreach ($curlHandles as $index => $ch) {
        $response = curl_multi_getcontent($ch);
        $result = json_decode($response, true);
        
        $results[$index] = [
            'success' => isset($result['choices']),
            'content' => $result['choices'][0]['message']['content'] ?? null,
            'error' => isset($result['error']) ? $result['error']['message'] : null
        ];
        
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    
    curl_multi_close($mh);
    
    return $results;
}
