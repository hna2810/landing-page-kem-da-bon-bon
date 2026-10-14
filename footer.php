<?php
/**
 * Footer template for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$hotline         = lx_get_field('lx_hotline', 'option', '0901234567');
$hotline_display = lx_get_field('lx_hotline_display', 'option', '0901.234.567');
?>
    </main>

    <footer class="footer">
        <b>Bon Bon - Home Care</b>
        Dịu dàng chăm bé, trọn vẹn yêu thương.<br>
        Hotline: <a href="tel:<?php echo $hotline; ?>" class="js-hotline"><?php echo $hotline_display; ?></a>
        <div style="margin-top:8px; opacity:.7">&copy; <span id="year"><?php echo date('Y'); ?></span> Home Care</div>
    </footer>
</div>

<?php
// Sticky Bar & Order Toast
get_template_part('template-parts/sticky-buy');

wp_footer();
?>
</body>
</html>
