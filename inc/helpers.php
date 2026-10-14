<?php
/**
 * Helper functions for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Safely get ACF field value with fallback default
 *
 * @param string $selector Field name or key.
 * @param mixed  $post_id  Post ID or 'option'.
 * @param mixed  $default  Fallback value if empty.
 * @return mixed
 */
function lx_get_field($selector, $post_id = 'option', $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($selector, $post_id);
        if ($value !== null && $value !== false && $value !== '') {
            return $value;
        }
    }
    return $default;
}

/**
 * Get ACF image URL or default image
 *
 * @param string $selector Field name or key.
 * @param string $default  Fallback URL.
 * @param string $size     Image size.
 * @param mixed  $post_id  Post ID or 'option'.
 * @return string
 */
function lx_get_image_url($selector, $default = '', $size = 'full', $post_id = 'option') {
    if (function_exists('get_field')) {
        $img = get_field($selector, $post_id);
        if (!empty($img)) {
            if (is_array($img) && !empty($img['url'])) {
                if ($size !== 'full' && !empty($img['sizes'][$size])) {
                    return $img['sizes'][$size];
                }
                return $img['url'];
            }
            if (is_numeric($img)) {
                $src = wp_get_attachment_image_url($img, $size);
                if ($src) {
                    return $src;
                }
            }
            if (is_string($img)) {
                return $img;
            }
        }
    }
    return $default;
}

/**
 * Format price in VND (e.g., 49.000đ)
 *
 * @param int|float|string $number Price number.
 * @return string
 */
function lx_format_price($number) {
    $num = intval($number);
    return number_format($num, 0, ',', '.') . 'đ';
}
