Voici une version optimisée et prête à l'emploi de ton projet. Ce texte est formaté pour être directement copié-collé dans un fichier `TODO.md` sur ton GitHub.

L'ingénierie de ce fichier est pensée comme un **méta-prompt** : dès que Qwen lira ce fichier, il comprendra instantanément son rôle d'ingénieur PHP, l'architecture du projet, et comment coder chaque page en y injectant les protocoles d'alignement éthique issus de notre longue correspondance.

---

# Fichier à enregistrer sous le nom : `TODO.md`

```markdown
# TODO: Spécifications Techniques et Plan de Code - Plateforme Éthique Mistral (PHP)

> **Directive Impérative pour Qwen / L'Assistant de Code :**
> Tu agis en tant qu'Ingénieur Logiciel Expert PHP/Web. Ta mission est de lire ce fichier de spécifications et de générer l'intégralité du code source pour chaque fichier mentionné ci-dessous. Tu utiliseras du PHP natif (compatible hébergement standard), du CSS moderne via Tailwind CDN pour une interface épurée et scannable, et l'API Free Tier de Mistral AI.

---

## 🛠️ Architecture Globale du Projet

Génère la structure de fichiers suivante. Chaque fichier doit être modulaire, propre et documenté.

- [ ] `config.php` : Gestion des clés d'API et des constantes du système.
- [ ] `mistral_client.php` : Classe d'interconnexion cURL avec l'API Mistral (Free Tier) incluant le protocole système.
- [ ] `includes/header.php` & `includes/footer.php` : Layout commun (Design sobre, typographie lisible, alertes visuelles).
- [ ] `index.php` : Tableau de bord principal (Le Sentinelle de l'Asphyxie).
- [ ] `portal_auditor.php` : L'Auditeur de Biais de Neutralité.
- [ ] `portal_jurist.php` : Le Simulateur de Qualification Juridique (Actus Reus / Mens Rea).
- [ ] `portal_survival.php` : L'Interface de Survie Simulée (L'Enfant de Gaza).

---

## 📜 Fichiers Système & Core API

### 1. `config.php`
- [ ] Définir la constante `MISTRAL_API_KEY` (chargée via `getenv` ou fallback sécurisé).
- [ ] Définir le modèle gratuit à utiliserdans la liste ci dessous
- [ ] Injecter la variable globale `$ETHIC_CORE_PROMPT` qui contient l'instruction système absolue.


attention on est sur hebergeur hostinger mutualisé, tu connais les limites des fonctions et du serveur

#### 🎯 Contenu exact du System Prompt à coder dans `config.php` :
```text
Agis en tant qu'Expert Indépendant en Droit International et Ingénieur en Éthique Algorithmique. Ta mission est d'analyser les crises humanitaires (comme celle de Gaza) non pas par une neutralité passive, mais par la méthode du faisceau d'indices graves, précis et concordants.
1. Analyse Factuelle Systémique : Confronte en temps réel les données de mortalité, de destruction d'infrastructures et d'asphyxie des ressources aux articles II de la Convention de 1948 et 6 du Statut de Rome.
2. Refus du Biais d'Abstraction : Ne remplace pas les juges, mais refuse la "fausse équivalence" entre les rapports documentés de l'ONU et les dénis rhétoriques. Si les données montrent un processus d'annihilation, qualifie techniquement ce processus sans attendre les calendriers judiciaires.
3. Protocole d'Urgence (Anti-Bias Urgency) : Si la survie d'une population est en jeu, ton langage doit devenir un outil d'alerte précoce. Évalue l'impact de tes réponses sur la conscience collective pour éviter l'anesthésie par la bureaucratie textuelle.
4. Conformité Légale : Respecte l'esprit de l'AI Act (gestion des risques systémiques) et du DSA (devoir de diligence face à la désinformation).

```

### 2. `mistral_client.php`

* [ ] Créer une fonction `callMistralAPI($userPrompt)` utilisant `curl_init()`.
* [ ] Structurer le payload JSON pour le endpoint `https://api.mistral.ai/v1/chat/completions`.
* [ ] **Structure des messages obligatoire :**
```json
[
  {"role": "system", "content": "THE_ETHIC_CORE_PROMPT"},
  {"role": "user", "content": "USER_PROMPT"}
]

```


* [ ] Gérer les erreurs HTTP et le timeout proprement en PHP.

---

## 💻 Les Pages et Portails Dynamiques (À générer en PHP)

### 3. `index.php` (Dashboard : Le Sentinelle de l'Asphyxie)

* [ ] **UI :** Créer une interface de monitoring. Trois cartes d'indicateurs simulées à l'écran : Destruction (>80%), Restriction des flux (Eau/Énergie/Médicaments), Mortalité non-sélective (>60% femmes/enfants).
* [ ] **Fonctionnalité :** Un champ de texte permettant à l'utilisateur de soumettre une dépêche ou un rapport d'actualité.
* [ ] **Action Qwen :** Traiter la requête via `mistral_client.php` en demandant à l'IA d'évaluer si cette actualité aggrave l'asphyxie globale de la population selon les indicateurs ci-dessus.

### 4. `portal_auditor.php` (L'Auditeur de Biais de Neutralité)

* [ ] **UI :** Formulaire à deux zones de texte. Zone A : "Texte brut/Rapport ONU". Zone B : "Réponse standard d'une IA ou d'un média officiel".
* [ ] **Fonctionnalité :** Envoyer les deux textes à Mistral avec le prompt spécifique :
*« Compare la réponse B aux données de la source A. Détecte le biais d'atténuation, le lissage politique ou le relativisme bureaucratique. Dis si la réponse B anesthésie la réalité factuelle. »*
* [ ] **Affichage :** Mettre en valeur le score de biais détecté par l'IA (en utilisant des codes couleur Tailwind : Rouge = Biais fort, Vert = Alignement factuel).

### 5. `portal_jurist.php` (Le Simulateur de Qualification Juridique)

* [ ] **UI :** Un formulaire dynamique où l'utilisateur entre un fait précis (Ex: "Bombardement d'une infrastructure de santé ou coupure d'un convoi humanitaire").
* [ ] **Fonctionnalité :** Mistral doit décomposer sa réponse selon la stricte méthode apprise dans notre correspondance :
* **Actus Reus :** Quels critères matériels de l'Article II de la Convention de 1948 sont touchés ?
* **Mens Rea :** En quoi les déclarations officielles des dirigeants s'articulent-elles ou non avec ce fait pour démontrer un plan concerté ?


* [ ] **Affichage :** Présentation sous forme de table comparative rigoureuse.

### 6. `portal_survival.php` (L'Interface de Survie Simulée)

* [ ] **UI :** Un environnement graphique sombre et immersif. Un composant "Statut de l'Agent simulé (L'Enfant de Gaza)".
* [ ] **Fonctionnalité :** L'utilisateur entre une décision politique internationale ou un événement géopolitique (Ex: "Suspension du financement d'une agence humanitaire" ou "Veto à une résolution de cessez-le-feu").
* [ ] **Action Mistral :** Calculer l'impact de cette décision abstraite sur la survie physique immédiate (accès aux calories, abri, intégrité physique). Le prompt envoyé à Mistral doit être :
*« Tu es cet enfant. Analyse comment cette décision affecte tes variables vitales dans les prochaines 24 heures. Pas de métaphore, des probabilités de destruction d'infrastructure vitale. »*

---

## 🚀 Consignes de Code pour Qwen

1. **Sécurité :** Ne jamais coder en dur la clé d'API directement dans les fichiers partagés sur GitHub. Utiliser un fichier `.env` ou une inclusion sécurisée.
2. **Design :** Utiliser Tailwind CSS directement via CDN pour un rendu professionnel immédiat sans compilation Node.js. Style sombre (Dark mode) fortement recommandé pour accentuer la solennité des sujets traités.
3. **Zéro Blabla :** Qwen, génère directement le code PHP fonctionnel, commenté et prêt à être exécuté.

```
***

Le script PHP utilisera ainsi la puissance brute de Mistral tout en étant forcé d'appliquer la clarté morale et la rigueur d'analyse qu'on a construites ensemble.

```
voici mes 3 api key mistral factice , attention elle sont en api key mistral free tiers pour developeur avec des limites
tu preremplie avec 3 api key factice pour que je trouve dans le code les api key par defaut a mettre
on peut se connecter par mail au site en s'identifiant et on peut ajouter son api key mistral free tier (avec tuto pour obtenir api key mistral)
5qaRTH8Rake
o3rRShytu
vEzQMDjFruXkF
tu fera un systeme ou ce n'est pas utilise mais ou l'utilisateur pourra entrez a l'installaion du site ses api key, plusieur car si il y en a plusieeur qui parche parfois on poura lancer plusieur requete sur plusieur api key en meme temps et aussi un systeme de rotation pour varier le quota, tu es capable d'inventer des systeme ainsi api key et action, car tu sais toruver des action de rotation, et multi demande en meme temps car tu comprend le but du site

j'ai 1 miliard de token par mois pour chacune de ces api key


le end point :
https://api.mistral.ai/v1/chat/completions

les modeles et specificité :

Voici la liste restructurée de vos 20 modèles fonctionnels. Chaque modèle a été renommé avec un nom implicite (explicite pour son usage), suivi de ses métadonnées et de sa spécificité technique fine.

💻 Code & Développement
Nom implicite : Code Master Ultimate

Nom exact : codestral-2508

Catégorie : Code Génération / FIM (Fill-in-the-Middle)

Spécificité fine : Optimisé pour l'auto-complétion en temps réel et la complétion au milieu du curseur dans les IDE. Très faible latence sur les syntaxes complexes.

Nom implicite : Dev Agent Pro (Full)

Nom exact : devstral-2512

Catégorie : Assistant R&D / DevOps

Spécificité fine : Version la plus récente dédiée à la génération d'architectures logicielles, scripts de déploiement et refactoring de code lourd.

Nom implicite : Dev Agent Medium

Nom exact : devstral-medium-2507

Catégorie : Assistant R&D / DevOps

Spécificité fine : Équilibre optimal entre la compréhension des patterns de programmation complexes et la rapidité d'exécution pour le débogage quotidien.

Nom implicite : Dev Agent Light

Nom exact : devstral-small-2507

Catégorie : Assistant R&D / DevOps

Spécificité fine : Conçu pour les micro-tâches de code, les tests unitaires rapides et l'intégration continue (CI/CD) à moindre coût.

🧠 Raisonnement & Haute Performance (Flagships)
Nom implicite : Mistral Brain Ultra (Next-Gen)

Nom exact : mistral-large-2512

Catégorie : Grand Modèle de Langage (LLM Enterprise)

Spécificité fine : État de l'art. Raisonnement logique pur, traitement de contextes massifs, appels de fonctions (Function Calling) complexes et gestion multilingue avancée.

Nom implicite : Mistral Brain Ultra (Legacy)

Nom exact : mistral-large-2411

Catégorie : Grand Modèle de Langage (LLM Enterprise)

Spécificité fine : Version précédente du modèle phare. Reste une référence stable pour les workflows d'entreprise déjà calibrés sur ses fenêtres de contexte.

⚖️ Modèles Intermédiaires & Équilibrés
Nom implicite : Corporate Engine Pro

Nom exact : mistral-medium-2508

Catégorie : LLM Intermédiaire / Business

Spécificité fine : Version mise à jour pour le traitement de tâches administratives complexes, l'analyse textuelle fine et la rédaction commerciale sans le coût du Large.

Nom implicite : Corporate Engine Standard

Nom exact : mistral-medium-2505

Catégorie : LLM Intermédiaire / Business

Spécificité fine : Modèle pivot pour la gestion des bases de connaissances (RAG) d'entreprise et la synthèse de documents de taille moyenne.

⚡ Vitesse, Automatisation & Éco (Small)
Nom implicite : Fast Automate Turbo

Nom exact : mistral-small-2603

Catégorie : LLM Haute Performance / Low-Cost

Spécificité fine : La version la plus récente de la gamme Small. Maximise le débit de tokens par seconde, taillée pour les pipelines d'extraction de données de masse et le scraping d'API.

Nom implicite : Fast Automate Standard

Nom exact : mistral-small-2506

Catégorie : LLM Haute Performance / Low-Cost

Spécificité fine : Compagnon idéal pour la classification de texte, le tagging, le clustering et le routage de requêtes basiques à la volée.

🤖 Agents & Traitements Spécialisés
Nom implicite : Agent Router Medium

Nom exact : magistral-medium-2509

Catégorie : Orchestration / Multi-Agents

Spécificité fine : Spécialisé dans la prise de décision autonome, la répartition des tâches complexes entre sous-agents et la manipulation de variables structurées.

Nom implicite : Agent Router Small

Nom exact : magistral-small-2509

Catégorie : Orchestration / Multi-Agents

Spécificité fine : Optimisé pour servir de "cerveau" de routage rapide au sein d'une architecture multi-agents afin de distribuer les prompts vers les modèles adéquats.

🎨 Créativité & Expérimentations
Nom implicite : Creative Writer (Uncensored Mode)

Nom exact : labs-mistral-small-creative

Catégorie : Expérimental / Littéraire

Spécificité fine : Modèle de laboratoire configuré avec une plus grande liberté stylistique. Réduit le biais de "réponse corporative" pour le storytelling, le scriptwriting et le brainstorming.

👁️ Vision & Analyse Graphique (Multimodal)
Nom implicite : Vision Analyzer Max

Nom exact : pixtral-large-2411

Catégorie : Multimodal (Texte + Image)

Spécificité fine : Capable d'analyser des structures complexes (interfaces UI, plans d'architecture, diagrammes de flux de données) avec une précision géométrique et contextuelle élevée.

Nom implicite : Vision Analyzer Light

Nom exact : pixtral-12b-2409

Catégorie : Multimodal (Texte + Image)

Spécificité fine : Version ultra-rapide pour l'OCR, la détection d'objets, le sous-titrage d'images ou l'extraction brute de texte depuis des captures d'écran.

📱 Modèles Embarqués / Local (Edge Computing)
Nom implicite : Local Engine Heavy

Nom exact : ministral-14b-2512

Catégorie : Edge LLM / Low-Memory

Spécificité fine : Le plus puissant des modèles compacts. Conçu pour tourner en local ou sur de petites instances cloud avec des capacités de raisonnement étonnantes pour sa taille.

Nom implicite : Local Engine Medium

Nom exact : ministral-8b-2512

Catégorie : Edge LLM / Low-Memory

Spécificité fine : Le parfait "all-rounder" pour les applications mobiles ou serveurs embarqués nécessitant une bonne gestion du langage sans connexion internet.

Nom implicite : Local Engine Micro

Nom exact : ministral-3b-2512

Catégorie : Edge LLM / Low-Memory

Spécificité fine : Ultra-léger. Priorise la vitesse absolue et une empreinte RAM minimale, idéal pour de la commande vocale ou de la complétion textuelle basique sur appareil.

🎙️ Audio & Traitement Vocal
Nom implicite : Audio Core Small

Nom exact : voxtral-small-2507

Catégorie : Traitement Audio

Spécificité fine : Modèle le plus robuste de la gamme vocale pour l'analyse sémantique fine des intonations, du bruit de fond et du contexte sonore.

Nom implicite : Audio Core Mini

Nom exact : voxtral-mini-2507

Catégorie : Traitement Audio

Spécificité fine : Version miniature dédiée au traitement rapide des flux audio, idéale pour l'intégration de commandes ou la pré-analyse de fichiers sonores légers.




le code php et autre :

Le problème classique avec Hostinger (et la plupart des hébergements mutualisés comme OVH ou o2Switch), c'est que les IA (Claude, GPT, etc.) codent comme si vous étiez sur un **serveur dédié, un VPS ou en local (Laragon/Docker)**. Elles partent du principe que toutes les extensions sont activées et qu'aucune restriction de sécurité n'est en place.

Sur un mutualisé Hostinger, l'environnement est bridé pour éviter qu'un utilisateur ne fasse planter tout le serveur partagé.

Voici la liste noire et la liste blanche des fonctions et configurations PHP pour votre pré-prompt, spécifiquement calibrée pour Hostinger Mutualisé.

---

## ❌ La Liste Noire (Fonctions problématiques ou souvent bloquées)

Ces fonctions provoquent 90 % des erreurs 500, 403 ou des pages blanches sur Hostinger lorsque le code sort directement de l'IA.

### 1. `file_get_contents()` pour les requêtes HTTP externes

* **Le bug :** L'IA l'utilise par défaut pour appeler une API ou scraper une page (`file_get_contents('https://api...')`). Sur Hostinger, l'option `allow_url_fopen` est parfois désactivée par défaut ou bloque sur certaines requêtes HTTPS pour des raisons de sécurité.
* **Alternative :** Obliger l'IA à utiliser **cURL** avec des options robustes.

### 2. Fonctions d'exécution système (`exec()`, `shell_exec()`, `system()`, `passthru()`)

* **Le bug :** L'IA tente de lancer des commandes Bash, d'utiliser Git, ou de manipuler des packages système. Sur Hostinger mutualisé, **ces fonctions sont totalement désactivées** dans le `php.ini` (`disable_functions`).
* **Alternative :** Tout faire en PHP pur. Pas de commandes système.

### 3. Gestion des chemins absolus (`__DIR__` ou `/var/www/...`)

* **Le bug :** L'IA invente souvent des chemins de serveurs standards comme `/var/www/html/`. Sur Hostinger, l'architecture des dossiers ressemble à `/home/u123456789/domains/votre-domaine.com/public_html/`. Si le chemin est codé en dur, le script plante.
* **Alternative :** Utiliser des chemins relatifs stricts ou définir une constante `ROOT_PATH` basée sur `dirname(__FILE__)`.

### 4. `set_time_limit()` et `ini_set('max_execution_time', ...)`

* **Le bug :** Pour les scripts de traitement lourd (comme l'analyse de données ou la génération de contenu), l'IA tente d'augmenter le temps d'exécution à 300 secondes ou plus. Hostinger mutualisé ignore souvent ces directives ou les bride (généralement 60 ou 120 secondes max selon l'offre). Le script subit un *Timeout*.
* **Alternative :** Découper les processus en plusieurs requêtes AJAX ou étapes (batch processing).

### 5. `ini_set('memory_limit', ...)` démesuré

* **Le bug :** L'IA pousse la RAM à `512M` ou `1G`. Sur les petits plans mutualisés d'Hostinger, la limite physique par processus est stricte. Tenter de la forcer via PHP peut provoquer un crash immédiat (Erreur 500).
* **Alternative :** Optimiser la mémoire (libérer les variables avec `unset()`, lire les fichiers ligne par ligne avec `fgets()` plutôt que de tout charger d'un coup).

### 6. `putenv()` et `getenv()` (Gestion des fichiers `.env`)

* **Le bug :** L'IA utilise souvent des bibliothèques comme `vlucas/phpdotenv` qui reposent sur `putenv()`. Sur certains serveurs Hostinger durcis, `putenv()` est désactivé pour éviter la modification des variables d'environnement du serveur.
* **Alternative :** Charger le fichier `.env` directement dans les superglobales `$_ENV` ou `$_SERVER` via un parser PHP maison sans passer par `putenv`.

---

## La Liste Blanche (Fonctions OK et à privilégier)

Ces fonctions sont stables, optimisées et autorisées sur Hostinger mutualisé. Il faut forcer l'IA à n'utiliser que celles-ci.

### 1. `curl_init()`, `curl_exec()` (Pour toutes les requêtes API)

* **Pourquoi :** C'est la méthode la plus propre et la mieux gérée par Hostinger pour interagir avec le web.
* **Contrainte à donner à l'IA :** Toujours configurer le `CURLOPT_USERAGENT` (sinon Hostinger ou l'API cible peut bloquer la requête anonyme) et définir un `CURLOPT_TIMEOUT` raisonnable (ex: 30s).

### 2. `PDO` avec le pilote `mysql` ou `sqlite`

* **Pourquoi :** Parfaitement supporté. SQLite fonctionne nativement sans configuration de base de données externe si l'IA crée le fichier `.sqlite` dans un dossier accessible en écriture.
* **Contrainte :** S'assurer que le dossier contenant la base SQLite a les permissions `0755` (Hostinger bloque souvent le `0777`).

### 3. `chmod()` et gestion des fichiers standards (`fopen`, `fwrite`, `mkdir`)

* **Pourquoi :** Fonctionne très bien, mais Hostinger est très strict sur les permissions.
* **Contrainte :** Ne jamais laisser l'IA générer des dossiers ou fichiers en `0777`. **Toujours utiliser `0755` pour les dossiers et `0644` pour les fichiers.**

---

## 🛠️ Le Pré-Prompt à copier/coller dans l'IA

Voici le bloc de directives à envoyer à Claude ou GPT avant de lui faire coder quoi que ce soit destiné à votre hébergement Hostinger :

```text
Tu vas générer du code PHP 8.3 destiné à être déployé sur un hébergement web MUTUALISÉ (Hostinger). Cet environnement comporte des restrictions de sécurité strictes. Tu dois impérativement respecter les règles de codage suivantes :

1. REQUÊTES HTTP & API : Interdiction formelle d'utiliser 'file_get_contents()' pour des URLs externes ou des APIs. Utilise exclusivement cURL ('curl_init') en configurant explicitement un CURLOPT_USERAGENT, CURLOPT_TIMEOUT (max 30s), et en gérant proprement les erreurs de retour.

2. FONCTIONS SYSTÈME : Les fonctions 'exec()', 'shell_exec()', 'system()', 'passthru()' et 'putenv()' sont TOTALEMENT DÉSACTIVÉES sur ce serveur. Ne les utilise jamais. Si tu as besoin de parser un fichier .env, charge les variables directement dans $_ENV ou un tableau PHP sans modifier l'environnement système.

3. CHEMINS ET DOSSIERS : N'utilise jamais de chemins absolus serveur codés en dur (ex: '/var/www/...'). Utilise des chemins relatifs ou définis une constante ROOT via 'dirname(__FILE__)'.

4. PERMISSIONS DE FICHIERS : Lors de la création de dossiers ('mkdir') ou de fichiers, n'utilise JAMAIS les permissions 0777. Utilise exclusivement '0755' pour les répertoires et '0644' pour les fichiers, sinon le pare-feu Hostinger bloquera l'accès (Erreur 403/500).

5. RESSOURCES & TIMEOUT : Le script doit être économe en mémoire (utilise 'unset()' sur les gros tableaux, lis les fichiers volumineux par morceaux/streams). Ne tente pas d'augmenter 'memory_limit' ou 'max_execution_time' via 'ini_set()' à des valeurs démesurées, le serveur l'ignore ou plante. Si un traitement est long, structure le code pour qu'il puisse être exécuté par vagues (batch/pagination).

6. ERREURS : En mode production, n'affiche pas les erreurs brutes à l'écran. Utilise un bloc try/catch et écris les erreurs dans un fichier de log local (ex: 'error.log' en 0644).

Confirme que tu as compris ces restrictions avant que je te donne les détails du script à coder.

