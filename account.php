<?php
/**
 * account.php - Gestion du compte utilisateur
 * 
 * Page pour gérer ses clés API Mistral
 * Compatible Hostinger Mutualisé
 */

$pageTitle = 'Mon Compte';
require_once __DIR__ . '/config.php';
requireLogin();

initDatabase();

$errorMessage = null;
$successMessage = null;
$userApiKeys = [];

$db = initDatabase();

// Récupérer les clés API de l'utilisateur
if ($db) {
    $stmt = $db->prepare("SELECT id, api_key, is_active, usage_count, last_used, created_at FROM api_keys WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userApiKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajouter une nouvelle clé API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_api_key'])) {
    $newKey = trim($_POST['new_api_key']);
    
    if (!empty($newKey)) {
        try {
            $stmt = $db->prepare("INSERT INTO api_keys (user_id, api_key) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $newKey]);
            $successMessage = "Clé API ajoutée avec succès!";
            
            // Rafraîchir la liste
            $stmt = $db->prepare("SELECT id, api_key, is_active, usage_count, last_used, created_at FROM api_keys WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $userApiKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $errorMessage = "Erreur lors de l'ajout de la clé API.";
        }
    }
}

// Supprimer une clé API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_api_key'])) {
    $keyId = (int)$_POST['key_id'];
    
    try {
        $stmt = $db->prepare("DELETE FROM api_keys WHERE id = ? AND user_id = ?");
        $stmt->execute([$keyId, $_SESSION['user_id']]);
        $successMessage = "Clé API supprimée avec succès!";
        
        // Rafraîchir la liste
        $stmt = $db->prepare("SELECT id, api_key, is_active, usage_count, last_used, created_at FROM api_keys WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $userApiKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $errorMessage = "Erreur lors de la suppression de la clé API.";
    }
}

// Activer/Désactiver une clé API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_api_key'])) {
    $keyId = (int)$_POST['key_id'];
    
    try {
        $stmt = $db->prepare("UPDATE api_keys SET is_active = NOT is_active WHERE id = ? AND user_id = ?");
        $stmt->execute([$keyId, $_SESSION['user_id']]);
        $successMessage = "Statut de la clé API modifié!";
        
        // Rafraîchir la liste
        $stmt = $db->prepare("SELECT id, api_key, is_active, usage_count, last_used, created_at FROM api_keys WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $userApiKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $errorMessage = "Erreur lors de la modification du statut.";
    }
}

// Changer le mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if (strlen($newPassword) < 8) {
        $errorMessage = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
    } elseif ($newPassword !== $confirmPassword) {
        $errorMessage = "Les nouveaux mots de passe ne correspondent pas.";
    } else {
        try {
            // Vérifier le mot de passe actuel
            $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($currentPassword, $user['password_hash'])) {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $updateStmt->execute([$newHash, $_SESSION['user_id']]);
                $successMessage = "Mot de passe modifié avec succès!";
            } else {
                $errorMessage = "Le mot de passe actuel est incorrect.";
            }
        } catch (Exception $e) {
            $errorMessage = "Erreur lors du changement de mot de passe.";
        }
    }
}
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- En-tête -->
<div class="row mb-5">
    <div class="col-12">
        <div class="cyber-card scanline">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="mb-2" style="background: linear-gradient(90deg, #00ff88, var(--neon-cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="fas fa-user-gear me-3"></i>MON COMPTE
                    </h1>
                    <p class="mb-0" style="color: var(--text-secondary);">
                        Gérez vos clés API et paramètres
                    </p>
                </div>
                <div>
                    <span class="status-badge online">
                        <span class="status-dot"></span>
                        <span><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Messages -->
<?php if ($errorMessage): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger" style="background: rgba(255, 42, 109, 0.1); border: 1px solid var(--neon-red); color: var(--neon-red);">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($successMessage): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-success" style="background: rgba(0, 255, 136, 0.1); border: 1px solid #00ff88; color: #00ff88;">
            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($successMessage); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Section: Clés API -->
<div class="row mb-5">
    <div class="col-lg-8 mx-auto">
        <div class="cyber-card">
            <h4 class="brand-font mb-4" style="color: var(--neon-cyan);">
                <i class="fas fa-key me-2"></i>MES CLÉS API MISTRAL
            </h4>
            
            <!-- Formulaire d'ajout -->
            <form method="POST" action="" class="mb-4">
                <div class="input-group">
                    <input 
                        type="text" 
                        class="form-control cyber-input" 
                        name="new_api_key" 
                        placeholder="Collez votre clé API Mistral (ex: sk-...)"
                        required
                    >
                    <button type="submit" name="add_api_key" class="btn btn-neon" style="padding: 12px 25px;">
                        <i class="fas fa-plus me-1"></i>Ajouter
                    </button>
                </div>
                <small style="color: var(--text-secondary); font-size: 0.85rem;" class="mt-2 d-block">
                    <i class="fas fa-info-circle me-1"></i>Obtenez vos clés gratuites sur <a href="https://console.mistral.ai/" target="_blank" style="color: var(--neon-cyan);">console.mistral.ai</a>
                </small>
            </form>
            
            <!-- Liste des clés -->
            <?php if (count($userApiKeys) > 0): ?>
            <div class="table-responsive">
                <table class="table" style="color: var(--text-primary);">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(0, 245, 255, 0.2);">
                            <th style="color: var(--neon-cyan);">Clé API</th>
                            <th style="color: var(--neon-cyan);">Statut</th>
                            <th style="color: var(--neon-cyan);">Utilisations</th>
                            <th style="color: var(--neon-cyan);">Dernière utilisation</th>
                            <th style="color: var(--neon-cyan);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userApiKeys as $key): ?>
                        <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                            <td>
                                <code style="color: var(--text-secondary);"><?php echo substr($key['api_key'], 0, 15); ?>...</code>
                            </td>
                            <td>
                                <?php if ($key['is_active']): ?>
                                <span class="status-badge online" style="font-size: 0.75rem; padding: 4px 10px;">
                                    <span class="status-dot"></span>Active
                                </span>
                                <?php else: ?>
                                <span class="status-badge offline" style="font-size: 0.75rem; padding: 4px 10px;">
                                    <span class="status-dot"></span>Inactive
                                </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $key['usage_count']; ?></td>
                            <td><?php echo $key['last_used'] ? date('d/m/Y H:i', strtotime($key['last_used'])) : 'Jamais'; ?></td>
                            <td>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="key_id" value="<?php echo $key['id']; ?>">
                                    <button type="submit" name="toggle_api_key" class="btn btn-sm" style="background: transparent; border: 1px solid var(--neon-blue); color: var(--neon-blue); padding: 5px 10px;">
                                        <i class="fas fa-toggle-<?php echo $key['is_active'] ? 'on' : 'off'; ?>"></i>
                                    </button>
                                </form>
                                <form method="POST" action="" class="d-inline" onsubmit="return confirm('Supprimer cette clé API?');">
                                    <input type="hidden" name="key_id" value="<?php echo $key['id']; ?>">
                                    <button type="submit" name="delete_api_key" class="btn btn-sm" style="background: transparent; border: 1px solid var(--neon-red); color: var(--neon-red); padding: 5px 10px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4" style="color: var(--text-secondary);">
                <i class="fas fa-key fa-3x mb-3" style="opacity: 0.3;"></i>
                <p>Aucune clé API configurée. Ajoutez votre première clé ci-dessus.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Section: Changer le mot de passe -->
<div class="row mb-5">
    <div class="col-lg-8 mx-auto">
        <div class="cyber-card">
            <h4 class="brand-font mb-4" style="color: #00ff88;">
                <i class="fas fa-lock me-2"></i>CHANGER LE MOT DE PASSE
            </h4>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="current_password" class="form-label" style="color: var(--text-secondary);">
                        Mot de passe actuel
                    </label>
                    <input 
                        type="password" 
                        class="form-control cyber-input" 
                        name="current_password" 
                        required
                    >
                </div>
                
                <div class="mb-3">
                    <label for="new_password" class="form-label" style="color: var(--text-secondary);">
                        Nouveau mot de passe
                    </label>
                    <input 
                        type="password" 
                        class="form-control cyber-input" 
                        name="new_password" 
                        minlength="8"
                        required
                    >
                </div>
                
                <div class="mb-4">
                    <label for="confirm_password" class="form-label" style="color: var(--text-secondary);">
                        Confirmer le nouveau mot de passe
                    </label>
                    <input 
                        type="password" 
                        class="form-control cyber-input" 
                        name="confirm_password" 
                        minlength="8"
                        required
                    >
                </div>
                
                <button type="submit" name="change_password" class="btn btn-neon" style="border-color: #00ff88; color: #00ff88;">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Info box -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="alert alert-info" style="background: rgba(0, 102, 255, 0.1); border: 1px solid var(--neon-blue); color: var(--text-primary);">
            <h6 class="brand-font mb-2" style="color: var(--neon-cyan);">
                <i class="fas fa-lightbulb me-2"></i>INFO: ROTATION AUTOMATIQUE DES CLÉS
            </h6>
            <p class="mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                Le système utilise automatiquement la rotation circulaire pour répartir les appels API 
                entre toutes vos clés actives. Cela permet de maximiser votre quota mensuel 
                (1 milliard de tokens par clé) et d'éviter les limites de rate limiting.
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
