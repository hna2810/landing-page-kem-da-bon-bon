<?php
/**
 * Template part: Ingredients Section (Thành phần & 5 Không)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$ingr_title = lx_get_field('lx_ingr_title', 'option', '5 ƯU ĐIỂM <span class="hl">VƯỢT TRỘI</span>');
if ($ingr_title === 'THÀNH PHẦN <span class="hl">DỊU NHẸ</span>' || $ingr_title === 'THÀNH PHẦN DỊU NHẸ' || empty($ingr_title)) {
    $ingr_title = '5 ƯU ĐIỂM <span class="hl">VƯỢT TRỘI</span>';
}
$ingr_sub   = lx_get_field('lx_ingr_sub', 'option', 'Phù hợp với trẻ sơ sinh & trẻ nhỏ Việt');
$price       = lx_format_price(lx_get_field('lx_price', 'option', 49000));
$cream_img   = lx_get_image_url('lx_cream_image', '', 'full', 'option');

$default_ingrs = array(
    array('icon' => '🛡️', 'name' => 'Petrolatum', 'desc' => 'Tạo lớp màng bảo vệ da, hạn chế mất nước'),
    array('icon' => '🥑', 'name' => 'Dầu bơ, dầu nghệ', 'desc' => 'Làm mềm da, hỗ trợ duy trì độ ẩm'),
    array('icon' => '🌾', 'name' => 'Tinh bột sắn', 'desc' => 'Hấp thụ độ ẩm dư thừa trên bề mặt da'),
    array('icon' => '💧', 'name' => 'PVP', 'desc' => 'Tạo lớp màng bảo vệ da'),
    array('icon' => '✨', 'name' => 'Nano bạc', 'desc' => 'Giảm viêm, xoa dịu vết hăm tã, hăm da'),
);
?>
<!-- 4. THÀNH PHẦN / 5 ƯU ĐIỂM VƯỢT TRỘI -->
<section class="sec tint" id="ingredients">
    <div class="container">
        <div class="sec-head reveal">
            <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-hippo.webp" alt="" class="mascot r" aria-hidden="true">
            <h2 class="sec-title"><?php echo $ingr_title; ?></h2>
            <p class="sec-sub"><?php echo $ingr_sub; ?></p>
        </div>
        <div class="split">
            <div>
                <div class="ingr">
                    <?php foreach ($default_ingrs as $ing) : ?>
                        <div class="ingr-item reveal">
                            <span class="ingr-icon"><?php echo $ing['icon']; ?></span>
                            <div>
                                <b><?php echo $ing['name']; ?></b>
                                <p><?php echo $ing['desc']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <a href="#order" class="ingr-item ingr-cta reveal js-buy">
                        <span class="ingr-icon">🎁</span>
                        <div class="ingr-cta-text">
                            <b>FREESHIP TOÀN QUỐC</b>
                            <p>Ưu đãi <strong class="js-price"><?php echo $price; ?></strong> — Mua ngay ➔</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="media ratio-1 reveal" data-media="creamTexture" data-label="Ảnh cận cảnh chất kem">
                <?php if ($cream_img) : ?>
                    <img src="<?php echo $cream_img; ?>" alt="Ảnh cận cảnh chất kem" loading="lazy">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
