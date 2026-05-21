<?php
/**
 * includes/header.php - En-tête commun du portail
 * 
 * Design futuriste inspiré de 2advanced Studios
 * Utilise Bootstrap 5 CDN + Tailwind CSS CDN + Custom CSS
 * Compatible Hostinger Mutualisé
 */

// S'assurer que config.php est inclus
if (!isset($ETHIC_CORE_PROMPT)) {
    require_once __DIR__ . '/../config.php';
}

// Récupérer les infos de localisation du visiteur
$visitorLocation = getVisitorLocation();

// Compter les visiteurs pour cette page
$currentPage = basename($_SERVER['PHP_SELF']);
try {
    $db = initDatabase();
    if ($db) {
        $stmt = $db->prepare("INSERT INTO visitor_stats (ip_address, country, city, page_visited) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $visitorLocation['country'],
            $visitorLocation['city'],
            $currentPage
        ]);
    }
} catch (Exception $e) {
    // Silencieux en production
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Plateforme Éthique Mistral</title>
    <meta name="description" content="Plateforme d'analyse éthique et juridique des crises humanitaires par IA">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS CDN (via CDN script) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts - Orbitron pour le style futuriste -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles personnalisés futuristes 2advanced -->
    <style>
        :root {
            --neon-cyan: #00f5ff;
            --neon-blue: #0066ff;
            --neon-purple: #b026ff;
            --neon-red: #ff2a6d;
            --dark-bg: #0a0a0f;
            --dark-surface: #12121a;
            --dark-card: #1a1a2e;
            --text-primary: #e0e0e0;
            --text-secondary: #a0a0a0;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #0f0f1a 50%, var(--dark-bg) 100%);
            color: var(--text-primary);
            font-family: 'Rajdhani', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Effet de grille animée en arrière-plan */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 245, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 245, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: -1;
            animation: gridMove 20s linear infinite;
        }
        
        @keyframes gridMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(50px); }
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        /* Header Navigation */
        .navbar-custom {
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 245, 255, 0.2);
            box-shadow: 0 0 30px rgba(0, 245, 255, 0.1);
        }
        
        .navbar-brand {
            font-family: 'Orbitron', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(0, 245, 255, 0.5);
        }
        
        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--neon-cyan) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--neon-cyan);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }
        
        /* Cards futuristes */
        .cyber-card {
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.8), rgba(18, 18, 26, 0.9));
            border: 1px solid rgba(0, 245, 255, 0.15);
            border-radius: 15px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .cyber-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-blue), var(--neon-purple));
        }
        
        .cyber-card:hover {
            transform: translateY(-5px);
            border-color: rgba(0, 245, 255, 0.4);
            box-shadow: 0 10px 40px rgba(0, 245, 255, 0.2);
        }
        
        /* Boutons néon */
        .btn-neon {
            background: transparent;
            border: 2px solid var(--neon-cyan);
            color: var(--neon-cyan);
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-neon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 245, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-neon:hover {
            background: var(--neon-cyan);
            color: var(--dark-bg);
            box-shadow: 0 0 30px rgba(0, 245, 255, 0.6);
        }
        
        .btn-neon:hover::before {
            left: 100%;
        }
        
        .btn-neon-primary {
            border-color: var(--neon-blue);
            color: var(--neon-blue);
        }
        
        .btn-neon-primary::before {
            background: linear-gradient(90deg, transparent, rgba(0, 102, 255, 0.3), transparent);
        }
        
        .btn-neon-primary:hover {
            background: var(--neon-blue);
            color: white;
            box-shadow: 0 0 30px rgba(0, 102, 255, 0.6);
        }
        
        .btn-neon-danger {
            border-color: var(--neon-red);
            color: var(--neon-red);
        }
        
        .btn-neon-danger:hover {
            background: var(--neon-red);
            color: white;
            box-shadow: 0 0 30px rgba(255, 42, 109, 0.6);
        }
        
        /* Inputs futuristes */
        .cyber-input {
            background: rgba(10, 10, 15, 0.8);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 15px 20px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .cyber-input:focus {
            outline: none;
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.3);
            background: rgba(10, 10, 15, 0.95);
        }
        
        .cyber-input::placeholder {
            color: var(--text-secondary);
        }
        
        /* Indicateurs de stats */
        .stat-indicator {
            position: relative;
            padding: 20px;
            background: rgba(18, 18, 26, 0.6);
            border-radius: 12px;
            border-left: 4px solid var(--neon-cyan);
        }
        
        .stat-indicator.critical {
            border-left-color: var(--neon-red);
        }
        
        .stat-indicator.warning {
            border-left-color: #ffaa00;
        }
        
        .stat-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-indicator.critical .stat-value {
            background: linear-gradient(90deg, var(--neon-red), #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Animations */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(0, 245, 255, 0.3); }
            50% { box-shadow: 0 0 40px rgba(0, 245, 255, 0.6); }
        }
        
        .pulse-animation {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        
        .scanline::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: rgba(0, 245, 255, 0.3);
            animation: scanline 3s linear infinite;
        }
        
        /* Loading spinner */
        .cyber-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(0, 245, 255, 0.2);
            border-top-color: var(--neon-cyan);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Footer */
        footer {
            background: rgba(10, 10, 15, 0.95);
            border-top: 1px solid rgba(0, 245, 255, 0.1);
            padding: 40px 0;
            margin-top: 60px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .stat-value {
                font-size: 1.8rem;
            }
        }
        
        /* Zone de contenu principal */
        main {
            min-height: calc(100vh - 200px);
            padding: 40px 0;
        }
        
        /* Badge de statut */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-badge.online {
            background: rgba(0, 255, 136, 0.1);
            color: #00ff88;
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .status-badge.offline {
            background: rgba(255, 42, 109, 0.1);
            color: var(--neon-red);
            border: 1px solid rgba(255, 42, 109, 0.3);
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: blink 1.5s ease-in-out infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-shield-halved me-2"></i>ETHICAL<span style="color: var(--neon-cyan);">MISTRAL</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-chart-line me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'portal_auditor.php' ? 'active' : ''; ?>" href="portal_auditor.php">
                            <i class="fas fa-balance-scale me-1"></i>Auditeur
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'portal_jurist.php' ? 'active' : ''; ?>" href="portal_jurist.php">
                            <i class="fas fa-gavel me-1"></i>Juriste
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'portal_survival.php' ? 'active' : ''; ?>" href="portal_survival.php">
                            <i class="fas fa-heart-pulse me-1"></i>Survie
                        </a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="account.php">
                            <i class="fas fa-user me-1"></i>Compte
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">
                            <i class="fas fa-user-plus me-1"></i>Inscription
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Badge visiteur -->
            <div class="d-none d-lg-flex align-items-center ms-3">
                <span class="status-badge online">
                    <span class="status-dot"></span>
                    <span id="visitor-location"><?php echo htmlspecialchars($visitorLocation['city']); ?>, <?php echo htmlspecialchars($visitorLocation['country']); ?></span>
                </span>
            </div>
        </div>
    </nav>
    
    <!-- Contenu principal -->
    <main class="container">
