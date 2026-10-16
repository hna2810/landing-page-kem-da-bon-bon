<?php
/**
 * Header template for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$logo_img        = lx_get_image_url('lx_logo_image', '', 'full', 'option');
if (empty($logo_img) && has_custom_logo()) {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_img = wp_get_attachment_image_url($custom_logo_id, 'full');
}
$logo_height     = intval(lx_get_field('lx_logo_height', 'option', 46));
if ($logo_height < 20 || $logo_height > 100) {
    $logo_height = 46;
}
$show_brand_text = lx_get_field('lx_show_brand_name_with_logo', 'option', 0);
$brand_name      = lx_get_field('lx_brand_name', 'option', 'Bon Bon');
$hotline         = lx_get_field('lx_hotline', 'option', '0901234567');
$hotline_display = lx_get_field('lx_hotline_display', 'option', '0901.234.567');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class('lx_landing_theme'); ?>>
<?php wp_body_open(); ?>

<div class="page">

    <!-- HEADER -->
    <header class="topbar">
        <div class="topbar-inner">
            <a href="<?php echo (function_exists('lx_is_thankyou_page') && lx_is_thankyou_page()) ? home_url('/') : '#top'; ?>" class="brand">
                <?php if ($logo_img) : ?>
                    <img src="<?php echo esc_url($logo_img); ?>" alt="<?php echo esc_attr($brand_name); ?>" class="brand-logo-img" style="height: <?php echo $logo_height; ?>px;">
                    <?php if (!empty($show_brand_text)) : ?>
                        <span class="brand-text"><?php echo esc_html($brand_name); ?></span>
                    <?php endif; ?>
                <?php else : ?>
                    <span class="brand-dot">B</span> <span class="brand-text"><?php echo esc_html($brand_name); ?></span>
                <?php endif; ?>
            </a>
            <nav class="topnav" id="topnav" aria-label="Điều hướng">
                <a href="#ingredients">Thành phần</a>
                <a href="#howto">Cách dùng</a>
                <a href="#reviews">Đánh giá</a>
                <a href="#faq">Hỏi đáp</a>
                <a href="#order" class="mobile-menu-cta pulse js-buy">FREESHIP TOÀN QUỐC - MUA NGAY!</a>
            </nav>
            <div class="topbar-actions">
                <a href="tel:<?php echo $hotline; ?>" class="topbar-call">📞 <span class="js-hotline"><?php echo $hotline_display; ?></span></a>
                <a href="#order" class="btn topbar-buy pulse js-buy">Mua ngay</a>
                <button type="button" class="menu-toggle" id="menuToggle" aria-label="Mở menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
        <div class="mobile-menu-overlay" id="menuOverlay"></div>
    </header>

    <main id="top">
