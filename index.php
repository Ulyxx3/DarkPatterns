<?php
$pageTitle = 'DarkBuy™ — Les Meilleures Affaires GARANTIES | -90% Aujourd\'hui SEULEMENT';
require_once 'includes/products.php';
require_once 'includes/header.php';
$allProducts = getAllProducts();
?>

<!-- FLASH SALE BAR -->
<div class="flash-sale-bar">
    🔥 VENTE FLASH — Se termine dans <span id="flash-timer">04:32:11</span> — STOCKS ULTRA LIMITÉS — NE RATEZ PAS ÇA 🔥
</div>

<!-- SOCIAL PROOF STRIP (FOMO) -->
<div class="social-strip">
    <span>🛒 <strong id="recent-buyers">127</strong> achats dans les dernières 24h</span>
    <span>👁️ <strong id="live-viewers">47</strong> personnes sur ce site en ce moment</span>
    <span>⭐ Note: 4.9/5 basée sur <strong>4,7M</strong> d'avis</span>
    <span>🚀 Livraison express disponible (frais s'appliquent)</span>
</div>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-badge">
            <span class="viewer-dot"></span>
            🔥 OFFRE EXCLUSIVE LIMITÉE — AUJOURD'HUI SEULEMENT
        </div>
        <h1>Bienvenue sur <span>DarkBuy™</span><br>Vos Achats, Vos Regrets</h1>
        <p class="hero-sub">
            Découvrez des produits révolutionnaires à des prix impossibles* pendant
            une durée ultra-limitée**. Rejoignez nos millions de clients très satisfaits***!
        </p>
        <div class="hero-cta">
            <a href="#products" class="btn-primary">
                🛒 Profiter des offres MAINTENANT
            </a>
            <!-- DARK PATTERN: Second button leads to subscription page, not discount -->
            <a href="cart.php" class="btn-secondary" style="font-size:0.85rem">
                ⚡ Voir mon offre personnalisée†
            </a>
        </div>

        <!-- DARK PATTERN: Fine print buried below CTA -->
        <p
            style="font-size:0.6rem;color:var(--text-muted);margin-bottom:2rem;max-width:700px;margin-left:auto;margin-right:auto">
            *Prix impossibles par rapport à nos propres prix gonflés. **Durée limitée recalculée toutes les heures.
            ***Satisfaction mesurée selon notre propre méthodologie. †Offre personnalisée = abonnement mensuel
            automatique.
        </p>

        <div class="hero-stats">
            <div>
                <span class="hero-stat-value" id="hero-clients">4,738,291</span>
                <span class="hero-stat-label">Clients "satisfaits"</span>
            </div>
            <div>
                <span class="hero-stat-value">97%</span>
                <span class="hero-stat-label">Taux de satisfaction (calculé par nous)</span>
            </div>
            <div>
                <span class="hero-stat-value">-90%</span>
                <span class="hero-stat-label">de réduction max (sur 1 article de 1€)</span>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORY FILTER (all lead to same products) -->
<section class="container" style="padding-top:2rem">
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1rem">
        <?php
        $cats = ['Tous', 'Tech', 'Beauté', 'Sport', 'Audio', 'Mode', 'Gaming'];
        foreach ($cats as $cat): ?>
            <button onclick="filterCat('<?= $cat ?>')" class="cat-btn <?= $cat === 'Tous' ? 'active' : '' ?>"
                data-cat="<?= $cat ?>">
                <?= $cat ?>
            </button>
        <?php endforeach; ?>
    </div>
</section>

<!-- PRODUCTS SECTION -->
<section class="container section" id="products">
    <div
        style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem;margin-bottom:0.5rem">
        <div>
            <h2 class="section-title">Offres <span>Impossibles</span> du Moment 🔥</h2>
            <p class="section-subtitle">
                Stock ultra-limité • Prix remontent dans <strong id="products-timer"
                    style="color:var(--accent-red)">04:32:11</strong>
            </p>
        </div>
        <!-- DARK PATTERN: Fake "sort by" that does nothing useful -->
        <select
            style="background:var(--bg-card);border:1px solid var(--border-bright);color:var(--text-secondary);padding:0.5rem 1rem;border-radius:8px;font-size:0.82rem;cursor:pointer">
            <option>Trier: Recommandés pour Vous (payant)</option>
            <option>Prix croissant (affiché sans frais)</option>
            <option>Nouveautés (toutes datent de 2019)</option>
            <option>Meilleures ventes (selon nous)</option>
        </select>
    </div>

    <div class="products-grid" id="products-grid">
        <?php
        $cartClasses = ['card-fire', '', 'card-fire', '', '', 'card-fire'];
        $i = 0;
        foreach ($allProducts as $product):
            $discount = round((1 - $product['price'] / $product['fake_original_price']) * 100);
            $timers = ['01:47', '00:23', '03:12', '02:55', '00:08', '04:01'];
            $viewerCounts = [47, 23, 89, 31, 67, 14];
            ?>
            <div class="product-card <?= $cartClasses[$i] ?>"
                onclick="window.location='product.php?id=<?= $product['id'] ?>'" data-cat="<?= $product['category'] ?>">

                <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                    class="product-card-img">

                <div class="product-card-body">
                    <div class="product-badge-row">
                        <span class="badge badge-sale">-
                            <?= $discount ?>%
                        </span>
                        <?php if ($i % 2 === 0): ?><span class="badge badge-hot">🔥 HOT</span>
                        <?php endif; ?>
                        <?php if ($i === 0): ?><span class="badge badge-limited">⚡ Limité</span>
                        <?php endif; ?>
                        <?php if ($i === 4): ?><span class="badge badge-new">NEW</span>
                        <?php endif; ?>
                    </div>

                    <div class="product-card-name">
                        <?= htmlspecialchars($product['name']) ?>
                    </div>
                    <div class="product-card-tagline">
                        <?= htmlspecialchars($product['tagline']) ?>
                    </div>

                    <!-- FOMO: Fake viewer count -->
                    <div class="product-card-fomo">
                        <span class="dot"></span>
                        <span class="viewer-dot"
                            style="width:5px;height:5px;background:#f97316;border-radius:50%;animation:viewerPulse 1.5s infinite;display:inline-block"></span>
                        <span>
                            <?= $viewerCounts[$i] + rand(-5, 15) ?> personnes regardent
                        </span>
                    </div>

                    <!-- FOMO: Fake low stock -->
                    <div class="stock-meter">
                        <div class="stock-fill"></div>
                    </div>
                    <div class="product-card-stock">
                        ⚠️ Plus que
                        <?= $product['stock'] ?> en stock ! <!-- Always 2 -->
                    </div>

                    <div class="product-card-rating">
                        <span class="stars">★★★★★</span>
                        <span style="font-size:0.8rem;font-weight:600">
                            <?= $product['rating'] ?>
                        </span>
                        <span class="rating-count">(
                            <?= number_format($product['reviews']) ?> avis)
                        </span>
                    </div>

                    <div class="product-card-pricing">
                        <span class="price-current">
                            <?= number_format($product['price'], 2) ?>€
                        </span>
                        <span class="price-original">
                            <?= number_format($product['fake_original_price'], 2) ?>€
                        </span>
                        <span class="price-discount">-
                            <?= $discount ?>%
                        </span>
                    </div>

                    <!-- FOMO: Countdown timer -->
                    <div class="product-card-timer">
                        <span style="color:var(--text-muted);font-size:0.72rem">⏰ Prix remonte dans :</span>
                        <span class="product-timer-count">
                            <?= $timers[$i] ?>
                        </span>
                    </div>

                    <button class="btn-primary" style="width:100%;justify-content:center;padding:0.75rem"
                        onclick="event.stopPropagation(); addToCart(<?= $product['id'] ?>)">
                        🛒 Ajouter au panier
                    </button>

                    <!-- DARK PATTERN: Confirmshaming on skip -->
                    <button class="btn-ghost" style="width:100%;text-align:center;margin-top:0.5rem"
                        onclick="event.stopPropagation()">
                        Non merci, je préfère payer plein tarif
                    </button>
                </div>
            </div>
            <?php $i++; endforeach; ?>
    </div>

    <!-- DARK PATTERN: Infinite scroll button -->
    <div style="text-align:center;margin-top:3rem">
        <button class="btn-secondary" onclick="loadMoreProducts()" id="load-more-btn">
            Voir encore plus d'offres incroyables ↓
        </button>
    </div>
</section>

<!-- FAKE TESTIMONIALS SECTION -->
<section
    style="background:var(--bg-secondary);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:3rem 0">
    <div class="container">
        <h2 class="section-title text-center" style="margin-bottom:0.5rem">Ce que disent nos clients <span>très
                satisfaits</span></h2>
        <p class="section-subtitle text-center">4,7M d'avis — tous vérifiés par notre équipe (nous-mêmes)</p>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem">
            <?php
            $reviews = [
                ['name' => 'Marie D.', 'avatar' => '👩', 'text' => 'INCROYABLE ! J\'ai reçu exactement ce que j\'ai commandé (pas du tout). Livraison en 47 jours au lieu de 24h, mais le sac en plastique était beau !', 'stars' => 5, 'verified' => true],
                ['name' => 'Jean-Marc L.', 'avatar' => '🧔', 'text' => 'La crème miracle a effectivement changé ma vie. Je suis maintenant ruiné ET toujours vieux. Les deux étoiles bonus pour le joli packaging.', 'stars' => 5, 'verified' => true],
                ['name' => 'Sophie R.', 'avatar' => '👱‍♀️', 'text' => 'Montre reçue ! Premier usage : heure fausse, GPS non inclus, diamants en plastique. PARFAIT pour ma collection d\'arnaques. Je recommande !', 'stars' => 5, 'verified' => true],
                ['name' => 'Kevin M.', 'avatar' => '🙋‍♂️', 'text' => 'Les suppléments quantiques ont transformé mes muscles en... attente. Toujours en attente. Mais délicieux ! Note 5/5 pour le goût de carton.', 'stars' => 5, 'verified' => true],
                ['name' => 'Prof. A. Expert', 'avatar' => '👨‍🔬', 'text' => '"Scientifiquement, ces produits dépassent toute logique. Et pas dans le bons sens." — Ce commentaire a été modifié par notre équipe ✅', 'stars' => 5, 'verified' => false],
                ['name' => 'Un Célébrité™', 'avatar' => '⭐', 'text' => 'J\'utilise DarkBuy tous les jours ! [Ce commentaire est une publicité non divulguée comme la loi l\'exige et que nous ignorons]', 'stars' => 5, 'verified' => true],
            ];
            foreach ($reviews as $rev): ?>
                <div
                    style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:1.25rem">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem">
                        <div
                            style="width:40px;height:40px;background:var(--gradient-purple);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.2rem">
                            <?= $rev['avatar'] ?>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.88rem">
                                <?= $rev['name'] ?>
                            </div>
                            <div style="font-size:0.65rem;color:var(--text-muted)">
                                <?= $rev['verified'] ? '✅ Achat vérifié' : '🤔 Source: inconnu' ?>
                            </div>
                        </div>
                        <div style="margin-left:auto;color:var(--accent-amber);font-size:0.85rem">★★★★★</div>
                    </div>
                    <p style="font-size:0.8rem;color:var(--text-secondary);line-height:1.5;font-style:italic">"
                        <?= $rev['text'] ?>"
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- DARK PATTERN: Hidden negative reviews notice -->
        <p style="text-align:center;font-size:0.6rem;color:#222;margin-top:1.5rem">
            * Les avis négatifs (99.3% du total) sont filtrés pour "qualité de contenu".
            Les avis 5 étoiles sont sélectionnés selon notre "Indice de Vérité Commerciale".
        </p>
    </div>
</section>

<!-- URGENCY BANNER BEFORE FOOTER -->
<section class="container" style="padding:3rem 0;text-align:center">
    <div
        style="background:linear-gradient(135deg,#1a0a0a,#1a0a2e);border:1px solid rgba(239,68,68,0.3);border-radius:var(--radius-xl);padding:3rem;max-width:700px;margin:0 auto">
        <h2 style="margin-bottom:0.75rem">⏰ Dernière chance !</h2>
        <p style="color:var(--text-secondary);margin-bottom:1.5rem">
            Les prix remontent dans exactement <strong style="color:var(--accent-red)"
                id="final-timer">04:32:11</strong>
        </p>
        <a href="product.php?id=1" class="btn-primary" style="font-size:1.1rem;padding:1rem 3rem">
            🚀 Profiter MAINTENANT avant qu'il soit trop tard
        </a>
        <p style="font-size:0.7rem;color:var(--text-muted);margin-top:1rem">
            ⚠️ En cliquant, vous acceptez notre offre VIP à 14,99€/mois (annulable après 12 mois en écrivant à notre
            siège)
        </p>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<script>
    // Product timer countdowns
    function productTimers() {
        const timers = document.querySelectorAll('.product-timer-count');
        timers.forEach(timer => {
            let parts = timer.textContent.split(':');
            let total = parseInt(parts[0]) * 60 + parseInt(parts[1]);
            setInterval(() => {
                if (total <= 0) total = 5 * 60 + Math.floor(Math.random() * 300); // Reset with random FOMO
                total--;
                const m = Math.floor(total / 60);
                const s = total % 60;
                timer.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }, 1000);
        });
    }
    productTimers();

    // Sync the other timers (all show same timer from header)
    document.getElementById('flash-timer').textContent = document.getElementById('global-timer')?.textContent || '04:32:11';
    document.getElementById('products-timer').textContent = document.getElementById('global-timer')?.textContent || '04:32:11';
    document.getElementById('final-timer').textContent = document.getElementById('global-timer')?.textContent || '04:32:11';

    // Hero client counter (ticks up to create FOMO)
    function animateCounter() {
        const el = document.getElementById('hero-clients');
        let n = 4738291;
        setInterval(() => {
            n += Math.floor(Math.random() * 3);
            el.textContent = n.toLocaleString('fr-FR');
        }, 2000);
    }
    animateCounter();

    // Category filter
    function filterCat(cat) {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        document.querySelector(`[data-cat="${cat}"]`)?.classList.add('active');
        // DARK PATTERN: filter doesn't work, shows all products
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = '';
        });
    }

    // random FOMO toasts
    const fomoMessages = [
        { name: 'Marie de Paris', msg: 'vient d\'acheter MiraclWatch™' },
        { name: 'Thomas (Lyon)', msg: 'vient d\'ajouter Quantum Energy au panier' },
        { name: 'Anonyme 🇫🇷', msg: 'a acheté 3 articles il y a 2 min' },
        { name: 'Céline M.', msg: 'vient de commander AEGIS Reviver™' },
        { name: 'Un utilisateur', msg: '🔒 achat sécurisé confirmé' },
    ];

    function showFomoToast() {
        const msg = fomoMessages[Math.floor(Math.random() * fomoMessages.length)];
        const toast = document.createElement('div');
        toast.className = 'fomo-toast';
        toast.innerHTML = `
        <div class="fomo-toast-avatar">👤</div>
        <div>
            <strong style="display:block;font-size:0.78rem">${msg.name}</strong>
            <span style="font-size:0.72rem;color:var(--text-muted)">${msg.msg}</span>
        </div>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }

    setInterval(showFomoToast, 7000);
    setTimeout(showFomoToast, 3000);

    // Add to cart
    function addToCart(id) {
        fetch('api/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add', id: id })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const badge = document.querySelector('.cart-badge');
                if (badge) {
                    badge.textContent = data.count;
                } else {
                    const cartLink = document.querySelector('.cart-link');
                    if (cartLink) {
                        const b = document.createElement('span');
                        b.className = 'cart-badge';
                        b.textContent = data.count;
                        cartLink.appendChild(b);
                    }
                }
                showCartSuccess(id);
            }
        }).catch(() => {
            window.location.href = 'cart.php?added=' + id;
        });
    }

    function showCartSuccess(id) {
        const toast = document.createElement('div');
        toast.className = 'fomo-toast';
        toast.style.left = 'auto';
        toast.style.right = '1.5rem';
        toast.style.bottom = '5rem';
        toast.innerHTML = `
        <div class="fomo-toast-avatar" style="background:linear-gradient(135deg,#10b981,#059669)">✅</div>
        <div>
            <strong style="display:block;font-size:0.78rem">Ajouté au panier !</strong>
            <a href="cart.php" style="font-size:0.72rem;color:var(--accent-red);font-weight:600">Passer commande →</a>
        </div>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }

    // Load more (infinite scroll DARK PATTERN)
    let loadCount = 0;
    function loadMoreProducts() {
        loadCount++;
        if (loadCount >= 3) {
            document.getElementById('load-more-btn').textContent = 'Vous avez tout vu ! Mais regardez quand même ↓';
            loadCount = 0;
        }
        // DARK PATTERN: Shows the exact same products again
        const grid = document.getElementById('products-grid');
        const cards = grid.querySelectorAll('.product-card');
        cards.forEach(card => {
            const clone = card.cloneNode(true);
            grid.appendChild(clone);
        });
    }

    // Live buyer/viewer update
    setInterval(() => {
        const el = document.getElementById('recent-buyers');
        if (el) el.textContent = 100 + Math.floor(Math.random() * 80);
    }, 5000);
</script>

<style>
    .cat-btn {
        background: var(--bg-card);
        border: 1px solid var(--border-bright);
        color: var(--text-secondary);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.82rem;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
    }

    .cat-btn:hover,
    .cat-btn.active {
        background: var(--gradient-accent);
        color: white;
        border-color: transparent;
    }
</style>