<?php
/**
 * includes/footer.php - Pied de page commun du portail
 * 
 * Design futuriste inspiré de 2advanced Studios
 * Compatible Hostinger Mutualisé
 */
?>
    </main>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="brand-font mb-3" style="color: var(--neon-cyan);">
                        <i class="fas fa-shield-halved me-2"></i>ETHICAL MISTRAL
                    </h5>
                    <p style="color: var(--text-secondary); line-height: 1.8;">
                        Plateforme d'analyse éthique et juridique des crises humanitaires 
                        propulsée par l'IA Mistral. Analyse factuelle, rigueur juridique, 
                        conscience éthique.
                    </p>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="brand-font mb-3" style="font-size: 0.9rem;">Navigation</h6>
                    <ul class="list-unstyled" style="line-height: 2;">
                        <li><a href="index.php" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--neon-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Dashboard</a></li>
                        <li><a href="portal_auditor.php" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--neon-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Auditeur</a></li>
                        <li><a href="portal_jurist.php" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--neon-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Juriste</a></li>
                        <li><a href="portal_survival.php" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--neon-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Survie</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="brand-font mb-3" style="font-size: 0.9rem;">Compte</h6>
                    <ul class="list-unstyled" style="line-height: 2;">
                        <?php if (isLoggedIn()): ?>
                        <li><a href="account.php" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--neon-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Mon Compte</a></li>
                        <li><a href="logout.php" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--neon-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Déconnexion</a></li>
                        <?php else: ?>
                        <li><a href="login.php" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--neon-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Connexion</a></li>
                        <li><a href="register.php" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--neon-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">Inscription</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="brand-font mb-3" style="font-size: 0.9rem;">Statistiques Visiteurs</h6>
                    <div class="cyber-card p-3" style="padding: 15px !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="color: var(--text-secondary);"><i class="fas fa-globe me-2"></i>Visiteurs en ligne:</span>
                            <span class="stat-value" style="font-size: 1.5rem;" id="online-visitors">--</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: var(--text-secondary);"><i class="fas fa-map-marker-alt me-2"></i>Votre localisation:</span>
                            <span style="color: var(--neon-cyan);" id="footer-location">--</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr style="border-color: rgba(0, 245, 255, 0.1); margin: 30px 0;">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p style="color: var(--text-secondary); margin: 0; font-size: 0.9rem;">
                        &copy; <?php echo date('Y'); ?> Ethical Mistral Platform. Tous droits réservés.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p style="color: var(--text-secondary); margin: 0; font-size: 0.9rem;">
                        <i class="fas fa-code me-1"></i>Propulsé par <span style="color: var(--neon-cyan);">Mistral AI</span>
                        <span class="mx-2">|</span>
                        <i class="fas fa-server me-1"></i>Hébergé sur <span style="color: var(--neon-blue);">Hostinger</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    <script>
        // Mise à jour dynamique des statistiques
        function updateVisitorStats() {
            fetch('api/visitor_stats.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('online-visitors').textContent = data.online || '--';
                    if (data.location) {
                        document.getElementById('footer-location').textContent = 
                            data.location.city + ', ' + data.location.country;
                    }
                })
                .catch(err => console.log('Stats non disponibles'));
        }
        
        // Mettre à jour toutes les 30 secondes
        updateVisitorStats();
        setInterval(updateVisitorStats, 30000);
        
        // Animation d'apparition au scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.cyber-card, .stat-indicator').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });
        
        // Gestion des notifications toast
        function showToast(message, type = 'info') {
            const colors = {
                'success': '#00ff88',
                'error': '#ff2a6d',
                'warning': '#ffaa00',
                'info': '#00f5ff'
            };
            
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 m-4 p-3 rounded shadow-lg';
            toast.style.cssText = `
                background: rgba(10, 10, 15, 0.95);
                border: 1px solid ${colors[type] || colors.info};
                color: ${colors[type] || colors.info};
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>${message}`;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Ajouter les animations slide
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
