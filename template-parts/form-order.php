<?php
/**
 * Template part: Order Form Section (Ưu đãi tặng riêng cho mẹ / Contact Form 7)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$order_title = lx_get_field('lx_order_title', 'option', 'Ưu đãi tặng riêng cho mẹ');
$price       = lx_format_price(lx_get_field('lx_price', 'option', 49000));
$list_price  = lx_format_price(lx_get_field('lx_list_price', 'option', 80000));
$zalo        = lx_get_field('lx_zalo', 'option', '0901234567');
?>
<!-- 10. FORM ƯU ĐÃI TẶNG RIÊNG CHO MẸ -->
<section class="order-sec" id="order">
    <div class="order-card">
        <div class="offer">
            <h2 class="offer-title"><?php echo $order_title; ?></h2>
            <p class="offer-price">Chỉ còn <b class="js-price"><?php echo $price; ?></b> <s>Giá gốc <span class="js-list-price"><?php echo $list_price; ?></span></s></p>
            <div class="countdown-wrap">
                <p class="countdown-label">⏰ Chỉ còn</p>
                <div class="countdown" role="timer" aria-live="off">
                    <div class="unit"><b id="cdMin">10</b><span>Phút</span></div>
                    <span class="colon">:</span>
                    <div class="unit"><b id="cdSec">30</b><span>Giây</span></div>
                </div>
            </div>
            <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-kitten.webp" alt="" class="offer-mascot" aria-hidden="true">
        </div>

        <div>
            <?php echo lx_render_order_form(); ?>

            <div class="success" id="successBox" role="status" aria-live="polite">
                <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-kitten.webp" alt="">
                <h3>Đặt hàng thành công!</h3>
                <p>Cảm ơn mẹ đã đặt hàng Bon Bon.</p>
                <div class="summary" id="summary"></div>
                <a href="https://zalo.me/<?php echo $zalo; ?>" target="_blank" rel="noopener" class="btn" style="background:#0068ff">💬 Nhắn Zalo cho Bon Bon</a>
            </div>
        </div>
    </div>
</section>
