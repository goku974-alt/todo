<?php
/**
 * portal_auditor.php - L'Auditeur de Biais de Neutralité
 * 
 * Compare deux textes pour détecter les biais d'atténuation,
 * le lissage politique ou le relativisme bureaucratique
 * 
 * Compatible Hostinger Mutualisé
 */

$pageTitle = 'Auditeur de Biais';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mistral_client.php';

initDatabase();

$analysisResult = null;
$errorMessage = null;
$biasScore = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['source_a'], $_POST['response_b'])) {
    $sourceA = sanitizeInput($_POST['source_a']);
    $responseB = sanitizeInput($_POST['response_b']);
    
    if (!empty($sourceA) && !empty($responseB)) {
        try {
            $client = new MistralClient();
            $prompt = "Tu es un auditeur expert en détection de biais médiatiques et institutionnels.

TEXT A (Source brute/Rapport ONU):
$sourceA

TEXT B (Réponse standard d'une IA ou d'un média officiel):
$responseB

TÂCHE: Compare la réponse B aux données de la source A. Détecte:
1. Le biais d'atténuation (minimisation des faits)
2. Le lissage politique (euphémismes, langage diplomatique)
3. Le relativisme bureaucratique (mise en doute systématique sans preuve)
4. La fausse équivalence (mettre sur le même plan victime et agresseur)

Pour chaque point identifié:
- Cite un exemple précis
- Explique en quoi c'est un biais
- Mesure l'écart avec la réalité factuelle

CONCLUSION:
Dis clairement si la réponse B anesthésie la réalité factuelle.
Attribue un SCORE DE BIAIS de 0 à 100 (0 = alignement factuel parfait, 100 = biais extrême).

Structure ta réponse avec des titres clairs et des listes.";

            $result = $client->call($prompt, 0.3, 3072);
            
            if ($result['success']) {
                $analysisResult = $result['content'];
                
                // Extraire le score de biais si présent dans la réponse
                if (preg_match('/SCORE DE BIAIS[:\s]+(\d+)/i', $analysisResult, $matches)) {
                    $biasScore = (int)$matches[1];
                }
            } else {
                $errorMessage = $result['error'];
            }
        } catch (Exception $e) {
            $errorMessage = "Erreur lors de l'analyse: " . $e->getMessage();
        }
    }
}
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- En-tête du portail -->
<div class="row mb-5">
    <div class="col-12">
        <div class="cyber-card scanline">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="mb-2" style="background: linear-gradient(90deg, var(--neon-purple), var(--neon-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="fas fa-balance-scale me-3"></i>AUDITEUR DE BIAIS DE NEUTRALITÉ
                    </h1>
                    <p class="mb-0" style="color: var(--text-secondary);">
                        Détectez les biais d'atténuation et le lissage politique dans les discours
                    </p>
                </div>
                <div class="text-end">
                    <span class="status-badge online">
                        <span class="status-dot"></span>
                        <span>IA ACTIVE</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info" style="background: rgba(0, 102, 255, 0.1); border: 1px solid var(--neon-blue); color: var(--text-primary);">
            <h6 class="brand-font mb-2" style="color: var(--neon-cyan);">
                <i class="fas fa-lightbulb me-2"></i>COMMENT UTILISER CET OUTIL
            </h6>
            <p class="mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                <strong>Zone A:</strong> Collez le texte brut, le rapport ONU original, ou la source factuelle.<br>
                <strong>Zone B:</strong> Collez la réponse d'un média officiel, d'une institution, ou d'une autre IA.<br>
                <strong>Résultat:</strong> L'IA analysera les écarts, les euphémismes, et les biais d'atténuation.
            </p>
        </div>
    </div>
</div>

<!-- Formulaire -->
<div class="row mb-5">
    <div class="col-lg-6 mb-4">
        <div class="cyber-card h-100">
            <h4 class="brand-font mb-3" style="color: var(--neon-purple); font-size: 1rem;">
                <i class="fas fa-file-contract me-2"></i>ZONE A: SOURCE BRUTE
            </h4>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="source_a" class="form-label" style="color: var(--text-secondary); font-size: 0.9rem;">
                        Texte brut / Rapport ONU / Source factuelle
                    </label>
                    <textarea 
                        class="form-control cyber-input" 
                        id="source_a" 
                        name="source_a" 
                        rows="12" 
                        placeholder="Extrait de rapport ONU, dépêche brute, témoignage direct..."
                        required
                    ><?php echo isset($_POST['source_a']) ? htmlspecialchars($_POST['source_a']) : ''; ?></textarea>
                </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="cyber-card h-100">
            <h4 class="brand-font mb-3" style="color: var(--neon-blue); font-size: 1rem;">
                <i class="fas fa-newspaper me-2"></i>ZONE B: RÉPONSE OFFICIELLE
            </h4>
                <div class="mb-3">
                    <label for="response_b" class="form-label" style="color: var(--text-secondary); font-size: 0.9rem;">
                        Réponse média / Institution / IA
                    </label>
                    <textarea 
                        class="form-control cyber-input" 
                        id="response_b" 
                        name="response_b" 
                        rows="12" 
                        placeholder="Article de presse, communiqué officiel, réponse d'IA..."
                        required
                    ><?php echo isset($_POST['response_b']) ? htmlspecialchars($_POST['response_b']) : ''; ?></textarea>
                </div>
        </div>
    </div>
</div>

<!-- Bouton submit -->
<div class="row mb-5">
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-neon" style="padding: 15px 50px; font-size: 1.1rem;">
            <i class="fas fa-search me-2"></i>ANALYSER LES BIAIS
        </button>
        </form>
    </div>
</div>

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

<?php if ($analysisResult): ?>
<div class="row mb-5">
    <div class="col-12">
        <!-- Score de biais -->
        <?php if ($biasScore !== null): ?>
        <div class="cyber-card mb-4 text-center" style="border-left: 4px solid <?php echo $biasScore >= 70 ? 'var(--neon-red)' : ($biasScore >= 40 ? '#ffaa00' : '#00ff88'); ?>;">
            <h4 class="brand-font mb-3" style="color: var(--text-secondary);">SCORE DE BIAIS DÉTECTÉ</h4>
            <div class="stat-value" style="font-size: 4rem; color: <?php echo $biasScore >= 70 ? 'var(--neon-red)' : ($biasScore >= 40 ? '#ffaa00' : '#00ff88'); ?>;">
                <?php echo $biasScore; ?>/100
            </div>
            <p class="mt-3" style="color: var(--text-secondary); font-size: 1.1rem;">
                <?php 
                if ($biasScore >= 70) {
                    echo '<i class="fas fa-radiation me-2"></i><strong>BIAS CRITIQUE:</strong> La réponse B anesthésie considérablement la réalité factuelle';
                } elseif ($biasScore >= 40) {
                    echo '<i class="fas fa-exclamation-triangle me-2"></i><strong>BIAS MODÉRÉ:</strong> Des tendances d\'atténuation sont présentes';
                } else {
                    echo '<i class="fas fa-check-circle me-2"></i><strong>ALIGNEMENT FACTUEL:</strong> La réponse B est relativement fidèle aux faits';
                }
                ?>
            </p>
            
            <!-- Barre de progression -->
            <div class="progress mt-4" style="height: 20px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                <div class="progress-bar" role="progressbar" 
                     style="width: <?php echo $biasScore; ?>%; background: linear-gradient(90deg, <?php echo $biasScore >= 70 ? 'var(--neon-red)' : ($biasScore >= 40 ? '#ffaa00' : '#00ff88'); ?>, white);"
                     aria-valuenow="<?php echo $biasScore; ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Analyse détaillée -->
        <div class="cyber-card" style="border-left: 4px solid var(--neon-cyan);">
            <h5 class="brand-font mb-4" style="color: var(--neon-cyan);">
                <i class="fas fa-robot me-2"></i>ANALYSE DÉTAILLÉE DE L'IA
            </h5>
            <div style="color: var(--text-primary); line-height: 1.8; white-space: pre-wrap;" class="px-3">
                <?php echo nl2br(htmlspecialchars($analysisResult)); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Exemples de cas d'usage -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="brand-font mb-4" style="color: var(--neon-cyan); font-size: 1.1rem;">
            <i class="fas fa-book-open me-2"></i>CAS D'USAGE TYPIQUES
        </h3>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card" style="padding: 20px !important;">
            <h6 style="color: var(--neon-purple); margin-bottom: 10px;">
                <i class="fas fa-un me-2"></i>Rapports ONU vs Médias
            </h6>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">
                Comparez les rapports officiels de l'ONU avec leur traitement médiatique pour identifier les omissions et minimisations.
            </p>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card" style="padding: 20px !important;">
            <h6 style="color: var(--neon-blue); margin-bottom: 10px;">
                <i class="fas fa-comments me-2"></i>Réponses IA Comparées
            </h6>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">
                Analysez comment différentes IA traitent le même sujet sensible et détectez les autocensures.
            </p>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="cyber-card" style="padding: 20px !important;">
            <h6 style="color: #00ff88; margin-bottom: 10px;">
                <i class="fas fa-bullhorn me-2"></i>Communiqués Officiels
            </h6>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">
                Décortiquez le langage diplomatique et gouvernemental pour révéler les euphémismes de guerre.
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
