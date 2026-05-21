<?php
/**
 * register.php - Page d'inscription
 * 
 * Création de compte avec gestion des API Keys Mistral
 * Compatible Hostinger Mutualisé
 */

$pageTitle = 'Inscription';
require_once __DIR__ . '/config.php';

initDatabase();

$errorMessage = null;
$successMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $apiKey1 = isset($_POST['api_key_1']) ? trim($_POST['api_key_1']) : '';
    $apiKey2 = isset($_POST['api_key_2']) ? trim($_POST['api_key_2']) : '';
    $apiKey3 = isset($_POST['api_key_3']) ? trim($_POST['api_key_3']) : '';
    
    $errors = [];
    
    // Validation email
    if (!validateEmail($email)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }
    
    // Validation mot de passe
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    
    if (empty($errors)) {
        try {
            $db = initDatabase();
            if ($db) {
                // Vérifier si l'email existe déjà
                $checkStmt = $db->prepare("SELECT id FROM users WHERE email = ?");
                $checkStmt->execute([$email]);
                
                if ($checkStmt->fetch()) {
                    $errors[] = "Cet email est déjà utilisé.";
                } else {
                    // Hash du mot de passe
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insérer l'utilisateur
                    $insertStmt = $db->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
                    $insertStmt->execute([$email, $passwordHash]);
                    $userId = $db->lastInsertId();
                    
                    // Ajouter les API keys si fournies
                    $apiKeys = array_filter([$apiKey1, $apiKey2, $apiKey3], function($key) {
                        return !empty($key);
                    });
                    
                    foreach ($apiKeys as $key) {
                        $keyStmt = $db->prepare("INSERT INTO api_keys (user_id, api_key) VALUES (?, ?)");
                        $keyStmt->execute([$userId, $key]);
                    }
                    
                    $successMessage = "Compte créé avec succès! Vous pouvez maintenant vous connecter.";
                }
            }
        } catch (Exception $e) {
            $errors[] = "Erreur lors de la création du compte. Veuillez réessayer.";
            error_log("Register error: " . $e->getMessage());
        }
    }
    
    if (!empty($errors)) {
        $errorMessage = implode(" ", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Ethical Mistral</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --neon-cyan: #00f5ff;
            --neon-blue: #0066ff;
            --neon-purple: #b026ff;
            --neon-red: #ff2a6d;
            --dark-bg: #0a0a0f;
            --dark-surface: #12121a;
            --text-primary: #e0e0e0;
            --text-secondary: #a0a0a0;
        }
        
        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #0f0f1a 50%, var(--dark-bg) 100%);
            color: var(--text-primary);
            font-family: 'Rajdhani', sans-serif;
            min-height: 100vh;
            padding: 40px 0;
        }
        
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
        }
        
        .register-card {
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.9), rgba(18, 18, 26, 0.95));
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            max-width: 550px;
            margin: 0 auto;
            box-shadow: 0 0 50px rgba(0, 245, 255, 0.1);
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        
        .cyber-input {
            background: rgba(10, 10, 15, 0.8);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 12px 15px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .cyber-input:focus {
            outline: none;
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.3);
            background: rgba(10, 10, 15, 0.95);
        }
        
        .btn-neon {
            background: transparent;
            border: 2px solid var(--neon-cyan);
            color: var(--neon-cyan);
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            padding: 15px 30px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-neon:hover {
            background: var(--neon-cyan);
            color: var(--dark-bg);
            box-shadow: 0 0 30px rgba(0, 245, 255, 0.6);
        }
        
        .alert-custom {
            background: rgba(255, 42, 109, 0.1);
            border: 1px solid var(--neon-red);
            color: var(--neon-red);
        }
        
        .alert-success-custom {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid #00ff88;
            color: #00ff88;
        }
        
        .back-link {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: var(--neon-cyan);
        }
        
        .api-key-section {
            background: rgba(0, 245, 255, 0.05);
            border: 1px solid rgba(0, 245, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .section-title {
            color: var(--neon-cyan);
            font-family: 'Orbitron', sans-serif;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .info-box {
            background: rgba(0, 102, 255, 0.1);
            border-left: 3px solid var(--neon-blue);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-card">
                    <div class="register-header">
                        <div class="register-logo">
                            <i class="fas fa-user-plus me-2"></i>CRÉER UN COMPTE
                        </div>
                        <p style="color: var(--text-secondary);">Rejoignez la plateforme Ethical Mistral</p>
                    </div>
                    
                    <?php if ($errorMessage): ?>
                    <div class="alert alert-custom mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($successMessage): ?>
                    <div class="alert alert-success-custom mb-4">
                        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($successMessage); ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label" style="color: var(--text-secondary);">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input 
                                type="email" 
                                class="form-control cyber-input" 
                                id="email" 
                                name="email" 
                                placeholder="votre@email.com"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                required
                            >
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label" style="color: var(--text-secondary);">
                                <i class="fas fa-lock me-2"></i>Mot de passe
                            </label>
                            <input 
                                type="password" 
                                class="form-control cyber-input" 
                                id="password" 
                                name="password" 
                                placeholder="Minimum 8 caractères"
                                required
                            >
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label" style="color: var(--text-secondary);">
                                <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
                            </label>
                            <input 
                                type="password" 
                                class="form-control cyber-input" 
                                id="confirm_password" 
                                name="confirm_password" 
                                placeholder="Répétez votre mot de passe"
                                required
                            >
                        </div>
                        
                        <!-- Section API Keys -->
                        <div class="api-key-section">
                            <h6 class="section-title">
                                <i class="fas fa-key me-2"></i>CLÉS API MISTRAL (OPTIONNEL)
                            </h6>
                            
                            <div class="info-box">
                                <i class="fas fa-info-circle me-2"></i>
                                Ajoutez vos clés API Mistral Free Tier pour utiliser la plateforme.
                                Obtenez-les gratuitement sur <a href="https://console.mistral.ai/" target="_blank" style="color: var(--neon-cyan);">console.mistral.ai</a>
                            </div>
                            
                            <div class="mb-2">
                                <input 
                                    type="text" 
                                    class="form-control cyber-input" 
                                    name="api_key_1" 
                                    placeholder="Clé API 1"
                                    value="<?php echo isset($_POST['api_key_1']) ? htmlspecialchars($_POST['api_key_1']) : ''; ?>"
                                >
                            </div>
                            <div class="mb-2">
                                <input 
                                    type="text" 
                                    class="form-control cyber-input" 
                                    name="api_key_2" 
                                    placeholder="Clé API 2 (optionnel)"
                                    value="<?php echo isset($_POST['api_key_2']) ? htmlspecialchars($_POST['api_key_2']) : ''; ?>"
                                >
                            </div>
                            <div class="mb-2">
                                <input 
                                    type="text" 
                                    class="form-control cyber-input" 
                                    name="api_key_3" 
                                    placeholder="Clé API 3 (optionnel)"
                                    value="<?php echo isset($_POST['api_key_3']) ? htmlspecialchars($_POST['api_key_3']) : ''; ?>"
                                >
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.8rem;">
                                <i class="fas fa-sync me-1"></i>Plusieurs clés permettent une rotation automatique et plus de requêtes simultanées.
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-neon mt-4">
                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <span style="color: var(--text-secondary);">Déjà un compte?</span>
                        <a href="login.php" class="back-link ms-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                        </a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="index.php" class="back-link" style="font-size: 0.9rem;">
                            <i class="fas fa-arrow-left me-1"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
