<?php
/**
 * Template part: Product Info & CTA Card Section
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$spec_brand   = lx_get_field('lx_spec_brand', 'option', 'Home Care');
$spec_origin  = lx_get_field('lx_spec_origin', 'option', 'Việt Nam');
$spec_weight  = lx_get_field('lx_spec_weight', 'option', '5g');
$spec_license = lx_get_field('lx_spec_license', 'option', '25618/25/CBMP-HN');

$cta_title  = lx_get_field('lx_cta_title', 'option', 'Thêm Bon Bon vào túi đồ chăm sóc bé của mẹ');
$price      = lx_format_price(lx_get_field('lx_price', 'option', 49000));
$list_price = lx_format_price(lx_get_field('lx_list_price', 'option', 80000));
?>
<!-- 9. THÔNG TIN SẢN PHẨM + CTA -->
<section class="sec" id="info">
    <div class="container split">
        <div>
            <div class="sec-head left-pc reveal">
                <h2 class="sec-title">Thông tin <span class="hl">sản phẩm</span></h2>
            </div>
            <dl class="spec reveal">
                <div><dt>Thương hiệu</dt><dd><?php echo $spec_brand; ?></dd></div>
                <div><dt>Xuất xứ</dt><dd><?php echo $spec_origin; ?></dd></div>
                <div><dt>Khối lượng</dt><dd><?php echo $spec_weight; ?></dd></div>
                <div><dt>Số công bố</dt><dd><?php echo $spec_license; ?></dd></div>
            </dl>
        </div>
        <div class="cta-card reveal">
            <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-lion.webp" alt="" class="mascot" aria-hidden="true">
            <h2 class="sec-title"><?php echo $cta_title; ?></h2>
            <div class="price-box">
                <span class="price-tag">⚡ Flash sale</span>
                <span class="price-now js-price"><?php echo $price; ?></span>
                <span class="price-old">Niêm yết <s class="js-list-price"><?php echo $list_price; ?></s></span>
            </div>
            <a href="#order" class="btn pulse js-buy">FREESHIP TOÀN QUỐC - MUA NGAY!</a>
        </div>
    </div>
</section>
