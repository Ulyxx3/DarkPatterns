<?php
session_start();
if (!isset($_SESSION['cart']))
    $_SESSION['cart'] = [];
$cartCount = array_sum(array_column($_SESSION['cart'], 'qty'));
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'DarkBuy™ — Les Meilleures Affaires du Monde Entier Garanti' ?></title>
    <meta name="description"
        content="DarkBuy™ - Achetez MAINTENANT avant que les prix n'explosent ! Livraison GRATUITE* dès 0€ d'achat. Satisfaction garantie** Promo exceptionnelle se termine AUJOURD'HUI.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/darkpatterns.css">
    <?= isset($extraCSS) ? $extraCSS : '' ?>
</head>

<body>

    <!-- DARK PATTERN: Cookie Banner impossible à refuser -->
    <div id="cookie-overlay" class="cookie-overlay">
        <div class="cookie-banner">
            <div class="cookie-header">
                <span class="cookie-icon">🍪</span>
                <h2>Nous respectons votre vie privée*</h2>
                <p class="cookie-subtitle">*Termes et conditions s'appliquent (voir page 847 de nos CGU)</p>
            </div>
            <p class="cookie-text">
                DarkBuy™ et nos <strong>47 947 partenaires de confiance</strong> utilisent des cookies pour améliorer
                votre expérience,
                vous tracker sur tous vos appareils, partager vos données avec des tiers, analyser vos habitudes
                alimentaires,
                prédire vos achats futurs et diffuser des publicités ultra-ciblées basées sur vos pensées intimes.
            </p>
            <div class="cookie-buttons">
                <button class="btn-accept-all" onclick="acceptCookies()">
                    ✅ Tout accepter et continuer
                </button>
                <button class="btn-manage" onclick="showCookieDetails()">
                    Gérer mes préférences (47 catégories)
                </button>
            </div>
            <p class="cookie-fine-print">
                En cliquant sur "Gérer", vous acceptez automatiquement nos cookies analytiques et marketing.
                Pour refuser, envoyez un courrier recommandé à notre siège social à Luxembourg (Les îles Caïmans, en
                réalité).
            </p>
        </div>
    </div>

    <!-- Cookie "Gestion" labyrinthe -->
    <div id="cookie-detail-modal" class="cookie-detail-modal hidden">
        <div class="cookie-detail-content">
            <h3>🔒 Gestion des Préférences de Confidentialité</h3>
            <p class="cookie-detail-subtitle">Personnalisez vos paramètres (toutes les cases sont obligatoires pour le
                bon fonctionnement du site)</p>
            <div class="cookie-categories">
                <div class="cookie-cat">
                    <label><input type="checkbox" checked disabled> <strong>Cookies essentiels</strong>
                        (obligatoires)</label>
                    <p>Nécessaires au fonctionnement. Non désactivables.</p>
                </div>
                <div class="cookie-cat">
                    <label><input type="checkbox" id="ck-analytics" checked> <strong>Cookies analytiques</strong>
                        (recommandés)</label>
                    <p>Nous aident à comprendre comment vous naviguez (et à vendre ces infos).</p>
                </div>
                <div class="cookie-cat">
                    <label><input type="checkbox" id="ck-marketing" checked> <strong>Cookies marketing</strong> (for
                        your benefit)</label>
                    <p>Publicités personnalisées basées sur toute votre activité en ligne depuis 2012.</p>
                </div>
                <div class="cookie-cat">
                    <label><input type="checkbox" id="ck-partners" checked> <strong>Cookies partenaires</strong> (47 947
                        sociétés)</label>
                    <p>Nos partenaires de confiance dans 83 pays dont 12 paradis fiscaux.</p>
                </div>
                <div class="cookie-cat">
                    <label><input type="checkbox" id="ck-ai" checked> <strong>Profiling IA
                            comportemental</strong></label>
                    <p>Notre IA analyse vos émotions à travers votre webcam (avec votre consentement implicite).</p>
                </div>
            </div>
            <div class="cookie-detail-buttons">
                <button class="btn-accept-all" onclick="acceptCookies()">Sauvegarder et accepter</button>
                <button class="btn-reject-all" onclick="showRefuseFlow()">Tout refuser</button>
            </div>
        </div>
    </div>

    <!-- Refuse flow (labyrinthe impossible) -->
    <div id="cookie-refuse-modal" class="cookie-detail-modal hidden">
        <div class="cookie-detail-content">
            <h3>⚠️ Êtes-vous VRAIMENT sûr(e) ?</h3>
            <p>En refusant les cookies, vous acceptez :</p>
            <ul style="text-align:left;margin:1rem 0;font-size:0.9rem;color:#ccc">
                <li>❌ Une expérience dégradée à 4800%</li>
                <li>❌ Des pages en noir et blanc</li>
                <li>❌ Des prix 40% plus élevés (prix sans personnalisation)</li>
                <li>❌ Votre commande pourrait être annulée</li>
                <li>❌ Votre karma pourrait en souffrir</li>
            </ul>
            <div class="cookie-detail-buttons">
                <button class="btn-accept-all" onclick="acceptCookies()">✅ Finalement j'accepte tout</button>
                <button class="btn-reject-small" onclick="cookieLabyrinth(1)">Continuer à refuser ›</button>
            </div>
        </div>
    </div>

    <!-- EXIT INTENT POPUP -->
    <div id="exit-popup" class="exit-popup hidden">
        <div class="exit-popup-content">
            <button class="exit-popup-close" onclick="closeExitPopup()">×</button>
            <div class="exit-popup-badge">🚨 ATTENDEZ !</div>
            <h2>Vous partez vraiment ?! 😱</h2>
            <p>Vous êtes sur le point de rater l'offre du SIÈCLE.</p>
            <div class="exit-offer">
                <span class="exit-old-price">Économie de 999€</span>
                <div class="exit-timer" id="exit-timer">03:47</div>
                <p class="exit-timer-text">Cette offre expire dans :</p>
            </div>
            <p class="exit-guilt">Vos concurrent(e)s prennent de l'avance pendant que vous hésitez...</p>
            <button class="btn-exit-accept" onclick="closeExitPopup()">
                🛒 Rester et PROFITER de l'offre !
            </button>
            <button class="btn-exit-refuse" onclick="document.getElementById('exit-popup').classList.add('hidden')">
                Non merci, je préfère perdre de l'argent et rater ma vie
            </button>
        </div>
    </div>

    <!-- DARK PATTERN: Notification push -->
    <div id="push-notif" class="push-notif hidden">
        <span class="push-icon">🔔</span>
        <div>
            <strong>Autoriser les notifications ?</strong>
            <p>Pour recevoir vos offres exclusives en temps réel</p>
        </div>
        <button class="push-allow" onclick="allowPush()">Autoriser</button>
        <button class="push-deny" onclick="denyPush()">✕</button>
    </div>

    <!-- HEADER -->
    <header class="site-header">
        <div class="header-top-bar">
            <marquee class="urgency-marquee">
                🔥 VENTE FLASH : -90% sur TOUT — Se termine dans
                <span id="global-timer">04:32:11</span> —
                🚀 Livraison GRATUITE* dès 0€ (*+frais de traitement 6,99€) —
                ⭐ Plus de 3 millions de clients TRES SATISFAITS —
                🔥 VENTE FLASH : -90% sur TOUT — Se termine dans
                <span>04:32:11</span>
            </marquee>
        </div>
        <nav class="main-nav">
            <a href="index.php" class="logo">
                <span class="logo-dark">Dark</span><span class="logo-buy">Buy™</span>
                <span class="logo-badge">OFFICIEL</span>
            </a>
            <div class="nav-center">
                <div class="search-wrapper">
                    <input type="text" placeholder="Chercher votre prochain regret..." class="search-input"
                        id="search-input">
                    <button class="search-btn">🔍</button>
                    <!-- DARK PATTERN: suggestions qui ne correspondent pas -->
                    <div class="search-suggestions hidden" id="search-suggestions">
                        <div class="suggestion-ad">📢 PUBLICITÉ</div>
                        <div class="suggestion-item">SuperOffre MAINTENANT →</div>
                        <div class="suggestion-item">Pack Famille Incontournable →</div>
                        <div class="suggestion-item">Abonnement VIP (recommandé) →</div>
                    </div>
                </div>
            </div>
            <div class="nav-right">
                <a href="login.php" class="nav-link">
                    <span class="nav-icon">👤</span>
                    <?php if (isset($_SESSION['user'])): ?>
                        <span><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                    <?php else: ?>
                        <span>Connexion</span>
                    <?php endif; ?>
                </a>
                <a href="cart.php" class="nav-link cart-link">
                    <span class="nav-icon">🛒</span>
                    <span>Panier</span>
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </nav>
        <!-- DARK PATTERN: bannière urgence sous le nav -->
        <div class="sub-header-urgency">
            <span>⏰</span>
            <span>Offre exclusive membre se termine dans <strong id="sub-timer">04:32:11</strong></span>
            <span>•</span>
            <span><strong id="viewers-count">47</strong> personnes regardent en ce moment</span>
            <span>•</span>
            <span>Stocks limités — commandez maintenant !</span>
        </div>
    </header>

    <script>
        // Cookie functions
        function acceptCookies() {
            document.getElementById('cookie-overlay').style.display = 'none';
            document.getElementById('cookie-detail-modal').classList.add('hidden');
            document.getElementById('cookie-refuse-modal').classList.add('hidden');
            localStorage.setItem('dp_cookies', 'all_accepted_no_choice');
            setTimeout(showPushNotif, 3000);
        }

        function showCookieDetails() {
            document.getElementById('cookie-detail-modal').classList.remove('hidden');
        }

        function showRefuseFlow() {
            document.getElementById('cookie-detail-modal').classList.add('hidden');
            document.getElementById('cookie-refuse-modal').classList.remove('hidden');
        }

        let labyrinthStep = 0;
        function cookieLabyrinth(step) {
            labyrinthStep = step;
            const messages = [
                "Vraiment vraiment sûr(e) ?",
                "Mais nos cookies sont si gentils... 🥺",
                "Dernier avertissement : prix augmentés de 40% sans cookies",
                "OK mais c'est votre perte. Confirmez en tapant 'JE REFUSE' ci-dessous :",
                "Pour finaliser votre refus, consultez notre délégué à la protection des données (disponible les mardis de 14h à 14h15)",
            ];
            if (step < messages.length) {
                document.querySelector('#cookie-refuse-modal h3').textContent = messages[step];
                document.querySelector('#cookie-refuse-modal .btn-reject-small').onclick = () => cookieLabyrinth(step + 1);
            } else {
                acceptCookies(); // After enough tries, just accept
            }
        }

        function showPushNotif() {
            const notif = document.getElementById('push-notif');
            notif.classList.remove('hidden');
            notif.classList.add('slide-in');
        }
        function allowPush() { document.getElementById('push-notif').classList.add('hidden'); }
        function denyPush() {
            document.getElementById('push-notif').classList.add('hidden');
            setTimeout(showPushNotif, 10000); // Show again after 10s
        }

        // Check cookie on load
        window.addEventListener('load', function () {
            if (!localStorage.getItem('dp_cookies')) {
                setTimeout(() => {
                    document.getElementById('cookie-overlay').style.display = 'flex';
                }, 800);
            }
        });

        // Global timer countdown
        function startGlobalTimer() {
            let total = 4 * 3600 + 32 * 60 + 11;
            const els = document.querySelectorAll('#global-timer, #sub-timer');
            setInterval(() => {
                if (total <= 0) total = 4 * 3600 + 59 * 60 + 59; // Resets! FOMO fake
                total--;
                const h = Math.floor(total / 3600);
                const m = Math.floor((total % 3600) / 60);
                const s = total % 60;
                const str = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                els.forEach(el => { if (el) el.textContent = str; });
            }, 1000);
        }
        startGlobalTimer();

        // Fake viewer count
        function updateViewers() {
            const el = document.getElementById('viewers-count');
            if (el) {
                const base = 31 + Math.floor(Math.random() * 40);
                el.textContent = base;
            }
            setTimeout(updateViewers, 2000 + Math.random() * 3000);
        }
        updateViewers();

        // Exit intent
        let exitShown = false;
        document.addEventListener('mouseleave', function (e) {
            if (e.clientY < 10 && !exitShown) {
                exitShown = true;
                document.getElementById('exit-popup').classList.remove('hidden');
                startExitTimer();
            }
        });

        function startExitTimer() {
            let s = 3 * 60 + 47;
            const el = document.getElementById('exit-timer');
            const itv = setInterval(() => {
                if (s <= 0) { clearInterval(itv); if (el) el.textContent = "EXPIRÉE !"; return; }
                s--;
                const m2 = Math.floor(s / 60); const s2 = s % 60;
                if (el) el.textContent = `${String(m2).padStart(2, '0')}:${String(s2).padStart(2, '0')}`;
            }, 1000);
        }

        function closeExitPopup() {
            document.getElementById('exit-popup').classList.add('hidden');
            exitShown = false;
        }

        // Search suggestions
        document.getElementById('search-input')?.addEventListener('focus', function () {
            document.getElementById('search-suggestions')?.classList.remove('hidden');
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.search-wrapper')) {
                document.getElementById('search-suggestions')?.classList.add('hidden');
            }
        });
    </script>