<?php
/**
 * Template part: Ingredients Section (Thành phần & 5 Không)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$ingr_title = lx_get_field('lx_ingr_title', 'option', 'THÀNH PHẦN <span class="hl">DỊU NHẸ</span>');
$ingr_sub   = lx_get_field('lx_ingr_sub', 'option', 'Phù hợp với trẻ sơ sinh & trẻ nhỏ Việt');
$five_no_str = lx_get_field('lx_five_no', 'option', 'Không Corticoid, Không Paraben, Không hương liệu, Không bết rít, Không để lại vệt trên da');
$five_nos    = array_map('trim', explode(',', $five_no_str));
$cream_img   = lx_get_image_url('lx_cream_image', 'option', '');

$default_ingrs = array(
    array('icon' => '🛡️', 'name' => 'Petrolatum', 'desc' => 'Tạo lớp màng bảo vệ da, hạn chế mất nước'),
    array('icon' => '🥑', 'name' => 'Dầu bơ, dầu nghệ', 'desc' => 'Làm mềm da, hỗ trợ duy trì độ ẩm'),
    array('icon' => '🌾', 'name' => 'Tinh bột sắn', 'desc' => 'Hấp thụ độ ẩm dư thừa trên bề mặt da'),
    array('icon' => '💧', 'name' => 'PVP', 'desc' => 'Tạo lớp màng bảo vệ da'),
    array('icon' => '✨', 'name' => 'Nano bạc', 'desc' => 'Giảm viêm, xoa dịu vết hăm tã, hăm da'),
);
?>
<!-- 4. THÀNH PHẦN -->
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
                </div>
                <div class="five-no reveal">
                    <h3>5 KHÔNG</h3>
                    <div class="five-no-grid">
                        <?php foreach ($five_nos as $no) : if ($no) : ?>
                            <span>🚫 <?php echo $no; ?></span>
                        <?php endif; endforeach; ?>
                    </div>
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
