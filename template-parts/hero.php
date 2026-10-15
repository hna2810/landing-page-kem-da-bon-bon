<?php
/**
 * Template part: Hero Section
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$hero_title   = lx_get_field('lx_hero_title', 'option', 'Da bé <span class="hl">ửng đỏ</span> vì hăm');
$hero_q       = lx_get_field('lx_hero_q', 'option', 'Mẹ đã tìm được cách chăm sóc phù hợp?');
$product_name = lx_get_field('lx_hero_product_name', 'option', 'Kem Hăm Cho Bé Bon Bon');
$item_1       = lx_get_field('lx_hero_item_1', 'option', 'Chuyên gia tin dùng');
$item_2       = lx_get_field('lx_hero_item_2', 'option', 'Giảm hăm tã, hăm da hiệu quả');
$no_list_str  = lx_get_field('lx_hero_no_list', 'option', 'Không Corticoid, Không Paraben, Không hương liệu');
$no_items     = array_map('trim', explode(',', $no_list_str));

$price       = lx_format_price(lx_get_field('lx_price', 'option', 49000));
$list_price  = lx_format_price(lx_get_field('lx_list_price', 'option', 80000));
$hero_img    = lx_get_image_url('lx_hero_image', '', 'full', 'option');
$show_mascot = lx_get_field('lx_hero_show_mascot', 'option', 0);
?>
<!-- 1. HERO: bên trái text, bên phải ảnh trước - sau -->
<section class="hero">
    <div class="container">
        <div class="hero-text-col">
            <?php if (!empty($show_mascot)) : ?>
                <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-bear.webp" alt="" class="hero-mascot hero-mascot-m" aria-hidden="true">
            <?php endif; ?>
            <h1 class="hero-title"><?php echo $hero_title; ?></h1>
            <p class="hero-q"><?php echo $hero_q; ?></p>
            <div class="hero-grid">
                <div>
                    <p class="hero-product"><?php echo $product_name; ?></p>
                    <ul class="check-list">
                        <li><?php echo $item_1; ?></li>
                        <li><?php echo $item_2; ?></li>
                        <li>
                            <div class="no-list">
                                <?php foreach ($no_items as $no) : if ($no) : ?>
                                    <span><?php echo $no; ?></span>
                                <?php endif; endforeach; ?>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="ba ba-mobile">
                    <div class="media hero-img ratio-1" data-media="heroImage" data-label="Ảnh trước - sau">
                        <?php if ($hero_img) : ?>
                            <img src="<?php echo $hero_img; ?>" alt="Ảnh trước - sau" loading="lazy">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="price-box">
                <span class="price-tag">⚡ Flash sale</span>
                <span class="price-now js-price"><?php echo $price; ?></span>
                <span class="price-old">Niêm yết <s class="js-list-price"><?php echo $list_price; ?></s></span>
            </div>
            <a href="#order" class="btn pulse js-buy">FREESHIP TOÀN QUỐC - MUA NGAY!</a>
        </div>
        <div class="hero-visual">
            <div class="ba">
                <div class="media hero-img ratio-1" data-media="heroImage" data-label="Ảnh trước - sau">
                    <?php if ($hero_img) : ?>
                        <img src="<?php echo $hero_img; ?>" alt="Ảnh trước - sau" loading="lazy">
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($show_mascot)) : ?>
                <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-bear.webp" alt="" class="hero-mascot" aria-hidden="true">
            <?php endif; ?>
        </div>
    </div>
</section>
