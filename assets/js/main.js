/**
 * Main JavaScript for Landing Bon Bon
 *
 * @package LXLanding
 */
document.documentElement.classList.add('js');

    (function(cfg) {
        if (cfg.facebookPixelId && !window.fbq) {
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', cfg.facebookPixelId);
            fbq('track', 'PageView');
        }
        if (cfg.facebookPixelId && window.fbq) {
            fbq('track', 'ViewContent', { content_name: cfg.productName, value: cfg.price, currency: 'VND' });
        }
        if (cfg.googleAnalyticsId && !window.gtag) {
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
    const fmt = n => {
        const num = parseInt(n, 10);
        if (isNaN(num)) return n;
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + 'đ';
    };
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
    function fillVideo(box, item, label) {
        const url = (typeof item === 'object' && item !== null) ? (item.src || '') : (item || '');
        const customThumb = (typeof item === 'object' && item !== null) ? (item.thumb || box.dataset.thumb || '') : (box.dataset.thumb || '');
        if (!url && !customThumb) { box.appendChild(placeholder(label, '🎬')); return; }
        const yt = url ? youtubeId(url) : null;
        if (yt) {
            const img = document.createElement('img');
            img.src = customThumb || ('https://i.ytimg.com/vi/' + yt + '/hqdefault.jpg');
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
        } else if (url) {
            if (customThumb) {
                const img = document.createElement('img');
                img.src = customThumb;
                img.alt = label;
                img.loading = 'lazy';
                const play = document.createElement('button');
                play.type = 'button';
                play.className = 'video-play';
                play.setAttribute('aria-label', 'Phát ' + label);
                play.innerHTML = '<i></i>';
                play.addEventListener('click', () => {
                    const v = document.createElement('video');
                    v.src = url;
                    v.controls = true;
                    v.playsInline = true;
                    v.autoplay = true;
                    v.poster = customThumb;
                    box.replaceChildren(v);
                    v.play();
                });
                box.append(img, play);
            } else {
                const v = document.createElement('video');
                v.src = url; v.controls = true; v.playsInline = true; v.preload = 'metadata';
                box.appendChild(v);
            }
        } else if (customThumb) {
            const img = document.createElement('img');
            img.src = customThumb;
            img.alt = label;
            img.loading = 'lazy';
            const play = document.createElement('button');
            play.type = 'button';
            play.className = 'video-play';
            play.setAttribute('aria-label', label);
            play.innerHTML = '<i></i>';
            box.append(img, play);
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
        const isVideo = wrap.dataset.kind === 'video';
        const isFeedback15 = wrap.closest('#feedback') && !isVideo;
        const goTo = s => {
            const leftPos = isFeedback15 
                ? (s.offsetLeft - track.offsetLeft) 
                : (s.offsetLeft - track.offsetLeft - (track.clientWidth - s.offsetWidth) / 2);
            track.scrollTo({ left: leftPos, behavior: 'smooth' });
        };

        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'slider-arrow prev';
        prevBtn.setAttribute('aria-label', 'Ảnh trước');
        prevBtn.innerHTML = '&#8249;';

        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'slider-arrow next';
        nextBtn.setAttribute('aria-label', 'Ảnh tiếp');
        nextBtn.innerHTML = '&#8250;';

        items.forEach((raw, i) => {
            const it = typeof raw === 'string' ? { src: raw } : raw;
            const s = document.createElement('div');
            s.className = 'slide media ' + wrap.dataset.ratio;
            const label = wrap.dataset.label + (items.length > 1 ? ' ' + (i + 1) : '');
            if (isVideo) fillVideo(s, it, label);
            else fillImage(s, it.src, it.src ? (it.caption || label) : label);
            if (it.caption) { const c = document.createElement('span'); c.className = 'caption'; c.textContent = it.caption; s.appendChild(c); }
            track.appendChild(s);
            const d = document.createElement('button');
            d.type = 'button'; d.setAttribute('aria-label', 'Ảnh ' + (i + 1));
            d.addEventListener('click', () => goTo(s));
            dots.appendChild(d);
        });

        prevBtn.addEventListener('click', () => {
            const sl = track.children;
            if (sl.length) {
                const targetIdx = Math.max(0, current - 1);
                goTo(sl[targetIdx]);
            }
        });
        nextBtn.addEventListener('click', () => {
            const sl = track.children;
            if (sl.length) {
                const targetIdx = Math.min(sl.length - 1, current + 1);
                goTo(sl[targetIdx]);
            }
        });

        wrap.append(prevBtn, track, nextBtn, dots);
        let current = 0;
        const update = () => {
            const checkPoint = isFeedback15 
                ? (track.scrollLeft + 20) 
                : (track.scrollLeft + track.clientWidth / 2);
            let dist = Infinity;
            [...track.children].forEach((s, i) => {
                if (!s.classList.contains('slide')) return;
                const sPoint = isFeedback15 
                    ? (s.offsetLeft - track.offsetLeft) 
                    : (s.offsetLeft - track.offsetLeft + s.offsetWidth / 2);
                const d = Math.abs(sPoint - checkPoint);
                if (d < dist) { dist = d; current = i; }
            });
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
        const hEl = document.getElementById('cdHour');
        const mEl = document.getElementById('cdMin');
        const sEl = document.getElementById('cdSec');
        const tick = () => {
            let left = end - Date.now();
            if (left <= 0) { end = Date.now() + DURATION; store.set('bonbon_offer_end', end); left = DURATION; }
            const hours = Math.floor(left / 3600000);
            const mins = Math.floor((left % 3600000) / 60000);
            const secs = Math.floor((left % 60000) / 1000);
            if (hEl) hEl.textContent = String(hours).padStart(2, '0');
            if (mEl) mEl.textContent = String(mins).padStart(2, '0');
            if (sEl) sEl.textContent = String(secs).padStart(2, '0');
        };
        tick(); setInterval(tick, 1000);
    })();

    // ===== Các gói "Chọn mua" =====
    const packs = CFG.packages && CFG.packages.length ? CFG.packages : [
        { name: 'Hũ nhỏ', qty: 1, price: 49000, listPrice: 80000, label: 'Hũ nhỏ 49.000đ (giá gốc 80.000đ) + FREESHIP' },
        { name: 'Hũ to', qty: 1, price: 140000, listPrice: 140000, label: 'Hũ to 140.000đ + FREESHIP' },
        { name: 'Combo 299K BIGSIZE', qty: 1, price: 299000, listPrice: 455000, label: 'Combo 299K BIGSIZE tắm gội + Kem bôi (giá gốc 455.000đ)' }
    ];
    const packsEl = document.getElementById('packs');
    if (packsEl) {
        packs.forEach((p, i) => {
            const label = document.createElement('label');
            label.className = 'pack' + (i === 0 ? ' on' : '');
            const r = document.createElement('input');
            r.type = 'radio'; r.name = 'pack'; r.value = i; r.checked = i === 0;
            const name = document.createElement('span'); name.className = 'pack-name';
            name.textContent = p.name || (p.qty + ' hũ');
            const price = document.createElement('span'); price.className = 'pack-price';
            const b = document.createElement('b'); b.textContent = fmt(p.price);
            price.appendChild(b);
            if (p.listPrice && p.listPrice > p.price) {
                const s = document.createElement('s'); s.textContent = 'Giá gốc ' + fmt(p.listPrice);
                price.appendChild(s);
            }
            if (p.label && p.label.includes('FREESHIP')) {
                const freeTag = document.createElement('small');
                freeTag.style.color = '#00a86b';
                freeTag.style.fontWeight = '700';
                freeTag.textContent = ' + FREESHIP';
                price.appendChild(freeTag);
            }
            label.append(r, name, price);
            packsEl.appendChild(label);
            r.addEventListener('change', () => packsEl.querySelectorAll('.pack').forEach(l => l.classList.toggle('on', l.querySelector('input').checked)));
        });
    }

    // ===== Gửi đơn =====
    const normalizePhone = raw => {
        let p = raw.replace(/[\s.\-()]/g, '');
        if (p.startsWith('+84')) p = '0' + p.slice(3);
        else if (p.startsWith('84') && p.length === 11) p = '0' + p.slice(2);
        return p;
    };
    function setErr(id, bad) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('error', bad);
        el.setAttribute('aria-invalid', bad ? 'true' : 'false');
        const err = document.getElementById(id + 'Err');
        if (err) err.classList.toggle('show', bad);
    }
    document.querySelectorAll('#orderForm .input').forEach(i => i.addEventListener('input', () => {
        i.classList.remove('error');
        const e = document.getElementById(i.id + 'Err'); if (e) e.classList.remove('show');
    }));
    const orderFormEl = document.getElementById('orderForm');
    if (orderFormEl) {
        orderFormEl.addEventListener('focusin', () => {
            if (!checkoutTracked) { checkoutTracked = true; window.bonbonTrack && bonbonTrack('InitiateCheckout', { currency: 'VND', value: CFG.price }); }
        });
    }

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
    if (form) {
        const submitBtn = document.getElementById('submitBtn');
        const btnHTML = submitBtn ? submitBtn.innerHTML : '';
        const alertBox = document.getElementById('formAlert');
        let sending = false;
        form.addEventListener('submit', e => {
            e.preventDefault();
            if (sending) return;
            if (alertBox) alertBox.classList.remove('show');
            const nameEl = document.getElementById('name');
            const phoneEl = document.getElementById('phone');
            const name = nameEl ? nameEl.value.trim() : '';
            const phone = phoneEl ? normalizePhone(phoneEl.value) : '';
            const bad = { name: name.length < 2, phone: !/^0[1-9]\d{8,9}$/.test(phone) };
            Object.keys(bad).forEach(k => setErr(k, bad[k]));
            const first = Object.keys(bad).find(k => bad[k]);
            if (first) { const fEl = document.getElementById(first); if (fEl) fEl.focus(); return; }

            const checkedPack = form.querySelector('input[name="pack"]:checked');
            const pack = checkedPack ? packs[parseInt(checkedPack.value, 10)] : packs[0];
            const websiteEl = document.getElementById('website');
            if (websiteEl && websiteEl.value) { showSuccess(name, phone, pack); return; }
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

            sending = true; 
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> ĐANG GỬI...';
            }
            sendLead(data).then(() => {
                window.bonbonTrack && bonbonTrack('Lead', { content_name: CFG.productName, value: pack.price, currency: 'VND' });
                try {
                    sessionStorage.setItem('bonbon_last_order', JSON.stringify({
                        name: name,
                        phone: phone,
                        package: pack.label || (CFG.productName + ' x' + pack.qty),
                        value: pack.price,
                        time: Date.now()
                    }));
                } catch (err) {}
                const thankyouUrl = CFG.thankyouUrl || '/cam-on/';
                const params = new URLSearchParams({
                    name: name,
                    phone: phone,
                    package: pack.label || (CFG.productName + ' x' + pack.qty),
                    value: pack.price
                });
                const dest = thankyouUrl + (thankyouUrl.includes('?') ? '&' : '?') + params.toString();
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'ĐANG CHUYỂN HƯỚNG...';
                }
                setTimeout(function () {
                    window.location.href = dest;
                }, 350);
            }).catch(() => {
                if (alertBox) {
                    alertBox.textContent = 'Gửi chưa thành công do mạng chập chờn. Mẹ thử lại hoặc ';
                    const a = document.createElement('a'); a.href = 'tel:' + (CFG.hotline || ''); a.textContent = 'gọi ' + (CFG.hotlineDisplay || 'hotline');
                    alertBox.append(a, document.createTextNode(' để đặt nhanh nhé!'));
                    alertBox.classList.add('show');
                }
            }).finally(() => { 
                sending = false; 
                if (submitBtn) {
                    submitBtn.disabled = false; 
                    submitBtn.innerHTML = btnHTML; 
                }
            });
        });

        function showSuccess(name, phone, pack) {
            form.style.display = 'none';
            const sum = document.getElementById('summary'); 
            if (sum) {
                sum.textContent = '';
                [['Người nhận', name], ['Số điện thoại', phone], ['Sản phẩm', CFG.productName + ' × ' + pack.qty], ['Tạm tính', fmt(pack.price)]].forEach(([k, v]) => {
                    const row = document.createElement('div'); const b = document.createElement('b'); b.textContent = k + ': ';
                    row.append(b, document.createTextNode(v)); sum.appendChild(row);
                });
            }
            const successBox = document.getElementById('successBox');
            if (successBox) successBox.classList.add('show');
            const orderEl = document.getElementById('order');
            if (orderEl) orderEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // ===== Thông báo đặt hàng: Đã tắt theo yêu cầu =====
})();

// Contact Form 7 validation error translation to Vietnamese
document.addEventListener('wpcf7invalid', function () {
    setTimeout(function () {
        document.querySelectorAll('.wpcf7-not-valid-tip').forEach(function (el) {
            const text = el.textContent.trim();
            if (text.includes('Undefined value') || text.includes('submitted through this field')) {
                el.textContent = 'Mẹ vui lòng chọn gói sản phẩm phù hợp nhé!';
            } else if (text.includes('Please fill in') || text.includes('required field')) {
                el.textContent = 'Mẹ vui lòng nhập thông tin vào trường này nhé!';
            }
        });
        const formResponse = document.querySelector('.wpcf7-response-output');
        if (formResponse) {
            const rText = formResponse.textContent.trim();
            if (rText.includes('validation errors') || rText.includes('check and try again')) {
                formResponse.textContent = '⚠️ Mẹ vui lòng kiểm tra lại thông tin và điền đầy đủ các mục bắt buộc nhé!';
            }
        }
    }, 20);
});

// Contact Form 7 tracking and success box transition
document.addEventListener('wpcf7mailsent', function (event) {
    let selectedPackage = '';
    let clientName = '';
    let clientPhone = '';
    if (event.detail && event.detail.inputs) {
        event.detail.inputs.forEach(input => {
            if (input.name === 'package') selectedPackage = input.value;
            if (input.name === 'your-name' || input.name === 'name') clientName = input.value;
            if (input.name === 'your-phone' || input.name === 'phone') clientPhone = input.value;
        });
    }

    let leadValue = 49000;
    if (selectedPackage.includes('140.000')) {
        leadValue = 140000;
    } else if (selectedPackage.includes('299K') || selectedPackage.includes('299.000')) {
        leadValue = 299000;
    }

    if (window.bonbonTrack) {
        window.bonbonTrack('Lead', {
            content_name: selectedPackage || (window.BONBON_CONFIG && window.BONBON_CONFIG.productName) || 'Kem Hăm Cho Bé Bon Bon',
            value: leadValue,
            currency: 'VND'
        });
    }

    // Lưu thông tin đơn hàng vào sessionStorage
    try {
        sessionStorage.setItem('bonbon_last_order', JSON.stringify({
            name: clientName,
            phone: clientPhone,
            package: selectedPackage || (window.BONBON_CONFIG && window.BONBON_CONFIG.productName) || 'Kem Hăm Cho Bé Bon Bon',
            value: leadValue,
            time: Date.now()
        }));
    } catch (err) {}

    const thankyouUrl = (window.BONBON_CONFIG && window.BONBON_CONFIG.thankyouUrl) || '/cam-on/';
    const params = new URLSearchParams({
        name: clientName,
        phone: clientPhone,
        package: selectedPackage || (window.BONBON_CONFIG && window.BONBON_CONFIG.productName) || 'Kem Hăm Cho Bé Bon Bon',
        value: leadValue
    });
    const dest = thankyouUrl + (thankyouUrl.includes('?') ? '&' : '?') + params.toString();

    const submitBtn = document.querySelector('.order-card .wpcf7 [type="submit"], .order-card #submitBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'ĐANG CHUYỂN HƯỚNG...';
    }

    const wpcf7Container = document.querySelector('.order-card .wpcf7');
    const successBox = document.getElementById('successBox');
    if (wpcf7Container && successBox) {
        wpcf7Container.style.display = 'none';
        successBox.classList.add('show');
    }

    setTimeout(function () {
        window.location.href = dest;
    }, 350);
}, false);

// Ngăn bấm gửi nhiều lần liên tiếp khi submit Contact Form 7
document.addEventListener('wpcf7submit', function (event) {
    const btn = event.target ? event.target.querySelector('.wpcf7-submit, [type="submit"]') : null;
    if (btn) {
        btn.disabled = true;
        setTimeout(function () {
            if (btn) btn.disabled = false;
        }, 5000);
    }
}, false);

// ===== Bộ lọc Đánh giá (Review Filters) =====
(function initReviewsFilter() {
    const chips = document.querySelectorAll('.chips .chip, .chips span');
    const reviews = document.querySelectorAll('.reviews-list .review');
    const emptyBox = document.getElementById('reviewsEmpty');

    if (!chips.length || !reviews.length) return;

    chips.forEach(chip => {
        chip.addEventListener('click', function () {
            chips.forEach(c => {
                c.classList.remove('on');
                c.setAttribute('aria-selected', 'false');
            });
            this.classList.add('on');
            this.setAttribute('aria-selected', 'true');

            const filter = this.dataset.filter || 'all';
            let visibleCount = 0;

            reviews.forEach(review => {
                const stars = review.dataset.stars || '5';
                const hasComment = review.dataset.hasComment === '1';
                const hasMedia = review.dataset.hasMedia === '1';

                let match = false;
                if (filter === 'all') {
                    match = true;
                } else if (filter === '5' || filter === '4' || filter === '3' || filter === '2' || filter === '1') {
                    match = (stars === filter);
                } else if (filter === 'comment') {
                    match = hasComment;
                } else if (filter === 'media') {
                    match = hasMedia;
                }

                if (match) {
                    review.style.display = '';
                    visibleCount++;
                } else {
                    review.style.display = 'none';
                }
            });

            if (emptyBox) {
                emptyBox.style.display = (visibleCount === 0) ? 'block' : 'none';
            }
        });
    });
})();

// ===== Lightbox xem ảnh phóng to (Toàn bộ ảnh trên trang) =====
(function initReviewLightbox() {
    function setupLightbox() {
        let modal = document.getElementById('reviewLightbox');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'reviewLightbox';
            modal.className = 'review-lightbox';
            modal.innerHTML = '<div class="lightbox-backdrop"></div><div class="lightbox-content"><img src="" alt="Ảnh phóng to"><button type="button" class="lightbox-close" aria-label="Đóng">&times;</button></div>';
            document.body.appendChild(modal);
        }

        const modalImg = modal.querySelector('img');
        const closeBtn = modal.querySelector('.lightbox-close');
        const backdrop = modal.querySelector('.lightbox-backdrop');

        const closeModal = () => {
            modal.classList.remove('open');
            document.body.classList.remove('is-lightbox-open');
        };

        const openModal = (src) => {
            if (!src || !modalImg) return;
            modalImg.src = src;
            modal.classList.add('open');
            document.body.classList.add('is-lightbox-open');
        };

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target === backdrop) closeModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        document.addEventListener('click', function (e) {
            // Không can thiệp nếu bấm vào bên trong modal popup
            if (e.target.closest('#reviewLightbox')) return;

            // Bỏ qua nếu bấm vào nút điều khiển, mũi tên slider, nút phát video, menu toggle, input form
            if (e.target.closest('button, .video-play, .slider-arrow, .dots, .menu-toggle, input, textarea, select')) return;

            // Tìm thẻ img được click hoặc nằm trong container được click
            let img = null;
            if (e.target.tagName === 'IMG') {
                img = e.target;
            } else {
                const container = e.target.closest('.slide, .media, .trust-img, .review-img-item, .ba, .hero-img, [data-slider], [data-media], [data-zoom], [data-full], .howto-step, .story-img, .product-img-wrap, .offer-product-wrap');
                if (container) {
                    img = container.querySelector('img');
                }
            }

            if (!img) return;

            // Bỏ qua logo thương hiệu, linh vật mascot hoạt hình, avatar
            if (img.closest('.brand, .footer-logo-wrap, .footer-logo, .avatar') || 
                img.classList.contains('brand-logo-img') || 
                img.classList.contains('footer-logo') || 
                img.classList.contains('mascot') || 
                img.classList.contains('hero-mascot') || 
                img.classList.contains('story-mascot') || 
                img.classList.contains('hmascot') || 
                img.classList.contains('info-banner-art') ||
                (img.src && img.src.includes('mascot-')) ||
                (img.src && img.src.includes('placeholder'))) {
                return;
            }

            // Nếu ảnh nằm trong link, ngăn điều hướng để mở popup
            if (img.closest('a')) {
                e.preventDefault();
            }

            const parentSlide = img.closest('.slide, [data-full], .review-img-item');
            const src = (parentSlide && parentSlide.dataset.full) || img.dataset.full || img.currentSrc || img.getAttribute('src') || img.src;
            if (src) {
                openModal(src);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupLightbox);
    } else {
        setupLightbox();
    }
})();

// ===== Định dạng nhãn gói chọn mua của CF7 (tách giá gốc và gạch ngang) =====
(function () {
    function formatCf7Packages() {
        document.querySelectorAll('.order-card .wpcf7-list-item-label').forEach(el => {
            if (el.dataset.formatted) return;
            const txt = el.textContent.trim();
            // 1. Trường hợp: Tên gói (giá gốc...) + FREESHIP
            let m = txt.match(/^(.*?)\s*(\(giá gốc.*?\))\s*(\+\s*FREESHIP)$/i);
            if (m) {
                el.innerHTML = '<span class="pkg-title">' + m[1] + '</span><span class="pkg-right"><span class="pkg-orig">' + m[2] + '</span><span class="pkg-free">+ FREESHIP</span></span>';
                el.dataset.formatted = 'true';
                return;
            }
            // 2. Trường hợp: Tên gói + FREESHIP (không có giá gốc)
            m = txt.match(/^(.*?)\s*(\+\s*FREESHIP)$/i);
            if (m) {
                el.innerHTML = '<span class="pkg-title">' + m[1] + '</span><span class="pkg-right"><span class="pkg-free">+ FREESHIP</span></span>';
                el.dataset.formatted = 'true';
                return;
            }
            // 3. Trường hợp: Tên gói (giá gốc...)
            m = txt.match(/^(.*?)\s*(\(giá gốc.*?\))$/i);
            if (m) {
                el.innerHTML = '<span class="pkg-title">' + m[1] + '</span><span class="pkg-right"><span class="pkg-orig">' + m[2] + '</span></span>';
                el.dataset.formatted = 'true';
                return;
            }
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', formatCf7Packages);
    } else {
        formatCf7Packages();
    }
    document.addEventListener('wpcf7init', formatCf7Packages);
})();

// ===== Mobile Menu Toggle =====
(function () {
    function initMobileMenu() {
        const toggle = document.getElementById('menuToggle');
        const nav = document.getElementById('topnav');
        const overlay = document.getElementById('menuOverlay');
        if (!toggle || !nav) return;

        function closeMenu() {
            toggle.classList.remove('active');
            toggle.setAttribute('aria-expanded', 'false');
            nav.classList.remove('open');
            if (overlay) overlay.classList.remove('open');
            document.body.classList.remove('is-menu-open');
        }

        function openMenu() {
            toggle.classList.add('active');
            toggle.setAttribute('aria-expanded', 'true');
            nav.classList.add('open');
            if (overlay) overlay.classList.add('open');
            document.body.classList.add('is-menu-open');
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            if (nav.classList.contains('open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        if (overlay) {
            overlay.addEventListener('click', closeMenu);
        }

        nav.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', function () {
                closeMenu();
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('open')) {
                closeMenu();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileMenu);
    } else {
        initMobileMenu();
    }
})();

// ===== Typewriter effect cho phần CÂU CHUYỆN THƯƠNG HIỆU =====
(function () {
    function initStoryTypewriter() {
        const box = document.getElementById('storyTypewriterBox');
        if (!box) return;

        const bodyTarget = document.getElementById('storyTypewriterTarget');
        const highlightTarget = document.getElementById('storyTypewriterHighlight');
        if (!bodyTarget) return;

        const bodyTextSpan = bodyTarget.querySelector('.typewriter-text');
        const highlightTextSpan = highlightTarget ? highlightTarget.querySelector('.typewriter-highlight-text') : null;
        const cursor = box.querySelector('.typewriter-cursor');

        // Hỗ trợ cả data-body/data-highlight mới và fallback data-text cũ
        const bodyText = box.getAttribute('data-body') || box.getAttribute('data-text') || (bodyTextSpan ? bodyTextSpan.textContent.trim() : '');
        const highlightText = box.getAttribute('data-highlight') || (highlightTextSpan ? highlightTextSpan.textContent.trim() : '');

        if (!bodyText && !highlightText) return;

        // Xóa text ban đầu để chuẩn bị hiệu ứng gõ
        if (bodyTextSpan) bodyTextSpan.textContent = '';
        if (highlightTextSpan) highlightTextSpan.textContent = '';
        if (highlightTarget) highlightTarget.style.display = 'none';

        // Tạm thời gắn con trỏ vào sau body text khi đang gõ body
        if (cursor && bodyTarget) {
            bodyTarget.appendChild(cursor);
        }

        let hasStarted = false;

        function startTyping() {
            if (hasStarted) return;
            hasStarted = true;

            let bodyIndex = 0;
            let highlightIndex = 0;
            const speed = 25; // 25ms mỗi ký tự

            function typeBody() {
                if (bodyIndex < bodyText.length) {
                    if (bodyTextSpan) {
                        bodyTextSpan.textContent += bodyText.charAt(bodyIndex);
                    }
                    bodyIndex++;
                    const char = bodyText.charAt(bodyIndex - 1);
                    const delay = (char === '.' || char === ',' || char === '—') ? 70 : speed;
                    setTimeout(typeBody, delay);
                } else if (highlightText && highlightTextSpan) {
                    // Xong body, tạm dừng 300ms rồi hiện dòng highlight và chuyển con trỏ sang
                    setTimeout(() => {
                        if (highlightTarget) {
                            highlightTarget.style.display = 'block';
                        }
                        if (cursor && highlightTarget) {
                            highlightTarget.appendChild(cursor);
                        }
                        typeHighlight();
                    }, 300);
                } else {
                    finishTyping();
                }
            }

            function typeHighlight() {
                if (highlightIndex < highlightText.length) {
                    if (highlightTextSpan) {
                        highlightTextSpan.textContent += highlightText.charAt(highlightIndex);
                    }
                    highlightIndex++;
                    const char = highlightText.charAt(highlightIndex - 1);
                    const delay = (char === '-' || char === ',' || char === '.') ? 80 : speed;
                    setTimeout(typeHighlight, delay);
                } else {
                    finishTyping();
                }
            }

            function finishTyping() {
                setTimeout(() => {
                    if (cursor) cursor.classList.add('cursor-finished');
                }, 3000);
            }

            if (bodyText.length > 0) {
                typeBody();
            } else if (highlightText.length > 0) {
                if (highlightTarget) highlightTarget.style.display = 'block';
                if (cursor && highlightTarget) highlightTarget.appendChild(cursor);
                typeHighlight();
            }
        }

        // Kích hoạt khi cuộn đến khung
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        startTyping();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });
            observer.observe(box);
        } else {
            startTyping();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initStoryTypewriter);
    } else {
        initStoryTypewriter();
    }
})();

