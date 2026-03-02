// DarkBuy™ — Dark Patterns Global Logic
// Cookie banner, exit intent, timer, FOMO toasts, push notifications

// ── Cookie Banner ────────────────────────────────────────────────────────────
function initCookieBanner() {
    if (localStorage.getItem('dp_cookies')) return;
    setTimeout(() => {
        document.getElementById('cookie-overlay').style.display = 'flex';
    }, 800);
}

function acceptCookies() {
    document.getElementById('cookie-overlay').style.display = 'none';
    ['cookie-detail-modal', 'cookie-refuse-modal'].forEach(id => {
        document.getElementById(id)?.classList.add('hidden');
    });
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
const labyrinthMsgs = [
    'Vraiment vraiment sûr(e) ?',
    'Mais nos cookies sont si gentils... 🥺',
    'Dernier avertissement : prix augmentés de 40% sans cookies',
    "OK mais c'est votre perte. Confirmez en tapant 'JE REFUSE' :",
    'Pour finaliser votre refus, contactez notre DPO (disponible les mardis 14h-14h15)',
];
function cookieLabyrinth(step) {
    labyrinthStep = step;
    const h = document.querySelector('#cookie-refuse-modal h3');
    const btn = document.querySelector('#cookie-refuse-modal .btn-reject-small');
    if (h) h.textContent = labyrinthMsgs[step] || '';
    if (btn) btn.onclick = () => {
        if (step + 1 >= labyrinthMsgs.length) acceptCookies();
        else cookieLabyrinth(step + 1);
    };
    if (step >= labyrinthMsgs.length) acceptCookies();
}

// ── Push Notification ────────────────────────────────────────────────────────
function showPushNotif() {
    const n = document.getElementById('push-notif');
    if (!n) return;
    n.classList.remove('hidden');
    n.classList.add('slide-in');
}
function allowPush() { document.getElementById('push-notif')?.classList.add('hidden'); }
function denyPush() {
    document.getElementById('push-notif')?.classList.add('hidden');
    setTimeout(showPushNotif, 10000);
}

// ── Global Countdown Timer ────────────────────────────────────────────────────
function startGlobalTimer() {
    let total = 4 * 3600 + 32 * 60 + 11;
    setInterval(() => {
        if (total <= 0) total = 4 * 3600 + 59 * 60 + 59;
        total--;
        const h = Math.floor(total / 3600);
        const m = Math.floor((total % 3600) / 60);
        const s = total % 60;
        const str = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        document.querySelectorAll('.global-timer').forEach(el => el.textContent = str);
    }, 1000);
}

// ── Fake Viewer Count ─────────────────────────────────────────────────────────
function startFakeViewers() {
    const update = () => {
        const n = 31 + Math.floor(Math.random() * 40);
        document.querySelectorAll('.viewers-count').forEach(el => el.textContent = n);
        setTimeout(update, 2000 + Math.random() * 3000);
    };
    update();
}

// ── Exit Intent Popup ─────────────────────────────────────────────────────────
function initExitIntent() {
    let shown = false;
    document.addEventListener('mouseleave', e => {
        if (e.clientY < 10 && !shown) {
            shown = true;
            document.getElementById('exit-popup')?.classList.remove('hidden');
            startExitTimer();
        }
    });
}
function startExitTimer() {
    let s = 3 * 60 + 47;
    const el = document.getElementById('exit-timer');
    const iv = setInterval(() => {
        if (s <= 0) { clearInterval(iv); if (el) el.textContent = 'EXPIRÉE !'; return; }
        s--;
        const m2 = Math.floor(s / 60), s2 = s % 60;
        if (el) el.textContent = `${String(m2).padStart(2, '0')}:${String(s2).padStart(2, '0')}`;
    }, 1000);
}
function closeExitPopup() { document.getElementById('exit-popup')?.classList.add('hidden'); }

// ── FOMO Toasts ───────────────────────────────────────────────────────────────
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
    toast.innerHTML = `<div class="fomo-toast-avatar">👤</div>
    <div><strong style="display:block;font-size:.78rem">${msg.name}</strong>
    <span style="font-size:.72rem;color:var(--text-muted)">${msg.msg}</span></div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}
function initFomoToasts() {
    setTimeout(showFomoToast, 3000);
    setInterval(showFomoToast, 7000);
}

// ── Newsletter subscription ───────────────────────────────────────────────────
function subscribeNewsletter(e) {
    e.preventDefault();
    const email = document.getElementById('nl-email')?.value;
    if (email) {
        localStorage.setItem('dp_email', email);
        const form = document.querySelector('.nl-form');
        if (form) form.innerHTML = `<div style="color:#4ade80;font-weight:600;padding:1rem">
      ✅ Merci ! Vous recevrez jusqu'à 50 emails par jour.<br>
      <small style="color:#888">Pour vous désabonner, visitez notre page <a href="unsubscribe.html" style="color:#666">désabonnement</a> (processus en 47 étapes)</small></div>`;
    }
}

// ── Search suggestions ────────────────────────────────────────────────────────
function initSearch() {
    const input = document.getElementById('search-input');
    const sugg = document.getElementById('search-suggestions');
    if (!input || !sugg) return;
    input.addEventListener('focus', () => sugg.classList.remove('hidden'));
    document.addEventListener('click', e => {
        if (!e.target.closest('.search-wrapper')) sugg.classList.add('hidden');
    });
}

// ── Boot ──────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    Store.updateCartBadge();
    initCookieBanner();
    startGlobalTimer();
    startFakeViewers();
    initExitIntent();
    initFomoToasts();
    initSearch();

    // Update nav user display
    const user = Store.getUser();
    document.querySelectorAll('.nav-user-name').forEach(el => {
        el.textContent = user ? user.name.split('@')[0] : 'Connexion';
    });
});
