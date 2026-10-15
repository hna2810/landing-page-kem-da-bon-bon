<?php
/**
 * Template part: Brand Story Section
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$story_title = lx_get_field('lx_story_title', 'option', 'YÊU CON -<br><span class="hl">MẸ CHỌN BON BON</span>');

$default_story_paragraph = 'Bon Bon đồng hành cùng mẹ từ những ngày đầu đời của bé, từ tắm bé, massage đến chăm sóc da và rốn tại nhà và trung tâm. Với sự chăm chút nhẹ nhàng và các thành phần được lựa chọn phù hợp, Bon Bon cùng mẹ nâng niu làn da non nớt, chăm sóc bé yêu mỗi ngày. Bon Bon còn đồng hành cùng mẹ tại các hội thảo tiền sản và bệnh viện Sản – Nhi trên khắp ba miền Bắc – Trung – Nam. Bon Bon - Dịu dàng chăm bé, trọn vẹn yêu thương.';

$story_paragraph = lx_get_field('lx_story_paragraph', 'option', $default_story_paragraph);
if (empty($story_paragraph)) {
    $story_paragraph = $default_story_paragraph;
}
?>
<!-- 12. CÂU CHUYỆN THƯƠNG HIỆU -->
<section class="sec story" id="story">
    <div class="container">
        <div class="story-hero reveal">
            <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-bear.webp" alt="" class="story-mascot" aria-hidden="true">
            <div class="sec-head">
                <h2 class="sec-title"><?php echo $story_title; ?></h2>
            </div>
        </div>

        <!-- Khung nền trắng, chữ xanh, hiệu ứng chữ gõ từ từ -->
        <div class="story-typewriter-box reveal" id="storyTypewriterBox" data-text="<?php echo esc_attr($story_paragraph); ?>">
            <span class="story-card-decor-quote" aria-hidden="true">“</span>
            <p class="story-typewriter-text" id="storyTypewriterTarget">
                <span class="typewriter-text"><?php echo esc_html($story_paragraph); ?></span><span class="typewriter-cursor" aria-hidden="true">|</span>
            </p>
        </div>
    </div>
</section>
