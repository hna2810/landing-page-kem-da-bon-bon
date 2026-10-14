<?php
/**
 * Template part: Regions & Seminars Section (3 Miền)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$seminar_title = lx_get_field('lx_seminar_title', 'option', 'BON BON <span class="hl">TRÊN KHẮP 3 MIỀN</span>');
$seminar_sub   = lx_get_field('lx_seminar_sub', 'option', 'Đồng hành cùng hàng ngàn mẹ bầu, mẹ bỉm tại các hội thảo tiền sản và bệnh viện Sản - Nhi uy tín');
?>
<!-- 7. 3 MIỀN -->
<section class="sec" id="seminar">
    <div class="container">
        <div class="sec-head reveal">
            <h2 class="sec-title"><?php echo $seminar_title; ?></h2>
            <p class="sec-sub"><?php echo $seminar_sub; ?></p>
        </div>
        <div class="slider reveal" data-slider="seminarSlides" data-ratio="ratio-43" data-label="Ảnh hội thảo Bon Bon"></div>
        <div style="max-width:380px; margin:24px auto 0"><a href="#order" class="btn js-buy">🛒 ĐẶT MUA NGAY</a></div>
    </div>
</section>
