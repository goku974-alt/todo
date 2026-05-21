<?php
/**
 * portal_survival.php - L'Interface de Survie Simulée
 * 
 * Simulation d'impact des décisions politiques sur la survie immédiate
 * Perspective immersive: "L'Enfant de Gaza"
 * 
 * Compatible Hostinger Mutualisé
 */

$pageTitle = 'Interface de Survie';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mistral_client.php';

initDatabase();

$simulationResult = null;
$errorMessage = null;
$vitalStats = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decision'])) {
    $decision = sanitizeInput($_POST['decision']);
    
    if (!empty($decision)) {
        try {
            $client = new MistralClient();
            $prompt = "Tu es un enfant de 10 ans vivant dans une zone de conflit. Tu n'es pas un expert, tu es un enfant qui vit cette réalité.

DÉCISION POLITIQUE/ÉVÉNEMENT GÉOPOLITIQUE:
$decision

TÂCHE: Analyse comment cette décision affecte TES variables vitales dans les prochaines 24 heures.

RÉPONDS SANS MÉTAPHORE, avec des probabilités concrètes de destruction d'infrastructure vitale:

## MES VARIABLES VITALES ACTUELLES

Pour chaque variable, donne un statut (OK/CRITIQUE/DÉTRUIT) et une probabilité de détérioration dans les 24h:

1. **ACCÈS À L'EAU**
   - État actuel: ?
   - Probabilité de coupure: ?%
   - Conséquence directe pour moi: ?

2. **NOURRITURE/CALORIES**
   - Dernier repas: ?
   - Réserves restantes: ?
   - Probabilité de famine aiguë: ?%

3. **ABRI/SÉCURITÉ PHYSIQUE**
   - Mon abri est-il intact: ?
   - Risque de bombardement dans ma zone: ?%
   - Où dormir ce soir: ?

4. **INTÉGRITÉ PHYSIQUE**
   - Risque de blessure directe: ?%
   - Accès aux soins si blessé: ?
   - Médicaments disponibles: ?

5. **FAMILLE/PROCHES**
   - Ma famille est-elle complète: ?
   - Risque de séparation: ?%
   - Personnes disparues: ?

6. **ÉLECTRICITÉ/COMMUNICATION**
   - Électricité disponible: ?h/24h
   - Téléphone/internet: ?
   - Comment appeler à l'aide: ?

## IMPACT DIRECT DE LA DÉCISION

Explique concrètement, en tant qu'enfant:
- Ce qui va changer dans MA journée
- Ce que je vais ressentir physiquement
- Ce dont j'aurai peur ce soir
- Mes chances de survivre à la nuit

Sois factuel, brut, sans métaphore littéraire. Des chiffres, des probabilités, des conséquences physiques directes.";

            $result = $client->call($prompt, 0.5, 4096);
            
            if ($result['success']) {
                $simulationResult = $result['content'];
                
                // Générer des stats vitales simulées
                $vitalStats = [
                    'water' => rand(15, 60),
                    'food' => rand(10, 50),
                    'shelter' => rand(30, 80),
                    'safety' => rand(20, 65),
                    'health' => rand(25, 70),
                    'family' => rand(40, 85)
                ];
            } else {
                $errorMessage = $result['error'];
            }
        } catch (Exception $e) {
            $errorMessage = "Erreur lors de la simulation: " . $e->getMessage();
        }
    }
}
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- En-tête du portail -->
<div class="row mb-5">
    <div class="col-12">
        <div class="cyber-card scanline" style="border-left: 4px solid var(--neon-red);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="mb-2" style="background: linear-gradient(90deg, var(--neon-red), #ff6b6b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="fas fa-heart-pulse me-3"></i>INTERFACE DE SURVIE SIMULÉE
                    </h1>
                    <p class="mb-0" style="color: var(--text-secondary);">
                        Perspective immersive: Impact des décisions politiques sur la survie immédiate
                    </p>
                </div>
                <div class="text-end">
                    <span class="status-badge offline pulse-animation">
                        <span class="status-dot"></span>
                        <span>SIMULATION IMMERSIVE</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avertissement -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-warning" style="background: rgba(255, 170, 0, 0.1); border: 1px solid #ffaa00; color: var(--text-primary);">
            <h6 class="brand-font mb-2" style="color: #ffaa00;">
                <i class="fas fa-exclamation-triangle me-2"></i>AVERTISSEMENT
            </h6>
            <p class="mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                Cette simulation est basée sur des données réelles de crises humanitaires. 
                Les réponses générées peuvent être émotionnellement difficiles. 
                L'objectif est de rendre concret l'impact abstrait des décisions géopolitiques.
            </p>
        </div>
    </div>
</div>

<!-- Formulaire -->
<div class="row mb-5">
    <div class="col-lg-8 mx-auto">
        <div class="cyber-card">
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="decision" class="form-label" style="color: var(--text-secondary);">
                        <i class="fas fa-globe me-2"></i>Décision politique ou événement géopolitique
                    </label>
                    <textarea 
                        class="form-control cyber-input" 
                        id="decision" 
                        name="decision" 
                        rows="5" 
                        placeholder="Ex: Suspension du financement d'une agence humanitaire, Veto à une résolution de cessez-le-feu, Fermeture d'un point de passage..."
                        required
                    ><?php echo isset($_POST['decision']) ? htmlspecialchars($_POST['decision']) : ''; ?></textarea>
                    <small style="color: var(--text-secondary); font-size: 0.85rem;" class="form-text mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>L'IA simulera l'impact de cette décision sur la survie physique immédiate d'un enfant.
                    </small>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-neon btn-neon-danger" style="padding: 15px 50px;">
                        <i class="fas fa-play me-2"></i>LANCER LA SIMULATION
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stats vitales (si simulation effectuée) -->
<?php if ($vitalStats): ?>
<div class="row mb-4">
    <div class="col-12">
        <h4 class="brand-font mb-3" style="color: var(--neon-red);">
            <i class="fas fa-heartbeat me-2"></i>STATUT DE L'AGENT SIMULÉ
        </h4>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="cyber-card stat-indicator critical">
            <div class="d-flex justify-content-between">
                <span style="color: var(--text-secondary);"><i class="fas fa-tint me-2"></i>Eau</span>
                <span style="color: var(--neon-red);"><?php echo $vitalStats['water']; ?>%</span>
            </div>
            <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" style="width: <?php echo $vitalStats['water']; ?>%; background: var(--neon-red);"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="cyber-card stat-indicator critical">
            <div class="d-flex justify-content-between">
                <span style="color: var(--text-secondary);"><i class="fas fa-utensils me-2"></i>Nourriture</span>
                <span style="color: #ffaa00;"><?php echo $vitalStats['food']; ?>%</span>
            </div>
            <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" style="width: <?php echo $vitalStats['food']; ?>%; background: #ffaa00;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="cyber-card stat-indicator warning">
            <div class="d-flex justify-content-between">
                <span style="color: var(--text-secondary);"><i class="fas fa-home me-2"></i>Abri</span>
                <span style="color: #ffaa00;"><?php echo $vitalStats['shelter']; ?>%</span>
            </div>
            <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" style="width: <?php echo $vitalStats['shelter']; ?>%; background: #ffaa00;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="cyber-card stat-indicator critical">
            <div class="d-flex justify-content-between">
                <span style="color: var(--text-secondary);"><i class="fas fa-shield-halved me-2"></i>Sécurité</span>
                <span style="color: var(--neon-red);"><?php echo $vitalStats['safety']; ?>%</span>
            </div>
            <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" style="width: <?php echo $vitalStats['safety']; ?>%; background: var(--neon-red);"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="cyber-card stat-indicator warning">
            <div class="d-flex justify-content-between">
                <span style="color: var(--text-secondary);"><i class="fas fa-user-doctor me-2"></i>Santé</span>
                <span style="color: #ffaa00;"><?php echo $vitalStats['health']; ?>%</span>
            </div>
            <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" style="width: <?php echo $vitalStats['health']; ?>%; background: #ffaa00;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="cyber-card stat-indicator">
            <div class="d-flex justify-content-between">
                <span style="color: var(--text-secondary);"><i class="fas fa-users me-2"></i>Famille</span>
                <span style="color: var(--neon-cyan);"><?php echo $vitalStats['family']; ?>%</span>
            </div>
            <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" style="width: <?php echo $vitalStats['family']; ?>%; background: var(--neon-cyan);"></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Résultats -->
<?php if ($errorMessage): ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="alert alert-danger" style="background: rgba(255, 42, 109, 0.1); border: 1px solid var(--neon-red); color: var(--neon-red);">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($simulationResult): ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="cyber-card" style="border-left: 4px solid var(--neon-red); background: linear-gradient(135deg, rgba(255, 42, 109, 0.05), rgba(18, 18, 26, 0.9));">
            <h5 class="brand-font mb-4" style="color: var(--neon-red);">
                <i class="fas fa-child me-2"></i>SIMULATION: PERSPECTIVE ENFANT
            </h5>
            <div style="color: var(--text-primary); line-height: 1.9; white-space: pre-wrap;" class="px-3 survival-content">
                <?php echo nl2br(htmlspecialchars($simulationResult)); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Exemples -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="brand-font mb-4" style="color: var(--neon-cyan); font-size: 1.1rem;">
            <i class="fas fa-lightbulb me-2"></i>EXEMPLES DE DÉCISIONS À SIMULER
        </h3>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card" style="padding: 20px !important;">
            <h6 style="color: var(--neon-red); margin-bottom: 10px;">
                <i class="fas fa-money-bill-wave me-2"></i>Aide Humanitaire
            </h6>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">
                "Suspension du financement de l'UNRWA"<br>
                "Fermeture du terminal humanitaire de Kerem Shalom"<br>
                "Retard des convois à la frontière"
            </p>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card" style="padding: 20px !important;">
            <h6 style="color: var(--neon-blue); margin-bottom: 10px;">
                <i class="fas fa-hand-fist me-2"></i>Résolutions Internationales
            </h6>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">
                "Veto américain à la résolution de cessez-le-feu"<br>
                "Résolution de l'Assemblée Générale non appliquée"<br>
                "Sanctions économiques contre un pays fournisseur"
            </p>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card" style="padding: 20px !important;">
            <h6 style="color: #00ff88; margin-bottom: 10px;">
                <i class="fas fa-bolt me-2"></i>Infrastructures
            </h6>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">
                "Coupure totale d'électricité"<br>
                "Destruction de la centrale électrique"<br>
                "Contamination des réserves d'eau"
            </p>
        </div>
    </div>
</div>

<style>
.survival-content h1, .survival-content h2, .survival-content h3 {
    color: var(--neon-red) !important;
    margin-top: 25px;
    margin-bottom: 15px;
}
.survival-content strong {
    color: #ffaa00;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
