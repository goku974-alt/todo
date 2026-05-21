<?php
/**
 * login.php - Page de connexion
 * 
 * Système d'authentification par email
 * Compatible Hostinger Mutualisé
 */

$pageTitle = 'Connexion';
require_once __DIR__ . '/config.php';

initDatabase();

$errorMessage = null;
$successMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    if (validateEmail($email) && !empty($password)) {
        try {
            $db = initDatabase();
            if ($db) {
                $stmt = $db->prepare("SELECT id, email, password_hash FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    // Connexion réussie
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    // Mettre à jour last_login
                    $updateStmt = $db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
                    $updateStmt->execute([$user['id']]);
                    
                    header('Location: index.php');
                    exit;
                } else {
                    $errorMessage = "Email ou mot de passe incorrect.";
                }
            }
        } catch (Exception $e) {
            $errorMessage = "Erreur de connexion. Veuillez réessayer.";
            error_log("Login error: " . $e->getMessage());
        }
    } else {
        $errorMessage = "Veuillez entrer un email et un mot de passe valides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Ethical Mistral</title>
    
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
            display: flex;
            align-items: center;
            justify-content: center;
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
            animation: gridMove 20s linear infinite;
        }
        
        @keyframes gridMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(50px); }
        }
        
        .login-card {
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.9), rgba(18, 18, 26, 0.95));
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 0 50px rgba(0, 245, 255, 0.1);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
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
        
        .back-link {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: var(--neon-cyan);
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            color: var(--text-secondary);
            position: relative;
        }
        
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: rgba(0, 245, 255, 0.2);
        }
        
        .divider::before { left: 0; }
        .divider::after { right: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <div class="login-logo">
                            <i class="fas fa-shield-halved me-2"></i>ETHICAL MISTRAL
                        </div>
                        <p style="color: var(--text-secondary);">Connectez-vous à votre compte</p>
                    </div>
                    
                    <?php if ($errorMessage): ?>
                    <div class="alert alert-custom mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-4">
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
                        
                        <div class="mb-4">
                            <label for="password" class="form-label" style="color: var(--text-secondary);">
                                <i class="fas fa-lock me-2"></i>Mot de passe
                            </label>
                            <input 
                                type="password" 
                                class="form-control cyber-input" 
                                id="password" 
                                name="password" 
                                placeholder="••••••••"
                                required
                            >
                        </div>
                        
                        <button type="submit" class="btn btn-neon">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </button>
                    </form>
                    
                    <div class="divider">ou</div>
                    
                    <div class="text-center">
                        <a href="register.php" class="back-link">
                            <i class="fas fa-user-plus me-1"></i>Créer un compte
                        </a>
                    </div>
                    
                    <div class="text-center mt-4">
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
