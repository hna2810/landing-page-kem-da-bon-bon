<?php
/**
 * Header template for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

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
            <a href="#top" class="brand"><span class="brand-dot">B</span> <?php echo $brand_name; ?></a>
            <nav class="topnav" aria-label="Điều hướng">
                <a href="#ingredients">Thành phần</a>
                <a href="#howto">Cách dùng</a>
                <a href="#reviews">Đánh giá</a>
                <a href="#faq">Hỏi đáp</a>
            </nav>
            <div class="topbar-actions">
                <a href="tel:<?php echo $hotline; ?>" class="topbar-call">📞 <span class="js-hotline"><?php echo $hotline_display; ?></span></a>
                <a href="#order" class="btn topbar-buy js-buy">Mua ngay</a>
            </div>
        </div>
    </header>

    <main id="top">
