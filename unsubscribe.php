<?php
$pageTitle = 'Se désabonner — DarkBuy™ (Processus en 47 étapes)';
$step = (int) ($_GET['step'] ?? 1);
$step = max(1, min(47, $step));
require_once 'includes/header.php';
?>
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:3rem 1rem">
    <div style="max-width:600px;width:100%">

        <div style="text-align:center;margin-bottom:2rem">
            <h1 style="font-size:1.5rem;margin-bottom:0.5rem">Se désabonner</h1>
            <p style="color:var(--text-secondary);font-size:0.85rem">
                Étape <strong style="color:var(--accent-red)">
                    <?= $step ?>
                </strong> sur <strong>47</strong>
            </p>
            <div class="progress-bar" style="margin:1rem auto;max-width:400px">
                <div class="progress-fill" style="width:<?= round(($step / 47) * 100) ?>%"></div>
            </div>
            <p style="font-size:0.72rem;color:var(--text-muted)">
                Délai de traitement estimé : 180 jours • Ce processus peut être interrompu à tout moment (par nous)
            </p>
        </div>

        <div
            style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-xl);padding:2rem">

            <?php if ($step === 1): ?>
                <h2 style="margin-bottom:1rem;font-size:1.2rem">😢 Vous souhaitez vraiment partir ?</h2>
                <p style="color:var(--text-secondary);font-size:0.85rem;margin-bottom:1.5rem">
                    Avant de continuer, nous aimerions vous rappeler les avantages auxquels vous renoncez :
                </p>
                <ul
                    style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:1.5rem;display:flex;flex-direction:column;gap:0.5rem">
                    <li>❌ Jusqu'à 50 emails quotidiens avec les "meilleures" offres</li>
                    <li>❌ Appels téléphoniques personnalisés (jusqu'à 3/jour)</li>
                    <li>❌ SMS géolocalisés en temps réel</li>
                    <li>❌ Votre magazine papier préféré (89€/an)</li>
                    <li>❌ Accès aux offres "exclusives" (disponibles partout)</li>
                </ul>
                <div style="display:flex;flex-direction:column;gap:0.75rem">
                    <a href="index.php" class="btn-primary" style="text-align:center;justify-content:center">
                        ✅ Rester abonné(e) — c'est tellement mieux !
                    </a>
                    <a href="?step=2" class="btn-back"
                        style="text-align:center;display:block;font-size:0.78rem;color:var(--text-muted);text-decoration:underline">
                        Continuer la désinscription (étape 2/47) ›
                    </a>
                </div>

            <?php elseif ($step === 2): ?>
                <h2 style="margin-bottom:1rem;font-size:1.2rem">📋 Motif de désinscription (obligatoire)</h2>
                <p style="color:var(--text-secondary);font-size:0.85rem;margin-bottom:1.5rem">Sélectionnez votre motif
                    principal (requis par nos équipes marketing) :</p>
                <select class="form-input" style="margin-bottom:1rem;width:100%" required>
                    <option value="">— Choisir un motif —</option>
                    <option>Trop d'emails (mais c'est parce que vous n'avez pas configuré vos préférences)</option>
                    <option>Je n'ai pas souvenir de m'être inscrit(e) (normal, c'est automatique)</option>
                    <option>Je ne suis pas satisfait(e) des produits (erreur de votre part)</option>
                    <option>Raison personnelle (veuillez préciser en 500 mots minimum)</option>
                    <option>Autre (traitement 60 jours supplémentaires)</option>
                </select>
                <textarea class="form-input" rows="4"
                    placeholder="Expliquez en détail votre motif de désinscription (minimum 500 mots, champ obligatoire)..."
                    style="margin-bottom:1rem;resize:vertical"></textarea>
                <div style="display:flex;justify-content:space-between;gap:1rem">
                    <a href="?step=1" class="btn-back">← Retour</a>
                    <a href="?step=3" class="btn-continue" style="padding:0.65rem 1.5rem;font-size:0.85rem">Suivant ›</a>
                </div>

            <?php elseif ($step === 3): ?>
                <h2 style="margin-bottom:1rem;font-size:1.2rem">🔐 Vérification d'identité (RGPD)</h2>
                <p style="color:var(--text-secondary);font-size:0.85rem;margin-bottom:1.5rem">Pour protéger votre compte,
                    nous devons vérifier votre identité :</p>
                <div class="form-group">
                    <label class="form-label">Email utilisé à l'inscription</label>
                    <input type="email" class="form-input" placeholder="votre@email.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Numéro de commande (obligatoire)</label>
                    <input type="text" class="form-input" placeholder="DRK-XXXXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Date de naissance (pour vérification)</label>
                    <input type="date" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Pièce d'identité (scan recto-verso, obligatoire)</label>
                    <input type="file" class="form-input" style="cursor:pointer">
                    <p style="font-size:0.65rem;color:#444;margin-top:4px">Votre pièce d'identité sera conservée 10 ans
                        selon nos CGV article 847</p>
                </div>
                <div style="display:flex;justify-content:space-between;gap:1rem">
                    <a href="?step=2" class="btn-back">← Retour</a>
                    <a href="?step=4" class="btn-continue" style="padding:0.65rem 1.5rem;font-size:0.85rem">Vérifier mon
                        identité ›</a>
                </div>

            <?php elseif ($step >= 4 && $step <= 10): ?>
                <h2 style="margin-bottom:1rem;font-size:1.2rem">
                    <?php $subStepTitles = [4 => '📝 Confirmation par email', 5 => '📞 Vérification téléphonique', 6 => '📬 Courrier recommandé', 7 => '🖨️ Formulaire imprimable', 8 => '🔏 Signature électronique', 9 => '⚖️ Service juridique', 10 => '🏛️ Validation DPO']; ?>
                    <?= $subStepTitles[$step] ?>
                </h2>
                <div
                    style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:var(--radius-md);padding:1.25rem;margin-bottom:1.5rem">
                    <?php if ($step === 4): ?>
                        <p style="font-size:0.85rem;color:var(--text-secondary)">Un email de vérification a été envoyé. Cliquez
                            sur le lien dans l'email pour continuer. <strong>Délai de réception : 24-72h.</strong> Si vous ne le
                            recevez pas, vérifiez vos spams (nous vous avons peut-être déjà rangé là-bas).</p>
                    <?php elseif ($step === 5): ?>
                        <p style="font-size:0.85rem;color:var(--text-secondary)">Notre équipe vous appellera au numéro
                            enregistré dans les <strong>5-10 jours ouvrés</strong> pour confirmer votre demande. Assurez-vous
                            d'être disponible de 8h à 22h.</p>
                    <?php elseif ($step === 6): ?>
                        <p style="font-size:0.85rem;color:var(--text-secondary)">Envoyez un <strong>courrier recommandé avec
                                accusé de réception</strong> à notre siège social :<br><em>DarkBuy™ SAS — Service
                                Désabonnement<br>BP 847 — 00000 Fiscalité-sur-Caïman<br>(Luxembourg fiscalement, Îles Caïman
                                réellement)</em></p>
                    <?php elseif ($step === 7): ?>
                        <p style="font-size:0.85rem;color:var(--text-secondary)">Téléchargez, imprimez, remplissez en
                            triplicate, scannez et renvoyez le formulaire officiel de désinscription (47 pages). <a href="#"
                                style="color:var(--accent-red)">Télécharger le formulaire</a></p>
                    <?php else: ?>
                        <p style="font-size:0.85rem;color:var(--text-secondary)">Étape en cours de traitement par notre équipe.
                            Délai :
                            <?= rand(5, 30) ?> jours ouvrés. Référence : DES-
                            <?= rand(10000, 99999) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <!-- DARK PATTERN: Make it easy to go back/cancel the unsub -->
                <div style="text-align:center;margin-bottom:1rem">
                    <a href="index.php" class="btn-primary" style="justify-content:center">
                        ✅ Annuler ma désinscription et rester abonné(e)
                    </a>
                </div>
                <a href="?step=<?= $step + 1 ?>"
                    style="display:block;text-align:center;font-size:0.72rem;color:#444;text-decoration:underline;margin-top:0.5rem">
                    Continuer quand même (étape
                    <?= $step + 1 ?>/47) ›
                </a>

            <?php else: // steps 11-47 ?>
                <h2 style="margin-bottom:1rem;font-size:1.2rem">⏳ Traitement en cours...</h2>
                <p style="color:var(--text-secondary);font-size:0.85rem;margin-bottom:1.5rem">
                    Votre demande de désinscription est en cours de traitement. Cette étape (
                    <?= $step ?>/47)
                    nécessite une validation de notre équipe spécialisée.
                </p>
                <div
                    style="background:var(--bg-secondary);border-radius:var(--radius-md);padding:1.25rem;margin-bottom:1.5rem;text-align:center">
                    <div style="font-size:2rem;margin-bottom:0.5rem">⏳</div>
                    <p style="font-size:0.85rem;font-weight:600;margin-bottom:0.25rem">Délai estimé :
                        <?= rand(10, 180) ?> jours
                    </p>
                    <p style="font-size:0.72rem;color:var(--text-muted)">Pendant ce temps, vous continuerez à recevoir nos
                        communications</p>
                </div>
                <?php if ($step < 47): ?>
                    <a href="?step=<?= $step + 1 ?>" class="btn-secondary"
                        style="display:block;text-align:center;font-size:0.78rem">
                        Passer à l'étape suivante (
                        <?= $step + 1 ?>/47) ›
                    </a>
                <?php else: ?>
                    <div
                        style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:var(--radius-md);padding:1.25rem;text-align:center">
                        <p style="font-size:0.9rem;color:var(--accent-emerald);font-weight:700">🎉 Processus terminé !</p>
                        <p style="font-size:0.78rem;color:var(--text-muted);margin-top:0.5rem">
                            Votre demande sera traitée dans un délai de 6 à 24 mois.
                            Vous recevrez une confirmation... par email (à l'adresse de laquelle vous vouliez vous désabonner).
                        </p>
                    </div>
                <?php endif; ?>
                <div style="text-align:center;margin-top:1rem">
                    <a href="index.php" class="btn-primary" style="justify-content:center;">
                        Abandonner et continuer à recevoir nos emails
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar guilt trip -->
        <div
            style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-md);padding:1rem;margin-top:1.5rem;text-align:center">
            <p style="font-size:0.78rem;color:var(--text-muted)">
                💔 Vous manquez déjà à notre équipe.
                <a href="index.php" style="color:var(--accent-red);font-weight:600">Revenir sur DarkBuy™</a>
            </p>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>