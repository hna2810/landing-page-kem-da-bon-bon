<?php
/**
 * ACF Options Page and Field Groups for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF Options Page
 */
function lx_register_acf_options_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => __('Cài đặt Landing Bon Bon', 'lx-landing'),
            'menu_title' => __('Landing Page', 'lx-landing'),
            'menu_slug'  => 'lx-landing-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
            'icon_url'   => 'dashicons-baby',
            'position'   => 2,
        ));
    }
}
add_action('acf/init', 'lx_register_acf_options_page');

/**
 * Set ACF JSON save and load paths
 */
function lx_acf_json_save_point($path) {
    return LX_THEME_DIR . '/acf-json';
}
add_filter('acf/settings/save_json', 'lx_acf_json_save_point');

function lx_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = LX_THEME_DIR . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'lx_acf_json_load_point');
