<?php
/**
 * Contact Form 7 Integration for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

// Disable automatic <p> and <br> wrapping in Contact Form 7
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Ensure default Bon Bon Contact Form 7 exists
 *
 * @return int|false Form ID or false
 */
function lx_ensure_cf7_form() {
    if (!class_exists('WPCF7_ContactForm')) {
        return false;
    }

    $form_content = '<div class="field">' . "\n"
        . '    <label for="your-name">Họ tên <i>*</i></label>' . "\n"
        . '    [text* your-name id:name class:input autocomplete:name placeholder "VD: Nguyễn Thị Lan"]' . "\n"
        . '</div>' . "\n"
        . '<div class="field">' . "\n"
        . '    <label for="your-phone">Số điện thoại <i>*</i></label>' . "\n"
        . '    [tel* your-phone id:phone class:input autocomplete:tel placeholder "VD: 0901 234 567"]' . "\n"
        . '</div>' . "\n"
        . '<div class="field">' . "\n"
        . '    <span class="field-label">Chọn mua <i style="color:#e5484d; font-style:normal">*</i></span>' . "\n"
        . '    <div class="packs-wrap">[radio package use_label_element default:1 "Hũ nhỏ 49.000đ (giá gốc 80.000đ) + FREESHIP" "Hũ to 140.000đ + FREESHIP" "Combo 299K BIGSIZE tắm gội + Kem bôi (giá gốc 455.000đ)"]</div>' . "\n"
        . '</div>' . "\n"
        . '[submit class:btn class:pink class:pulse id:submitBtn "🎁 ĐẶT HÀNG NGAY!"]';

    $messages = array(
        'mail_sent_ok'             => '🎉 Cảm ơn mẹ! Đơn đặt hàng đã được gửi thành công. Chuyên viên Bon Bon sẽ liên hệ xác nhận đơn với mẹ ngay ạ!',
        'mail_sent_ng'             => '❌ Có lỗi xảy ra trong quá trình gửi đơn. Mẹ vui lòng liên hệ hotline hoặc thử lại sau nhé.',
        'validation_error'         => '⚠️ Mẹ vui lòng kiểm tra lại thông tin và điền đầy đủ các mục bắt buộc nhé!',
        'spam'                     => '❌ Có lỗi xảy ra trong quá trình gửi đơn. Mẹ vui lòng thử lại sau nhé.',
        'accept_terms'             => 'Mẹ vui lòng đồng ý với các điều khoản trước khi gửi đơn.',
        'invalid_required'         => 'Mẹ vui lòng nhập thông tin vào trường này nhé!',
        'invalid_too_long'         => 'Nội dung quá dài, mẹ vui lòng nhập ngắn gọn hơn.',
        'invalid_too_short'        => 'Nội dung quá ngắn, mẹ vui lòng nhập đầy đủ hơn.',
        'upload_failed'            => 'Không thể tải tệp lên. Mẹ vui lòng thử lại sau.',
        'upload_file_type_invalid' => 'Định dạng tệp không được hỗ trợ.',
        'upload_file_too_large'    => 'Dung lượng tệp tải lên quá lớn.',
        'upload_failed_php_error'  => 'Có lỗi hệ thống khi tải tệp lên.',
        'invalid_date'             => 'Ngày tháng không hợp lệ (định dạng YYYY-MM-DD).',
        'date_too_early'           => 'Ngày đã chọn quá sớm.',
        'date_too_late'            => 'Ngày đã chọn quá muộn.',
        'invalid_number'           => 'Vui lòng nhập một chữ số hợp lệ.',
        'number_too_small'         => 'Số lượng nhập vào quá nhỏ.',
        'number_too_large'         => 'Số lượng nhập vào quá lớn.',
        'quiz_answer_not_correct'  => 'Câu trả lời xác thực chưa chính xác.',
        'invalid_email'            => 'Địa chỉ email chưa chính xác. Mẹ vui lòng kiểm tra lại nhé.',
        'invalid_url'              => 'Đường dẫn liên kết (URL) chưa chính xác.',
        'invalid_tel'              => 'Số điện thoại chưa đúng định dạng. Mẹ vui lòng kiểm tra lại nhé (VD: 0901 234 567).',
        'invalid_radio'            => 'Mẹ vui lòng chọn gói sản phẩm phù hợp nhé!',
        'invalid_select'           => 'Mẹ vui lòng chọn lựa chọn phù hợp nhé!',
        'invalid_checkbox'         => 'Mẹ vui lòng chọn lựa chọn phù hợp nhé!',
    );

    // Check if form already exists
    $existing = get_posts(array(
        'post_type'      => 'wpcf7_contact_form',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'title'          => 'Đặt Hàng Bon Bon',
    ));

    if (!empty($existing)) {
        $fid = $existing[0]->ID;
        update_post_meta($fid, '_form', $form_content);
        update_post_meta($fid, '_messages', $messages);
        update_post_meta($fid, '_locale', 'vi');
        return $fid;
    }

    // Also check if ANY contact form exists
    $any_form = get_posts(array(
        'post_type'      => 'wpcf7_contact_form',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
    ));

    if (!empty($any_form)) {
        $fid = $any_form[0]->ID;
        update_post_meta($fid, '_form', $form_content);
        update_post_meta($fid, '_messages', $messages);
        update_post_meta($fid, '_locale', 'vi');
        return $fid;
    }

    $form_post = array(
        'post_title'   => 'Đặt Hàng Bon Bon',
        'post_type'    => 'wpcf7_contact_form',
        'post_status'  => 'publish',
    );

    $form_id = wp_insert_post($form_post);
    if ($form_id && !is_wp_error($form_id)) {
        $mail_tags = array(
            'active'    => true,
            'subject'   => 'Đơn đặt hàng Kem Hăm Bon Bon mới từ [your-name]',
            'sender'    => '[_site_title] <wordpress@' . ($_SERVER['SERVER_NAME'] ?? 'kemham.bonbonhomecare.vn') . '>',
            'recipient' => 'tuyenhomecare@gmail.com',
            'body'      => "Thông tin đơn hàng mới từ Landing Page:\n\n"
                         . "Họ tên: [your-name]\n"
                         . "Số điện thoại: [your-phone]\n"
                         . "Gói chọn mua: [package]\n"
                         . "Thời gian: [_date] [_time]\n"
                         . "Trang gửi: [_url]\n",
        );

        update_post_meta($form_id, '_form', $form_content);
        update_post_meta($form_id, '_mail', $mail_tags);
        update_post_meta($form_id, '_messages', $messages);
        update_post_meta($form_id, '_locale', 'vi');
        return $form_id;
    }

    return false;
}
add_action('init', 'lx_ensure_cf7_form');

/**
 * Tắt cơ chế kiểm tra enum cứng nhắc của CF7 SWV
 * Ngăn chặn lỗi "Undefined value was submitted through this field" khi chọn gói radio
 */
add_action('wpcf7_init', function () {
    remove_action('wpcf7_swv_create_schema', 'wpcf7_swv_add_checkbox_enum_rules', 20);
    remove_action('wpcf7_swv_create_schema', 'wpcf7_swv_add_select_enum_rules', 20);
}, 20);

/**
 * Đảm bảo mọi tin nhắn mặc định của Contact Form 7 là tiếng Việt
 */
add_filter('wpcf7_messages', function ($messages) {
    $messages['invalid_radio'] = array(
        'description' => __('Undefined value was submitted through this field.', 'contact-form-7'),
        'default'     => 'Mẹ vui lòng chọn gói sản phẩm phù hợp nhé!',
    );
    $messages['invalid_select'] = array(
        'description' => __('Undefined value was submitted through this field.', 'contact-form-7'),
        'default'     => 'Mẹ vui lòng chọn lựa chọn phù hợp nhé!',
    );
    $messages['invalid_required'] = array(
        'description' => __('Please fill in the required field.', 'contact-form-7'),
        'default'     => 'Mẹ vui lòng nhập thông tin vào trường này nhé!',
    );
    return $messages;
});

/**
 * Bộ lọc phản hồi AJAX CF7 - Tuyệt đối không để sót bất kỳ thông báo tiếng Anh nào
 */
add_filter('wpcf7_feedback_response', function ($response, $result) {
    if (!empty($response['invalid_fields'])) {
        foreach ($response['invalid_fields'] as &$field) {
            if (isset($field['message'])) {
                if (stripos($field['message'], 'Undefined value') !== false || stripos($field['message'], 'submitted through this field') !== false) {
                    $field['message'] = 'Mẹ vui lòng chọn gói sản phẩm phù hợp nhé!';
                } elseif (stripos($field['message'], 'fill in') !== false || stripos($field['message'], 'required') !== false) {
                    $field['message'] = 'Mẹ vui lòng nhập thông tin vào trường này nhé!';
                }
            }
        }
    }
    if (isset($response['message'])) {
        if (stripos($response['message'], 'validation errors occurred') !== false || stripos($response['message'], 'check and try again') !== false) {
            $response['message'] = '⚠️ Mẹ vui lòng kiểm tra lại thông tin và điền đầy đủ các mục bắt buộc nhé!';
        }
    }
    return $response;
}, 20, 2);

/**
 * Tự động gửi dữ liệu đơn hàng Contact Form 7 sang Google Sheet
 *
 * @param WPCF7_ContactForm $contact_form
 */
function lx_cf7_save_to_google_sheet($contact_form) {
    if (!class_exists('WPCF7_Submission')) {
        return;
    }

    $submission = WPCF7_Submission::get_instance();
    if (!$submission) {
        return;
    }

    $posted_data = $submission->get_posted_data();
    $name    = sanitize_text_field($posted_data['your-name'] ?? $posted_data['name'] ?? '');
    $phone   = sanitize_text_field($posted_data['your-phone'] ?? $posted_data['phone'] ?? '');
    $package = is_array($posted_data['package'] ?? '') ? implode(', ', $posted_data['package']) : ($posted_data['package'] ?? '');

    // Bỏ qua nếu thiếu dữ liệu cơ bản
    if (empty($name) || empty($phone)) {
        return;
    }

    // Lấy URL Web App Google Sheet từ cài đặt ACF hoặc options
    $sheet_url = lx_get_field('lx_sheet_endpoint', 'option', '');
    if (empty($sheet_url)) {
        $sheet_url = get_option('options_lx_sheet_endpoint', '');
    }
    if (empty($sheet_url)) {
        $sheet_url = get_option('lx_sheet_endpoint', '');
    }

    if (!empty($sheet_url) && filter_var($sheet_url, FILTER_VALIDATE_URL)) {
        // Tự động phân loại giá tiền
        $price = '49.000đ';
        if (strpos($package, '140.000') !== false) {
            $price = '140.000đ';
        } elseif (strpos($package, '299K') !== false || strpos($package, '299.000') !== false) {
            $price = '299.000đ';
        }

        $payload = array(
            'time'       => current_time('d/m/Y H:i:s'),
            'name'       => $name,
            'phone'      => $phone,
            'package'    => $package,
            'product'    => $package,
            'price'      => $price,
            'quantity'   => 1,
            'page'       => wp_get_referer() ?: home_url(),
            'user_agent' => sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? ''),
        );

        // Đính kèm các trường UTM Tracking nếu có
        $tracking_keys = array('utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'ttclid');
        foreach ($tracking_keys as $key) {
            if (!empty($_COOKIE[$key])) {
                $payload[$key] = sanitize_text_field($_COOKIE[$key]);
            } elseif (!empty($_GET[$key])) {
                $payload[$key] = sanitize_text_field($_GET[$key]);
            }
        }

        // Gửi ngầm không chặn luồng (Non-blocking) để form phản hồi tức thì
        wp_remote_post($sheet_url, array(
            'method'      => 'POST',
            'timeout'     => 15,
            'redirection' => 5,
            'blocking'    => false,
            'body'        => $payload,
        ));
    }
}
add_action('wpcf7_mail_sent', 'lx_cf7_save_to_google_sheet');

/**
 * Get rendered CF7 form or fallback HTML
 *
 * @return string
 */
function lx_render_order_form() {
    $shortcode = lx_get_field('lx_cf7_shortcode', 'option', '');

    if (!empty($shortcode)) {
        return do_shortcode($shortcode);
    }

    if (class_exists('WPCF7_ContactForm')) {
        $form_id = lx_ensure_cf7_form();
        if ($form_id) {
            return do_shortcode('[contact-form-7 id="' . $form_id . '" title="Đặt Hàng Bon Bon"]');
        }
    }

    // Built-in form fallback if CF7 is not active or hasn't created a form
    ob_start();
    ?>
    <form id="orderForm" novalidate>
        <div class="field">
            <label for="name">Họ tên <i>*</i></label>
            <input class="input" id="name" name="name" autocomplete="name" maxlength="80" placeholder="VD: Nguyễn Thị Lan">
            <div class="err" id="nameErr">Mẹ nhập giúp họ tên nhé</div>
        </div>
        <div class="field">
            <label for="phone">Số điện thoại <i>*</i></label>
            <input class="input" id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="16" placeholder="VD: 0901 234 567">
            <div class="err" id="phoneErr">Số điện thoại chưa đúng</div>
        </div>
        <div class="field">
            <span class="field-label">Chọn mua <i style="color:#e5484d; font-style:normal">*</i></span>
            <div class="packs" id="packs" role="radiogroup" aria-label="Chọn mua"></div>
        </div>
        <div class="hp" aria-hidden="true"><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
        <button type="submit" class="btn pink pulse" id="submitBtn">🎁 ĐẶT HÀNG NGAY!</button>
        <div class="form-alert" id="formAlert" role="alert"></div>
    </form>
    <?php
    return ob_get_clean();
}
