// DarkBuy™ — Cart & User Store (localStorage-based, replaces PHP sessions)

const Store = {
    // ── Cart ────────────────────────────────────────────────
    getCart() {
        try { return JSON.parse(localStorage.getItem('darkbuy_cart') || '{}'); }
        catch { return {}; }
    },
    saveCart(cart) { localStorage.setItem('darkbuy_cart', JSON.stringify(cart)); },

    addToCart(productId) {
        const p = getProduct(productId);
        if (!p) return;
        const cart = this.getCart();
        if (cart[productId]) { cart[productId].qty++; }
        else { cart[productId] = { id: productId, name: p.name, price: p.price, qty: 1, image: p.image, subscription: true, subscriptionPrice: p.subscriptionPrice }; }
        this.saveCart(cart);
        this.updateCartBadge();
        return this.getCartCount();
    },

    removeFromCart(productId) {
        const cart = this.getCart();
        delete cart[productId];
        this.saveCart(cart);
        this.updateCartBadge();
    },

    clearCart() { localStorage.removeItem('darkbuy_cart'); this.updateCartBadge(); },

    getCartCount() {
        return Object.values(this.getCart()).reduce((s, i) => s + i.qty, 0);
    },

    getCartSubtotal() {
        // DARK PATTERN: prices are higher in cart (bait & switch)
        return Object.values(this.getCart()).reduce((s, i) => {
            const p = getProduct(i.id);
            return s + (p ? p.cartPrice : i.price) * i.qty;
        }, 0);
    },

    updateCartBadge() {
        const count = this.getCartCount();
        document.querySelectorAll('.cart-badge').forEach(el => el.textContent = count);
        document.querySelectorAll('.cart-count').forEach(el => el.textContent = count);
        if (count > 0) {
            document.querySelectorAll('.cart-badge-wrap').forEach(el => el.classList.remove('hidden'));
        }
    },

    // ── User ────────────────────────────────────────────────
    getUser() {
        try { return JSON.parse(localStorage.getItem('darkbuy_user') || 'null'); }
        catch { return null; }
    },
    setUser(user) { localStorage.setItem('darkbuy_user', JSON.stringify(user)); },
    isLoggedIn() { return !!this.getUser(); },
    logout() { localStorage.removeItem('darkbuy_user'); },

    // ── Cart Extras (hidden fees, step 4) ───────────────────
    EXTRAS: [
        { key: 'protection', name: 'Plan Protection DarkCare+', price: 14.99, required: false },
        { key: 'express', name: 'Livraison Express (sélectionnée auto)', price: 12.99, required: false },
        { key: 'packaging', name: 'Emballage Cadeau Premium', price: 4.99, required: false },
        { key: 'insurance', name: 'Assurance Colis Obligatoire', price: 6.99, required: true },
        { key: 'processing', name: 'Frais de Traitement', price: 8.99, required: true },
    ],
    getExtrasTotal() { return this.EXTRAS.reduce((s, e) => s + e.price, 0); },
};
