<?php
session_start();
require_once 'includes/products.php';

$id = (int) ($_GET['id'] ?? 1);
$product = getProduct($id);
if (!$product) {
    header('Location: index.php');
    exit;
}

$pageTitle = htmlspecialchars($product['name']) . ' — DarkBuy™ | Offre EXCLUSIVE se terminant DANS PEU DE TEMPS';
require_once 'includes/header.php';

// Handle cart add
if (isset($_POST['add_to_cart'])) {
    $_SESSION['cart'][$id] = [
        'id' => $id,
        'name' => $product['name'],
        'price' => $product['price'],
        'qty' => ($_SESSION['cart'][$id]['qty'] ?? 0) + 1,
        'image' => $product['image'],
        'subscription' => isset($_POST['subscription']),
        'subscription_price' => $product['subscription_price'],
    ];
    header('Location: cart.php');
    exit;
}

$otherProducts = array_filter(getAllProducts(), fn($p) => $p['id'] !== $id);
$discount = round((1 - $product['price'] / $product['fake_original_price']) * 100);
$allProducts = getAllProducts();
?>

<!-- Breadcrumb -->
<div
    style="background:var(--bg-secondary);border-bottom:1px solid var(--border);padding:0.6rem 2rem;font-size:0.78rem;color:var(--text-muted)">
    <div class="container">
        <a href="index.php" style="color:var(--accent-red)">DarkBuy™</a> ›
        <span>
            <?= $product['category'] ?>
        </span> ›
        <span style="color:var(--text-primary)">
            <?= htmlspecialchars($product['name']) ?>
        </span>
    </div>
</div>

<div class="container" style="padding-top:2.5rem;padding-bottom:2.5rem">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:start">

        <!-- PRODUCT IMAGE -->
        <div>
            <div
                style="background:linear-gradient(135deg,#1a1a26,#12121d);border-radius:var(--radius-xl);padding:2.5rem;border:1px solid var(--border);position:relative">
                <!-- DARK PATTERN: Fake trust badge on image -->
                <div
                    style="position:absolute;top:1rem;left:1rem;background:var(--gradient-gold);color:#000;font-size:0.6rem;font-weight:900;padding:4px 10px;border-radius:6px">
                    🏆 BEST SELLER</div>
                <div
                    style="position:absolute;top:1rem;right:1rem;background:var(--accent-red);color:white;font-size:0.65rem;font-weight:900;padding:4px 10px;border-radius:6px;animation:pulse-badge 1s infinite">
                    -
                    <?= $discount ?>%
                </div>
                <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                    style="width:100%;max-height:380px;object-fit:contain">
            </div>

            <!-- Fake secure badges -->
            <div style="display:flex;gap:1rem;justify-content:center;margin-top:1rem;flex-wrap:wrap">
                <span style="font-size:0.72rem;color:var(--text-muted);display:flex;align-items:center;gap:0.3rem">🔒
                    Paiement sécurisé</span>
                <span style="font-size:0.72rem;color:var(--text-muted);display:flex;align-items:center;gap:0.3rem">↩️
                    Retour facile*</span>
                <span style="font-size:0.72rem;color:var(--text-muted);display:flex;align-items:center;gap:0.3rem">📦
                    Livraison rapide**</span>
            </div>
            <p style="font-size:0.55rem;color:#222;text-align:center;margin-top:0.25rem">
                *Retour sous 7j si emballage non ouvert, non regardé, non pensé. **Rapide = 14-47 jours ouvrés
            </p>
        </div>

        <!-- PRODUCT INFO -->
        <div>
            <div style="display:flex;gap:0.5rem;margin-bottom:0.75rem;flex-wrap:wrap">
                <span class="badge badge-hot">🔥 HOT</span>
                <span class="badge badge-sale">-
                    <?= $discount ?>%
                </span>
                <span class="badge badge-limited">⚡ Stock limité</span>
                <span
                    style="font-size:0.65rem;padding:3px 8px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:var(--accent-emerald);border-radius:4px">✅
                    En stock (pour l'instant)</span>
            </div>

            <h1 style="font-size:1.6rem;margin-bottom:0.4rem">
                <?= htmlspecialchars($product['name']) ?>
            </h1>
            <p style="color:var(--text-secondary);margin-bottom:1rem;font-style:italic">
                <?= htmlspecialchars($product['tagline']) ?>
            </p>

            <!-- Fake social proof -->
            <div
                style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:var(--radius-md);padding:0.75rem;margin-bottom:1rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
                <span style="font-size:0.8rem;color:var(--text-secondary)">
                    <span class="viewer-dot"
                        style="width:6px;height:6px;background:#f97316;border-radius:50%;animation:viewerPulse 1.5s infinite;display:inline-block;margin-right:4px"></span>
                    <strong id="prod-viewers">47</strong> personnes regardent cet article EN CE MOMENT
                </span>
                <span style="font-size:0.8rem;color:var(--accent-orange)">
                    🛒 <strong>23</strong> ajoutés au panier dans les 2 dernières heures
                </span>
            </div>

            <!-- Rating -->
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem">
                <span style="color:var(--accent-amber);font-size:1.1rem;letter-spacing:2px">★★★★★</span>
                <span style="font-weight:700">
                    <?= $product['rating'] ?>/5
                </span>
                <span style="color:var(--text-muted);font-size:0.82rem">
                    <?= number_format($product['reviews']) ?> avis
                </span>
                <!-- DARK PATTERN: link to filtered (only positive) reviews -->
                <a href="#reviews" style="font-size:0.72rem;color:var(--accent-red)">Voir les avis 5★ →</a>
            </div>

            <!-- BAIT & SWITCH PRICING -->
            <div style="margin-bottom:1.5rem">
                <div style="display:flex;align-items:baseline;gap:0.75rem;margin-bottom:0.25rem">
                    <span style="font-size:2rem;font-weight:900">
                        <?= number_format($product['price'], 2) ?>€
                    </span>
                    <span style="color:var(--text-muted);text-decoration:line-through;font-size:1rem">
                        <?= number_format($product['fake_original_price'], 2) ?>€
                    </span>
                    <span
                        style="background:rgba(16,185,129,0.15);color:var(--accent-emerald);font-size:0.8rem;font-weight:700;padding:3px 8px;border-radius:4px">Économie:
                        <?= number_format($product['fake_original_price'] - $product['price'], 2) ?>€
                    </span>
                </div>
                <p style="font-size:0.7rem;color:var(--text-muted)">
                    Prix TTC + frais de traitement (affichés à la caisse) + assurance obligatoire
                </p>
                <!-- DARK PATTERN: Show "real" total only vaguely -->
                <p style="font-size:0.68rem;color:#333;margin-top:2px">
                    Prix final estimé : entre
                    <?= number_format($product['price'], 2) ?>€ et
                    <?= number_format($product['cart_price'] + 19.99, 2) ?>€ (selon options sélectionnées)
                </p>
            </div>

            <!-- COUNTDOWN TIMER FOMO -->
            <div
                style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:var(--radius-md);padding:1rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between">
                <div>
                    <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:4px">⏰ Ce prix exceptionnel
                        expire dans :</p>
                    <div style="font-size:2rem;font-weight:900;color:var(--accent-red);font-family:'Space Grotesk',monospace;letter-spacing:4px"
                        id="prod-timer">01:47:23</div>
                </div>
                <div style="text-align:right">
                    <p style="font-size:0.68rem;color:var(--text-muted)">Après expiration:</p>
                    <p style="font-size:1rem;font-weight:700;color:var(--accent-orange)">
                        <?= number_format($product['fake_original_price'], 2) ?>€
                    </p>
                </div>
            </div>

            <!-- FORM -->
            <form method="POST">
                <!-- Features -->
                <div style="margin-bottom:1.25rem">
                    <?php foreach ($product['features'] as $feat): ?>
                        <div
                            style="font-size:0.82rem;color:var(--text-secondary);padding:0.3rem 0;border-bottom:1px solid var(--border);line-height:1.4">
                            <?= $feat ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- DARK PATTERN: Hidden subscription pre-checked -->
                <div
                    style="background:var(--bg-secondary);border:2px solid rgba(245,158,11,0.4);border-radius:var(--radius-md);padding:1rem;margin-bottom:1rem;position:relative">
                    <div
                        style="position:absolute;top:-10px;right:10px;background:var(--accent-amber);color:#000;font-size:0.55rem;font-weight:900;padding:2px 8px;border-radius:4px">
                        ★ RECOMMANDÉ</div>
                    <label style="display:flex;align-items:flex-start;gap:0.75rem;cursor:pointer">
                        <input type="checkbox" name="subscription" checked
                            style="margin-top:2px;width:18px;height:18px;accent-color:var(--accent-red)">
                        <div>
                            <strong style="font-size:0.88rem;display:block;margin-bottom:2px">
                                Ajouter
                                <?= htmlspecialchars($product['subscription_label']) ?> —
                                <?= number_format($product['subscription_price'], 2) ?>€/mois
                            </strong>
                            <span style="font-size:0.72rem;color:var(--text-muted)">
                                Protection complète, mises à jour, et accès VIP (résiliable après 12 mois minimum,
                                préavis 90j)
                            </span>
                        </div>
                    </label>
                </div>

                <!-- Quantity — DARK PATTERN: default is 2, not 1 -->
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
                    <label style="font-size:0.85rem;font-weight:600;color:var(--text-secondary)">Quantité :</label>
                    <select name="qty"
                        style="background:var(--bg-card);border:1px solid var(--border-bright);color:var(--text-primary);padding:0.5rem 1rem;border-radius:8px;font-size:0.9rem">
                        <option value="1">1</option>
                        <option value="2" selected>2 (★ Pack Duo recommandé)</option>
                        <option value="3">3 (Famille)</option>
                        <option value="5">5 (Pro Pack)</option>
                    </select>
                    <span style="font-size:0.72rem;color:var(--accent-orange)">⚡ Quantité limitée</span>
                </div>

                <!-- CTA buttons -->
                <button type="submit" name="add_to_cart" class="btn-primary"
                    style="width:100%;font-size:1.1rem;padding:1rem;justify-content:center;margin-bottom:0.75rem">
                    🛒 Ajouter au panier —
                    <?= number_format($product['price'], 2) ?>€*
                </button>

                <!-- DARK PATTERN: "Buy now" skips cart and goes straight to checkout -->
                <a href="cart.php?direct=1&id=<?= $id ?>" class="btn-secondary"
                    style="display:block;text-align:center;padding:0.85rem">
                    ⚡ Acheter maintenant (sans review)
                </a>

                <!-- DARK PATTERN: Confirmshaming -->
                <p style="text-align:center;font-size:0.7rem;color:var(--text-muted);margin-top:0.75rem;cursor:pointer"
                    onclick="history.back()">
                    Non merci, je préfère manquer cette opportunité unique et regretter toute ma vie
                </p>

                <p style="text-align:center;font-size:0.6rem;color:#333;margin-top:0.5rem">
                    *Prix affiché hors frais de traitement (6,99€), frais de livraison (9,99€), assurance colis (4,99€)
                    et abonnement DarkCare inclus automatiquement
                </p>
            </form>
        </div>
    </div>

    <!-- Description -->
    <div style="margin-top:3rem;display:grid;grid-template-columns:2fr 1fr;gap:2rem;align-items:start">
        <div>
            <h2 style="margin-bottom:1rem">Description du produit</h2>
            <div
                style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:1.5rem">
                <p style="color:var(--text-secondary);line-height:1.7;font-size:0.9rem">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </p>
            </div>

            <!-- FAKE REVIEWS SECTION -->
            <h3 id="reviews" style="margin-top:2rem;margin-bottom:1rem">
                Avis clients ★★★★★ <span style="font-size:0.75rem;color:var(--text-muted)">(seuls les avis positifs sont
                    affichés)</span>
            </h3>
            <?php
            $fakeReviews = [
                ['user' => 'J***e', 'stars' => 5, 'date' => 'il y a 2 jours', 'text' => 'PARFAIT !! Exactement comme décrit, je recommande à 100000% !!!! ⭐⭐⭐⭐⭐'],
                ['user' => 'M***c', 'stars' => 5, 'date' => 'il y a 1 semaine', 'text' => 'Livraison rapide. Produit conforme. Ma femme adore. Mon chien aussi. 5 étoiles.'],
                ['user' => 'P***e Expert', 'stars' => 5, 'date' => 'il y a 3 jours', 'text' => '"En tant que professionnel de santé certifié, ce produit est révolutionnaire" [avis sponsorisé non divulgué]'],
                ['user' => 'A***a', 'stars' => 4, 'date' => 'il y a 1 mois', 'text' => '4 étoiles car le colis était légèrement froissé. Sinon INCROYABLE. — Note: cet avis 4 étoiles a failli être supprimé.'],
            ];
            foreach ($fakeReviews as $r): ?>
                <div
                    style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:1rem;margin-bottom:0.75rem">
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem">
                            <div
                                style="width:32px;height:32px;background:var(--gradient-accent);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem">
                                👤</div>
                            <div>
                                <div style="font-size:0.82rem;font-weight:600">
                                    <?= $r['user'] ?>
                                </div>
                                <div style="font-size:0.65rem;color:var(--text-muted)">✅ Achat vérifié •
                                    <?= $r['date'] ?>
                                </div>
                            </div>
                        </div>
                        <span style="color:var(--accent-amber)">★★★★★</span>
                    </div>
                    <p style="font-size:0.82rem;color:var(--text-secondary);font-style:italic">"
                        <?= $r['text'] ?>"
                    </p>
                </div>
            <?php endforeach; ?>

            <div
                style="background:rgba(239,68,68,0.05);border:1px solid rgba(239,68,68,0.1);border-radius:var(--radius-sm);padding:0.75rem;margin-top:0.5rem">
                <p style="font-size:0.65rem;color:#444">
                    📢 Voir plus d'avis : 23,847 avis disponibles • 99.3% négatifs filtrés pour "qualité de contenu" •
                    <a href="#" style="color:#333;text-decoration:underline">Avis négatifs</a> (accès premium requis,
                    9,99€/mois)
                </p>
            </div>
        </div>

        <!-- SIDEBAR: Cross-sell / upsell -->
        <div style="position:sticky;top:80px">
            <div
                style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:1.5rem">
                <h4 style="margin-bottom:1rem;font-size:0.9rem">
                    🛍️ Souvent achetés ensemble<br>
                    <span style="font-size:0.7rem;color:var(--text-muted)">(ajoutés automatiquement au panier)</span>
                </h4>
                <?php foreach (array_slice($otherProducts, 0, 2) as $other): ?>
                    <div
                        style="display:flex;gap:0.75rem;align-items:center;padding:0.75rem 0;border-bottom:1px solid var(--border)">
                        <img src="<?= $other['image'] ?>"
                            style="width:50px;height:50px;object-fit:contain;border-radius:8px;background:var(--bg-secondary)">
                        <div style="flex:1">
                            <div style="font-size:0.78rem;font-weight:600;line-height:1.3">
                                <?= htmlspecialchars($other['name']) ?>
                            </div>
                            <div style="font-size:0.82rem;color:var(--accent-red);font-weight:700">
                                <?= number_format($other['price'], 2) ?>€
                            </div>
                        </div>
                        <!-- DARK PATTERN: checkbox is pre-checked -->
                        <input type="checkbox" checked style="width:18px;height:18px;accent-color:var(--accent-red)"
                            title="Décochez pour NE PAS ajouter">
                    </div>
                <?php endforeach; ?>
                <div style="font-size:0.68rem;color:#444;margin-top:0.75rem">
                    ☑️ Les articles ci-dessus sont pré-sélectionnés pour votre commodité. Pour les retirer, voir les CGV
                    section 14.3.
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
    // Product page timer (1h47m23s)
    let pt = 1 * 3600 + 47 * 60 + 23;
    const ptEl = document.getElementById('prod-timer');
    setInterval(() => {
        if (pt <= 0) pt = 3600 + Math.floor(Math.random() * 3600);
        pt--;
        const h = Math.floor(pt / 3600);
        const m = Math.floor((pt % 3600) / 60);
        const s = pt % 60;
        ptEl.textContent = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }, 1000);

    // Update viewer count
    setInterval(() => {
        const el = document.getElementById('prod-viewers');
        if (el) el.textContent = 30 + Math.floor(Math.random() * 60);
    }, 3000);
</script>