<?php
session_start();
$pageTitle = 'Connexion — DarkBuy™ (Obligatoire pour continuer)';
$returnUrl = $_GET['return'] ?? 'cart.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // DARK PATTERN: Accept any credentials
        $_SESSION['user'] = [
            'name' => htmlspecialchars($_POST['email']),
            'email' => htmlspecialchars($_POST['email']),
        ];
        // DARK PATTERN: Subscribe to newsletter without asking
        $_SESSION['newsletter'] = true;
        header('Location: ' . $returnUrl);
        exit;
    }
    if (isset($_POST['register'])) {
        $_SESSION['user'] = [
            'name' => htmlspecialchars($_POST['first_name'] . ' ' . $_POST['last_name']),
            'email' => htmlspecialchars($_POST['email']),
        ];
        $_SESSION['newsletter'] = true;
        header('Location: ' . $returnUrl);
        exit;
    }
}

require_once 'includes/header.php';
?>
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:3rem 1rem">
    <div style="width:100%;max-width:480px">

        <!-- DARK PATTERN: Urgency even on login page -->
        <div
            style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:var(--radius-md);padding:0.75rem 1rem;margin-bottom:1.5rem;text-align:center">
            <span style="font-size:0.82rem;color:var(--accent-red);font-weight:600">
                ⚠️ Votre panier est réservé pendant encore <span id="login-timer">09:47</span> !
            </span>
        </div>

        <div
            style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-xl);overflow:hidden">
            <!-- Tabs -->
            <div style="display:flex;border-bottom:1px solid var(--border)">
                <button onclick="showTab('login')" id="tab-login" class="auth-tab active"
                    style="flex:1;padding:1rem;background:none;border:none;cursor:pointer;font-size:0.9rem;font-weight:600;color:var(--text-primary);border-bottom:2px solid var(--accent-red)">
                    Se connecter
                </button>
                <button onclick="showTab('register')" id="tab-register" class="auth-tab"
                    style="flex:1;padding:1rem;background:none;border:none;cursor:pointer;font-size:0.9rem;font-weight:600;color:var(--text-muted);border-bottom:2px solid transparent">
                    Créer un compte
                </button>
            </div>

            <!-- LOGIN FORM -->
            <div id="form-login" style="padding:2rem">
                <h2 style="margin-bottom:0.4rem;font-size:1.3rem">Content de vous revoir !</h2>
                <p style="color:var(--text-secondary);font-size:0.82rem;margin-bottom:1.5rem">
                    Connectez-vous pour finaliser votre commande (OBLIGATOIRE — impossible de continuer sans compte)
                </p>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label" for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" class="form-input" required
                            placeholder="votre@email.com">
                    </div>
                    <div class="form-group" style="position:relative">
                        <label class="form-label" for="login-pass">Mot de passe</label>
                        <input type="password" id="login-pass" name="password" class="form-input" required
                            placeholder="••••••••">
                    </div>

                    <!-- DARK PATTERN: Trick checkbox – forgetting to uncheck subscribes you -->
                    <div
                        style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:var(--radius-sm);padding:0.75rem;margin-bottom:1rem">
                        <label
                            style="display:flex;gap:0.5rem;cursor:pointer;font-size:0.78rem;color:var(--text-secondary)">
                            <input type="checkbox" name="remember" checked
                                style="accent-color:var(--accent-amber);margin-top:2px">
                            <span>Ne pas ne pas me déconnecter automatiquement (et m'abonner à la newsletter VIP
                                gratuite*)</span>
                        </label>
                        <p style="font-size:0.6rem;color:#333;margin-top:3px;padding-left:1.5rem">*Gratuit les 7
                            premiers jours, puis 4,99€/mois sans limite</p>
                    </div>

                    <!-- DARK PATTERN: Forgotten password link designed to look like regular text -->
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem">
                        <span></span>
                        <span style="font-size:0.72rem;color:var(--text-muted)">
                            Mot de passe oublié ? <a href="#" style="color:#444;text-decoration:underline">Récupérer</a>
                            (service payant 2,99€)
                        </span>
                    </div>

                    <button type="submit" name="login" class="btn-primary"
                        style="width:100%;justify-content:center;font-size:1rem;padding:0.9rem">
                        🔑 Se connecter et continuer
                    </button>

                    <p style="text-align:center;font-size:0.65rem;color:#333;margin-top:0.75rem">
                        En vous connectant, vous acceptez nos CGV (1,247 pages), notre politique de cookies,
                        notre newsletter (jusqu'à 50/jour), notre politique de monétisation
                        de vos données et notre vision artistique.
                    </p>
                </form>

                <!-- DARK PATTERN: Social login buttons hidden under "more options" -->
                <div style="margin-top:1.25rem;text-align:center">
                    <details style="font-size:0.72rem;color:var(--text-muted)">
                        <summary style="cursor:pointer">Plus d'options de connexion (Google, Facebook, Apple)</summary>
                        <div style="margin-top:0.75rem;opacity:0.4;pointer-events:none">
                            <button
                                style="width:100%;background:var(--bg-secondary);border:1px solid var(--border);color:var(--text-muted);padding:0.6rem;border-radius:8px;margin-bottom:0.5rem;cursor:not-allowed;font-family:inherit">
                                🔒 Google (Premium requis)
                            </button>
                            <button
                                style="width:100%;background:var(--bg-secondary);border:1px solid var(--border);color:var(--text-muted);padding:0.6rem;border-radius:8px;cursor:not-allowed;font-family:inherit">
                                🔒 Facebook (Maintenance)
                            </button>
                        </div>
                    </details>
                </div>
            </div>

            <!-- REGISTER FORM -->
            <div id="form-register" style="padding:2rem;display:none">
                <h2 style="margin-bottom:0.4rem;font-size:1.3rem">Rejoignez DarkBuy™</h2>
                <p style="color:var(--text-secondary);font-size:0.82rem;margin-bottom:1.5rem">
                    Créez votre compte gratuitement* pour accéder au meilleur des offres exclusives
                </p>
                <form method="POST">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="first_name" class="form-input" required placeholder="Prénom">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nom</label>
                            <input type="text" name="last_name" class="form-input" required placeholder="Nom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" required placeholder="votre@email.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone (pour SMS promotionnels)</label>
                        <!-- DARK PATTERN: Phone is "required" with no asterisk explanation -->
                        <input type="tel" name="phone" class="form-input" required placeholder="+33 6 XX XX XX XX">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-input" required
                            placeholder="Min. 12 car., 1 majuscule, 1 symbole, 1 cunéiforme">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de naissance (pour "cadeaux personnalisés")</label>
                        <input type="date" name="dob" class="form-input" required>
                    </div>

                    <!-- TRICK CHECKBOXES section -->
                    <div style="margin-bottom:1.25rem;display:flex;flex-direction:column;gap:0.5rem">

                        <!-- DARK PATTERN: Required to accept everything -->
                        <label
                            style="display:flex;gap:0.5rem;cursor:pointer;font-size:0.78rem;color:var(--text-secondary)">
                            <input type="checkbox" required style="accent-color:var(--accent-red);margin-top:2px">
                            <span>J'accepte les <a href="#" style="color:var(--accent-red)">CGV</a>, <a href="#"
                                    style="color:var(--accent-red)">CGU</a>, la <a href="#"
                                    style="color:var(--accent-red)">Politique de Confidentialité</a> et les <a href="#"
                                    style="color:var(--accent-red)">Conditions Spéciales</a> (obligatoire)</span>
                        </label>

                        <!-- DARK PATTERN: Double negative with pre-checked -->
                        <label
                            style="display:flex;gap:0.5rem;cursor:pointer;font-size:0.78rem;color:var(--text-secondary)">
                            <input type="checkbox" name="nl1" checked
                                style="accent-color:var(--accent-red);margin-top:2px">
                            <span>Je ne souhaite pas ne pas recevoir les offres DarkBuy™ par email</span>
                        </label>

                        <label
                            style="display:flex;gap:0.5rem;cursor:pointer;font-size:0.78rem;color:var(--text-secondary)">
                            <input type="checkbox" name="nl2" checked
                                style="accent-color:var(--accent-red);margin-top:2px">
                            <span>Je refuse de refuser les offres de nos partenaires (47 947 sociétés)</span>
                        </label>

                        <label
                            style="display:flex;gap:0.5rem;cursor:pointer;font-size:0.78rem;color:var(--text-secondary)">
                            <input type="checkbox" name="nl3" checked
                                style="accent-color:var(--accent-red);margin-top:2px">
                            <span>J'accepte de recevoir des appels téléphoniques (max 3/jour de nos équipes
                                commerciales)</span>
                        </label>

                        <!-- DARK PATTERN: Legal disclaimer in tiny grey text -->
                        <label style="display:flex;gap:0.5rem;cursor:pointer;font-size:0.65rem;color:#333">
                            <input type="checkbox" name="tracking"
                                style="accent-color:var(--accent-red);margin-top:2px">
                            <span>J'autorise DarkBuy™ et ses 47 947 partenaires à utiliser mes données biométriques, mes
                                données de navigation, mon historique d'achat, ma localisation GPS en temps réel, et mes
                                données comportementales pour du "profilage avancé" (requis pour le bon fonctionnement
                                du site)</span>
                        </label>
                    </div>

                    <button type="submit" name="register" class="btn-primary"
                        style="width:100%;justify-content:center;font-size:1rem;padding:0.9rem">
                        🚀 Créer mon compte GRATUITEMENT*
                    </button>
                    <p style="text-align:center;font-size:0.6rem;color:#333;margin-top:0.5rem">
                        *Gratuit = 0€ l'inscription. Le compte inclut automatiquement DarkBuy VIP (14,99€/mois)
                        résiliable après 12 mois.
                    </p>
                </form>
            </div>

            <!-- DARK PATTERN: Guest checkout is impossible (button disabled) -->
            <div style="padding:1rem 2rem 1.5rem;border-top:1px solid var(--border);text-align:center">
                <button disabled
                    style="background:none;border:none;color:#333;font-size:0.72rem;cursor:not-allowed;text-decoration:line-through">
                    Continuer sans compte (fonctionnalité désactivée suite à maintenance)
                </button>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>

<script>
    // Login timer countdown
    let lt = 9 * 60 + 47;
    setInterval(() => {
        if (lt <= 0) lt = 9 * 60 + 59;
        lt--;
        const m = Math.floor(lt / 60); const s = lt % 60;
        const el = document.getElementById('login-timer');
        if (el) el.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }, 1000);

    function showTab(tab) {
        const isLogin = tab === 'login';
        document.getElementById('form-login').style.display = isLogin ? 'block' : 'none';
        document.getElementById('form-register').style.display = isLogin ? 'none' : 'block';
        document.getElementById('tab-login').style.borderColor = isLogin ? 'var(--accent-red)' : 'transparent';
        document.getElementById('tab-login').style.color = isLogin ? 'var(--text-primary)' : 'var(--text-muted)';
        document.getElementById('tab-register').style.borderColor = !isLogin ? 'var(--accent-red)' : 'transparent';
        document.getElementById('tab-register').style.color = !isLogin ? 'var(--text-primary)' : 'var(--text-muted)';
    }
</script>