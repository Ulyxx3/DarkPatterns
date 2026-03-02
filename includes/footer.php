<!-- FOOTER avec liens impossibles à trouver -->
<footer class="site-footer">
    <div class="footer-newsletter">
        <div class="footer-nl-inner">
            <h3>📧 Restez informé(e) de nos offres exclusives !</h3>
            <p>Rejoignez nos 4,7 millions d'abonnés satisfaits*</p>
            <form class="nl-form" onsubmit="subscribeNewsletter(event)">
                <input type="email" placeholder="votre@email.com" id="nl-email" required>
                <!-- DARK PATTERN: Trick question - double négation cachée -->
                <button type="submit" class="btn-nl-subscribe">S'abonner GRATUITEMENT</button>
            </form>
            <div class="nl-checkboxes">
                <!-- DARK PATTERN: Cases pré-cochées, double négation -->
                <label class="nl-check"><input type="checkbox" checked id="nl-offers">
                    Je souhaite recevoir les offres DarkBuy™ (décochez pour ne pas recevoir les meilleures
                    offres)</label>
                <label class="nl-check"><input type="checkbox" checked id="nl-partners">
                    Ne pas ne pas recevoir les offres de nos partenaires (47 947 sociétés)</label>
                <label class="nl-check"><input type="checkbox" checked id="nl-sms">
                    J'accepte de ne pas refuser les SMS promotionnels (jusqu'à 50/jour)</label>
                <label class="nl-check"><input type="checkbox" id="nl-unsub" style="display:none">
                    Option technique requise pour la cohérence de nos systèmes (obligatoire)</label>
            </div>
            <p class="nl-fine-print">
                *Satisfaction mesurée parmi les 3 employés qui ont répondu au sondage.<br>
                Pour se désabonner : <a href="unsubscribe.php" style="color:#666;font-size:0.6rem">cliquez ici</a>
                (processus en 47 étapes, délai 180 jours)
            </p>
        </div>
    </div>

    <div class="footer-main">
        <div class="footer-col">
            <div class="logo" style="margin-bottom:1rem">
                <span class="logo-dark">Dark</span><span class="logo-buy">Buy™</span>
            </div>
            <p style="color:#888;font-size:0.85rem;line-height:1.6">
                DarkBuy™ est votre destination shopping #1 pour des produits de qualité* discutable.
                <br>*Qualité non garantie, non testée, non vérifiée.
            </p>
            <div class="footer-social">
                <a href="#" class="social-btn">f</a>
                <a href="#" class="social-btn">🐦</a>
                <a href="#" class="social-btn">📷</a>
                <a href="#" class="social-btn">▶️</a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Aide & Contact</h4>
            <ul>
                <li><a href="#">FAQ (payante après 3 questions)</a></li>
                <li><a href="#">Nous contacter (délai: 6-8 semaines)</a></li>
                <li><a href="#">Retours & Remboursements (voir CGV p.847)</a></li>
                <li><a href="#">Suivi commande (requiert un compte premium)</a></li>
                <li><a href="unsubscribe.php">Se désabonner (bonne chance)</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Légal (ennuyeux mais important)</h4>
            <ul>
                <!-- DARK PATTERN: Liens légaux minuscules et cachés -->
                <li><a href="#" style="font-size:0.6rem;color:#444">Conditions Générales de Vente (1,247 pages)</a></li>
                <li><a href="#" style="font-size:0.6rem;color:#444">Politique de Confidentialité (version 847.3)</a>
                </li>
                <li><a href="#" style="font-size:0.6rem;color:#444">Politique Cookies (47 sous-sections)</a></li>
                <li><a href="#" style="font-size:0.6rem;color:#444">Mentions Légales (en latin)</a></li>
                <li><a href="#" style="font-size:0.6rem;color:#444">Droit de rétractation* (*non applicable)</a></li>
                <li><a href="#" style="font-size:0.6rem;color:#444">RGPD (nous y travaillons depuis 2018)</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Paiements sécurisés*</h4>
            <div class="payment-badges">
                <span class="pay-badge">VISA</span>
                <span class="pay-badge">MC</span>
                <span class="pay-badge">PAYPAL</span>
                <span class="pay-badge">CRYPTO</span>
            </div>
            <p style="font-size:0.6rem;color:#444;margin-top:0.5rem">
                *Sécurisé par SSL 0.5. Notre serveur utilise Windows XP.<br>
                Nous ne stockons PAS vos données de carte (dans nos propres serveurs —
                nous les stockons chez nos 47 partenaires).
            </p>
            <div class="trust-badges">
                <div class="trust-badge">🏆 Meilleur site 2019<br><span>Prix auto-décerné</span></div>
                <div class="trust-badge">✅ Certifié "Fiable"<br><span>Par nous-mêmes</span></div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2026 DarkBuy™ SAS — Siège social: Rue des Illusions, 75000 Paris (Fiscalement aux Îles Caïman)</p>
        <p style="font-size:0.6rem;color:#333;margin-top:0.5rem">
            En naviguant sur ce site vous acceptez nos CGV, notre politique de cookies, notre charte graphique,
            notre vision artistique, nos erreurs de calcul de TVA et notre politique de non-remboursement.
            DarkBuy™ n'est pas responsable de vos décisions d'achat, de votre satisfaction, de vos regrets,
            ni de l'état général de votre compte bancaire. Les photos sont non contractuelles.
            Les avis clients sont triés selon un algorithme propriétaire (on cache les mauvais).
            Prix TTC hors taxes hors frais hors raison.
        </p>
    </div>
</footer>

<script>
    function subscribeNewsletter(e) {
        e.preventDefault();
        const email = document.getElementById('nl-email').value;
        if (email) {
            // DARK PATTERN: Always subscribes regardless of checkboxes
            localStorage.setItem('dp_email', email);
            document.querySelector('.nl-form').innerHTML = `
            <div style="color:#4ade80;font-weight:600;padding:1rem">
                ✅ Merci ! Vous recevrez jusqu'à 50 emails par jour.<br>
                <small style="color:#888">Pour vous désabonner, visitez notre page <a href="unsubscribe.php" style="color:#666">désabonnement</a> (processus en 47 étapes)</small>
            </div>`;
        }
    }
</script>