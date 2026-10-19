<?php
/**
 * Click Tracker for Hotline & Zalo in WordPress Admin
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle AJAX click tracking from frontend
 */
function lx_handle_track_click() {
    $type = isset($_POST['type']) ? sanitize_key($_POST['type']) : '';
    if (!in_array($type, array('hotline', 'zalo'), true)) {
        wp_send_json_error(array('message' => 'Invalid type'));
    }

    $today        = current_time('Y-m-d');
    $now          = current_time('d/m/Y H:i:s');
    $page         = isset($_POST['page']) ? esc_url_raw($_POST['page']) : home_url('/');
    $fbclid       = isset($_POST['fbclid']) ? sanitize_text_field($_POST['fbclid']) : '';
    $utm_source   = isset($_POST['utm_source']) ? sanitize_text_field($_POST['utm_source']) : '';
    $utm_campaign = isset($_POST['utm_campaign']) ? sanitize_text_field($_POST['utm_campaign']) : '';
    $user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
    $ip           = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';

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
 * Register Submenu Page under Landing Page
 */
function lx_register_click_stats_menu() {
    add_submenu_page(
        'lx-landing-settings',
        'Thống Kê Click Hotline & Zalo',
        'Thống Kê Click',
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
        '📞 Thống Kê Click Hotline & Zalo (Landing Page)',
        'lx_render_dashboard_click_widget'
    );
}
add_action('wp_dashboard_setup', 'lx_register_dashboard_click_widget');

/**
 * Render Dashboard Widget Content
 */
function lx_render_dashboard_click_widget() {
    $stats = get_option('lx_click_stats_summary', array(
        'total_hotline' => 0,
        'total_zalo'    => 0,
        'by_date'       => array(),
    ));

    $today        = current_time('Y-m-d');
    $today_hotline = intval($stats['by_date'][$today]['hotline'] ?? 0);
    $today_zalo    = intval($stats['by_date'][$today]['zalo'] ?? 0);
    $total_hotline = intval($stats['total_hotline'] ?? 0);
    $total_zalo    = intval($stats['total_zalo'] ?? 0);
    ?>
    <div style="display:flex; gap:16px; margin-bottom:16px; text-align:center;">
        <div style="flex:1; background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:12px;">
            <div style="font-size:12px; color:#0369a1; font-weight:600;">📞 HOTLINE</div>
            <div style="font-size:24px; font-weight:800; color:#0284c7; margin:4px 0;"><?php echo $total_hotline; ?></div>
            <div style="font-size:11px; color:#64748b;">Hôm nay: <strong><?php echo $today_hotline; ?></strong></div>
        </div>
        <div style="flex:1; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px;">
            <div style="font-size:12px; color:#15803d; font-weight:600;">💬 ZALO</div>
            <div style="font-size:24px; font-weight:800; color:#16a34a; margin:4px 0;"><?php echo $total_zalo; ?></div>
            <div style="font-size:11px; color:#64748b;">Hôm nay: <strong><?php echo $today_zalo; ?></strong></div>
        </div>
    </div>
    <div style="text-align:right;">
        <a href="<?php echo esc_url(admin_url('admin.php?page=lx-click-stats')); ?>" class="button button-primary">Xem chi tiết nhật ký &rarr;</a>
    </div>
    <?php
}

/**
 * Render Click Stats Page in WP Admin
 */
function lx_render_click_stats_page() {
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
    $total_all     = $total_hotline + $total_zalo;

    $by_date = $stats['by_date'] ?? array();
    krsort($by_date);

    $logs = get_option('lx_click_stats_logs', array());
    if (!is_array($logs)) {
        $logs = array();
    }
    ?>
    <div class="wrap" style="max-width:1200px;">
        <h1 style="display:flex; align-items:center; gap:8px; margin-bottom:20px;">
            <span class="dashicons dashicons-chart-area" style="font-size:28px; width:28px; height:28px;"></span>
            Thống Kê Lượt Click Hotline & Zalo
        </h1>

        <?php if (!empty($_GET['reset'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong>Đã làm mới dữ liệu thống kê thành công!</strong></p>
            </div>
        <?php endif; ?>

        <!-- SUMMARY CARDS -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:16px; margin-bottom:24px;">
            <!-- Hotline Card -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); border-left:4px solid #0284c7;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:13px; font-weight:600; color:#64748b; text-transform:uppercase;">Click Gọi Hotline</span>
                    <span class="dashicons dashicons-phone" style="color:#0284c7; font-size:22px;"></span>
                </div>
                <div style="font-size:32px; font-weight:800; color:#0f172a; margin:8px 0 4px 0;"><?php echo number_format_i18n($total_hotline); ?></div>
                <div style="font-size:13px; color:#64748b;">
                    Hôm nay: <strong style="color:#0284c7; font-weight:700;"><?php echo number_format_i18n($today_hotline); ?></strong> lượt
                </div>
            </div>

            <!-- Zalo Card -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); border-left:4px solid #16a34a;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:13px; font-weight:600; color:#64748b; text-transform:uppercase;">Click Chat Zalo</span>
                    <span class="dashicons dashicons-format-chat" style="color:#16a34a; font-size:22px;"></span>
                </div>
                <div style="font-size:32px; font-weight:800; color:#0f172a; margin:8px 0 4px 0;"><?php echo number_format_i18n($total_zalo); ?></div>
                <div style="font-size:13px; color:#64748b;">
                    Hôm nay: <strong style="color:#16a34a; font-weight:700;"><?php echo number_format_i18n($today_zalo); ?></strong> lượt
                </div>
            </div>

            <!-- Total Card -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); border-left:4px solid #d64d86;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:13px; font-weight:600; color:#64748b; text-transform:uppercase;">Tổng Lượt Liên Hệ</span>
                    <span class="dashicons dashicons-groups" style="color:#d64d86; font-size:22px;"></span>
                </div>
                <div style="font-size:32px; font-weight:800; color:#0f172a; margin:8px 0 4px 0;"><?php echo number_format_i18n($total_all); ?></div>
                <div style="font-size:13px; color:#64748b;">
                    Hôm nay: <strong style="color:#d64d86; font-weight:700;"><?php echo number_format_i18n($today_hotline + $today_zalo); ?></strong> lượt
                </div>
            </div>
        </div>

        <!-- ACTIONS BAR -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <div style="display:flex; gap:8px;">
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="lx_export_click_stats">
                    <?php wp_nonce_field('lx_export_clicks_action', 'lx_export_clicks_nonce'); ?>
                    <button type="submit" class="button button-primary" style="display:flex; align-items:center; gap:4px;">
                        <span class="dashicons dashicons-download" style="margin-top:2px;"></span> Xuất File CSV
                    </button>
                </form>
            </div>
            <div>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn đặt lại toàn bộ số liệu thống kê click?');">
                    <input type="hidden" name="action" value="lx_reset_click_stats">
                    <?php wp_nonce_field('lx_reset_clicks_action', 'lx_reset_clicks_nonce'); ?>
                    <button type="submit" class="button" style="color:#e11d48;">
                        <span class="dashicons dashicons-trash" style="margin-top:2px;"></span> Đặt lại số liệu
                    </button>
                </form>
            </div>
        </div>

        <!-- TWO COLUMN LAYOUT: DAILY STATS & DETAILED LOGS -->
        <div style="display:grid; grid-template-columns: 320px 1fr; gap:20px;">
            <!-- Cột thống kê theo ngày -->
            <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.05); height:fit-content;">
                <h3 style="margin-top:0; font-size:15px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">📅 Theo Ngày</h3>
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
    </div>
    <?php
}
