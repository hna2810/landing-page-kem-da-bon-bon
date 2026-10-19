<?php
/**
 * Click Tracker for Hotline & Zalo + Order Leads Manager in WordPress Admin
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Redirect shortcuts /wp-admin/lx-click-stats or /lx-click-stats to correct admin page
 */
function lx_redirect_stats_shortcut() {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    // If accessing wp-admin/lx-click-stats without admin.php
    if (strpos($request_uri, 'lx-click-stats') !== false && strpos($request_uri, 'admin.php') === false) {
        wp_safe_redirect(admin_url('admin.php?page=lx-click-stats'));
        exit;
    }
}
add_action('init', 'lx_redirect_stats_shortcut', 1);

/**
 * Add Shortcut to WordPress Admin Bar (Thanh menu đen ở trên cùng)
 */
function lx_add_stats_admin_bar($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }
    $wp_admin_bar->add_node(array(
        'id'    => 'lx_stats_shortcut',
        'title' => '<span class="ab-icon dashicons dashicons-chart-area" style="top:2px;"></span> <span class="ab-label" style="font-weight:700; color:#38bdf8;">📊 Thống Kê & Đơn Hàng</span>',
        'href'  => admin_url('admin.php?page=lx-click-stats'),
        'meta'  => array('title' => 'Xem Thống Kê Click và Đơn Hàng'),
    ));
}
add_action('admin_bar_menu', 'lx_add_stats_admin_bar', 100);

/**
 * Handle AJAX click tracking from frontend
 */
function lx_handle_track_click() {
    $type = isset($_REQUEST['type']) ? sanitize_key($_REQUEST['type']) : '';
    if (!in_array($type, array('hotline', 'zalo'), true)) {
        wp_send_json_error(array('message' => 'Invalid type'));
    }

    $today        = current_time('Y-m-d');
    $now          = current_time('d/m/Y H:i:s');
    $page         = isset($_REQUEST['page']) ? esc_url_raw($_REQUEST['page']) : home_url('/');
    $fbclid       = isset($_REQUEST['fbclid']) ? sanitize_text_field($_REQUEST['fbclid']) : '';
    $utm_source   = isset($_REQUEST['utm_source']) ? sanitize_text_field($_REQUEST['utm_source']) : '';
    $utm_campaign = isset($_REQUEST['utm_campaign']) ? sanitize_text_field($_REQUEST['utm_campaign']) : '';
    $user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
    $ip           = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';

    // Khóa chống lặp (Deduplication): Trong vòng 3 giây, cùng 1 IP bấm cùng loại nút thì không đếm lặp
    $dedup_key = 'lx_clk_' . md5($ip . '_' . $type);
    if (get_transient($dedup_key)) {
        if (!empty($_GET['_t'])) {
            header('Content-Type: image/gif');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            exit;
        }
        wp_send_json_success(array('recorded' => false, 'note' => 'deduplicated'));
    }
    set_transient($dedup_key, 1, 3);

    // 1. Cập nhật tổng đếm (Summary Counts)
    $stats = get_option('lx_click_stats_summary', array(
        'total_hotline' => 0,
        'total_zalo'    => 0,
        'by_date'       => array(),
    ));

    if (!is_array($stats)) {
        $stats = array('total_hotline' => 0, 'total_zalo' => 0, 'by_date' => array());
    }
    if (!isset($stats['by_date']) || !is_array($stats['by_date'])) {
        $stats['by_date'] = array();
    }
    if (!isset($stats['by_date'][$today])) {
        $stats['by_date'][$today] = array('hotline' => 0, 'zalo' => 0);
    }

    if ($type === 'hotline') {
        $stats['total_hotline'] = intval($stats['total_hotline'] ?? 0) + 1;
        $stats['by_date'][$today]['hotline'] = intval($stats['by_date'][$today]['hotline'] ?? 0) + 1;
    } else {
        $stats['total_zalo'] = intval($stats['total_zalo'] ?? 0) + 1;
        $stats['by_date'][$today]['zalo'] = intval($stats['by_date'][$today]['zalo'] ?? 0) + 1;
    }

    update_option('lx_click_stats_summary', $stats, false);

    // 2. Ghi nhật ký chi tiết (Recent logs, giữ tối đa 300 bản ghi mới nhất)
    $logs = get_option('lx_click_stats_logs', array());
    if (!is_array($logs)) {
        $logs = array();
    }

    $new_entry = array(
        'time'         => $now,
        'date'         => $today,
        'type'         => $type,
        'page'         => $page,
        'fbclid'       => $fbclid,
        'utm_source'   => $utm_source,
        'utm_campaign' => $utm_campaign,
        'user_agent'   => $user_agent,
        'ip'           => $ip,
    );

    array_unshift($logs, $new_entry);
    if (count($logs) > 300) {
        $logs = array_slice($logs, 0, 300);
    }
    update_option('lx_click_stats_logs', $logs, false);

    if (!empty($_GET['_t'])) {
        header('Content-Type: image/gif');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        exit;
    }

    wp_send_json_success(array('recorded' => true, 'type' => $type));
}
add_action('wp_ajax_nopriv_lx_track_click', 'lx_handle_track_click');
add_action('wp_ajax_lx_track_click', 'lx_handle_track_click');

/**
 * Handle Export CSV for click stats
 */
function lx_handle_export_click_stats_csv() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Bạn không có quyền truy cập trang này.', 'lx-landing'));
    }
    check_admin_referer('lx_export_clicks_action', 'lx_export_clicks_nonce');

    $logs = get_option('lx_click_stats_logs', array());
    if (!is_array($logs)) {
        $logs = array();
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="thong-ke-click-bon-bon-' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Xuất BOM UTF-8 để Microsoft Excel hiển thị tiếng Việt chuẩn
    echo "\xEF\xBB\xBF";

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Thời gian', 'Kênh liên hệ', 'Trang đặt hàng', 'Mã Fbclid', 'UTM Source', 'UTM Campaign', 'Thiết bị', 'Địa chỉ IP'));

    foreach ($logs as $row) {
        fputcsv($output, array(
            $row['time'] ?? '',
            ($row['type'] === 'hotline') ? 'Hotline (Gọi điện)' : 'Zalo (Nhắn tin)',
            $row['page'] ?? '',
            $row['fbclid'] ?? '',
            $row['utm_source'] ?? '',
            $row['utm_campaign'] ?? '',
            $row['user_agent'] ?? '',
            $row['ip'] ?? '',
        ));
    }

    fclose($output);
    exit;
}
add_action('admin_post_lx_export_click_stats', 'lx_handle_export_click_stats_csv');

/**
 * Handle Export CSV for Order Leads
 */
function lx_handle_export_order_leads_csv() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Bạn không có quyền truy cập trang này.', 'lx-landing'));
    }
    check_admin_referer('lx_export_leads_action', 'lx_export_leads_nonce');

    global $wpdb;
    $table = $wpdb->prefix . 'cf7db_gsheet_forms';
    $rows = $wpdb->get_results("SELECT id, value, date FROM {$table} ORDER BY id ASC", ARRAY_A);

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="danh-sach-don-hang-bon-bon-' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo "\xEF\xBB\xBF";

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Thời gian đặt', 'Họ và tên', 'Số điện thoại', 'Địa chỉ giao hàng', 'Gói sản phẩm', 'Nguồn quảng cáo', 'Mã Fbclid / Link'));

    if (!empty($rows)) {
        foreach ($rows as $r) {
            $val = @unserialize($r['value']);
            if (!is_array($val)) {
                $val = array();
            }
            $url = $val['url'] ?? '';
            $is_fb = (stripos($url, 'fbclid') !== false) ? 'Facebook Ads' : 'Trực tiếp / Test';
            $pkg = is_array($val['package'] ?? null) ? implode(', ', $val['package']) : ($val['package'] ?? '');

            fputcsv($output, array(
                $r['id'],
                $r['date'],
                $val['your-name'] ?? '',
                $val['your-phone'] ?? '',
                $val['your-address'] ?? '',
                $pkg,
                $is_fb,
                $url,
            ));
        }
    }

    fclose($output);
    exit;
}
add_action('admin_post_lx_export_order_leads', 'lx_handle_export_order_leads_csv');

/**
 * Handle Reset Click Stats
 */
function lx_handle_reset_click_stats() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Bạn không có quyền.', 'lx-landing'));
    }
    check_admin_referer('lx_reset_clicks_action', 'lx_reset_clicks_nonce');

    update_option('lx_click_stats_summary', array(
        'total_hotline' => 0,
        'total_zalo'    => 0,
        'by_date'       => array(),
    ), false);
    update_option('lx_click_stats_logs', array(), false);

    wp_safe_redirect(add_query_arg(array('page' => 'lx-click-stats', 'reset' => '1'), admin_url('admin.php')));
    exit;
}
add_action('admin_post_lx_reset_click_stats', 'lx_handle_reset_click_stats');

/**
 * Register Top-Level Menu & Submenu in WordPress Admin
 */
function lx_register_click_stats_menu() {
    // 1. Menu chính ngoài sidebar Admin (vị trí 3 - ngay dưới Bảng tin)
    add_menu_page(
        'Thống Kê & Đơn Hàng',
        'Thống Kê & Đơn',
        'manage_options',
        'lx-click-stats',
        'lx_render_click_stats_page',
        'dashicons-chart-area',
        3
    );

    // 2. Menu con dưới "Landing Page" để vào từ đâu cũng được
    add_submenu_page(
        'lx-landing-settings',
        'Thống Kê Click & Đơn Hàng',
        'Thống Kê & Đơn Hàng',
        'manage_options',
        'lx-click-stats',
        'lx_render_click_stats_page'
    );
}
add_action('admin_menu', 'lx_register_click_stats_menu', 20);

/**
 * Register Dashboard Widget
 */
function lx_register_dashboard_click_widget() {
    wp_add_dashboard_widget(
        'lx_dashboard_click_stats',
        '📊 Thống Kê Nhanh (Click Hotline, Zalo & Đơn Hàng)',
        'lx_render_dashboard_click_widget'
    );
}
add_action('wp_dashboard_setup', 'lx_register_dashboard_click_widget');

/**
 * Render Dashboard Widget Content
 */
function lx_render_dashboard_click_widget() {
    global $wpdb;
    $stats = get_option('lx_click_stats_summary', array(
        'total_hotline' => 0,
        'total_zalo'    => 0,
        'by_date'       => array(),
    ));

    $today         = current_time('Y-m-d');
    $today_hotline = intval($stats['by_date'][$today]['hotline'] ?? 0);
    $today_zalo    = intval($stats['by_date'][$today]['zalo'] ?? 0);
    $total_hotline = intval($stats['total_hotline'] ?? 0);
    $total_zalo    = intval($stats['total_zalo'] ?? 0);

    // Lấy số lượng đơn hàng
    $table = $wpdb->prefix . 'cf7db_gsheet_forms';
    $total_orders = 0;
    if ($wpdb->get_var("SHOW TABLES LIKE '{$table}'") === $table) {
        $total_orders = intval($wpdb->get_var("SELECT COUNT(*) FROM {$table}"));
    }
    ?>
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:12px; margin-bottom:16px; text-align:center;">
        <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:10px;">
            <div style="font-size:11px; color:#0369a1; font-weight:700;">📞 HOTLINE</div>
            <div style="font-size:22px; font-weight:800; color:#0284c7; margin:2px 0;"><?php echo $total_hotline; ?></div>
            <div style="font-size:11px; color:#64748b;">Hôm nay: <strong><?php echo $today_hotline; ?></strong></div>
        </div>
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px;">
            <div style="font-size:11px; color:#15803d; font-weight:700;">💬 ZALO</div>
            <div style="font-size:22px; font-weight:800; color:#16a34a; margin:2px 0;"><?php echo $total_zalo; ?></div>
            <div style="font-size:11px; color:#64748b;">Hôm nay: <strong><?php echo $today_zalo; ?></strong></div>
        </div>
        <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; padding:10px;">
            <div style="font-size:11px; color:#be123c; font-weight:700;">🛒 ĐƠN FORM</div>
            <div style="font-size:22px; font-weight:800; color:#e11d48; margin:2px 0;"><?php echo $total_orders; ?></div>
            <div style="font-size:11px; color:#64748b;">Đã lưu hệ thống</div>
        </div>
    </div>
    <div style="text-align:right;">
        <a href="<?php echo esc_url(admin_url('admin.php?page=lx-click-stats')); ?>" class="button button-primary">Xem chi tiết & Xuất dữ liệu &rarr;</a>
    </div>
    <?php
}

/**
 * Render Click Stats & Orders Page in WP Admin
 */
function lx_render_click_stats_page() {
    global $wpdb;

    $stats = get_option('lx_click_stats_summary', array(
        'total_hotline' => 0,
        'total_zalo'    => 0,
        'by_date'       => array(),
    ));

    $today         = current_time('Y-m-d');
    $today_hotline = intval($stats['by_date'][$today]['hotline'] ?? 0);
    $today_zalo    = intval($stats['by_date'][$today]['zalo'] ?? 0);
    $total_hotline = intval($stats['total_hotline'] ?? 0);
    $total_zalo    = intval($stats['total_zalo'] ?? 0);
    $total_clicks  = $total_hotline + $total_zalo;

    $by_date = $stats['by_date'] ?? array();
    krsort($by_date);

    $logs = get_option('lx_click_stats_logs', array());
    if (!is_array($logs)) {
        $logs = array();
    }

    // Lấy toàn bộ đơn hàng từ bảng CF7DB
    $table = $wpdb->prefix . 'cf7db_gsheet_forms';
    $raw_orders = array();
    if ($wpdb->get_var("SHOW TABLES LIKE '{$table}'") === $table) {
        $raw_orders = $wpdb->get_results("SELECT id, value, date FROM {$table} ORDER BY id DESC", ARRAY_A);
    }

    $orders = array();
    $orders_today = 0;
    $orders_yesterday = 0;
    $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($today)));

    if (!empty($raw_orders)) {
        foreach ($raw_orders as $ro) {
            $val = @unserialize($ro['value']);
            if (!is_array($val)) {
                $val = array();
            }
            $order_date = explode(' ', $ro['date'])[0] ?? '';
            if ($order_date === $today) {
                $orders_today++;
            } elseif ($order_date === $yesterday) {
                $orders_yesterday++;
            }

            $url = $val['url'] ?? '';
            $is_fb = (stripos($url, 'fbclid') !== false);
            $pkg = is_array($val['package'] ?? null) ? implode(', ', $val['package']) : ($val['package'] ?? '');

            $orders[] = array(
                'id'         => $ro['id'],
                'date'       => $ro['date'],
                'name'       => $val['your-name'] ?? '',
                'phone'      => $val['your-phone'] ?? '',
                'address'    => $val['your-address'] ?? '',
                'package'    => $pkg,
                'is_fb'      => $is_fb,
                'url'        => $url,
            );
        }
    }
    $total_orders = count($orders);

    $current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'orders';
    ?>
    <div class="wrap" style="max-width:1300px;">
        <h1 style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
            <span class="dashicons dashicons-chart-area" style="font-size:32px; width:32px; height:32px; color:#0284c7;"></span>
            Trung Tâm Thống Kê & Quản Lý Đơn Hàng (Bon Bon)
        </h1>

        <?php if (!empty($_GET['reset'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong>Đã làm mới dữ liệu thống kê thành công!</strong></p>
            </div>
        <?php endif; ?>

        <!-- TOP STATS CARDS -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
            <!-- Tổng đơn hàng Card -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:16px 20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); border-left:4px solid #e11d48;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase;">Tổng Đơn Điền Form</span>
                    <span class="dashicons dashicons-cart" style="color:#e11d48; font-size:22px;"></span>
                </div>
                <div style="font-size:30px; font-weight:800; color:#0f172a; margin:6px 0 4px 0;"><?php echo number_format_i18n($total_orders); ?></div>
                <div style="font-size:12px; color:#64748b;">
                    Hôm nay: <strong style="color:#e11d48;"><?php echo $orders_today; ?></strong> | Hôm qua: <strong><?php echo $orders_yesterday; ?></strong>
                </div>
            </div>

            <!-- Hotline Card -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:16px 20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); border-left:4px solid #0284c7;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase;">Click Gọi Hotline</span>
                    <span class="dashicons dashicons-phone" style="color:#0284c7; font-size:22px;"></span>
                </div>
                <div style="font-size:30px; font-weight:800; color:#0f172a; margin:6px 0 4px 0;"><?php echo number_format_i18n($total_hotline); ?></div>
                <div style="font-size:12px; color:#64748b;">
                    Hôm nay: <strong style="color:#0284c7;"><?php echo $today_hotline; ?></strong> lượt
                </div>
            </div>

            <!-- Zalo Card -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:16px 20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); border-left:4px solid #16a34a;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase;">Click Chat Zalo</span>
                    <span class="dashicons dashicons-format-chat" style="color:#16a34a; font-size:22px;"></span>
                </div>
                <div style="font-size:30px; font-weight:800; color:#0f172a; margin:6px 0 4px 0;"><?php echo number_format_i18n($total_zalo); ?></div>
                <div style="font-size:12px; color:#64748b;">
                    Hôm nay: <strong style="color:#16a34a;"><?php echo $today_zalo; ?></strong> lượt
                </div>
            </div>

            <!-- Total Contact Card -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:16px 20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); border-left:4px solid #8b5cf6;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase;">Tổng Lượt Click Gọi/Chat</span>
                    <span class="dashicons dashicons-groups" style="color:#8b5cf6; font-size:22px;"></span>
                </div>
                <div style="font-size:30px; font-weight:800; color:#0f172a; margin:6px 0 4px 0;"><?php echo number_format_i18n($total_clicks); ?></div>
                <div style="font-size:12px; color:#64748b;">
                    Hôm nay: <strong style="color:#8b5cf6;"><?php echo $today_hotline + $today_zalo; ?></strong> lượt
                </div>
            </div>
        </div>

        <!-- NAVIGATION TABS -->
        <h2 class="nav-tab-wrapper" style="margin-bottom:20px;">
            <a href="<?php echo esc_url(admin_url('admin.php?page=lx-click-stats&tab=orders')); ?>" class="nav-tab <?php echo ($current_tab === 'orders') ? 'nav-tab-active' : ''; ?>" style="font-weight:700; font-size:14px;">
                🛒 Danh Sách Đơn Điền Form (<?php echo $total_orders; ?>)
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=lx-click-stats&tab=clicks')); ?>" class="nav-tab <?php echo ($current_tab === 'clicks') ? 'nav-tab-active' : ''; ?>" style="font-weight:700; font-size:14px;">
                📞 Thống Kê Click Hotline & Zalo
            </a>
        </h2>

        <?php if ($current_tab === 'orders') : ?>
            <!-- ================= TAB ĐƠN HÀNG ================= -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px;">
                    <div>
                        <h3 style="margin:0 0 4px 0; font-size:16px; color:#0f172a;">Lịch Sử Toàn Bộ Đơn Hàng Từ Ngày 16/10 Đến Nay</h3>
                        <p style="margin:0; font-size:12px; color:#64748b;">Dữ liệu gốc được lưu trực tiếp trên máy chủ WordPress từ biểu mẫu đặt hàng.</p>
                    </div>
                    <div>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <input type="hidden" name="action" value="lx_export_order_leads">
                            <?php wp_nonce_field('lx_export_leads_action', 'lx_export_leads_nonce'); ?>
                            <button type="submit" class="button button-primary button-large" style="display:flex; align-items:center; gap:6px;">
                                <span class="dashicons dashicons-download" style="margin-top:3px;"></span>
                                <strong>Xuất File Excel (CSV) Đơn Hàng</strong>
                            </button>
                        </form>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table class="widefat striped" style="border:none;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="font-size:12px; width:50px;">ID</th>
                                <th style="font-size:12px; width:140px;">Thời gian</th>
                                <th style="font-size:12px; width:160px;">Khách hàng</th>
                                <th style="font-size:12px; width:130px;">Số điện thoại</th>
                                <th style="font-size:12px;">Địa chỉ giao hàng</th>
                                <th style="font-size:12px; width:220px;">Gói đặt mua</th>
                                <th style="font-size:12px; width:110px;">Nguồn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)) : ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; padding:30px; color:#94a3b8;">Chưa có đơn hàng nào.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($orders as $order) : ?>
                                    <tr>
                                        <td style="font-size:12px; color:#94a3b8; font-weight:700;">#<?php echo esc_html($order['id']); ?></td>
                                        <td style="font-size:12px; white-space:nowrap;"><?php echo esc_html($order['date']); ?></td>
                                        <td style="font-size:13px; font-weight:700; color:#0f172a;"><?php echo esc_html($order['name']); ?></td>
                                        <td>
                                            <a href="tel:<?php echo esc_attr($order['phone']); ?>" style="font-size:13px; font-weight:700; color:#0284c7; text-decoration:none;">
                                                📞 <?php echo esc_html($order['phone']); ?>
                                            </a>
                                        </td>
                                        <td style="font-size:12px; color:#334155;">
                                            <?php echo !empty($order['address']) ? esc_html($order['address']) : '<span style="color:#cbd5e1;">(Không có)</span>'; ?>
                                        </td>
                                        <td style="font-size:12px;">
                                            <span style="display:inline-block; padding:2px 8px; border-radius:6px; background:#fef2f2; color:#b91c1c; font-weight:600;">
                                                <?php echo esc_html($order['package']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($order['is_fb']) : ?>
                                                <span style="display:inline-block; padding:2px 8px; border-radius:12px; background:#dbeafe; color:#1d4ed8; font-size:11px; font-weight:700;">FB Ads</span>
                                            <?php else : ?>
                                                <span style="display:inline-block; padding:2px 8px; border-radius:12px; background:#f1f5f9; color:#64748b; font-size:11px;">Direct/Test</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else : ?>
            <!-- ================= TAB THỐNG KÊ CLICK ================= -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <div style="display:flex; gap:8px;">
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <input type="hidden" name="action" value="lx_export_click_stats">
                        <?php wp_nonce_field('lx_export_clicks_action', 'lx_export_clicks_nonce'); ?>
                        <button type="submit" class="button button-primary" style="display:flex; align-items:center; gap:4px;">
                            <span class="dashicons dashicons-download" style="margin-top:2px;"></span> Xuất File CSV Click
                        </button>
                    </form>
                </div>
                <div>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn đặt lại toàn bộ số liệu thống kê click?');">
                        <input type="hidden" name="action" value="lx_reset_click_stats">
                        <?php wp_nonce_field('lx_reset_clicks_action', 'lx_reset_clicks_nonce'); ?>
                        <button type="submit" class="button" style="color:#e11d48;">
                            <span class="dashicons dashicons-trash" style="margin-top:2px;"></span> Đặt lại số liệu click
                        </button>
                    </form>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 320px 1fr; gap:20px;">
                <!-- Cột thống kê theo ngày -->
                <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.05); height:fit-content;">
                    <h3 style="margin-top:0; font-size:15px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">📅 Lượt Click Theo Ngày</h3>
                    <?php if (empty($by_date)) : ?>
                        <p style="color:#94a3b8; font-size:13px; text-align:center; padding:20px 0;">Chưa có dữ liệu lượt click nào.</p>
                    <?php else : ?>
                        <table class="widefat striped" style="border:none;">
                            <thead>
                                <tr>
                                    <th style="font-size:12px;">Ngày</th>
                                    <th style="font-size:12px; text-align:center;">Hotline</th>
                                    <th style="font-size:12px; text-align:center;">Zalo</th>
                                    <th style="font-size:12px; text-align:right;">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($by_date as $date => $counts) : 
                                    $h = intval($counts['hotline'] ?? 0);
                                    $z = intval($counts['zalo'] ?? 0);
                                    $display_date = date('d/m/Y', strtotime($date));
                                    if ($date === $today) {
                                        $display_date .= ' (Hôm nay)';
                                    }
                                ?>
                                    <tr>
                                        <td style="font-size:12px; font-weight:600;"><?php echo esc_html($display_date); ?></td>
                                        <td style="font-size:12px; text-align:center; color:#0284c7;"><?php echo $h; ?></td>
                                        <td style="font-size:12px; text-align:center; color:#16a34a;"><?php echo $z; ?></td>
                                        <td style="font-size:12px; text-align:right; font-weight:700;"><?php echo $h + $z; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <!-- Cột chi tiết nhật ký click -->
                <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                    <h3 style="margin-top:0; font-size:15px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                        📝 Nhật Ký 50 Lượt Click Gần Nhất
                    </h3>
                    <?php if (empty($logs)) : ?>
                        <p style="color:#94a3b8; font-size:13px; text-align:center; padding:40px 0;">
                            Chưa có lượt click nào được ghi nhận.<br>
                            <small style="color:#cbd5e1;">Hệ thống sẽ tự động cập nhật ngay khi khách hàng bấm Hotline hoặc Zalo trên trang web.</small>
                        </p>
                    <?php else : 
                        $recent_logs = array_slice($logs, 0, 50);
                    ?>
                        <div style="overflow-x:auto;">
                            <table class="widefat striped" style="border:none;">
                                <thead>
                                    <tr>
                                        <th style="font-size:12px;">Thời gian</th>
                                        <th style="font-size:12px;">Kênh</th>
                                        <th style="font-size:12px;">Nguồn Ads / Fbclid</th>
                                        <th style="font-size:12px;">Thiết bị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_logs as $entry) : 
                                        $is_hotline = ($entry['type'] === 'hotline');
                                    ?>
                                        <tr>
                                            <td style="font-size:12px; white-space:nowrap;"><?php echo esc_html($entry['time'] ?? ''); ?></td>
                                            <td>
                                                <?php if ($is_hotline) : ?>
                                                    <span style="display:inline-block; padding:2px 8px; border-radius:12px; background:#e0f2fe; color:#0369a1; font-weight:700; font-size:11px;">📞 HOTLINE</span>
                                                <?php else : ?>
                                                    <span style="display:inline-block; padding:2px 8px; border-radius:12px; background:#dcfce7; color:#15803d; font-weight:700; font-size:11px;">💬 ZALO</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="font-size:12px;">
                                                <?php 
                                                $ads_info = array();
                                                if (!empty($entry['fbclid'])) {
                                                    $ads_info[] = '<span title="' . esc_attr($entry['fbclid']) . '" style="color:#1d4ed8; font-family:monospace;">fbclid: ' . esc_html(substr($entry['fbclid'], 0, 15)) . '...</span>';
                                                }
                                                if (!empty($entry['utm_source'])) {
                                                    $ads_info[] = 'source: <strong>' . esc_html($entry['utm_source']) . '</strong>';
                                                }
                                                if (!empty($entry['utm_campaign'])) {
                                                    $ads_info[] = 'campaign: ' . esc_html($entry['utm_campaign']);
                                                }
                                                if (empty($ads_info)) {
                                                    echo '<span style="color:#94a3b8;">Trực tiếp / Không qua Ads</span>';
                                                } else {
                                                    echo implode(' | ', $ads_info);
                                                }
                                                ?>
                                            </td>
                                            <td style="font-size:11px; color:#64748b; max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?php echo esc_attr($entry['user_agent'] ?? ''); ?>">
                                                <?php 
                                                $ua = $entry['user_agent'] ?? '';
                                                if (stripos($ua, 'iPhone') !== false) {
                                                    echo '📱 iPhone (iOS)';
                                                } elseif (stripos($ua, 'Android') !== false) {
                                                    echo '📱 Android Mobile';
                                                } elseif (stripos($ua, 'Windows') !== false) {
                                                    echo '💻 Windows PC';
                                                } elseif (stripos($ua, 'Mac') !== false) {
                                                    echo '💻 Mac Desktop';
                                                } else {
                                                    echo '🌐 Trình duyệt web';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
