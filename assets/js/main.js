/**
 * Main JavaScript for Landing Bon Bon
 *
 * @package LXLanding
 */
document.documentElement.classList.add('js');

    (function(cfg) {
        if (cfg.facebookPixelId) {
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', cfg.facebookPixelId);
            fbq('track', 'PageView');
            fbq('track', 'ViewContent', { content_name: cfg.productName, value: cfg.price, currency: 'VND' });
        }
        if (cfg.googleAnalyticsId) {
            var g = document.createElement('script');
            g.async = true;
            g.src = 'https://www.googletagmanager.com/gtag/js?id=' + cfg.googleAnalyticsId;
            document.head.appendChild(g);
            window.dataLayer = window.dataLayer || [];
            window.gtag = function(){ dataLayer.push(arguments); };
            gtag('js', new Date());
            gtag('config', cfg.googleAnalyticsId);
        }
        if (cfg.tiktokPixelId) {
            !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var s=d.createElement("script");s.type="text/javascript",s.async=!0,s.src=r+"?sdkid="+e+"&lib="+t;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(s,a)};
            ttq.load(cfg.tiktokPixelId);
            ttq.page();
            }(window, document, 'ttq');
        }
        window.bonbonTrack = function(event, params) {
            params = params || {};
            try {
                if (window.fbq) fbq('track', event, params);
                if (window.gtag) {
                    var gaMap = { Lead: 'generate_lead', InitiateCheckout: 'begin_checkout', Contact: 'contact' };
                    gtag('event', gaMap[event] || event, params);
                }
                if (window.ttq) {
                    var ttMap = { Lead: 'SubmitForm', InitiateCheckout: 'InitiateCheckout', Contact: 'Contact' };
                    ttq.track(ttMap[event] || event, params);
                }
            } catch (err) {}
        };
    })(window.BONBON_CONFIG);



(function () {
    const CFG = window.BONBON_CONFIG || {};
    const M = CFG.media || {};
    const fmt = n => n.toLocaleString('vi-VN') + 'đ';
    const store = {
        get(k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
        set(k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
    };

    // ===== Áp cấu hình liên hệ & giá =====
    if (CFG.hotline) document.querySelectorAll('a[href^="tel:"]').forEach(a => a.href = 'tel:' + CFG.hotline);
    if (CFG.zalo) document.querySelectorAll('a[href*="zalo.me/"]').forEach(a => a.href = 'https://zalo.me/' + CFG.zalo);
    if (CFG.hotlineDisplay) document.querySelectorAll('.js-hotline').forEach(el => el.textContent = CFG.hotlineDisplay);
    if (CFG.price) document.querySelectorAll('.js-price').forEach(el => el.textContent = fmt(CFG.price));
    if (CFG.listPrice) document.querySelectorAll('.js-list-price').forEach(el => el.textContent = fmt(CFG.listPrice));
    document.getElementById('year').textContent = new Date().getFullYear();
    document.querySelectorAll('a[href^="tel:"], a[href*="zalo.me/"]').forEach(a => a.addEventListener('click', () => {
        window.bonbonTrack && bonbonTrack('Contact', { method: a.href.startsWith('tel:') ? 'phone' : 'zalo' });
    }));

    // ===== Ảnh / video từ cấu hình (trống thì hiện khung chờ) =====
    function placeholder(label, icon) {
        const d = document.createElement('div');
        d.className = 'placeholder';
        const i = document.createElement('span'); i.textContent = icon || '📷';
        d.append(i, document.createTextNode(label));
        return d;
    }
    function fillImage(box, src, label) {
        if (src) {
            const img = document.createElement('img');
            img.src = src; img.alt = label; img.loading = 'lazy';
            box.prepend(img);
        } else {
            box.prepend(placeholder(label));
        }
    }
    function youtubeId(url) {
        const m = url.match(/(?:youtu\.be\/|v=|shorts\/|embed\/)([\w-]{11})/);
        return m ? m[1] : null;
    }
    function fillVideo(box, url, label) {
        if (!url) { box.appendChild(placeholder(label, '🎬')); return; }
        const yt = youtubeId(url);
        if (yt) {
            const img = document.createElement('img');
            img.src = 'https://i.ytimg.com/vi/' + yt + '/hqdefault.jpg';
            img.alt = label; img.loading = 'lazy';
            const play = document.createElement('button');
            play.type = 'button'; play.className = 'video-play'; play.setAttribute('aria-label', 'Phát ' + label);
            play.innerHTML = '<i></i>';
            play.addEventListener('click', () => {
                const f = document.createElement('iframe');
                f.src = 'https://www.youtube.com/embed/' + yt + '?autoplay=1&playsinline=1&rel=0';
                f.allow = 'autoplay; encrypted-media; picture-in-picture'; f.allowFullscreen = true; f.title = label;
                box.replaceChildren(f);
            });
            box.append(img, play);
        } else {
            const v = document.createElement('video');
            v.src = url; v.controls = true; v.playsInline = true; v.preload = 'metadata';
            box.appendChild(v);
        }
    }
    const get = path => path.split('.').reduce((o, k) => (o == null ? undefined : o[k]), M);

    document.querySelectorAll('[data-media]').forEach(box => fillImage(box, get(box.dataset.media), box.dataset.label));
    document.querySelectorAll('[data-video]').forEach(box => fillVideo(box, get(box.dataset.video), box.dataset.label));
    document.querySelectorAll('[data-list]').forEach(wrap => {
        (get(wrap.dataset.list) || []).forEach((src, i) => {
            const fig = document.createElement('div');
            fig.className = 'media ' + wrap.dataset.ratio;
            fillImage(fig, src, wrap.dataset.label + ' ' + (i + 1));
            wrap.appendChild(fig);
        });
    });

    // ===== Slider vuốt ngang + chấm + tự chạy =====
    document.querySelectorAll('[data-slider]').forEach(wrap => {
        const items = get(wrap.dataset.slider) || [];
        const track = document.createElement('div'); track.className = 'slides';
        const dots = document.createElement('div'); dots.className = 'dots';
        const goTo = s => track.scrollTo({ left: s.offsetLeft - track.offsetLeft - (track.clientWidth - s.offsetWidth) / 2, behavior: 'smooth' });
        const isVideo = wrap.dataset.kind === 'video';
        items.forEach((raw, i) => {
            const it = typeof raw === 'string' ? { src: raw } : raw;
            const s = document.createElement('div');
            s.className = 'slide media ' + wrap.dataset.ratio;
            const label = wrap.dataset.label + (items.length > 1 ? ' ' + (i + 1) : '');
            if (isVideo) fillVideo(s, it.src, label);
            else fillImage(s, it.src, it.src ? (it.caption || label) : label);
            if (it.caption) { const c = document.createElement('span'); c.className = 'caption'; c.textContent = it.caption; s.appendChild(c); }
            track.appendChild(s);
            const d = document.createElement('button');
            d.type = 'button'; d.setAttribute('aria-label', 'Ảnh ' + (i + 1));
            d.addEventListener('click', () => goTo(s));
            dots.appendChild(d);
        });
        wrap.append(track, dots);
        let current = 0;
        const update = () => {
            const center = track.scrollLeft + track.clientWidth / 2;
            let dist = Infinity;
            [...track.children].forEach((s, i) => { const d = Math.abs(s.offsetLeft - track.offsetLeft + s.offsetWidth / 2 - center); if (d < dist) { dist = d; current = i; } });
            [...dots.children].forEach((d, i) => d.classList.toggle('on', i === current));
            // Ẩn chấm khi mọi ảnh đã hiển thị cùng lúc (PC, slider 3 ảnh)
            dots.style.visibility = track.scrollWidth <= track.clientWidth + 2 ? 'hidden' : '';
        };
        track.addEventListener('scroll', () => requestAnimationFrame(update), { passive: true });
        window.addEventListener('resize', update);
        update();
        if (isVideo) return; // video: không tự chuyển để khỏi cắt ngang khi mẹ đang xem
        const auto = setInterval(() => {
            if (track.scrollWidth <= track.clientWidth + 2) return;
            const sl = track.children; if (sl.length) goTo(sl[(current + 1) % sl.length]);
        }, 3500);
        ['touchstart', 'pointerdown', 'wheel'].forEach(ev => track.addEventListener(ev, () => clearInterval(auto), { once: true, passive: true }));
    });

    // ===== Hiện dần khi cuộn =====
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }), { rootMargin: '0px 0px -8% 0px' });
        document.querySelectorAll('.reveal').forEach(el => io.observe(el));
    } else {
        document.querySelectorAll('.reveal').forEach(el => el.classList.add('in'));
    }

    // ===== Nguồn quảng cáo (UTM) =====
    const attribution = (() => {
        let saved = {};
        try { saved = JSON.parse(sessionStorage.getItem('bonbon_attr') || '{}'); } catch (e) {}
        const q = new URLSearchParams(location.search);
        ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'ttclid'].forEach(k => { if (q.get(k)) saved[k] = q.get(k); });
        if (!saved.referrer && document.referrer) saved.referrer = document.referrer;
        try { sessionStorage.setItem('bonbon_attr', JSON.stringify(saved)); } catch (e) {}
        return saved;
    })();

    // ===== Nút MUA NGAY: cuộn tới form, đặt con trỏ vào ô họ tên =====
    let checkoutTracked = false;
    document.querySelectorAll('.js-buy').forEach(b => b.addEventListener('click', e => {
        e.preventDefault();
        document.getElementById('order').scrollIntoView({ behavior: 'smooth', block: 'start' });
        if (!checkoutTracked) { checkoutTracked = true; window.bonbonTrack && bonbonTrack('InitiateCheckout', { currency: 'VND', value: CFG.price }); }
        const name = document.getElementById('name');
        if (name && name.offsetParent) setTimeout(() => name.focus({ preventScroll: true }), 700);
    }));

    // ===== Đồng hồ ưu đãi (lưu theo từng khách, hết giờ tự chạy lại) =====
    (function countdown() {
        const DURATION = Math.round((CFG.offerMinutes || 10.5) * 60 * 1000);
        let end = parseInt(store.get('bonbon_offer_end'), 10);
        if (!end || end < Date.now()) { end = Date.now() + DURATION; store.set('bonbon_offer_end', end); }
        const mEl = document.getElementById('cdMin'), sEl = document.getElementById('cdSec');
        const tick = () => {
            let left = end - Date.now();
            if (left <= 0) { end = Date.now() + DURATION; store.set('bonbon_offer_end', end); left = DURATION; }
            mEl.textContent = String(Math.floor(left / 60000)).padStart(2, '0');
            sEl.textContent = String(Math.floor((left % 60000) / 1000)).padStart(2, '0');
        };
        tick(); setInterval(tick, 1000);
    })();

    // ===== Các gói "Chọn mua" =====
    const packs = CFG.packages && CFG.packages.length ? CFG.packages : [{ qty: 1, price: CFG.price, listPrice: CFG.listPrice }];
    const packsEl = document.getElementById('packs');
    packs.forEach((p, i) => {
        const label = document.createElement('label');
        label.className = 'pack' + (i === 0 ? ' on' : '');
        const r = document.createElement('input');
        r.type = 'radio'; r.name = 'pack'; r.value = i; r.checked = i === 0;
        const name = document.createElement('span'); name.className = 'pack-name'; name.textContent = p.qty + ' hũ';
        const price = document.createElement('span'); price.className = 'pack-price';
        const b = document.createElement('b'); b.textContent = fmt(p.price);
        const s = document.createElement('s'); s.textContent = 'Giá gốc ' + fmt(p.listPrice);
        price.append(b, s);
        label.append(r, name, price);
        packsEl.appendChild(label);
        r.addEventListener('change', () => packsEl.querySelectorAll('.pack').forEach(l => l.classList.toggle('on', l.querySelector('input').checked)));
    });

    // ===== Gửi đơn =====
    const normalizePhone = raw => {
        let p = raw.replace(/[\s.\-()]/g, '');
        if (p.startsWith('+84')) p = '0' + p.slice(3);
        else if (p.startsWith('84') && p.length === 11) p = '0' + p.slice(2);
        return p;
    };
    function setErr(id, bad) {
        const el = document.getElementById(id);
        el.classList.toggle('error', bad);
        el.setAttribute('aria-invalid', bad ? 'true' : 'false');
        document.getElementById(id + 'Err').classList.toggle('show', bad);
    }
    document.querySelectorAll('#orderForm .input').forEach(i => i.addEventListener('input', () => {
        i.classList.remove('error');
        const e = document.getElementById(i.id + 'Err'); if (e) e.classList.remove('show');
    }));
    document.getElementById('orderForm').addEventListener('focusin', () => {
        if (!checkoutTracked) { checkoutTracked = true; window.bonbonTrack && bonbonTrack('InitiateCheckout', { currency: 'VND', value: CFG.price }); }
    });

    function sendLead(data) {
        let backup = [];
        try { backup = JSON.parse(store.get('bonbon_orders') || '[]'); } catch (e) {}
        backup.push(data); store.set('bonbon_orders', JSON.stringify(backup.slice(-20)));
        if (!CFG.sheetEndpoint) { console.warn('[Bon Bon] Chưa cấu hình sheetEndpoint — đơn chỉ lưu tạm trên trình duyệt:', data); return Promise.resolve(); }
        const ctrl = 'AbortController' in window ? new AbortController() : null;
        const t = ctrl && setTimeout(() => ctrl.abort(), 15000);
        // Google Apps Script không trả CORS header => no-cors
        return fetch(CFG.sheetEndpoint, { method: 'POST', mode: 'no-cors', body: new URLSearchParams(data), signal: ctrl ? ctrl.signal : undefined })
            .finally(() => t && clearTimeout(t));
    }

    const form = document.getElementById('orderForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnHTML = submitBtn.innerHTML;
    const alertBox = document.getElementById('formAlert');
    let sending = false;
    form.addEventListener('submit', e => {
        e.preventDefault();
        if (sending) return;
        alertBox.classList.remove('show');
        const name = document.getElementById('name').value.trim();
        const phone = normalizePhone(document.getElementById('phone').value);
        const bad = { name: name.length < 2, phone: !/^0[1-9]\d{8,9}$/.test(phone) };
        Object.keys(bad).forEach(k => setErr(k, bad[k]));
        const first = Object.keys(bad).find(k => bad[k]);
        if (first) { document.getElementById(first).focus(); return; }

        const pack = packs[parseInt(form.querySelector('input[name="pack"]:checked').value, 10)];
        if (document.getElementById('website').value) { showSuccess(name, phone, pack); return; }
        const data = Object.assign({
            time: new Date().toLocaleString('vi-VN', { timeZone: 'Asia/Ho_Chi_Minh' }),
            name, phone,
            product: CFG.productName + ' x' + pack.qty,
            product_code: 'kem-ham-5g-' + pack.qty,
            quantity: pack.qty,
            price: pack.price,
            page: location.href.split('?')[0],
            user_agent: navigator.userAgent
        }, attribution);

        sending = true; submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> ĐANG GỬI...';
        sendLead(data).then(() => {
            window.bonbonTrack && bonbonTrack('Lead', { content_name: CFG.productName, value: pack.price, currency: 'VND' });
            showSuccess(name, phone, pack);
        }).catch(() => {
            alertBox.textContent = 'Gửi chưa thành công do mạng chập chờn. Mẹ thử lại hoặc ';
            const a = document.createElement('a'); a.href = 'tel:' + (CFG.hotline || ''); a.textContent = 'gọi ' + (CFG.hotlineDisplay || 'hotline');
            alertBox.append(a, document.createTextNode(' để đặt nhanh nhé!'));
            alertBox.classList.add('show');
        }).finally(() => { sending = false; submitBtn.disabled = false; submitBtn.innerHTML = btnHTML; });
    });

    function showSuccess(name, phone, pack) {
        form.style.display = 'none';
        const sum = document.getElementById('summary'); sum.textContent = '';
        [['Người nhận', name], ['Số điện thoại', phone], ['Sản phẩm', CFG.productName + ' × ' + pack.qty], ['Tạm tính', fmt(pack.price)]].forEach(([k, v]) => {
            const row = document.createElement('div'); const b = document.createElement('b'); b.textContent = k + ': ';
            row.append(b, document.createTextNode(v)); sum.appendChild(row);
        });
        document.getElementById('successBox').classList.add('show');
        document.getElementById('order').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ===== Hiệu ứng thông báo đặt hàng =====
    const notif = CFG.orderNotifications || {};
    const people = (notif.enabled && notif.items) || [];
    const toast = document.getElementById('toast');
    const toastText = document.getElementById('toastText');
    let ti = Math.floor(Math.random() * people.length);
    function showToast() {
        const [n, c] = people[ti++ % people.length];
        const r = Math.random();
        const q = r < 0.6 ? 1 : r < 0.85 ? 2 : 3;
        const mins = 1 + Math.floor(Math.random() * 9);
        const b = document.createElement('b'); b.textContent = n + ' (' + c + ')';
        const sm = document.createElement('small'); sm.textContent = mins + ' phút trước';
        toastText.replaceChildren(b, document.createTextNode(' vừa đặt ' + q + ' hũ Kem Hăm Bon Bon'), sm);
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4500);
    }
    if (people.length) setTimeout(() => { showToast(); setInterval(showToast, 16000); }, 6000);
})();

// Contact Form 7 tracking and success box transition
document.addEventListener('wpcf7mailsent', function (event) {
    if (window.bonbonTrack) {
        window.bonbonTrack('Lead', {
            content_name: (window.BONBON_CONFIG && window.BONBON_CONFIG.productName) || 'Kem Hăm Cho Bé Bon Bon',
            value: (window.BONBON_CONFIG && window.BONBON_CONFIG.price) || 49000,
            currency: 'VND'
        });
    }

    const wpcf7Container = document.querySelector('.order-card .wpcf7');
    const successBox = document.getElementById('successBox');
    if (wpcf7Container && successBox) {
        wpcf7Container.style.display = 'none';
        successBox.classList.add('show');
        successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}, false);
