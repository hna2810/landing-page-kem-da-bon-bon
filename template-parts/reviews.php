<?php
/**
 * Template part: Reviews Section (Đánh giá sản phẩm)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$rating_num   = lx_get_field('lx_rating_num', 'option', '4.9');
$chip_all     = lx_get_field('lx_chip_all_label', 'option', 'Tất cả');
$chip_5       = lx_get_field('lx_chip_5star_label', 'option', '5 Sao (1.6K)');
$chip_4       = lx_get_field('lx_chip_4star_label', 'option', '4 Sao (55)');
$chip_3       = lx_get_field('lx_chip_3star_label', 'option', '3 Sao (12)');
$chip_comment = lx_get_field('lx_chip_comment_label', 'option', 'Có Bình Luận (684)');
$chip_media   = lx_get_field('lx_chip_media_label', 'option', 'Có Hình Ảnh / Video (572)');

$custom_reviews = lx_get_field('lx_reviews_list', 'option', array());

$default_reviews = array(
    array(
        'name'        => 'n*****',
        'avatar_type' => 'text',
        'avatar_text' => 'N',
        'stars'       => '5',
        'date'        => '2 ngày trước',
        'comment'     => 'Chất kem mịn, dễ thấm, bé dùng thấy đỡ đỏ, mềm hơn. Sẽ mua lại nè!',
        'gallery'     => array(
            'https://kemham.bonbonhomecare.vn/wp-content/uploads/2026/10/feedback-truoc-sau-kem-boi-ham-cho-be-bonbon-homecare-1.png',
        ),
    ),
    array(
        'name'        => 'Thu Trang (Mẹ Bắp)',
        'avatar_type' => 'text',
        'avatar_text' => 'T',
        'stars'       => '5',
        'date'        => 'Hôm qua',
        'comment'     => 'Kem mịn, không bết dính, mùi thảo dược dịu nhẹ. Bé nhà mình bôi tối hôm trước sáng hôm sau mông bớt hăm đỏ hẳn. Đóng gói cẩn thận, giao nhanh.',
        'gallery'     => array(
            'https://kemham.bonbonhomecare.vn/wp-content/uploads/2026/10/feedback-truoc-sau-kem-boi-ham-cho-be-bonbon-homecare-2.png',
            'https://kemham.bonbonhomecare.vn/wp-content/uploads/2026/10/feedback-truoc-sau-kem-boi-ham-cho-be-bonbon-homecare-3.png',
        ),
    ),
    array(
        'name'        => 'Mẹ Ken',
        'avatar_type' => 'text',
        'avatar_text' => 'K',
        'stars'       => '5',
        'date'        => '3 ngày trước',
        'comment'     => 'Đã dùng sang hũ thứ 2 rồi. Thành phần tự nhiên an toàn nên rất yên tâm bôi cho con gái nhỏ. Shop tư vấn rất nhiệt tình!',
        'gallery'     => array(),
    ),
    array(
        'name'        => 'Hồng Nhung',
        'avatar_type' => 'text',
        'avatar_text' => 'H',
        'stars'       => '4',
        'date'        => '1 tuần trước',
        'comment'     => 'Giao hàng nhanh, kem thơm nhẹ dễ thoa, bôi 2 ngày là đỡ hăm nhiều. Hũ 5g dùng nhanh hết, mong shop ra thêm hũ to hơn nữa nha.',
        'gallery'     => array(
            'https://kemham.bonbonhomecare.vn/wp-content/uploads/2026/10/feedback-truoc-sau-kem-boi-ham-cho-be-bonbon-homecare-4.png',
        ),
    ),
    array(
        'name'        => 'Minh Anh',
        'avatar_type' => 'text',
        'avatar_text' => 'M',
        'stars'       => '3',
        'date'        => '2 tuần trước',
        'comment'     => 'Sản phẩm dùng tốt, bé bớt đỏ rát sau 2 hôm. Trừ 1 sao do đơn vị vận chuyển giao hơi chậm mất 3 ngày mới tới.',
        'gallery'     => array(),
    ),
);

$reviews_to_display = (!empty($custom_reviews) && is_array($custom_reviews)) ? $custom_reviews : $default_reviews;
?>
<!-- 13. ĐÁNH GIÁ SẢN PHẨM -->
<section class="sec tint" id="reviews">
    <div class="container">
        <div class="sec-head reveal">
            <h2 class="sec-title">ĐÁNH GIÁ <span class="hl">SẢN PHẨM</span></h2>
        </div>
        <div class="rating-box reveal">
            <div class="rating-top">
                <div class="rating-num"><?php echo esc_html($rating_num); ?> <small>trên 5</small></div>
                <div class="stars">★★★★★</div>
            </div>
            <div class="chips" role="tablist" aria-label="<?php esc_attr_e('Bộ lọc đánh giá', 'lx-landing'); ?>">
                <button type="button" class="chip on" data-filter="all" role="tab" aria-selected="true"><?php echo esc_html($chip_all); ?></button>
                <button type="button" class="chip" data-filter="5" role="tab" aria-selected="false"><?php echo esc_html($chip_5); ?></button>
                <button type="button" class="chip" data-filter="4" role="tab" aria-selected="false"><?php echo esc_html($chip_4); ?></button>
                <button type="button" class="chip" data-filter="3" role="tab" aria-selected="false"><?php echo esc_html($chip_3); ?></button>
                <button type="button" class="chip" data-filter="comment" role="tab" aria-selected="false"><?php echo esc_html($chip_comment); ?></button>
                <button type="button" class="chip" data-filter="media" role="tab" aria-selected="false"><?php echo esc_html($chip_media); ?></button>
            </div>
        </div>

        <div class="reviews-list">
            <?php foreach ($reviews_to_display as $index => $rev):
                $name        = !empty($rev['name']) ? $rev['name'] : __('Khách hàng', 'lx-landing');
                $stars_val   = isset($rev['stars']) ? intval($rev['stars']) : 5;
                if ($stars_val < 1 || $stars_val > 5) {
                    $stars_val = 5;
                }
                $stars_str   = str_repeat('★', $stars_val) . str_repeat('☆', 5 - $stars_val);
                $date_val    = !empty($rev['date']) ? $rev['date'] : '';
                $comment_val = !empty($rev['comment']) ? trim($rev['comment']) : '';
                
                // Avatar handling
                $avatar_type = !empty($rev['avatar_type']) ? $rev['avatar_type'] : 'text';
                $avatar_text = !empty($rev['avatar_text']) ? $rev['avatar_text'] : mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8');
                $avatar_img  = '';
                if ($avatar_type === 'image' && !empty($rev['avatar_img'])) {
                    if (is_array($rev['avatar_img']) && !empty($rev['avatar_img']['url'])) {
                        $avatar_img = $rev['avatar_img']['url'];
                    } elseif (is_string($rev['avatar_img'])) {
                        $avatar_img = $rev['avatar_img'];
                    }
                }

                // Images/Gallery handling
                $images = array();
                if (!empty($rev['gallery'])) {
                    $raw_gallery = is_array($rev['gallery']) ? $rev['gallery'] : array($rev['gallery']);
                    foreach ($raw_gallery as $item) {
                        if (is_string($item) && !empty($item)) {
                            $images[] = $item;
                        } elseif (is_array($item) && !empty($item['url'])) {
                            $images[] = $item['url'];
                        } elseif (is_numeric($item)) {
                            $src = wp_get_attachment_image_url($item, 'large');
                            if ($src) {
                                $images[] = $src;
                            }
                        }
                    }
                }

                $has_comment = !empty($comment_val);
                $has_media   = !empty($images);
            ?>
                <div class="review reveal" 
                     data-stars="<?php echo esc_attr($stars_val); ?>" 
                     data-has-comment="<?php echo $has_comment ? '1' : '0'; ?>" 
                     data-has-media="<?php echo $has_media ? '1' : '0'; ?>">
                    <div class="review-head">
                        <?php if (!empty($avatar_img)): ?>
                            <div class="avatar avatar-img"><img src="<?php echo esc_url($avatar_img); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy"></div>
                        <?php else: ?>
                            <div class="avatar"><?php echo esc_html($avatar_text); ?></div>
                        <?php endif; ?>
                        <div class="review-meta">
                            <div class="review-name"><?php echo esc_html($name); ?></div>
                            <div class="review-sub">
                                <span class="stars" style="font-size:13px"><?php echo esc_html($stars_str); ?></span>
                                <?php if (!empty($date_val)): ?>
                                    <span class="review-date"><?php echo esc_html($date_val); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($comment_val)): ?>
                        <p><?php echo nl2br(esc_html($comment_val)); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($images)): ?>
                        <div class="review-imgs">
                            <?php foreach ($images as $img_url): ?>
                                <div class="review-img-item" data-full="<?php echo esc_url($img_url); ?>">
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(sprintf(__('Ảnh feedback từ %s', 'lx-landing'), $name)); ?>" loading="lazy">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="reviews-empty" id="reviewsEmpty" style="display: none;">
            <div class="reviews-empty-icon">💬</div>
            <p><?php esc_html_e('Chưa có đánh giá nào phù hợp với bộ lọc này.', 'lx-landing'); ?></p>
        </div>
    </div>
</section>
