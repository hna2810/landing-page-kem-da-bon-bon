<?php
/**
 * Template part: Trust Section (An tâm)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$trust_title = lx_get_field('lx_trust_title', 'option', 'An tâm <span class="hl">sử dụng</span>');
$cert_text   = lx_get_field('lx_cert_text', 'option', '80/26/CBMP-NB');
$stamp_text  = lx_get_field('lx_stamp_text', 'option', 'Bộ Công An');
$cert_img    = lx_get_image_url('lx_cert_image', '', 'full', 'option');
$stamp_img   = lx_get_image_url('lx_stamp_image', '', 'full', 'option');
?>
<!-- 5. AN TÂM -->
<section class="sec" id="trust">
    <div class="container">
        <div class="sec-head reveal">
            <h2 class="sec-title"><?php echo $trust_title; ?></h2>
        </div>
        <div class="duo trust-duo reveal">
            <figure>
                <div class="media ratio-34" data-media="certificate" data-label="Ảnh giấy công bố">
                    <?php if ($cert_img) : ?>
                        <img src="<?php echo $cert_img; ?>" alt="Ảnh giấy công bố" loading="lazy">
                    <?php endif; ?>
                </div>
                <figcaption>Giấy công bố<small><?php echo $cert_text; ?></small></figcaption>
            </figure>
            <figure>
                <div class="media ratio-34" data-media="antiFakeStamp" data-label="Ảnh tem chống hàng giả">
                    <?php if ($stamp_img) : ?>
                        <img src="<?php echo $stamp_img; ?>" alt="Ảnh tem chống hàng giả" loading="lazy">
                    <?php endif; ?>
                </div>
                <figcaption>Tem chống hàng giả<small><?php echo $stamp_text; ?></small></figcaption>
            </figure>
        </div>
    </div>
</section>
