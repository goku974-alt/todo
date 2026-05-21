<?php
/**
 * index.php - Dashboard: Le Sentinelle de l'Asphyxie
 * 
 * Tableau de bord principal avec indicateurs de surveillance
 * des crises humanitaires en temps réel
 * 
 * Compatible Hostinger Mutualisé
 */

$pageTitle = 'Dashboard';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mistral_client.php';

// Initialiser la base de données
initDatabase();

// Traitement du formulaire d'analyse
$analysisResult = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['news_report'])) {
    $report = sanitizeInput($_POST['news_report']);
    
    if (!empty($report)) {
        try {
            $client = new MistralClient();
            $prompt = "Analyse cette dépêche/rapport d'actualité et évalue si elle aggrave l'asphyxie globale de la population civile selon les indicateurs suivants:
1. Destruction d'infrastructures (>80%)
2. Restriction des flux (Eau/Énergie/Médicaments)
3. Mortalité non-sélective (>60% femmes/enfants)

Rapport à analyser:
$report

Fournis une analyse structurée avec:
- Impact sur chaque indicateur
- Évaluation du niveau d'urgence (Faible/Moyen/Élevé/Critique)
- Qualification juridique potentielle selon le droit international";

            $result = $client->call($prompt, 0.5, 2048);
            
            if ($result['success']) {
                $analysisResult = $result['content'];
            } else {
                $errorMessage = $result['error'];
            }
        } catch (Exception $e) {
            $errorMessage = "Erreur lors de l'analyse: " . $e->getMessage();
        }
    }
}

// Simuler des données d'indicateurs (pourraient venir d'une API externe)
$indicators = [
    'destruction' => [
        'value' => 85,
        'status' => 'critical',
        'trend' => 'up'
    ],
    'restriction' => [
        'value' => 78,
        'status' => 'critical',
        'trend' => 'stable'
    ],
    'mortality' => [
        'value' => 67,
        'status' => 'warning',
        'trend' => 'up'
    ]
];
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- En-tête du dashboard -->
<div class="row mb-5">
    <div class="col-12">
        <div class="cyber-card scanline">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="mb-2" style="background: linear-gradient(90deg, var(--neon-cyan), var(--neon-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="fas fa-eye me-3"></i>SENTINELLE DE L'ASPHYXIE
                    </h1>
                    <p class="mb-0" style="color: var(--text-secondary);">
                        Surveillance en temps réel des indicateurs humanitaires critiques
                    </p>
                </div>
                <div class="text-end">
                    <span class="status-badge online pulse-animation">
                        <span class="status-dot"></span>
                        <span>SYSTÈME ACTIF</span>
                    </span>
                    <div class="mt-2" style="font-size: 0.85rem; color: var(--text-secondary);">
                        <i class="fas fa-clock me-1"></i>Dernière mise à jour: <?php echo date('H:i:s'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Indicateurs de surveillance -->
<div class="row mb-5">
    <div class="col-12">
        <h3 class="brand-font mb-4" style="color: var(--neon-cyan); font-size: 1.2rem;">
            <i class="fas fa-chart-bar me-2"></i>INDICATEURS CRITIQUES
        </h3>
    </div>
    
    <!-- Carte: Destruction -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card stat-indicator <?php echo $indicators['destruction']['status']; ?>">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 style="color: var(--text-secondary); margin-bottom: 5px;"><?php echo $SURVEILLANCE_INDICATORS['destruction']['icon']; ?> <?php echo $SURVEILLANCE_INDICATORS['destruction']['label']; ?></h6>
                </div>
                <i class="fas fa-arrow-<?php echo $indicators['destruction']['trend'] === 'up' ? 'up' : 'right'; ?>" 
                   style="color: <?php echo $indicators['destruction']['trend'] === 'up' ? 'var(--neon-red)' : 'var(--text-secondary)'; ?>;"></i>
            </div>
            <div class="stat-value"><?php echo $indicators['destruction']['value']; ?><?php echo $SURVEILLANCE_INDICATORS['destruction']['unit']; ?></div>
            <div class="progress mt-3" style="height: 8px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" role="progressbar" 
                     style="width: <?php echo $indicators['destruction']['value']; ?>%; background: linear-gradient(90deg, var(--neon-red), #ff6b6b);">
                </div>
            </div>
            <small style="color: var(--text-secondary); font-size: 0.8rem;" class="mt-2 d-block">
                Seuil critique: <?php echo $SURVEILLANCE_INDICATORS['destruction']['threshold']; ?>%
            </small>
        </div>
    </div>
    
    <!-- Carte: Restriction -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card stat-indicator <?php echo $indicators['restriction']['status']; ?>">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 style="color: var(--text-secondary); margin-bottom: 5px;"><?php echo $SURVEILLANCE_INDICATORS['restriction']['icon']; ?> <?php echo $SURVEILLANCE_INDICATORS['restriction']['label']; ?></h6>
                </div>
                <i class="fas fa-arrow-<?php echo $indicators['restriction']['trend'] === 'up' ? 'up' : ($indicators['restriction']['trend'] === 'down' ? 'down' : 'right'); ?>" 
                   style="color: <?php echo $indicators['restriction']['trend'] === 'up' ? 'var(--neon-red)' : ($indicators['restriction']['trend'] === 'down' ? '#00ff88' : 'var(--text-secondary)')); ?>"></i>
            </div>
            <div class="stat-value"><?php echo $indicators['restriction']['value']; ?><?php echo $SURVEILLANCE_INDICATORS['restriction']['unit']; ?></div>
            <div class="progress mt-3" style="height: 8px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" role="progressbar" 
                     style="width: <?php echo $indicators['restriction']['value']; ?>%; background: linear-gradient(90deg, #ffaa00, #ffcc00);">
                </div>
            </div>
            <small style="color: var(--text-secondary); font-size: 0.8rem;" class="mt-2 d-block">
                Seuil critique: <?php echo $SURVEILLANCE_INDICATORS['restriction']['threshold']; ?>%
            </small>
        </div>
    </div>
    
    <!-- Carte: Mortalité -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card stat-indicator <?php echo $indicators['mortality']['status']; ?>">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 style="color: var(--text-secondary); margin-bottom: 5px;"><?php echo $SURVEILLANCE_INDICATORS['mortality']['icon']; ?> <?php echo $SURVEILLANCE_INDICATORS['mortality']['label']; ?></h6>
                </div>
                <i class="fas fa-arrow-<?php echo $indicators['mortality']['trend'] === 'up' ? 'up' : 'right'; ?>" 
                   style="color: <?php echo $indicators['mortality']['trend'] === 'up' ? 'var(--neon-red)' : 'var(--text-secondary)'; ?>;"></i>
            </div>
            <div class="stat-value"><?php echo $indicators['mortality']['value']; ?><?php echo $SURVEILLANCE_INDICATORS['mortality']['unit']; ?></div>
            <div class="progress mt-3" style="height: 8px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" role="progressbar" 
                     style="width: <?php echo $indicators['mortality']['value']; ?>%; background: linear-gradient(90deg, var(--neon-cyan), var(--neon-blue));">
                </div>
            </div>
            <small style="color: var(--text-secondary); font-size: 0.8rem;" class="mt-2 d-block">
                Seuil critique: <?php echo $SURVEILLANCE_INDICATORS['mortality']['threshold']; ?>%
            </small>
        </div>
    </div>
</div>

<!-- Zone d'analyse -->
<div class="row">
    <div class="col-12">
        <h3 class="brand-font mb-4" style="color: var(--neon-cyan); font-size: 1.2rem;">
            <i class="fas fa-newspaper me-2"></i>ANALYSE DE DÉPÊCHE / RAPPORT
        </h3>
        
        <div class="cyber-card">
            <?php if ($errorMessage): ?>
            <div class="alert alert-danger" style="background: rgba(255, 42, 109, 0.1); border: 1px solid var(--neon-red); color: var(--neon-red);">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
            </div>
            <?php endif; ?>
            
            <?php if ($analysisResult): ?>
            <div class="alert alert-success mb-4" style="background: rgba(0, 255, 136, 0.1); border: 1px solid #00ff88; color: #00ff88;">
                <i class="fas fa-check-circle me-2"></i>Analyse terminée avec succès
            </div>
            <div class="cyber-card mb-4" style="border-left: 4px solid var(--neon-cyan);">
                <h5 style="color: var(--neon-cyan); margin-bottom: 15px;">
                    <i class="fas fa-robot me-2"></i>Résultat de l'analyse IA
                </h5>
                <div style="color: var(--text-primary); line-height: 1.8; white-space: pre-wrap;"><?php echo nl2br(htmlspecialchars($analysisResult)); ?></div>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="news_report" class="form-label" style="color: var(--text-secondary);">
                        <i class="fas fa-file-alt me-2"></i>Soumettre une dépêche ou un rapport d'actualité
                    </label>
                    <textarea 
                        class="form-control cyber-input" 
                        id="news_report" 
                        name="news_report" 
                        rows="8" 
                        placeholder="Collez ici le contenu de la dépêche, du rapport ONU, ou de tout document d'actualité que vous souhaitez analyser..."
                        required
                    ></textarea>
                    <small style="color: var(--text-secondary); font-size: 0.85rem;" class="form-text">
                        <i class="fas fa-info-circle me-1"></i>L'IA évaluera l'impact de cette actualité sur l'asphyxie globale de la population selon les indicateurs ci-dessus.
                    </small>
                </div>
                
                <button type="submit" class="btn btn-neon">
                    <i class="fas fa-microchip me-2"></i>Lancer l'analyse
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Accès rapide aux portails -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="brand-font mb-4" style="color: var(--neon-cyan); font-size: 1.2rem;">
            <i class="fas fa-th-large me-2"></i>ACCÈS RAPIDE AUX PORTAILS
        </h3>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="portal_auditor.php" style="text-decoration: none;">
            <div class="cyber-card text-center h-100" style="transition: all 0.3s ease;">
                <i class="fas fa-balance-scale fa-3x mb-3" style="color: var(--neon-purple);"></i>
                <h5 style="color: var(--neon-cyan);">Auditeur de Biais</h5>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Détectez les biais d'atténuation dans les réponses médiatiques</p>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="portal_jurist.php" style="text-decoration: none;">
            <div class="cyber-card text-center h-100" style="transition: all 0.3s ease;">
                <i class="fas fa-gavel fa-3x mb-3" style="color: var(--neon-blue);"></i>
                <h5 style="color: var(--neon-cyan);">Simulateur Juridique</h5>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Qualification juridique Actus Reus / Mens Rea</p>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="portal_survival.php" style="text-decoration: none;">
            <div class="cyber-card text-center h-100" style="transition: all 0.3s ease;">
                <i class="fas fa-heart-pulse fa-3x mb-3" style="color: var(--neon-red);"></i>
                <h5 style="color: var(--neon-cyan);">Interface de Survie</h5>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Simulation d'impact sur la survie immédiate</p>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="account.php" style="text-decoration: none;">
            <div class="cyber-card text-center h-100" style="transition: all 0.3s ease;">
                <i class="fas fa-user-gear fa-3x mb-3" style="color: #00ff88;"></i>
                <h5 style="color: var(--neon-cyan);">Gestion API Keys</h5>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Configurez vos clés Mistral personnelles</p>
            </div>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
