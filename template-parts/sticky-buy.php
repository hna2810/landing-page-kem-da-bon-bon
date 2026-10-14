<?php
/**
 * Template part: Sticky Buy Bar & Toast Notification
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$hotline = lx_get_field('lx_hotline', 'option', '0901234567');
$zalo    = lx_get_field('lx_zalo', 'option', '0901234567');
$price   = lx_format_price(lx_get_field('lx_price', 'option', 49000));
?>
<!-- THANH MUA DÍNH DƯỚI (mobile) / NÚT NỔI (PC) -->
<div class="sticky-buy">
    <a href="tel:<?php echo $hotline; ?>" class="sticky-call" aria-label="Gọi hotline">📞</a>
    <a href="https://zalo.me/<?php echo $zalo; ?>" target="_blank" rel="noopener" class="sticky-zalo" aria-label="Chat Zalo">Zalo</a>
    <a href="#order" class="btn pulse js-buy"><span>MUA NGAY <small class="js-price"><?php echo $price; ?></small></span></a>
</div>

<!-- THÔNG BÁO ĐẶT HÀNG -->
<div class="toast" id="toast" role="status" aria-live="polite">
    <div class="toast-icon">🛍️</div>
    <div class="toast-text" id="toastText"></div>
</div>
