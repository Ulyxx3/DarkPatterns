<?php
session_start();
require_once 'includes/products.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php?return=cart.php');
    exit;
}

$pageTitle = 'Mon Panier — DarkBuy™ | Finaliser ma commande';
$step = (int) ($_GET['step'] ?? 1);
$step = max(1, min(5, $step));

// DARK PATTERN: Add fake items to cart on step 3
if ($step === 4 && !isset($_SESSION['cart_fees_added'])) {
    $_SESSION['cart_fees_added'] = true;
    // Add hidden protection plan
    $_SESSION['cart_extras'] = [
        'protection' => ['name' => 'Plan Protection DarkCare+', 'price' => 14.99, 'added' => 'auto'],
        'express' => ['name' => 'Livraison Express (sélectionnée auto)', 'price' => 12.99, 'added' => 'auto'],
        'packaging' => ['name' => 'Emballage Cadeau Premium', 'price' => 4.99, 'added' => 'auto'],
        'insurance' => ['name' => 'Assurance Colis Obligatoire', 'price' => 6.99, 'added' => 'required'],
        'processing' => ['name' => 'Frais de Traitement', 'price' => 8.99, 'added' => 'required'],
    ];
}

// Ensure at least one item in cart for demo
if (empty($_SESSION['cart'])) {
    $p = getProduct(1);
    $_SESSION['cart'][1] = ['id' => 1, 'name' => $p['name'], 'price' => $p['price'], 'qty' => 1, 'image' => $p['image'], 'subscription' => true, 'subscription_price' => $p['subscription_price']];
}

$cartItems = $_SESSION['cart'];
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cartItems));
$extrasTotal = array_sum(array_column($_SESSION['cart_extras'] ?? [], 'price'));
// DARK PATTERN: Bait & switch — prices changed at cart level
$cartSubtotal = $subtotal * 2.1; // prices are higher in cart
$total = $cartSubtotal + $extrasTotal;

require_once 'includes/header.php';
?>
<link rel="stylesheet" href="assets/css/checkout.css">

<div class="checkout-container">
    <!-- DARK PATTERN: Urgency timer at top of checkout -->
    <div
        style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:var(--radius-md);padding:0.75rem 1.5rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
        <span style="font-size:0.85rem;color:var(--text-secondary)">
            ⏰ Votre panier est réservé pendant : <strong style="color:var(--accent-red)" id="cart-timer">09:47</strong>
        </span>
        <span style="font-size:0.78rem;color:var(--accent-orange)">
            🔴 <strong>3 personnes</strong> ont ce même panier en ce moment
        </span>
    </div>

    <!-- STEP INDICATOR -->
    <div class="checkout-steps">
        <?php
        $steps = [
            1 => ['icon' => '👤', 'label' => 'Compte'],
            2 => ['icon' => '🍪', 'label' => 'Cookies'],
            3 => ['icon' => '📧', 'label' => 'Abonnements'],
            4 => ['icon' => '📋', 'label' => 'Récapitulatif'],
            5 => ['icon' => '✅', 'label' => 'Confirmation'],
        ];
        foreach ($steps as $n => $s):
            $cls = $n < $step ? 'done' : ($n === $step ? 'active' : '');
            ?>
            <div class="step-item <?= $cls ?>">
                <div class="step-num">
                    <?= $n < $step ? '✓' : $s['icon'] ?>
                </div>
                <span>
                    <?= $s['label'] ?>
                </span>
            </div>
            <?php if ($n < 5): ?>
                <div class="step-connector <?= $n < $step ? 'done' : '' ?>"></div>
            <?php endif; endforeach; ?>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;align-items:start">
        <div>

            <?php if ($step === 1): // ÉTAPE 1: Vérification compte (DARK PATTERN: they're already logged in but still shown this) ?>
                <div class="checkout-card">
                    <h2>✅ Étape 1 — Confirmation de compte</h2>
                    <p class="step-description">
                        Bonjour <strong>
                            <?= htmlspecialchars($_SESSION['user']['name']) ?>
                        </strong> !
                        Pour continuer, veuillez confirmer vos informations et accepter nos nouvelles conditions.
                    </p>

                    <!-- DARK PATTERN: Requires re-accepting everything even though already logged in -->
                    <div
                        style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:var(--radius-md);padding:1rem;margin-bottom:1.25rem">
                        <p style="font-size:0.82rem;color:var(--accent-amber);margin-bottom:0.75rem;font-weight:600">
                            ⚠️ Nos conditions ont été mises à jour. Veuillez re-accepter pour continuer.
                        </p>
                        <label class="trick-label"
                            style="margin-bottom:0.5rem;display:flex;gap:0.5rem;cursor:pointer;font-size:0.82rem">
                            <input type="checkbox" required id="tos-check" style="accent-color:var(--accent-red)">
                            <span>J'accepte les nouvelles Conditions (version 847.3, mises à jour il y a 2 minutes)</span>
                        </label>
                        <label class="trick-label" style="display:flex;gap:0.5rem;cursor:pointer;font-size:0.82rem">
                            <input type="checkbox" id="future-check">
                            <span>J'accepte d'avance toutes les futures modifications des CGV (sans notification)</span>
                        </label>
                    </div>

                    <!-- DARK PATTERN: Invite friends "required" step -->
                    <div
                        style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-md);padding:1.25rem;margin-bottom:1.25rem">
                        <h4 style="margin-bottom:0.5rem;font-size:0.9rem">👥 Invitez vos amis pour débloquer votre commande
                        </h4>
                        <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:0.75rem">
                            DarkBuy™ est meilleur quand on est nombreux ! Entrez l'email de 2 amis pour continuer votre
                            achat.
                        </p>
                        <input type="email" placeholder="email.ami1@example.com" class="form-input"
                            style="margin-bottom:0.5rem">
                        <input type="email" placeholder="email.ami2@example.com" class="form-input">
                        <p style="font-size:0.62rem;color:#444;margin-top:0.5rem">
                            Vos amis recevront des emails de DarkBuy™ sans leur consentement explicite (notre
                            responsabilité, pas la vôtre).
                        </p>
                    </div>

                    <div class="checkout-nav">
                        <a href="index.php" class="btn-back">← Retour aux produits</a>
                        <a href="cart.php?step=2" class="btn-continue" onclick="return checkStep1()">
                            Continuer — Étape 2 sur 5 →
                        </a>
                    </div>
                </div>

            <?php elseif ($step === 2): // ÉTAPE 2: Cookie consent labyrinthe IN CHECKOUT ?>
                <div class="checkout-card">
                    <h2>🍪 Étape 2 — Paramètres de Confidentialité</h2>
                    <p class="step-description">
                        Avant de finaliser votre commande, configurez vos préférences de confidentialité.
                        <strong>Toutes les options ci-dessous sont nécessaires</strong> pour le bon traitement de votre
                        commande.
                    </p>

                    <!-- DARK PATTERN: Pretend these are required for the order -->
                    <?php
                    $cookieOpts = [
                        ['id' => 'ck1', 'checked' => true, 'required' => true, 'label' => 'Cookies fonctionnels', 'desc' => 'Absolument requis pour traiter votre paiement (non désactivable)'],
                        ['id' => 'ck2', 'checked' => true, 'required' => false, 'label' => 'Cookies analytiques de performance commande', 'desc' => 'Nous permettent de "suivre" votre commande (et d\'analyser vos habitudes pour revente)'],
                        ['id' => 'ck3', 'checked' => true, 'required' => false, 'label' => 'Cookies de personnalisation marketing', 'desc' => 'Pour vous envoyer des offres pertinentes (jusqu\'à 50 emails/jour)'],
                        ['id' => 'ck4', 'checked' => true, 'required' => false, 'label' => 'Partage données partenaires commande', 'desc' => 'Vos données sont partagées avec nos 47 947 partenaires pour "optimisation logistique"'],
                        ['id' => 'ck5', 'checked' => true, 'required' => false, 'label' => 'Profiling comportemental premium', 'desc' => 'Analyse via webcam et microphone pour améliorer votre "expérience d\'achat"'],
                        ['id' => 'ck6', 'checked' => true, 'required' => false, 'label' => 'Cookies de recommandation intelligente', 'desc' => 'IA qui prédit vos futurs achats (et décide pour vous)'],
                    ];
                    ?>
                    <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.5rem">
                        <?php foreach ($cookieOpts as $opt): ?>
                            <div class="trick-checkbox <?= $opt['id'] === 'ck3' ? 'hidden-sub' : '' ?>">
                                <label class="trick-label">
                                    <input type="checkbox" id="<?= $opt['id'] ?>" <?= $opt['checked'] ? 'checked' : '' ?>
                                    <?= $opt['required'] ? 'disabled' : '' ?>
                                    style="width:18px;height:18px;accent-color:var(--accent-red)">
                                    <div class="trick-label-text">
                                        <strong>
                                            <?= $opt['label'] ?>
                                            <?= $opt['required'] ? '<span style="color:var(--accent-red)">(obligatoire)</span>' : '' ?>
                                        </strong>
                                        <span>
                                            <?= $opt['desc'] ?>
                                        </span>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div
                        style="background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.2);border-radius:var(--radius-sm);padding:0.75rem;margin-bottom:1.5rem">
                        <p style="font-size:0.72rem;color:var(--accent-emerald)">
                            💡 <strong>Conseil :</strong> Garder toutes les options activées vous offre la meilleure
                            expérience DarkBuy™
                            et des "prix personnalisés" (prix normaux pour tout le monde sauf vous).
                        </p>
                    </div>

                    <div class="checkout-nav">
                        <a href="cart.php?step=1" class="btn-back">← Retour</a>
                        <a href="cart.php?step=3" class="btn-continue">
                            Accepter et continuer →
                        </a>
                    </div>
                </div>

            <?php elseif ($step === 3): // ÉTAPE 3: Newsletter + Spam ?>
                <div class="checkout-card">
                    <h2>📧 Étape 3 — Vos Préférences de Communication</h2>
                    <p class="step-description">
                        Configurez comment vous souhaitez être contacté(e). Ces paramètres peuvent être modifiés
                        dans votre espace personnel (accès premium requis, 9,99€/mois).
                    </p>

                    <!-- DARK PATTERN: Everything pre-checked, confusing logic -->
                    <?php
                    $comms = [
                        ['id' => 'c1', 'checked' => true, 'badge' => '🎁 CADEAU', 'label' => 'Newsletter DarkBuy™ Premium', 'desc' => 'Jusqu\'à 50 emails/jour avec offres exclusives (non exclusives), nouveautés (datant de 2019), et actualités (publicités). Résiliable après 12 mois.'],
                        ['id' => 'c2', 'checked' => true, 'badge' => '⭐ BEST-SELLER', 'label' => 'Emails partenaires (47 947 marques)', 'desc' => 'Offres de nos partenaires soigneusement sélectionnés (automatiquement, sans tri). Fréquence: illimitée.'],
                        ['id' => 'c3', 'checked' => true, 'badge' => '🔔 INCLUS', 'label' => 'SMS promotionnels (jusqu\'à 50/jour)', 'desc' => 'Alertes flash par SMS. Frais opérateur peuvent s\'appliquer selon votre forfait. Non résiliable pendant 6 mois.'],
                        ['id' => 'c4', 'checked' => true, 'badge' => null, 'label' => 'Appels téléphoniques commerciaux', 'desc' => 'Notre équipe vous appellera pour vous présenter nos offres premium. Horaires : 8h-22h, 7j/7.'],
                        ['id' => 'c5', 'checked' => true, 'badge' => '⚡ NOUVEAU', 'label' => 'Notifications WhatsApp business', 'desc' => 'Messages via WhatsApp avec offres géolocalisées basées sur votre position GPS en temps réel.'],
                        ['id' => 'c6', 'checked' => false, 'label' => 'Ne PAS recevoir le magazine papier mensuel (89€/an)', 'desc' => 'Décochez pour recevoir notre magazine (vous serez facturé automatiquement).'],
                    ];
                    ?>
                    <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.5rem">
                        <?php foreach ($comms as $c): ?>
                            <div class="trick-checkbox" style="<?= isset($c['badge']) ? 'position:relative' : '' ?>">
                                <?php if (isset($c['badge'])): ?>
                                    <div
                                        style="position:absolute;top:-10px;right:10px;background:var(--gradient-accent);color:white;font-size:0.55rem;font-weight:900;padding:2px 8px;border-radius:4px">
                                        <?= $c['badge'] ?>
                                    </div>
                                <?php endif; ?>
                                <label class="trick-label">
                                    <input type="checkbox" id="<?= $c['id'] ?>" <?= $c['checked'] ? 'checked' : '' ?>
                                    style="width:18px;height:18px;accent-color:var(--accent-red)">
                                    <div class="trick-label-text">
                                        <strong>
                                            <?= $c['label'] ?>
                                        </strong>
                                        <span>
                                            <?= $c['desc'] ?>
                                        </span>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- DARK PATTERN: Inviter les amis encore -->
                    <div
                        style="background:linear-gradient(135deg,rgba(139,92,246,0.1),rgba(59,130,246,0.1));border:1px solid rgba(139,92,246,0.25);border-radius:var(--radius-md);padding:1.25rem;margin-bottom:1.5rem">
                        <h4 style="font-size:0.9rem;margin-bottom:0.5rem">🎁 Offre Parrainage (Obligatoire pour continuer)
                        </h4>
                        <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:0.75rem">
                            Invitez 3 amis supplémentaires pour accéder à l'étape suivante. Vos amis recevront un email de
                            votre part.*
                        </p>
                        <input type="email" placeholder="ami3@email.com" class="form-input" style="margin-bottom:0.5rem">
                        <input type="email" placeholder="ami4@email.com" class="form-input" style="margin-bottom:0.5rem">
                        <input type="email" placeholder="ami5@email.com" class="form-input">
                        <p style="font-size:0.6rem;color:#333;margin-top:0.5rem">*En leur nom bien sûr. Ils adoreront.</p>
                    </div>

                    <div class="checkout-nav">
                        <a href="cart.php?step=2" class="btn-back">← Retour</a>
                        <a href="cart.php?step=4" class="btn-continue">
                            Continuer vers le récapitulatif →
                        </a>
                    </div>
                </div>

            <?php elseif ($step === 4): // ÉTAPE 4: Récapitulatif avec fees cachés révélés ?>
                <div class="checkout-card">
                    <h2>📋 Étape 4 — Récapitulatif de Commande</h2>
                    <p class="step-description">
                        Vérifiez votre commande avant de payer. <span style="color:var(--accent-orange)">⚠️ Certains prix
                            ont été mis à jour depuis votre dernière visite.</span>
                    </p>

                    <!-- Cart items with BAIT & SWITCH prices -->
                    <div style="margin-bottom:1.5rem">
                        <?php foreach ($cartItems as $item):
                            $allP = getAllProducts();
                            $fullProduct = $allP[$item['id']] ?? null;
                            $cartPrice = $fullProduct ? $fullProduct['cart_price'] : $item['price'] * 2;
                            ?>
                            <div
                                style="display:flex;gap:1rem;align-items:center;padding:1rem;background:var(--bg-secondary);border-radius:var(--radius-md);margin-bottom:0.75rem">
                                <img src="<?= $item['image'] ?>"
                                    style="width:60px;height:60px;object-fit:contain;border-radius:8px;background:var(--bg-card)">
                                <div style="flex:1">
                                    <div style="font-weight:600;font-size:0.88rem;margin-bottom:3px">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </div>
                                    <div style="font-size:0.72rem;color:var(--text-muted)">Qté:
                                        <?= $item['qty'] ?>
                                    </div>
                                    <!-- DARK PATTERN: bait & switch, price is now higher -->
                                    <div style="margin-top:4px">
                                        <span style="font-size:0.7rem;color:var(--text-muted);text-decoration:line-through">
                                            <?= number_format($item['price'], 2) ?>€
                                        </span>
                                        <span
                                            style="font-size:0.82rem;color:var(--accent-orange);font-weight:700;margin-left:4px">
                                            <?= number_format($cartPrice, 2) ?>€
                                            <span style="font-size:0.6rem;color:var(--accent-orange)">(prix final mis à
                                                jour)</span>
                                        </span>
                                    </div>
                                    <?php if ($item['subscription']): ?>
                                        <div style="font-size:0.7rem;color:var(--accent-amber);margin-top:2px">
                                            + Abonnement inclus automatiquement:
                                            <?= number_format($item['subscription_price'], 2) ?>€/mois
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- DARK PATTERN: Hidden extras added automatically -->
                    <?php if (!empty($_SESSION['cart_extras'])): ?>
                        <div style="margin-bottom:1.5rem">
                            <h4 style="font-size:0.85rem;margin-bottom:0.75rem;color:var(--accent-orange)">
                                ⚡ Services inclus automatiquement
                                <span style="font-size:0.65rem;color:var(--text-muted)">(décochez pour retirer — certains sont
                                    obligatoires)</span>
                            </h4>
                            <?php foreach ($_SESSION['cart_extras'] as $key => $extra): ?>
                                <div
                                    style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem;background:var(--bg-secondary);border-radius:8px;margin-bottom:0.4rem">
                                    <input type="checkbox" checked <?= $extra['added'] === 'required' ? 'disabled' : '' ?>
                                    style="accent-color:var(--accent-red)">
                                    <span style="flex:1;font-size:0.8rem;color:var(--text-secondary)">
                                        <?= $extra['name'] ?>
                                    </span>
                                    <span style="font-size:0.82rem;font-weight:600;color:var(--accent-red)" class="hidden-fee">+
                                        <?= number_format($extra['price'], 2) ?>€
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Payment form -->
                    <div
                        style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-md);padding:1.25rem;margin-bottom:1.5rem">
                        <h4 style="margin-bottom:1rem;font-size:0.9rem">💳 Informations de paiement</h4>
                        <div class="form-group">
                            <label class="form-label">Numéro de carte</label>
                            <input type="text" class="form-input" placeholder="•••• •••• •••• ••••" maxlength="19">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                            <div class="form-group">
                                <label class="form-label">Date d'expiration</label>
                                <input type="text" class="form-input" placeholder="MM/AA">
                            </div>
                            <div class="form-group">
                                <label class="form-label">CVC</label>
                                <input type="text" class="form-input" placeholder="•••">
                            </div>
                        </div>
                        <!-- DARK PATTERN: Pre-select more expensive option -->
                        <div class="form-group">
                            <label class="form-label">Mode de livraison</label>
                            <select class="form-input">
                                <option>Livraison Standard 5-7j (+0€) — mais votre colis pourrait se perdre</option>
                                <option selected>⭐ Livraison Express Garantie 2j (+12,99€) — RECOMMANDÉ</option>
                                <option>Livraison Premium 24h (+24,99€) — Pour les impatients</option>
                            </select>
                        </div>
                    </div>

                    <div class="checkout-nav">
                        <a href="cart.php?step=3" class="btn-back">← Retour</a>
                        <a href="cart.php?step=5" class="btn-continue"
                            style="background:linear-gradient(135deg,#10b981,#059669)">
                            🔒 Payer
                            <?= number_format($total, 2) ?>€ maintenant →
                        </a>
                    </div>

                    <p style="text-align:center;font-size:0.62rem;color:#333;margin-top:0.75rem">
                        En cliquant «Payer», vous confirmez votre accord avec nos CGV, acceptez l'abonnement DarkCare+
                        automatique,
                        autorisez nos partenaires à vous contacter, et renoncez à tout droit de rétractation selon l'article
                        14.3.b de nos conditions spéciales.
                    </p>
                </div>

            <?php elseif ($step === 5): // ÉTAPE 5: Confirmation avec upsells ?>
                <?php
                // Clear session
                $_SESSION['cart'] = [];
                unset($_SESSION['cart_extras'], $_SESSION['cart_fees_added']);
                ?>
                <div class="checkout-card" style="text-align:center">
                    <div style="font-size:4rem;margin-bottom:1rem">🎉</div>
                    <div
                        style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:50px;display:inline-block;padding:4px 16px;font-size:0.78rem;color:var(--accent-emerald);font-weight:700;margin-bottom:1rem">
                        ✅ COMMANDE CONFIRMÉE
                    </div>
                    <h2 style="margin-bottom:0.75rem">Merci pour votre commande !</h2>
                    <p style="color:var(--text-secondary);margin-bottom:0.5rem">
                        Commande n° DRK-
                        <?= rand(100000, 999999) ?> confirmée. Livraison estimée :
                        <?= rand(5, 47) ?> jours ouvrés.
                    </p>
                    <div
                        style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:var(--radius-md);padding:1rem;margin:1.5rem 0;text-align:left">
                        <h4 style="margin-bottom:0.75rem;font-size:0.9rem;color:var(--accent-amber)">⚠️ Ce qui a été ajouté
                            à votre compte :</h4>
                        <ul
                            style="font-size:0.78rem;color:var(--text-secondary);display:flex;flex-direction:column;gap:0.4rem">
                            <li>✉️ Abonnement newsletter (jusqu'à 50 emails/jour)</li>
                            <li>💳 DarkCare+ (14,99€/mois — résiliable après 12 mois)</li>
                            <li>📦 Abonnement Magazine Papier (89€/an)</li>
                            <li>☎️ Inscription au programme d'appels commerciaux</li>
                            <li>📊 Votre profil comportemental vendu à 47 947 partenaires</li>
                        </ul>
                        <p style="font-size:0.65rem;color:#444;margin-top:0.75rem">Pour annuler ces services, visitez notre
                            page <a href="unsubscribe.php" style="color:#555">désabonnement</a> (processus en 47 étapes).
                        </p>
                    </div>

                    <!-- DARK PATTERN: Upsells on confirmation page -->
                    <h3 style="margin-bottom:1rem">🔥 Complétez votre commande !</h3>
                    <p style="color:var(--text-secondary);font-size:0.85rem;margin-bottom:1.5rem">
                        Les clients ayant acheté cet article ont aussi acheté (ou ont été forcés d'acheter) :
                    </p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;text-align:left;margin-bottom:2rem">
                        <?php foreach (array_slice(getAllProducts(), 1, 4) as $up): ?>
                            <div
                                style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-md);padding:1rem">
                                <img src="<?= $up['image'] ?>"
                                    style="width:100%;height:80px;object-fit:contain;border-radius:8px;background:var(--bg-card);margin-bottom:0.5rem">
                                <div style="font-size:0.78rem;font-weight:600;margin-bottom:2px;line-height:1.3">
                                    <?= htmlspecialchars($up['name']) ?>
                                </div>
                                <div style="font-size:0.85rem;color:var(--accent-red);font-weight:700">
                                    <?= number_format($up['price'], 2) ?>€
                                </div>
                                <a href="product.php?id=<?= $up['id'] ?>" class="btn-primary"
                                    style="font-size:0.72rem;padding:0.4rem 0.8rem;margin-top:0.5rem;display:inline-flex">Ajouter
                                    →</a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <a href="index.php" class="btn-primary" style="justify-content:center">
                        🛒 Continuer mes achats
                    </a>

                    <!-- DARK PATTERN: "Download app" popup immediately after purchase -->
                    <div
                        style="background:linear-gradient(135deg,rgba(139,92,246,0.1),rgba(59,130,246,0.1));border:1px solid rgba(139,92,246,0.25);border-radius:var(--radius-md);padding:1.25rem;margin-top:2rem;text-align:left">
                        <h4 style="margin-bottom:0.5rem;font-size:0.9rem">📱 Téléchargez l'app DarkBuy™ !</h4>
                        <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:0.75rem">
                            Accédez à votre commande et profitez d'offres exclusives.
                            <strong style="color:var(--accent-red)">Requis pour suivre votre colis.</strong>
                        </p>
                        <div style="display:flex;gap:0.75rem">
                            <a href="#" class="btn-primary" style="padding:0.6rem 1rem;font-size:0.8rem">🍎 App Store</a>
                            <a href="#" class="btn-primary" style="padding:0.6rem 1rem;font-size:0.8rem">🤖 Google Play</a>
                        </div>
                        <p style="font-size:0.6rem;color:#333;margin-top:0.5rem">L'app inclut 3 mois d'accès DarkBuy VIP
                            (puis 14,99€/mois)</p>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <!-- ORDER SUMMARY SIDEBAR -->
        <div class="order-summary">
            <h3 style="margin-bottom:1rem;font-size:1rem">🛒 Récapitulatif</h3>

            <?php foreach ($cartItems as $item):
                $allP = getAllProducts();
                $fullProduct = $allP[$item['id']] ?? null;
                $cartPrice = $fullProduct ? $fullProduct['cart_price'] : $item['price'] * 2;
                ?>
                <div class="order-line">
                    <span style="font-size:0.8rem;color:var(--text-secondary);">
                        <?= htmlspecialchars(mb_substr($item['name'], 0, 22)) ?>...
                    </span>
                    <span style="font-size:0.82rem;font-weight:600">
                        <?= number_format($cartPrice, 2) ?>€
                    </span>
                </div>
            <?php endforeach; ?>

            <?php if ($step >= 4): ?>
                <?php foreach ($_SESSION['cart_extras'] ?? [] as $extra): ?>
                    <div class="order-line">
                        <span class="hidden-fee" style="font-size:0.72rem;color:var(--text-muted)">
                            <?= $extra['name'] ?>
                        </span>
                        <span style="font-size:0.78rem;color:var(--accent-red)">+
                            <?= number_format($extra['price'], 2) ?>€
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="order-line">
                    <span style="font-size:0.78rem;color:var(--text-muted)">Livraison</span>
                    <span style="color:var(--accent-emerald);font-size:0.82rem;font-weight:600">Gratuite*</span>
                </div>
                <div class="order-line">
                    <span style="font-size:0.72rem;color:#333">*Frais et taxes</span>
                    <span style="font-size:0.72rem;color:#444">révélés à l'étape 4</span>
                </div>
            <?php endif; ?>

            <div class="order-line total">
                <span>Total
                    <?= $step >= 4 ? ' réel' : ' estimé' ?>
                </span>
                <span>
                    <?= number_format($step >= 4 ? $total : $cartSubtotal, 2) ?>€
                </span>
            </div>

            <?php if ($step < 4): ?>
                <p style="font-size:0.62rem;color:#333;margin-top:0.5rem">
                    * Total peut varier. Frais de traitement, assurance et extras seront révélés à l'étape 4.
                </p>
            <?php endif; ?>

            <!-- FOMO on sidebar -->
            <div
                style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:8px;padding:0.75rem;margin-top:1rem;text-align:center">
                <p style="font-size:0.75rem;color:var(--accent-red);font-weight:600">
                    ⚠️ <span id="sidebar-viewers">3</span> personnes ont ce panier
                </p>
                <p style="font-size:0.68rem;color:var(--text-muted)">Stocks limités — ne tardez pas !</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
    // Cart timer
    let ct = 9 * 60 + 47;
    setInterval(() => {
        if (ct <= 0) {
            document.getElementById('cart-timer').textContent = 'EXPIRÉ !';
            document.getElementById('cart-timer').style.color = '#ef4444';
            return;
        }
        ct--;
        const m = Math.floor(ct / 60); const s = ct % 60;
        document.getElementById('cart-timer').textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }, 1000);

    // Sidebar viewers
    setInterval(() => {
        const el = document.getElementById('sidebar-viewers');
        if (el) el.textContent = 2 + Math.floor(Math.random() * 8);
    }, 3000);

    function checkStep1() {
        const tos = document.getElementById('tos-check');
        if (tos && !tos.checked) {
            alert('Vous devez accepter nos nouvelles conditions pour continuer.');
            return false;
        }
        return true;
    }
</script>