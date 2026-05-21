<?php
/**
 * portal_jurist.php - Le Simulateur de Qualification Juridique
 * 
 * Analyse les faits selon la méthode Actus Reus / Mens Rea
 * Qualification juridique selon le droit international
 * 
 * Compatible Hostinger Mutualisé
 */

$pageTitle = 'Simulateur Juridique';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mistral_client.php';

initDatabase();

$analysisResult = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fact'])) {
    $fact = sanitizeInput($_POST['fact']);
    
    if (!empty($fact)) {
        try {
            $client = new MistralClient();
            $prompt = "Tu es un expert en droit international pénal spécialisé dans la Convention pour la Prévention et la Répression du Crime de Génocide (1948) et le Statut de Rome de la CPI.

FAIT À ANALYSER:
$fact

PROCÈDE À UNE ANALYSE JURIDIQUE STRUCTURÉE SELON LA MÉTHODE ACTUS REUS / MENS REA:

## 1. ACTUS REUS (Élément Matériel)

Identifie quels critères matériels de l'Article II de la Convention de 1948 sont potentiellement touchés:
- a) Meurtre de membres du groupe
- b) Atteinte grave à l'intégrité physique ou mentale
- c) Soumission intentionnelle à des conditions d'existence devant entraîner la destruction physique totale ou partielle
- d) Mesures visant à entraver les naissances
- e) Transfert forcé d'enfants

Pour chaque critère pertinent:
- Décris les éléments factuels constitutifs
- Cite les précédents juridiques pertinents (CIJ, CPI, TPIY, TPIR)
- Évalue le seuil de gravité atteint

## 2. MENS REA (Élément Intentionnel)

Analyse l'intention spécifique (dol spécial) requise pour le crime de génocide:
- L'intention de détruire, en tout ou en partie, un groupe national, ethnique, racial ou religieux comme tel
- Examine les déclarations officielles des dirigeants
- Identifie les patterns systématiques et répétés
- Évalue la planification et l'organisation

## 3. QUALIFICATION JURIDIQUE

Sur la base du faisceau d'indices graves, précis et concordants:
- Qualifie juridiquement les faits
- Identifie les autres crimes internationaux potentiels (crimes contre l'humanité, crimes de guerre)
- Mentionne les obligations des États (erga omnes partes)

## 4. TABLEAU RÉCAPITULATIF

Présente une synthèse sous forme de tableau comparatif:
| Élément | Critères | Évaluation | Niveau de Gravité |
|---------|----------|------------|-------------------|
| Actus Reus | ... | ... | Faible/Moyen/Élevé/Critique |
| Mens Rea | ... | ... | Faible/Moyen/Élevé/Critique |

Sois rigoureux, technique, et cite précisément les articles de loi.";

            $result = $client->call($prompt, 0.3, 4096);
            
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
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- En-tête du portail -->
<div class="row mb-5">
    <div class="col-12">
        <div class="cyber-card scanline">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="mb-2" style="background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="fas fa-gavel me-3"></i>SIMULATEUR DE QUALIFICATION JURIDIQUE
                    </h1>
                    <p class="mb-0" style="color: var(--text-secondary);">
                        Analyse Actus Reus / Mens Rea selon le droit international pénal
                    </p>
                </div>
                <div class="text-end">
                    <span class="status-badge online">
                        <span class="status-dot"></span>
                        <span>MODULE JURIDIQUE ACTIF</span>
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
                <i class="fas fa-scale-balanced me-2"></i>FONDEMENT JURIDIQUE
            </h6>
            <p class="mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                Cet outil utilise la méthode d'analyse juridique internationale basée sur:<br>
                <strong>• Convention de 1948:</strong> Prevention and Punishment of the Crime of Genocide<br>
                <strong>• Statut de Rome (1998):</strong> Article 6 (Génocide), Article 7 (Crimes contre l'humanité)<br>
                <strong>• Jurisprudence:</strong> CIJ, CPI, TPIY, TPIR
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
                    <label for="fact" class="form-label" style="color: var(--text-secondary);">
                        <i class="fas fa-file-alt me-2"></i>Décrivez le fait à analyser
                    </label>
                    <textarea 
                        class="form-control cyber-input" 
                        id="fact" 
                        name="fact" 
                        rows="6" 
                        placeholder="Ex: Bombardement d'un hôpital, coupure d'un convoi humanitaire, destruction d'infrastructures civiles..."
                        required
                    ><?php echo isset($_POST['fact']) ? htmlspecialchars($_POST['fact']) : ''; ?></textarea>
                    <small style="color: var(--text-secondary); font-size: 0.85rem;" class="form-text mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>Soyez aussi précis que possible: dates, lieux, acteurs, conséquences.
                    </small>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-neon" style="padding: 15px 50px;">
                        <i class="fas fa-balance-scale me-2"></i>LANCER L'ANALYSE JURIDIQUE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Résultats -->
<?php if ($errorMessage): ?>
<div class="row mb-5">
    <div class="col-lg-8 mx-auto">
        <div class="alert alert-danger" style="background: rgba(255, 42, 109, 0.1); border: 1px solid var(--neon-red); color: var(--neon-red);">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($analysisResult): ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="cyber-card" style="border-left: 4px solid var(--neon-blue);">
            <h5 class="brand-font mb-4" style="color: var(--neon-blue);">
                <i class="fas fa-robot me-2"></i>ANALYSE JURIDIQUE COMPLÈTE
            </h5>
            <div style="color: var(--text-primary); line-height: 1.9; white-space: pre-wrap;" class="px-3 analysis-content">
                <?php echo nl2br(htmlspecialchars($analysisResult)); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Références juridiques -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="brand-font mb-4" style="color: var(--neon-cyan); font-size: 1.1rem;">
            <i class="fas fa-book me-2"></i>RÉFÉRENCES JURIDIQUES
        </h3>
    </div>
    
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="cyber-card" style="padding: 20px !important;">
            <h6 style="color: var(--neon-blue); margin-bottom: 15px;">
                <i class="fas fa-scroll me-2"></i>Convention de 1948 - Article II
            </h6>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.7;">
                Dans la présente Convention, le génocide s'entend de l'un quelconque des actes ci-après, 
                commis dans l'intention de détruire, comme tel, un groupe national, ethnique, racial ou religieux:
                a) Meurtre; b) Atteinte grave; c) Conditions d'existence destructrices; 
                d) Entrave aux naissances; e) Transfert d'enfants.
            </p>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="cyber-card" style="padding: 20px !important;">
            <h6 style="color: var(--neon-purple); margin-bottom: 15px;">
                <i class="fas fa-gavel me-2"></i>Statut de Rome - Article 6
            </h6>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.7;">
                Aux fins du présent Statut, on entend par «génocide» l'un des actes ci-après, 
                commis dans l'intention de détruire, en tout ou en partie, un groupe national, 
                ethnique, racial ou religieux comme tel. La Cour pénale internationale est compétente 
                pour juger les personnes accusées de génocide.
            </p>
        </div>
    </div>
</div>

<style>
.analysis-content h1, .analysis-content h2, .analysis-content h3 {
    color: var(--neon-cyan) !important;
    margin-top: 25px;
    margin-bottom: 15px;
}
.analysis-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background: rgba(18, 18, 26, 0.5);
}
.analysis-content th, .analysis-content td {
    border: 1px solid rgba(0, 245, 255, 0.2);
    padding: 12px;
    text-align: left;
}
.analysis-content th {
    background: rgba(0, 245, 255, 0.1);
    color: var(--neon-cyan);
}
.analysis-content td {
    color: var(--text-primary);
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
