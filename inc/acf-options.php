<?php
/**
 * ACF Options Page and Field Group Registration
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
            'page_title' => 'Cài Đặt Landing Page',
            'menu_title' => 'Landing Page',
            'menu_slug'  => 'lx-landing-settings',
            'capability' => 'manage_options',
            'icon_url'   => 'dashicons-layout',
            'redirect'   => false,
            'position'   => 3,
        ));
    }
}
add_action('acf/init', 'lx_register_acf_options_page');

/**
 * ACF JSON save point
 *
 * @param string $path
 * @return string
 */
function lx_acf_json_save_point($path) {
    return LX_THEME_DIR . '/acf-json';
}
add_filter('acf/settings/save_json', 'lx_acf_json_save_point');

/**
 * ACF JSON load point
 *
 * @param array $paths
 * @return array
 */
function lx_acf_json_load_point($paths) {
    $paths[] = LX_THEME_DIR . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'lx_acf_json_load_point');
