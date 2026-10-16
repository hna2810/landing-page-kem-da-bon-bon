<?php
/**
 * Module Loader for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$lx_modules = array(
    'helpers.php',
    'cleanup.php',
    'assets.php',
    'acf.php',
    'cf7.php',
    'thankyou.php',
);

foreach ($lx_modules as $file) {
    $path = LX_THEME_DIR . '/inc/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}
