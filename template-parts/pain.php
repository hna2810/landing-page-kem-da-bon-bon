<?php
/**
 * Template part: Pain Section (Nỗi lo)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$pain_title = lx_get_field('lx_pain_title', 'option', '<span class="nw">Mẹ lo lắng mỗi lần thấy</span><br><span class="hl nw">da con đỏ ửng</span>');
$pain_alert = lx_get_field('lx_pain_alert', 'option', 'Đừng để tình trạng hăm kéo dài, Mẹ ạ!');

$raw_items = lx_get_field('lx_pain_items', 'option', array());
$items = array();
if (!empty($raw_items) && is_array($raw_items)) {
    foreach ($raw_items as $item) {
        $icon = $item['icon'] ?? '😣';
        $text = $item['text'] ?? '';
        if ($text) {
            $items[] = array('icon' => $icon, 'text' => $text);
        }
    }
}
if (empty($items)) {
    $items = array(
        array('icon' => '😣', 'text' => 'Vùng da mông, bẹn ửng đỏ khiến bé khó chịu, quấy khóc'),
        array('icon' => '🔁', 'text' => 'Bé bị hăm kéo dài, tái phát'),
        array('icon' => '🤲', 'text' => 'Bé bị hăm vùng nếp gấp, kẽ tay, kẽ chân'),
    );
}
?>
<!-- 2. NỖI LO -->
<section class="sec tint" id="pain">
    <div class="container split">
        <div>
            <div class="sec-head left-pc reveal">
                <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-kitten.webp" alt="" class="mascot l" aria-hidden="true">
                <h2 class="sec-title"><?php echo $pain_title; ?></h2>
            </div>
            <ul class="pain-list">
                <?php foreach ($items as $item) : ?>
                    <li class="reveal"><span><?php echo $item['icon']; ?></span> <?php echo $item['text']; ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="alert-line reveal">
                <span class="alert-icon">⚠️</span>
                <span><?php echo $pain_alert; ?></span>
            </div>
        </div>
        <div class="slider reveal" data-slider="rashSlides" data-ratio="ratio-43" data-label="Ảnh vị trí bé bị hăm"></div>
    </div>
</section>
