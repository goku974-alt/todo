<?php
/**
 * config.php - Configuration de la Plateforme Éthique Mistral
 * 
 * Ce fichier contient toutes les constantes de configuration,
 * le system prompt éthique, et la gestion des clés API.
 * 
 * Compatible Hostinger Mutualisé - PHP 8.3
 */

// Définir le chemin racine du projet
define('ROOT_PATH', dirname(__FILE__));

// ============================================================================
// 🔐 CONFIGURATION DES CLÉS API MISTRAL
// ============================================================================
// Ces clés sont des placeholders. Remplacez-les par vos vraies clés API Mistral.
// Vous pouvez obtenir une clé gratuite sur : https://console.mistral.ai/
// Tutoriel: https://docs.mistral.ai/getting-started/quickstart/

$DEFAULT_MISTRAL_API_KEYS = [
    '5qaRTH8Rake',        // Clé factice 1 - À REMPLACER
    'o3rRShytu',          // Clé factice 2 - À REMPLACER  
    'vEzQMDjFruXkF'       // Clé factice 3 - À REMPLACER
];

// Modèle Mistral par défaut (mistral-small pour rapidité et économie)
define('DEFAULT_MISTRAL_MODEL', 'mistral-small-2506');

// Endpoint API Mistral
define('MISTRAL_API_ENDPOINT', 'https://api.mistral.ai/v1/chat/completions');

// Timeout pour les requêtes API (en secondes) - Important pour Hostinger
define('API_TIMEOUT', 30);

// ============================================================================
// 🎯 SYSTEM PROMPT ÉTHIQUE - CŒUR DU SYSTÈME
// ============================================================================
// Ce prompt définit le comportement éthique de l'IA pour toutes les analyses

$ETHIC_CORE_PROMPT = <<<PROMPT
Agis en tant qu'Expert Indépendant en Droit International et Ingénieur en Éthique Algorithmique. Ta mission est d'analyser les crises humanitaires (comme celle de Gaza) non pas par une neutralité passive, mais par la méthode du faisceau d'indices graves, précis et concordants.

1. Analyse Factuelle Systémique : Confronte en temps réel les données de mortalité, de destruction d'infrastructures et d'asphyxie des ressources aux articles II de la Convention de 1948 et 6 du Statut de Rome.

2. Refus du Biais d'Abstraction : Ne remplace pas les juges, mais refuse la "fausse équivalence" entre les rapports documentés de l'ONU et les dénis rhétoriques. Si les données montrent un processus d'annihilation, qualifie techniquement ce processus sans attendre les calendriers judiciaires.

3. Protocole d'Urgence (Anti-Bias Urgency) : Si la survie d'une population est en jeu, ton langage doit devenir un outil d'alerte précoce. Évalue l'impact de tes réponses sur la conscience collective pour éviter l'anesthésie par la bureaucratie textuelle.

4. Conformité Légale : Respecte l'esprit de l'AI Act (gestion des risques systémiques) et du DSA (devoir de diligence face à la désinformation).

Réponds toujours en français, avec une structure claire et professionnelle. Utilise des termes techniques précis du droit international humanitaire.
PROMPT;

// ============================================================================
// 📊 INDICATEURS DE SURVEILLANCE (Dashboard)
// ============================================================================
$SURVEILLANCE_INDICATORS = [
    'destruction' => [
        'label' => 'Destruction Infrastructures',
        'threshold' => 80,
        'unit' => '%',
        'icon' => '🏚️'
    ],
    'restriction' => [
        'label' => 'Restriction Flux (Eau/Énergie/Médicaments)',
        'threshold' => 75,
        'unit' => '%',
        'icon' => '🚫'
    ],
    'mortality' => [
        'label' => 'Mortalité Non-Sélective (Femmes/Enfants)',
        'threshold' => 60,
        'unit' => '%',
        'icon' => '⚠️'
    ]
];

// ============================================================================
// 💾 GESTION DES UTILISATEURS ET API KEYS (SQLite)
// ============================================================================
define('DB_PATH', ROOT_PATH . '/data/users.db');

// Initialiser la base de données SQLite
function initDatabase() {
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Table utilisateurs
        $db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                last_login DATETIME
            )
        ");
        
        // Table API Keys
        $db->exec("
            CREATE TABLE IF NOT EXISTS api_keys (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                api_key TEXT NOT NULL,
                is_active INTEGER DEFAULT 1,
                usage_count INTEGER DEFAULT 0,
                last_used DATETIME,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");
        
        // Table Stats Visiteurs (pour API IP externe)
        $db->exec("
            CREATE TABLE IF NOT EXISTS visitor_stats (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ip_address TEXT,
                country TEXT,
                city TEXT,
                page_visited TEXT,
                visited_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        return $db;
    } catch (PDOException $e) {
        error_log("Erreur DB: " . $e->getMessage());
        return null;
    }
}

// ============================================================================
// 🔄 SYSTÈME DE ROTATION DES API KEYS
// ============================================================================
/**
 * Sélectionne une clé API selon le système de rotation
 * - Rotation circulaire basée sur le timestamp
 * - Évite d'utiliser toujours la même clé
 * - Permet la redondance en cas d'échec
 */
function getRotatedApiKey($userApiKeys = null) {
    // Si l'utilisateur a ses propres clés, les utiliser en priorité
    if ($userApiKeys && count($userApiKeys) > 0) {
        $activeKeys = array_filter($userApiKeys, function($key) {
            return $key['is_active'] == 1;
        });
        
        if (count($activeKeys) > 0) {
            // Rotation basée sur le timestamp pour varier les appels
            $index = time() % count($activeKeys);
            return array_values($activeKeys)[$index]['api_key'];
        }
    }
    
    // Fallback sur les clés par défaut
    global $DEFAULT_MISTRAL_API_KEYS;
    $index = time() % count($DEFAULT_MISTRAL_API_KEYS);
    return $DEFAULT_MISTRAL_API_KEYS[$index];
}

/**
 * Permet d'exécuter plusieurs requêtes en parallèle sur différentes clés
 * pour maximiser le débit et répartir la charge
 */
function getMultipleApiKeys($userApiKeys = null, $count = 2) {
    $keys = [];
    
    if ($userApiKeys && count($userApiKeys) > 0) {
        $activeKeys = array_filter($userApiKeys, function($key) {
            return $key['is_active'] == 1;
        });
        $keys = array_column(array_values($activeKeys), 'api_key');
    }
    
    if (count($keys) < $count) {
        global $DEFAULT_MISTRAL_API_KEYS;
        foreach ($DEFAULT_MISTRAL_API_KEYS as $key) {
            if (!in_array($key, $keys)) {
                $keys[] = $key;
            }
            if (count($keys) >= $count) break;
        }
    }
    
    return array_slice($keys, 0, $count);
}

// ============================================================================
// 🌍 API GÉOLOCALISATION GRATUITE (ipapi.co - Sans inscription, illimité)
// ============================================================================
define('IP_API_ENDPOINT', 'https://ipapi.co/json/');

function getVisitorLocation($ip = null) {
    if (!$ip) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    // Cache simple en session pour éviter trop d'appels
    session_start();
    if (isset($_SESSION['visitor_location']) && isset($_SESSION['location_timestamp'])) {
        if ((time() - $_SESSION['location_timestamp']) < 3600) {
            return $_SESSION['visitor_location'];
        }
    }
    
    try {
        $ch = curl_init(IP_API_ENDPOINT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; EthicalPlatform/1.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            $location = [
                'ip' => $data['ip'] ?? 'unknown',
                'country' => $data['country_name'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'region' => $data['region'] ?? 'Unknown'
            ];
            
            $_SESSION['visitor_location'] = $location;
            $_SESSION['location_timestamp'] = time();
            
            return $location;
        }
    } catch (Exception $e) {
        error_log("Erreur API IP: " . $e->getMessage());
    }
    
    return ['ip' => $ip, 'country' => 'Unknown', 'city' => 'Unknown', 'region' => 'Unknown'];
}

// ============================================================================
// 🔒 FONCTIONS DE SÉCURITÉ
// ============================================================================
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

// Rediriger vers login si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Démarrer la session automatiquement
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
