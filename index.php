<?php
/**
 * Main Landing Page Template
 *
 * @package LXLanding
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// 1. Hero: bên trái text, bên phải ảnh trước - sau
get_template_part('template-parts/hero');

// 2. Nỗi lo
get_template_part('template-parts/pain');

// 3. Giải pháp
get_template_part('template-parts/solution');

// 4. Thành phần dịu nhẹ & 5 Không
get_template_part('template-parts/ingredients');

// 5. An tâm sử dụng
get_template_part('template-parts/trust');

// 6. Hàng ngàn mẹ tin dùng (Feedback & Video)
get_template_part('template-parts/feedback');

// 7. Bon Bon trên khắp 3 miền
get_template_part('template-parts/regions');

// 8. Chăm sóc da bé nhẹ nhàng với 3 bước
get_template_part('template-parts/steps');

// 9. Thông tin sản phẩm + CTA
get_template_part('template-parts/product-info');

// 10. Form ưu đãi tặng riêng cho mẹ (Contact Form 7)
get_template_part('template-parts/form-order');

// 11. FAQ
get_template_part('template-parts/faq');

// 12. Câu chuyện thương hiệu
get_template_part('template-parts/story');

// 13. Đánh giá sản phẩm
get_template_part('template-parts/reviews');

get_footer();
