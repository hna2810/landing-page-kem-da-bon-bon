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

$default_story_paragraph = 'Bon Bon đồng hành cùng mẹ từ những ngày đầu đời của bé, từ tắm bé, massage đến chăm sóc da và rốn tại nhà và trung tâm. Với sự chăm chút nhẹ nhàng và các thành phần được lựa chọn phù hợp, Bon Bon cùng mẹ nâng niu làn da non nớt, chăm sóc bé yêu mỗi ngày. Bon Bon còn đồng hành cùng mẹ tại các hội thảo tiền sản và bệnh viện Sản – Nhi trên khắp ba miền Bắc – Trung – Nam.';
$default_story_highlight = 'Bon Bon - Dịu dàng chăm bé, trọn vẹn yêu thương.';

$raw_story_paragraph = lx_get_field('lx_story_paragraph', 'option', '');
$story_highlight     = lx_get_field('lx_story_tagline', 'option', $default_story_highlight);
if (empty($story_highlight)) {
    $story_highlight = $default_story_highlight;
}

if (!empty($raw_story_paragraph)) {
    // Tự động tách câu slogan nếu người dùng có gõ chung vào ô đoạn văn
    if (mb_strpos($raw_story_paragraph, $story_highlight) !== false) {
        $story_paragraph = trim(str_replace($story_highlight, '', $raw_story_paragraph));
    } else {
        $story_paragraph = trim($raw_story_paragraph);
    }
} else {
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
        <div class="story-typewriter-box reveal" id="storyTypewriterBox" 
             data-body="<?php echo esc_attr($story_paragraph); ?>" 
             data-highlight="<?php echo esc_attr($story_highlight); ?>">
            <span class="story-card-decor-quote" aria-hidden="true">“</span>
            <div class="story-typewriter-inner">
                <p class="story-typewriter-text" id="storyTypewriterTarget">
                    <span class="typewriter-text"><?php echo esc_html($story_paragraph); ?></span>
                </p>
                <div class="story-typewriter-highlight" id="storyTypewriterHighlight">
                    <strong class="typewriter-highlight-text"><?php echo esc_html($story_highlight); ?></strong>
                    <span class="typewriter-cursor" aria-hidden="true">|</span>
                </div>
            </div>
        </div>
    </div>
</section>
