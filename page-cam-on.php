<?php
/**
 * Template Name: Trang Cảm Ơn
 * Description: Trang cảm ơn đặt hàng thành công tích hợp Meta Pixel tracking
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$thankyou_title   = lx_get_field('lx_thankyou_title', 'option', 'Đặt Hàng Thành Công!');
if (empty($thankyou_title)) {
    $thankyou_title = 'Đặt Hàng Thành Công!';
}
$thankyou_message = lx_get_field('lx_thankyou_message', 'option', 'Cảm ơn mẹ đã tin tưởng Bon Bon! Đơn hàng của mẹ đã được chuyển thành công tới hệ thống chăm sóc da bé Bon Bon.');
if (empty($thankyou_message)) {
    $thankyou_message = 'Cảm ơn mẹ đã tin tưởng Bon Bon! Đơn hàng của mẹ đã được chuyển thành công tới hệ thống chăm sóc da bé Bon Bon.';
}
$hotline_display = lx_get_field('lx_hotline_display', 'option', '');
$hotline         = lx_get_field('lx_hotline', 'option', '');
if (empty($hotline_display) && !empty($hotline)) {
    $hotline_display = $hotline;
}
if (empty($hotline) && !empty($hotline_display)) {
    $hotline = preg_replace('/[^0-9]/', '', $hotline_display);
}
if (empty($hotline_display)) {
    $hotline_display = '0934602525';
}
if (empty($hotline)) {
    $hotline = '0934602525';
}
$zalo             = lx_get_field('lx_zalo', 'option', '0901234567');
$product_name     = lx_get_field('lx_product_name', 'option', 'Kem Hăm Cho Bé Bon Bon 5g');
?>

<section class="lx_thankyou_sec" id="thankyou">
    <div class="lx_thankyou_card">
        <!-- Icon dấu tích hoàn tất -->
        <div class="lx_thankyou_icon_wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <span class="lx_thankyou_badge">🎉 ĐẶT HÀNG THÀNH CÔNG</span>

        <h1 class="lx_thankyou_title" id="tyTitle"><?php echo htmlspecialchars(strip_tags($thankyou_title), ENT_QUOTES, 'UTF-8'); ?></h1>

        <p class="lx_thankyou_desc" id="tyDesc">
            <?php echo nl2br(htmlspecialchars(strip_tags($thankyou_message), ENT_QUOTES, 'UTF-8')); ?>
        </p>

        <!-- Khung tóm tắt đơn hàng -->
        <div class="lx_thankyou_order_box">
            <div class="lx_thankyou_order_header">
                <span>📋 THÔNG TIN ĐƠN HÀNG</span>
            </div>
            <div class="lx_thankyou_row">
                <span class="row_label">Khách hàng:</span>
                <span class="row_val" id="tyCustomerName">Quý khách</span>
            </div>
            <div class="lx_thankyou_row">
                <span class="row_label">Số điện thoại:</span>
                <span class="row_val" id="tyCustomerPhone">Đang cập nhật</span>
            </div>
            <div class="lx_thankyou_row">
                <span class="row_label">Sản phẩm:</span>
                <span class="row_val" id="tyPackageName" style="color:#d64d86"><?php echo htmlspecialchars(strip_tags($product_name), ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="lx_thankyou_row">
                <span class="row_label">Tạm tính:</span>
                <span class="row_val" id="tyOrderPrice" style="color:#1f7f90; font-size:16px">49.000đ</span>
            </div>
            <div class="lx_thankyou_row">
                <span class="row_label">Vận chuyển:</span>
                <span class="row_val" style="color:#16a34a">Miễn phí toàn quốc (FREESHIP)</span>
            </div>
            <div class="lx_thankyou_row">
                <span class="row_label">Thanh toán:</span>
                <span class="row_val">Kiểm tra hàng trước khi thanh toán (COD)</span>
            </div>
        </div>

        <!-- Các nút liên hệ hành động -->
        <div class="lx_thankyou_actions">
            <?php if (!empty($zalo)) : ?>
                <a href="https://zalo.me/<?php echo htmlspecialchars(strip_tags($zalo), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="lx_thankyou_btn_zalo">
                    💬 Nhắn Zalo cho Bon Bon
                </a>
            <?php endif; ?>

            <?php if (!empty($hotline_display)) : ?>
                <a href="tel:<?php echo htmlspecialchars(strip_tags($hotline), ENT_QUOTES, 'UTF-8'); ?>" class="lx_thankyou_btn_call">
                    📞 Hotline: <span class="js-hotline"><?php echo htmlspecialchars(strip_tags($hotline_display), ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            <?php endif; ?>

            <a href="<?php echo home_url('/'); ?>" class="lx_thankyou_btn_home">
                ← Về lại trang chủ
            </a>
        </div>
    </div>
</section>

<!-- Tracking Script cho Trang Cảm Ơn -->
<script>
(function() {
    var defaultProductName = <?php echo json_encode($product_name); ?> || 'Kem Hăm Cho Bé Bon Bon 5g';
    var orderValue = 49000;
    var packageName = defaultProductName;
    var customerName = '';
    var customerPhone = '';

    // 1. Đọc dữ liệu từ URL Query Parameters
    var params = new URLSearchParams(window.location.search);
    if (params.get('value')) {
        var parsedVal = parseInt(params.get('value'), 10);
        if (!isNaN(parsedVal) && parsedVal > 0) orderValue = parsedVal;
    }
    if (params.get('package')) packageName = params.get('package');
    if (params.get('name')) customerName = params.get('name');
    if (params.get('phone')) customerPhone = params.get('phone');

    // 2. Dự phòng đọc từ sessionStorage
    try {
        var stored = sessionStorage.getItem('bonbon_last_order');
        if (stored) {
            var data = JSON.parse(stored);
            if (!params.get('value') && data.value) orderValue = parseInt(data.value, 10) || orderValue;
            if (!params.get('package') && data.package) packageName = data.package;
            if (!customerName && data.name) customerName = data.name;
            if (!customerPhone && data.phone) customerPhone = data.phone;
        }
    } catch (e) {}

    // 3. Cập nhật giao diện chi tiết đơn hàng
    function formatPriceVND(n) {
        return (n || 0).toLocaleString('vi-VN') + 'đ';
    }

    if (customerName) {
        var elName = document.getElementById('tyCustomerName');
        if (elName) elName.textContent = customerName;
        var elTitle = document.getElementById('tyTitle');
        if (elTitle) elTitle.textContent = 'Cảm ơn ' + customerName + ' đã đặt hàng!';
    }
    if (customerPhone) {
        var elPhone = document.getElementById('tyCustomerPhone');
        if (elPhone) elPhone.textContent = customerPhone;
    }
    if (packageName) {
        var elPackage = document.getElementById('tyPackageName');
        if (elPackage) elPackage.textContent = packageName;
    }
    if (orderValue) {
        var elPrice = document.getElementById('tyOrderPrice');
        if (elPrice) elPrice.textContent = formatPriceVND(orderValue);
    }

    // 4. Kích hoạt Pixel FB và Google Analytics Tracking Chuyển Đổi (Purchase & Lead)
    var orderKey = 'bonbon_tracked_' + (customerPhone ? customerPhone.replace(/\D/g, '') : '') + '_' + orderValue;
    var isTracked = false;
    try {
        isTracked = sessionStorage.getItem(orderKey) === '1';
    } catch (e) {}

    if (!isTracked) {
        // Meta Pixel Tracking
        if (typeof fbq === 'function') {
            fbq('track', 'Purchase', {
                content_name: packageName,
                content_type: 'product',
                value: orderValue,
                currency: 'VND'
            });
            fbq('track', 'Lead', {
                content_name: packageName,
                value: orderValue,
                currency: 'VND'
            });
        }

        // Google Analytics Tracking
        if (typeof gtag === 'function') {
            gtag('event', 'purchase', {
                transaction_id: 'BONBON_' + Date.now(),
                value: orderValue,
                currency: 'VND',
                items: [{
                    item_name: packageName,
                    price: orderValue,
                    quantity: 1
                }]
            });
            gtag('event', 'generate_lead', {
                value: orderValue,
                currency: 'VND'
            });
        }

        // TikTok Pixel Tracking nếu có
        if (typeof ttq !== 'undefined' && ttq.track) {
            ttq.track('CompletePayment', {
                content_name: packageName,
                value: orderValue,
                currency: 'VND'
            });
        }

        try {
            sessionStorage.setItem(orderKey, '1');
        } catch (e) {}
    }
})();
</script>

<?php
get_footer();
