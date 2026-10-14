<?php
/**
 * Template part: Order Form Section (Ưu đãi tặng riêng cho mẹ / Contact Form 7)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$order_title       = lx_get_field('lx_order_title', 'option', 'Ưu đãi tặng riêng cho mẹ');
$price_val         = intval(lx_get_field('lx_price', 'option', 49000));
$list_price_val    = intval(lx_get_field('lx_list_price', 'option', 80000));
$price             = lx_format_price($price_val);
$list_price        = lx_format_price($list_price_val);
$zalo              = lx_get_field('lx_zalo', 'option', '0901234567');
$order_product_img = lx_get_image_url('lx_order_product_img', 'https://kemham.bonbonhomecare.vn/wp-content/uploads/2026/10/kem-da-bon-bon-plus.png');

$discount_percent = 0;
if ($list_price_val > $price_val && $list_price_val > 0) {
    $discount_percent = round((($list_price_val - $price_val) / $list_price_val) * 100);
}
?>
<!-- 10. FORM ƯU ĐÃI TẶNG RIÊNG CHO MẸ -->
<section class="order-sec" id="order">
    <div class="order-card">
        <div class="offer">
            <div class="offer-badge">🎁 ƯU ĐÃI ĐẶC QUYỀN HÔM NAY</div>
            <h2 class="offer-title"><?php echo $order_title; ?></h2>
            <div class="offer-price-wrap">
                <span class="offer-price-now js-price"><?php echo $price; ?></span>
                <s class="offer-price-old js-list-price"><?php echo $list_price; ?></s>
                <?php if ($discount_percent > 0): ?>
                    <span class="offer-discount-tag">-<?php echo $discount_percent; ?>%</span>
                <?php endif; ?>
            </div>

            <div class="countdown-wrap">
                <p class="countdown-label">⏰ Ưu đãi kết thúc sau:</p>
                <div class="countdown" role="timer" aria-live="off">
                    <div class="unit"><b id="cdHour">00</b><span>Giờ</span></div>
                    <span class="colon">:</span>
                    <div class="unit"><b id="cdMin">10</b><span>Phút</span></div>
                    <span class="colon">:</span>
                    <div class="unit"><b id="cdSec">30</b><span>Giây</span></div>
                </div>
            </div>

            <div class="offer-product-wrap">
                <img src="<?php echo esc_url($order_product_img); ?>" alt="<?php esc_attr_e('Kem Hăm Cho Bé Bon Bon', 'lx-landing'); ?>" class="offer-product-img" loading="lazy">
                <div class="offer-trust-badges">
                    <span class="badge-item"><i class="badge-icon">✓</i> 100% thảo dược thiên nhiên, dịu nhẹ cho da bé</span>
                    <span class="badge-item"><i class="badge-icon">✓</i> Miễn phí giao hàng toàn quốc từ 2 hũ</span>
                    <span class="badge-item"><i class="badge-icon">✓</i> Được kiểm tra hàng trước khi thanh toán</span>
                </div>
            </div>
        </div>

        <div class="order-form-container">
            <?php echo lx_render_order_form(); ?>

            <div class="success" id="successBox" role="status" aria-live="polite">
                <img src="<?php echo esc_url($order_product_img); ?>" alt="<?php esc_attr_e('Kem Hăm Bon Bon', 'lx-landing'); ?>" class="success-product-img">
                <h3>Đặt hàng thành công!</h3>
                <p>Cảm ơn mẹ đã tin tưởng Bon Bon. Chuyên viên chăm sóc sẽ gọi điện xác nhận đơn cho mẹ ngay nhé!</p>
                <div class="summary" id="summary"></div>
                <a href="https://zalo.me/<?php echo esc_attr($zalo); ?>" target="_blank" rel="noopener" class="btn" style="background:#0068ff">💬 Nhắn Zalo cho Bon Bon</a>
            </div>
        </div>
    </div>
</section>
