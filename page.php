<?php
/**
 * Generic Page Template
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('lx_is_thankyou_page') && lx_is_thankyou_page()) {
    get_template_part('page-cam-on');
    return;
}

get_template_part('index');
