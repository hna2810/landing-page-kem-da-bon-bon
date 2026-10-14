<?php
/**
 * Template part: Reviews Section (Đánh giá sản phẩm)
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

$rating_num = lx_get_field('lx_rating_num', 'option', '4.9');
?>
<!-- 13. ĐÁNH GIÁ -->
<section class="sec tint" id="reviews">
    <div class="container">
        <div class="sec-head reveal">
            <h2 class="sec-title">ĐÁNH GIÁ <span class="hl">SẢN PHẨM</span></h2>
        </div>
        <div class="rating-box reveal">
            <div class="rating-top">
                <div class="rating-num"><?php echo $rating_num; ?> <small>trên 5</small></div>
                <div class="stars">★★★★★</div>
            </div>
            <div class="chips">
                <span class="on">Tất cả</span><span>5 Sao (1.6K)</span><span>4 Sao (55)</span><span>3 Sao (12)</span>
                <span>Có Bình Luận (684)</span><span>Có Hình Ảnh / Video (572)</span>
            </div>
        </div>
        <div class="reviews-list">
            <div class="review reveal">
                <div class="review-head">
                    <div class="avatar">N</div>
                    <div><div class="review-name">n*****</div><div class="stars" style="font-size:13px">★★★★★</div></div>
                </div>
                <p>Chất kem mịn, dễ thấm, bé dùng thấy đỡ đỏ, mềm hơn. Sẽ mua lại nè!</p>
                <div class="review-imgs" data-list="reviewImages.0" data-ratio="ratio-1" data-label="Ảnh"></div>
            </div>
            <div class="review reveal">
                <div class="review-head">
                    <div class="avatar">T</div>
                    <div><div class="review-name">T*****</div><div class="stars" style="font-size:13px">★★★★★</div></div>
                </div>
                <p>Kem mịn, không bết, mùi dịu nhẹ. Đóng gói cẩn thận, giao nhanh.</p>
                <div class="review-imgs" data-list="reviewImages.1" data-ratio="ratio-1" data-label="Ảnh"></div>
            </div>
        </div>
    </div>
</section>
