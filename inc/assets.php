<?php
/**
 * Asset enqueues & Dynamic Configuration for LX Landing Theme
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles
 */
function lx_enqueue_scripts() {
    // Google Fonts: Quicksand & Nunito
    wp_enqueue_style(
        'lx-landing-fonts',
        'https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Quicksand:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Main Theme CSS
    wp_enqueue_style(
        'lx-landing-main',
        LX_THEME_URI . '/assets/css/main.css',
        array(),
        LX_THEME_VERSION
    );

    // Main Theme JavaScript
    wp_enqueue_script(
        'lx-landing-main',
        LX_THEME_URI . '/assets/js/main.js',
        array(),
        LX_THEME_VERSION,
        true
    );

    // Dynamic Configuration for BONBON_CONFIG
    $hotline         = lx_get_field('lx_hotline', 'option', '0901234567');
    $hotline_display = lx_get_field('lx_hotline_display', 'option', '0901.234.567');
    $zalo            = lx_get_field('lx_zalo', 'option', '0901234567');
    $product_name    = lx_get_field('lx_product_name', 'option', 'Kem Hăm Cho Bé Bon Bon 5g');
    $price           = intval(lx_get_field('lx_price', 'option', 49000));
    $list_price      = intval(lx_get_field('lx_list_price', 'option', 80000));
    $offer_minutes   = floatval(lx_get_field('lx_offer_minutes', 'option', 10.5));

    $sheet_endpoint  = lx_get_field('lx_sheet_endpoint', 'option', '');
    $fb_pixel        = lx_get_field('lx_facebook_pixel_id', 'option', '');
    $ga_id           = lx_get_field('lx_google_analytics_id', 'option', 'G-DS38TS5KPW');
    if (empty($ga_id)) {
        $ga_id = 'G-DS38TS5KPW';
    }
    $tiktok_pixel    = lx_get_field('lx_tiktok_pixel_id', 'option', '');

    // Packages
    $raw_packages = lx_get_field('lx_packages', 'option', array());
    $packages     = array();
    if (!empty($raw_packages) && is_array($raw_packages)) {
        foreach ($raw_packages as $pkg) {
            $packages[] = array(
                'name'      => $pkg['name'] ?? '',
                'qty'       => intval($pkg['qty'] ?? 1),
                'price'     => intval($pkg['price'] ?? 49000),
                'listPrice' => intval($pkg['list_price'] ?? 80000),
                'label'     => $pkg['label'] ?? '',
            );
        }
    }
    if (empty($packages)) {
        $packages = array(
            array('name' => 'Hũ nhỏ', 'qty' => 1, 'price' => 49000,  'listPrice' => 80000,  'label' => 'Hũ nhỏ 49.000đ (giá gốc 80.000đ) + FREESHIP'),
            array('name' => 'Hũ to',  'qty' => 1, 'price' => 140000, 'listPrice' => 140000, 'label' => 'Hũ to 140.000đ + FREESHIP'),
            array('name' => 'Combo 299K BIGSIZE', 'qty' => 1, 'price' => 299000, 'listPrice' => 455000, 'label' => 'Combo 299K BIGSIZE tắm gội + Kem bôi (giá gốc 455.000đ)'),
        );
    }

    // Order Notifications
    $notif_enabled = lx_get_field('lx_notif_enabled', 'option', true);
    $raw_notifs    = lx_get_field('lx_notif_items', 'option', array());
    $notif_items   = array();
    if (!empty($raw_notifs) && is_array($raw_notifs)) {
        foreach ($raw_notifs as $item) {
            $notif_items[] = array($item['name'] ?? '', $item['city'] ?? '');
        }
    }
    if (empty($notif_items)) {
        $notif_items = array(
            array('Chị Lan', 'Hà Nội'),
            array('Mẹ Hương', 'TP.HCM'),
            array('Chị Thảo', 'Đà Nẵng'),
            array('Mẹ Ngọc', 'Cần Thơ'),
            array('Chị Mai', 'Hải Phòng'),
        );
    }

    // Media
    $hero_img      = lx_get_image_url('lx_hero_image', '', 'full', 'option');
    $mother_baby   = lx_get_image_url('lx_solution_image', '', 'full', 'option');
    $cream_texture = lx_get_image_url('lx_cream_image', '', 'full', 'option');
    $cert_img      = lx_get_image_url('lx_cert_image', '', 'full', 'option');
    $stamp_img         = lx_get_image_url('lx_stamp_image', '', 'full', 'option');
    $guide_video       = lx_get_field('lx_guide_video', 'option', '');
    $guide_video_thumb = lx_get_image_url('lx_guide_video_thumb', '', 'full', 'option');

    // Rash Slides
    $raw_rash = lx_get_field('lx_rash_slides', 'option', array());
    $rash_slides = array();
    if (!empty($raw_rash) && is_array($raw_rash)) {
        foreach ($raw_rash as $s) {
            $src = is_array($s['image'] ?? null) ? ($s['image']['url'] ?? '') : ($s['image'] ?? '');
            $rash_slides[] = array('src' => $src, 'caption' => $s['caption'] ?? '');
        }
    }
    if (empty($rash_slides)) {
        $rash_slides = array(
            array('src' => '', 'caption' => ''),
            array('src' => '', 'caption' => ''),
            array('src' => '', 'caption' => ''),
            array('src' => '', 'caption' => ''),
            array('src' => '', 'caption' => ''),
        );
    }

    // Feedback Slides
    $raw_feedback = lx_get_field('lx_feedback_slides', 'option', array());
    $feedback = array();
    if (!empty($raw_feedback) && is_array($raw_feedback)) {
        foreach ($raw_feedback as $f) {
            $src = is_array($f) ? ($f['url'] ?? '') : $f;
            if ($src) {
                $feedback[] = $src;
            }
        }
    }
    if (empty($feedback)) {
        $feedback = array('', '', '');
    }

    // Testimonial Videos
    $raw_videos = lx_get_field('lx_video_slides', 'option', array());
    $testimonial_videos = array();
    if (!empty($raw_videos) && is_array($raw_videos)) {
        foreach ($raw_videos as $v) {
            $src   = '';
            $thumb = '';
            if (is_array($v)) {
                $src = !empty($v['video_url']) ? trim($v['video_url']) : '';
                if (!empty($v['video_thumb'])) {
                    if (is_array($v['video_thumb']) && !empty($v['video_thumb']['url'])) {
                        $thumb = $v['video_thumb']['url'];
                    } elseif (is_numeric($v['video_thumb'])) {
                        $thumb = wp_get_attachment_image_url($v['video_thumb'], 'large');
                    } elseif (is_string($v['video_thumb'])) {
                        $thumb = trim($v['video_thumb']);
                    }
                }
            } elseif (is_string($v)) {
                $src = trim($v);
            }
            if ($src) {
                $testimonial_videos[] = array(
                    'src'   => $src,
                    'thumb' => $thumb,
                );
            }
        }
    }
    if (empty($testimonial_videos)) {
        $testimonial_videos = array(
            array('src' => '', 'thumb' => ''),
            array('src' => '', 'thumb' => ''),
        );
    }

    // Seminar Slides
    $raw_seminar = lx_get_field('lx_seminar_slides', 'option', array());
    $seminar_slides = array();
    if (!empty($raw_seminar) && is_array($raw_seminar)) {
        foreach ($raw_seminar as $s) {
            $src = is_array($s['image'] ?? null) ? ($s['image']['url'] ?? '') : ($s['image'] ?? '');
            $seminar_slides[] = array('src' => $src, 'caption' => $s['caption'] ?? '');
        }
    }
    if (empty($seminar_slides)) {
        $seminar_slides = array(
            array('src' => '', 'caption' => ''),
            array('src' => '', 'caption' => ''),
            array('src' => '', 'caption' => ''),
        );
    }

    $bonbon_config = array(
        'hotline'            => $hotline,
        'hotlineDisplay'     => $hotline_display,
        'zalo'               => $zalo,
        'productName'        => $product_name,
        'price'              => $price,
        'listPrice'          => $list_price,
        'offerMinutes'       => $offer_minutes,
        'packages'           => $packages,
        'orderNotifications' => array(
            'enabled' => (bool)$notif_enabled,
            'items'   => $notif_items,
        ),
        'sheetEndpoint'      => $sheet_endpoint,
        'facebookPixelId'    => $fb_pixel,
        'googleAnalyticsId'  => $ga_id,
        'tiktokPixelId'      => $tiktok_pixel,
        'media'              => array(
            'heroImage'         => $hero_img,
            'rashSlides'        => $rash_slides,
            'motherBaby'        => $mother_baby,
            'creamTexture'      => $cream_texture,
            'certificate'       => $cert_img,
            'antiFakeStamp'     => $stamp_img,
            'feedback'          => $feedback,
            'testimonialVideos' => $testimonial_videos,
            'seminarSlides'     => $seminar_slides,
            'guideVideo'        => array(
                'src'   => $guide_video,
                'thumb' => $guide_video_thumb,
            ),
            'reviewImages'      => array(array('', ''), array('', '')),
        ),
    );

    wp_localize_script('lx-landing-main', 'BONBON_CONFIG', $bonbon_config);
}
add_action('wp_enqueue_scripts', 'lx_enqueue_scripts');

/**
 * Output Google Analytics 4 (gtag.js) in head
 */
function lx_print_google_analytics() {
    $ga_id = lx_get_field('lx_google_analytics_id', 'option', 'G-DS38TS5KPW');
    if (empty($ga_id)) {
        $ga_id = 'G-DS38TS5KPW';
    }
    $ga_id = trim(strip_tags($ga_id));
    if (!empty($ga_id)) {
        ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_id; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo $ga_id; ?>');
</script>
        <?php
    }
}
add_action('wp_head', 'lx_print_google_analytics', 1);
