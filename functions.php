<?php
/**
 * Landing Bon Bon Theme functions and definitions
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

// ── Theme Constants ─────────────────────────────────────────
if (!defined('WPCF7_AUTOP')) {
    define('WPCF7_AUTOP', false);
}
if (!defined('LX_THEME_VERSION')) {
    define('LX_THEME_VERSION', '1.3.7');
}
if (!defined('LX_THEME_DIR')) {
    define('LX_THEME_DIR', get_template_directory());
}
if (!defined('LX_THEME_URI')) {
    define('LX_THEME_URI', get_template_directory_uri());
}

// ── Theme Setup ─────────────────────────────────────────────
function lx_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
}
add_action('after_setup_theme', 'lx_theme_setup');

// ── Load Modules from /inc/ ─────────────────────────────────
require_once LX_THEME_DIR . '/inc/index.php';
