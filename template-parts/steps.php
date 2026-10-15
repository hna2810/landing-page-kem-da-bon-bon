<?php
/**
 * Template part: 3 Steps How-to & Guide Video
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$steps_title = lx_get_field('lx_steps_title', 'option', 'Chăm sóc da bé nhẹ nhàng <span class="hl">với 3 bước</span>');
$guide_video_thumb = lx_get_image_url('lx_guide_video_thumb', '', 'full', 'option');

$raw_steps = lx_get_field('lx_steps', 'option', array());
$steps = array();
if (!empty($raw_steps) && is_array($raw_steps)) {
    foreach ($raw_steps as $s) {
        $text = $s['step_text'] ?? '';
        if ($text) {
            $steps[] = $text;
        }
    }
}
if (empty($steps)) {
    $steps = array(
        'Vệ sinh và lau khô vùng da cần chăm sóc.',
        'Dùng tăm bông lấy một lượng kem vừa đủ, bôi trực tiếp lên vùng da bị hăm.',
        'Sử dụng đều đặn 2-3 lần/ngày.',
    );
}
?>
<!-- 8. 3 BƯỚC -->
<section class="sec tint" id="howto">
    <div class="container split">
        <div>
            <div class="sec-head left-pc reveal">
                <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-elephant.webp" alt="" class="mascot l" aria-hidden="true">
                <h2 class="sec-title"><?php echo $steps_title; ?></h2>
            </div>
            <ol class="steps">
                <?php foreach ($steps as $st) : ?>
                    <li class="reveal"><?php echo $st; ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
        <div class="video-wrap reveal">
            <div class="media ratio-916" data-video="guideVideo" data-label="Video hướng dẫn sử dụng"<?php echo !empty($guide_video_thumb) ? ' data-thumb="' . esc_url($guide_video_thumb) . '"' : ''; ?>></div>
        </div>
    </div>
</section>
