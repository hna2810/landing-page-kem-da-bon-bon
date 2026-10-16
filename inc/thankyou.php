<?php
/**
 * Thank You Page Module for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if the current request is for the Thank You page
 *
 * @return bool
 */
function lx_is_thankyou_page() {
    if (is_page('cam-on') || is_page('thank-you')) {
        return true;
    }
    if (isset($_GET['cam_on']) || isset($_GET['thank_you'])) {
        return true;
    }
    $uri  = $_SERVER['REQUEST_URI'] ?? '';
    $path = trim(parse_url($uri, PHP_URL_PATH), '/');
    if ($path === 'cam-on' || $path === 'thank-you') {
        return true;
    }
    return false;
}

/**
 * Add custom body class on thank you page
 *
 * @param array $classes
 * @return array
 */
function lx_thankyou_body_class($classes) {
    if (lx_is_thankyou_page()) {
        $classes[] = 'lx_thankyou_page';
    }
    return $classes;
}
add_filter('body_class', 'lx_thankyou_body_class');

/**
 * Serve page-cam-on.php when visiting /cam-on/ or /thank-you/
 *
 * @param string $template
 * @return string
 */
function lx_thankyou_template_include($template) {
    if (lx_is_thankyou_page()) {
        status_header(200);
        if (isset($GLOBALS['wp_query']) && is_object($GLOBALS['wp_query'])) {
            $GLOBALS['wp_query']->is_404  = false;
            $GLOBALS['wp_query']->is_page = true;
        }
        $ty_template = LX_THEME_DIR . '/page-cam-on.php';
        if (file_exists($ty_template)) {
            return $ty_template;
        }
    }
    return $template;
}
add_filter('template_include', 'lx_thankyou_template_include', 99);

/**
 * Ensure Thank You page exists in WordPress database
 */
function lx_ensure_thankyou_page() {
    $slug = 'cam-on';
    $page = get_page_by_path($slug);
    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_title'     => 'Cảm Ơn Đã Đặt Hàng',
            'post_name'      => $slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_content'   => '',
        ));
        if ($page_id && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', 'page-cam-on.php');
        }
    }
}
add_action('init', 'lx_ensure_thankyou_page');
