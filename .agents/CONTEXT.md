# Data Context

## Custom Post Types (CPT)
- `lx_form`: Quản lý các form liên hệ (ẩn frontend). Render qua Contact Form 7.

## ACF Fields
- **LX Form Builder** (`group_lx_form_builder`): Dành cho CPT `lx_form`.
- **Theme Settings** (`group_lx_theme_options`): Nằm ở Options Page (`theme-settings`). Chứa cấu hình Global:
  - Font chữ (`lx_primary_font`)
  - Màu chủ đạo (`lx_primary_color`)
  - CSS Buttons (`lx_btn_*`)
  - Floating actions & Chatbot
- **WooCommerce** (`inc-woo/acf-config.php`): Field cho Product/Category.
- **Polylang Sync**: Bật translatable cho Options Page & CPT.

## Elementor Widgets (130+ Widgets)
Các widget được tự động quét và khai báo (auto-load), danh sách ID được quản lý tại `widgets/widget-library.md`.
Danh sách Category hiện có:
`lx_banner`, `lx_menu`, `lx_gioi_thieu`, `lx_gia_tri`, `lx_so_lieu`, `lx_khach_hang`, `lx_doi_tac`, `lx_van_de`, `lx_giai_phap`, `lx_loi_ich`, `lx_tinh_nang`, `lx_dich_vu`, `lx_du_an`, `lx_quy_trinh`, `lx_danh_gia`, `lx_doi_ngu`, `lx_bang_gia`, `lx_faqs`, `lx_cta`, `lx_lien_he`, `lx_archive`, `lx_single`, `lx_tin_tuc`, `lx_footer`, `lx_404`, `lx_sidebar`, `lx_mo_ta`, `lx_woocommerce`

**Quy tắc Widget**: Đặt tại `widgets/template/{category_slug}/{category_slug}_v{version}.php` (Dùng snake_case. Ví dụ: `lx_banner_v1.php`).

## Cấu trúc Widget (Bắt buộc)
Bọc trong `.lx_wrap > .lx_con` & dùng Helper:
```php
<section class="lx_wrap"><div class="lx_con">
    <?php lx_render_widget_header($title, $desc, $args); ?>
    <div class="row lx_g32">
        <div class="col-xl-4"><!-- Content --></div>
    </div>
    <?php echo lx_render_button($btn_text, $link, 'lx_btn_primary'); ?>
</div></section>
```
