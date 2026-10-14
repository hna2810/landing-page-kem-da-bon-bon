<?php
/**
 * Template part: Solution Section (Giải pháp)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$solution_title = lx_get_field('lx_solution_title', 'option', '<span class="hl">Bon Bon</span> – Nhẹ nhàng chăm sóc vùng da bé bị hăm');
$solution_desc  = lx_get_field('lx_solution_desc', 'option', 'Với kết cấu kem mịn cùng công thức an lành, Kem Da Bon Bon giúp mẹ chủ động chăm sóc da bé thoát khỏi hăm.');
$mother_baby    = lx_get_image_url('lx_solution_image', 'option', '');
?>
<!-- 3. GIẢI PHÁP -->
<section class="sec" id="solution">
    <div class="container split flip">
        <div>
            <div class="sec-head left-pc reveal">
                <h2 class="sec-title"><?php echo $solution_title; ?></h2>
            </div>
            <p class="lead reveal"><?php echo $solution_desc; ?></p>
        </div>
        <div class="media ratio-1 reveal" data-media="motherBaby" data-label="Ảnh mẹ ôm bé cầm hũ kem hăm">
            <?php if ($mother_baby) : ?>
                <img src="<?php echo $mother_baby; ?>" alt="Ảnh mẹ ôm bé cầm hũ kem hăm" loading="lazy">
            <?php endif; ?>
        </div>
    </div>
</section>
