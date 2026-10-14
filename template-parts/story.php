<?php
/**
 * Template part: Brand Story Section
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$story_title   = lx_get_field('lx_story_title', 'option', 'YÊU CON -<br><span class="hl">MẸ CHỌN BON BON</span>');
$story_tagline = lx_get_field('lx_story_tagline', 'option', 'Bon Bon - Dịu dàng chăm bé, trọn vẹn yêu thương.');

$raw_steps = lx_get_field('lx_story_steps', 'option', array());
$story_steps = array();
if (!empty($raw_steps) && is_array($raw_steps)) {
    foreach ($raw_steps as $s) {
        $icon = $s['icon'] ?? '🌿';
        $text = $s['text'] ?? '';
        if ($text) {
            $story_steps[] = array('icon' => $icon, 'text' => $text);
        }
    }
}
if (empty($story_steps)) {
    $story_steps = array(
        array('icon' => '🛁', 'text' => 'Bon Bon đồng hành cùng mẹ từ những ngày đầu đời của bé, từ tắm bé, massage đến chăm sóc da và rốn tại nhà và trung tâm.'),
        array('icon' => '🌿', 'text' => 'Với sự chăm chút nhẹ nhàng và các thành phần được lựa chọn phù hợp, Bon Bon cùng mẹ nâng niu làn da non nớt, chăm sóc bé yêu mỗi ngày.'),
        array('icon' => '🏥', 'text' => 'Bon Bon còn đồng hành cùng mẹ tại các hội thảo tiền sản và bệnh viện Sản – Nhi trên khắp ba miền Bắc – Trung – Nam.'),
    );
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
        <ol class="story-steps">
            <?php foreach ($story_steps as $step) : ?>
                <li class="reveal">
                    <span class="story-icon" aria-hidden="true"><?php echo $step['icon']; ?></span>
                    <p><?php echo $step['text']; ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
        <p class="story-tagline reveal"><span><?php echo $story_tagline; ?></span></p>
    </div>
</section>
