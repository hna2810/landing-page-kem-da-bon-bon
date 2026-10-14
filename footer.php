<?php
/**
 * Footer template for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$default_logo = 'https://kemham.bonbonhomecare.vn/wp-content/uploads/2026/10/Logo-BonBon-scaled.png';
$footer_logo  = lx_get_image_url('lx_footer_logo', '', 'full', 'option');
if (empty($footer_logo)) {
    $footer_logo = lx_get_image_url('lx_logo_image', $default_logo, 'full', 'option');
}
if (empty($footer_logo)) {
    $footer_logo = $default_logo;
}

$footer_title   = lx_get_field('lx_footer_title', 'option', 'Bon Bon - Nâng Niu Làn Da Bé');
$footer_company = lx_get_field('lx_footer_company', 'option', 'CÔNG TY TNHH CHĂM SÓC MẸ VÀ BÉ TẠI NHÀ HOME CARE');
$footer_address = lx_get_field('lx_footer_address', 'option', 'Địa chỉ: 20 Huy Du, Phường Từ Liêm, TP Hà Nội, Việt Nam.');
$footer_tax     = lx_get_field('lx_footer_tax', 'option', 'Mã số doanh nghiệp: 0107375668 do Sở Kế hoạch & Đầu tư TP Hà Nội cấp lần đầu ngày 29/03/2016');
?>
    </main>

    <footer class="footer">
        <div class="container">
            <?php if (!empty($footer_logo)) : ?>
                <div class="footer-logo-wrap">
                    <img src="<?php echo esc_url($footer_logo); ?>" alt="Bon Bon" class="footer-logo" loading="lazy">
                </div>
            <?php endif; ?>

            <h4 class="footer-title"><?php echo esc_html($footer_title); ?></h4>

            <p class="footer-hotline">
                Hotline: <a href="tel:0973871376">0973.871.376</a> - <a href="tel:0962131515">0962.131.515</a>
            </p>

            <p class="footer-company"><?php echo esc_html($footer_company); ?></p>
            <p class="footer-address"><?php echo esc_html($footer_address); ?></p>
            <p class="footer-tax"><?php echo esc_html($footer_tax); ?></p>

            <div class="footer-copy">
                &copy; <span id="year"><?php echo date('Y'); ?></span> Home Care. All rights reserved.
            </div>
        </div>
    </footer>
</div>

<?php
// Sticky Bar & Order Toast
get_template_part('template-parts/sticky-buy');

wp_footer();
?>
</body>
</html>
