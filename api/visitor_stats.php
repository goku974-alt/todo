<?php
/**
 * api/visitor_stats.php - API pour statistiques visiteurs
 * 
 * Retourne les statistiques de visiteurs en JSON
 * Utilise l'API ipapi.co gratuite et illimitée
 * Compatible Hostinger Mutualisé
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

// Initialiser la base de données
$db = initDatabase();

$response = [
    'online' => 0,
    'location' => null,
    'timestamp' => time()
];

try {
    // Compter les visiteurs uniques des dernières 5 minutes
    if ($db) {
        $stmt = $db->query("SELECT COUNT(DISTINCT ip_address) as count FROM visitor_stats WHERE visited_at >= datetime('now', '-5 minutes')");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $response['online'] = (int)($result['count'] ?? 0);
        
        // Statistiques supplémentaires
        $stmt = $db->query("SELECT country, COUNT(*) as count FROM visitor_stats GROUP BY country ORDER BY count DESC LIMIT 5");
        $response['top_countries'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    // En cas d'erreur, retourner au moins la localisation
    $response['error'] = $e->getMessage();
}

// Obtenir la localisation du visiteur actuel
$location = getVisitorLocation();
$response['location'] = [
    'ip' => $location['ip'],
    'country' => $location['country'],
    'city' => $location['city'],
    'region' => $location['region']
];

echo json_encode($response);
