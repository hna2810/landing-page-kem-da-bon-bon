<?php
/**
 * Template part: Feedback Section
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$feedback_title = lx_get_field('lx_feedback_title', 'option', 'Hàng ngàn mẹ <span class="hl">tin dùng</span>');
?>
<!-- 6. FEEDBACK -->
<section class="sec tint" id="feedback">
    <div class="container">
        <div class="sec-head reveal">
            <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-puppy.webp" alt="" class="mascot l" aria-hidden="true">
            <h2 class="sec-title"><?php echo $feedback_title; ?></h2>
        </div>
        <div class="split">
            <div>
                <p class="label-line">Hình ảnh feedback</p>
                <div class="slider reveal" data-slider="feedback" data-ratio="ratio-34" data-label="Ảnh feedback"></div>
            </div>
            <div>
                <p class="label-line">Nghe các mẹ chia sẻ</p>
                <div class="slider videos reveal" data-slider="testimonialVideos" data-kind="video" data-ratio="ratio-916" data-label="Video các mẹ chia sẻ"></div>
            </div>
        </div>
    </div>
</section>
