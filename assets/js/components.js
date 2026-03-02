// DarkBuy™ — Shared HTML Components (header + footer injection)
// Replaces PHP require_once 'includes/header.php' / footer.php

function renderHeader(pageTitle = 'DarkBuy™ — Les Meilleures Affaires du Monde Entier Garanti') {
    document.title = pageTitle;
    const headerEl = document.getElementById('site-header-placeholder');
    if (!headerEl) return;

    const user = Store.getUser();
    const cartCount = Store.getCartCount();

    headerEl.innerHTML = `
<!-- COOKIE OVERLAY -->
<div id="cookie-overlay" class="cookie-overlay">
  <div class="cookie-banner">
    <div class="cookie-header">
      <span class="cookie-icon">🍪</span>
      <h2>Nous respectons votre vie privée*</h2>
      <p class="cookie-subtitle">*Termes et conditions s'appliquent (voir page 847 de nos CGU)</p>
    </div>
    <p class="cookie-text">DarkBuy™ et nos <strong>47 947 partenaires de confiance</strong> utilisent des cookies pour améliorer votre expérience, vous tracker sur tous vos appareils, partager vos données avec des tiers, analyser vos habitudes alimentaires, prédire vos achats futurs et diffuser des publicités ultra-ciblées basées sur vos pensées intimes.</p>
    <div class="cookie-buttons">
      <button class="btn-accept-all" onclick="acceptCookies()">✅ Tout accepter et continuer</button>
      <button class="btn-manage" onclick="showCookieDetails()">Gérer mes préférences (47 catégories)</button>
    </div>
    <p class="cookie-fine-print">En cliquant sur "Gérer", vous acceptez automatiquement nos cookies analytiques et marketing. Pour refuser, envoyez un courrier recommandé à notre siège social à Luxembourg (Les îles Caïmans, en réalité).</p>
  </div>
</div>

<div id="cookie-detail-modal" class="cookie-detail-modal hidden">
  <div class="cookie-detail-content">
    <h3>🔒 Gestion des Préférences de Confidentialité</h3>
    <p class="cookie-detail-subtitle">Personnalisez vos paramètres (toutes les cases sont obligatoires pour le bon fonctionnement du site)</p>
    <div class="cookie-categories">
      ${[
            ['Cookies essentiels (obligatoires)', true, true, 'Nécessaires au fonctionnement. Non désactivables.'],
            ['Cookies analytiques (recommandés)', true, false, 'Nous aident à comprendre comment vous naviguez (et à vendre ces infos).'],
            ['Cookies marketing (for your benefit)', true, false, 'Publicités personnalisées basées sur toute votre activité en ligne depuis 2012.'],
            ['Cookies partenaires (47 947 sociétés)', true, false, 'Nos partenaires de confiance dans 83 pays dont 12 paradis fiscaux.'],
            ['Profiling IA comportemental', true, false, 'Notre IA analyse vos émotions à travers votre webcam (avec votre consentement implicite).'],
        ].map(([label, checked, disabled, desc]) => `
        <div class="cookie-cat">
          <label><input type="checkbox" ${checked ? 'checked' : ''} ${disabled ? 'disabled' : ''}> <strong>${label}</strong></label>
          <p>${desc}</p>
        </div>`).join('')}
    </div>
    <div class="cookie-detail-buttons">
      <button class="btn-accept-all" onclick="acceptCookies()">Sauvegarder et accepter</button>
      <button class="btn-reject-all" onclick="showRefuseFlow()">Tout refuser</button>
    </div>
  </div>
</div>

<div id="cookie-refuse-modal" class="cookie-detail-modal hidden">
  <div class="cookie-detail-content">
    <h3>⚠️ Êtes-vous VRAIMENT sûr(e) ?</h3>
    <p>En refusant les cookies, vous acceptez :</p>
    <ul style="text-align:left;margin:1rem 0;font-size:.9rem;color:#ccc">
      <li>❌ Une expérience dégradée à 4800%</li>
      <li>❌ Des pages en noir et blanc</li>
      <li>❌ Des prix 40% plus élevés</li>
      <li>❌ Votre commande pourrait être annulée</li>
      <li>❌ Votre karma pourrait en souffrir</li>
    </ul>
    <div class="cookie-detail-buttons">
      <button class="btn-accept-all" onclick="acceptCookies()">✅ Finalement j'accepte tout</button>
      <button class="btn-reject-small" onclick="cookieLabyrinth(1)">Continuer à refuser ›</button>
    </div>
  </div>
</div>

<!-- EXIT POPUP -->
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
    <button class="btn-exit-accept" onclick="closeExitPopup()">🛒 Rester et PROFITER de l'offre !</button>
    <button class="btn-exit-refuse" onclick="document.getElementById('exit-popup').classList.add('hidden')">Non merci, je préfère perdre de l'argent et rater ma vie</button>
  </div>
</div>

<!-- PUSH NOTIF -->
<div id="push-notif" class="push-notif hidden">
  <span class="push-icon">🔔</span>
  <div><strong>Autoriser les notifications ?</strong><p>Pour recevoir vos offres exclusives en temps réel</p></div>
  <button class="push-allow" onclick="allowPush()">Autoriser</button>
  <button class="push-deny" onclick="denyPush()">✕</button>
</div>

<!-- HEADER -->
<div class="header-top-bar">
  <marquee class="urgency-marquee">🔥 VENTE FLASH : -90% sur TOUT — Se termine dans <span class="global-timer">04:32:11</span> — 🚀 Livraison GRATUITE* dès 0€ d'achat (*+frais de traitement 6,99€) — ⭐ Plus de 3 millions de clients TRES SATISFAITS — 🔥 VENTE FLASH</marquee>
</div>
<nav class="main-nav">
  <a href="index.html" class="logo">
    <span class="logo-dark">Dark</span><span class="logo-buy">Buy™</span>
    <span class="logo-badge">OFFICIEL</span>
  </a>
  <div class="nav-center">
    <div class="search-wrapper">
      <input type="text" placeholder="Chercher votre prochain regret..." class="search-input" id="search-input">
      <button class="search-btn">🔍</button>
      <div class="search-suggestions hidden" id="search-suggestions">
        <div class="suggestion-ad">📢 PUBLICITÉ</div>
        <div class="suggestion-item">SuperOffre MAINTENANT →</div>
        <div class="suggestion-item">Pack Famille Incontournable →</div>
        <div class="suggestion-item">Abonnement VIP (recommandé) →</div>
      </div>
    </div>
  </div>
  <div class="nav-right">
    <a href="${user ? '#' : 'login.html'}" class="nav-link" onclick="${user ? 'return false' : ''}">
      <span class="nav-icon">👤</span>
      <span class="nav-user-name">${user ? user.name.split('@')[0] : 'Connexion'}</span>
    </a>
    <a href="cart.html" class="nav-link cart-link">
      <span class="nav-icon">🛒</span>
      <span>Panier</span>
      <span class="cart-badge cart-badge-wrap ${cartCount === 0 ? 'hidden' : ''}">${cartCount}</span>
    </a>
  </div>
</nav>
<div class="sub-header-urgency">
  <span>⏰</span>
  <span>Offre exclusive membre se termine dans <strong class="global-timer">04:32:11</strong></span>
  <span>•</span>
  <span><strong class="viewers-count">47</strong> personnes regardent en ce moment</span>
  <span>•</span>
  <span>Stocks limités — commandez maintenant !</span>
</div>`;
}

function renderFooter() {
    const footerEl = document.getElementById('site-footer-placeholder');
    if (!footerEl) return;
    footerEl.innerHTML = `<footer class="site-footer">
  <div class="footer-newsletter">
    <div class="footer-nl-inner">
      <h3>📧 Restez informé(e) de nos offres exclusives !</h3>
      <p>Rejoignez nos 4,7 millions d'abonnés satisfaits*</p>
      <form class="nl-form" onsubmit="subscribeNewsletter(event)">
        <input type="email" placeholder="votre@email.com" id="nl-email" required>
        <button type="submit" class="btn-nl-subscribe">S'abonner GRATUITEMENT</button>
      </form>
      <div class="nl-checkboxes">
        <label class="nl-check"><input type="checkbox" checked> Je souhaite recevoir les offres DarkBuy™ (décochez pour ne pas recevoir les meilleures offres)</label>
        <label class="nl-check"><input type="checkbox" checked> Ne pas ne pas recevoir les offres de nos partenaires (47 947 sociétés)</label>
        <label class="nl-check"><input type="checkbox" checked> J'accepte de ne pas refuser les SMS promotionnels (jusqu'à 50/jour)</label>
      </div>
      <p class="nl-fine-print">*Satisfaction mesurée parmi les 3 employés qui ont répondu au sondage.<br>
        Pour se désabonner : <a href="unsubscribe.html" style="color:#666;font-size:.6rem">cliquez ici</a> (processus en 47 étapes, délai 180 jours)</p>
    </div>
  </div>
  <div class="footer-main">
    <div class="footer-col">
      <div class="logo" style="margin-bottom:1rem"><span class="logo-dark">Dark</span><span class="logo-buy">Buy™</span></div>
      <p style="color:#888;font-size:.85rem;line-height:1.6">DarkBuy™ est votre destination shopping #1 pour des produits de qualité* discutable.<br>*Qualité non garantie, non testée, non vérifiée.</p>
      <div class="footer-social">
        <a href="#" class="social-btn">f</a><a href="#" class="social-btn">🐦</a>
        <a href="#" class="social-btn">📷</a><a href="#" class="social-btn">▶️</a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Aide & Contact</h4>
      <ul>
        <li><a href="#">FAQ (payante après 3 questions)</a></li>
        <li><a href="#">Nous contacter (délai: 6-8 semaines)</a></li>
        <li><a href="#">Retours & Remboursements (voir CGV p.847)</a></li>
        <li><a href="unsubscribe.html">Se désabonner (bonne chance)</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Légal</h4>
      <ul>
        <li><a href="#" style="font-size:.6rem;color:#444">CGV (1,247 pages)</a></li>
        <li><a href="#" style="font-size:.6rem;color:#444">Politique de Confidentialité (v847.3)</a></li>
        <li><a href="#" style="font-size:.6rem;color:#444">Politique Cookies (47 sous-sections)</a></li>
        <li><a href="#" style="font-size:.6rem;color:#444">Mentions Légales (en latin)</a></li>
        <li><a href="#" style="font-size:.6rem;color:#444">Droit de rétractation* (*non applicable)</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Paiements sécurisés*</h4>
      <div class="payment-badges">
        <span class="pay-badge">VISA</span><span class="pay-badge">MC</span>
        <span class="pay-badge">PAYPAL</span><span class="pay-badge">CRYPTO</span>
      </div>
      <p style="font-size:.6rem;color:#444;margin-top:.5rem">*Sécurisé par SSL 0.5. Notre serveur utilise Windows XP.</p>
      <div class="trust-badges">
        <div class="trust-badge">🏆 Meilleur site 2019<br><span>Prix auto-décerné</span></div>
        <div class="trust-badge">✅ Certifié "Fiable"<br><span>Par nous-mêmes</span></div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 DarkBuy™ SAS — Siège social: Rue des Illusions, 75000 Paris (Fiscalement aux Îles Caïman)</p>
    <p style="font-size:.6rem;color:#333;margin-top:.5rem">En naviguant sur ce site vous acceptez nos CGV, notre politique de cookies, notre charte graphique, notre vision artistique, nos erreurs de calcul de TVA et notre politique de non-remboursement. DarkBuy™ n'est pas responsable de vos décisions d'achat, de votre satisfaction, de vos regrets, ni de l'état général de votre compte bancaire. Prix TTC hors taxes hors frais hors raison.</p>
  </div>
</footer>`;
}
