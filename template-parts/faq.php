<?php
/**
 * Template part: FAQ Section
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$faq_title = lx_get_field('lx_faq_title', 'option', 'Mẹ còn băn khoăn điều gì về <span class="hl">Kem Hăm Bon Bon?</span>');

$raw_faqs = lx_get_field('lx_faqs', 'option', array());
$faqs = array();
if (!empty($raw_faqs) && is_array($raw_faqs)) {
    foreach ($raw_faqs as $f) {
        $q = $f['question'] ?? '';
        $a = $f['answer'] ?? '';
        if ($q && $a) {
            $faqs[] = array('q' => $q, 'a' => $a);
        }
    }
}
if (empty($faqs)) {
    $faqs = array(
        array('q' => 'Kem Da Bon Bon có chứa Corticoid không?', 'a' => 'Sản phẩm không chứa Corticoid, Paraben và hương liệu.'),
        array('q' => 'Mẹ sử dụng kem như thế nào?', 'a' => 'Làm sạch, nhẹ nhàng lau khô vùng da cần chăm sóc rồi thoa một lớp kem mỏng, ngày bôi 2 - 3 lần.'),
        array('q' => 'Khi nào mẹ nên đưa bé đi khám?', 'a' => 'Nếu vùng da tổn thương nặng, có mụn mủ, rỉ dịch, bé sốt hoặc tình trạng không cải thiện, mẹ nên đưa bé đi khám.'),
    );
}
?>
<!-- 11. FAQ -->
<section class="sec tint" id="faq">
    <div class="container faq-wrap">
        <div class="sec-head reveal">
            <h2 class="sec-title"><?php echo $faq_title; ?></h2>
        </div>
        <div class="faq">
            <?php foreach ($faqs as $faq) : ?>
                <details class="reveal">
                    <summary><?php echo $faq['q']; ?></summary>
                    <p><?php echo $faq['a']; ?></p>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
