<?php
/**
 * Template part: Feedback Section
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$feedback_title        = lx_get_field('lx_feedback_title', 'option', 'Hàng ngàn mẹ <span class="hl">tin dùng</span>');
$feedback_img_title    = lx_get_field('lx_feedback_img_title', 'option', 'Hình ảnh trước và sau khi sử dụng kem bôi hăm cho bé bon bon');
$feedback_video_title  = lx_get_field('lx_feedback_video_title', 'option', 'Nghe các mom chia sẻ sau khi sử dụng kem bôi hăm cho bé bon bon');
?>
<!-- 6. FEEDBACK -->
<section class="sec tint" id="feedback">
    <div class="container">
        <div class="sec-head reveal">
            <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-puppy.webp" alt="" class="mascot l" aria-hidden="true">
            <h2 class="sec-title"><?php echo $feedback_title; ?></h2>
        </div>
        
        <!-- Khối 1: Slide hình ảnh feedback ở trên -->
        <div class="feedback-block feedback-images-wrap">
            <h3 class="label-line"><?php echo esc_html($feedback_img_title); ?></h3>
            <div class="slider feedback-slider reveal" data-slider="feedback" data-ratio="ratio-1" data-label="Ảnh feedback"></div>
        </div>

        <!-- Khối 2: Slide video các mẹ chia sẻ ở dưới -->
        <div class="feedback-block feedback-videos-wrap">
            <h3 class="label-line"><?php echo esc_html($feedback_video_title); ?></h3>
            <div class="slider videos reveal" data-slider="testimonialVideos" data-kind="video" data-ratio="ratio-916" data-label="Video các mẹ chia sẻ"></div>
        </div>
    </div>
</section>
