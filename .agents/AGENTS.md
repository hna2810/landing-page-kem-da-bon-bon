# Stack
- WP Main Theme (Elementor Pro, ACF Pro 6.2)
- PHP 7.4+, SCSS, jQuery, Bootstrap Grid, Slick JS

# Core Rules
- Text Domain: `lx-landing` (chuỗi cố định, không dùng biến)
- Prefix CSS: `lx_` (snake_case)
- Prefix PHP: `lx_`
- File Encoding: UTF-8 without BOM (CRITICAL)
- Ngôn ngữ: UI/Label tiếng Việt, Code/Slug tiếng Anh
- Không dùng Browser Tool test UI
- Khi xuất nội dung soạn thảo bằng editor (WYSIWYG), bắt buộc bọc ngoài bằng class `lx_text_editor`.
- Không sử dụng thuộc tính style background-image (CSS background) để hiển thị hình ảnh chính/banner, mà bắt buộc sử dụng thẻ `<img>` hoặc các hàm WordPress in ra mã HTML của hình ảnh (như `the_post_thumbnail()`, `wp_get_attachment_image()`) để tối ưu tốc độ load trang và cho phép các plugin caching lưu cache được hình ảnh.

# PHP
- Chỉ chứa hằng số/module loader ở `functions.php`
- Mọi logic thêm vào `/inc/` hoặc `/inc-woo/`
- **Tách riêng chức năng đặc thù (Modularization)**: Không gộp chung các chức năng, tiện ích hoặc logic nghiệp vụ khác nhau (ví dụ: AJAX reviews handler và theme zipper, v.v.) vào cùng một file helper chung. Bắt buộc tách riêng thành các file PHP độc lập theo chức năng và đăng ký đầy đủ tại array loader của `inc/index.php` hoặc `inc-woo/main.php`.
- Dùng Hook ngay sau khai báo hàm
- Đảm bảo code PHP tương thích tối thiểu từ PHP 7.4 trở lên (KHÔNG sử dụng các tính năng mới của PHP 8.0+ như: union types, match expression, constructor promotion, named arguments, nullsafe operator ?->, v.v.).
- Không dùng các hàm escape mặc định của WP (`esc_html`, `wp_kses_post`...)
- Debug: Chỉ dùng `write_log()`, xóa trước khi build

# SCSS & UI
- Tuyệt đối không viết CSS/code trực tiếp vào file `style.css` mặc định của theme. Mọi thay đổi CSS phải thực hiện thông qua các file SCSS tương ứng.
- **CSS Inline & Caching:** Tuyệt đối KHÔNG viết mã CSS trực tiếp bằng thẻ `<style>` vào trong các file HTML/PHP hoặc nhúng qua các hook như `wp_footer`/`wp_head`. Việc này phá vỡ quy trình đánh cache (minification/caching) của hệ thống sau này. Toàn bộ CSS bắt buộc phải được quản lý tập trung và viết hoàn toàn trong các file `.scss` tương ứng.
- Bọc toàn bộ style trong `body.lx_landing_theme`
- File phụ thêm prefix `_`
- Đơn vị & Kích thước: CHỈ sử dụng `px` (không dùng `rem`, `em`). Mọi giá trị spacing/sizing (margin, padding, font-size...) phải là **bội số của 4** (vd: 4, 8, 12, 16, 20, 24, 32...).
- Không can thiệp CSS của `.lx_wrap`, `.lx_con` (dùng wrapper bên trong)
- Không dùng margin trên cột Bootstrap. Dùng class `.lx_g32` ở thẻ row
- Không custom background SCSS (trừ widget darkmode/gradient đặc thù)
- Aspect Ratio: Tuyệt đối KHÔNG sử dụng thuộc tính CSS `aspect-ratio: value;`. Bắt buộc dùng mixin `@include aspect-ratio(W, H)` hoặc kỹ thuật `padding-bottom` kết hợp `position: absolute`.
- Hover: Không dùng `translateY/scale`. Chỉ dùng `box-shadow`, `border-color`, `opacity`
- Grid: `col` (<768px), `col-md` (768-1199px), `col-xl` (>=1200px). Tuyệt đối BỎ QUA `col-sm`, `col-lg`, `col-xxl`.
- Slick Slider: Không viết lại CSS UI. Dùng chung từ `main.scss`, chỉ override khi thật sự cần.
- **Media Query:** Bắt buộc sử dụng các biến breakpoint đã định nghĩa sẵn trong `_variables.scss` (như `$bp-mobile`, `$bp-tablet`, `$bp-pc`) thay vì viết cứng giá trị pixel để đảm bảo quy chuẩn chung.
- **Biên dịch SCSS:** Không cần biên dịch SCSS thành CSS (User đã có công cụ biên dịch riêng ngoài môi trường Agent).

# Elementor
- Class: `LX_{Category}_{Name}_Widget` (tên VN + version)
- **Cài đặt Widget:** Chỉ sử dụng chung 1 tab cài đặt duy nhất (`TAB_CONTENT`), không tạo và không sử dụng `TAB_STYLE`. Các thành phần nhập liệu có thể phân tách ra thành các section (`start_controls_section`) trong duy nhất 1 tab này để tiện quản lý.
- Không control Margin/Padding/Typography
- **JavaScript:** Tuyệt đối không viết inline JavaScript (`<script>`) hoặc hook `wp_footer` bên trong widget. Mọi mã JS của widget phải được viết ra file tĩnh (`.js`) riêng trong `assets/js/widgets/` và đăng ký bằng phương thức `get_script_depends()` của class widget. Nếu widget chỉ gồm HTML/CSS tĩnh, KHÔNG khai báo hàm `get_script_depends()`.
- Hàm Helper dùng chung (BẮT BUỘC):
  - Header: `lx_render_widget_header($title, $desc, $args)`
  - Button: `lx_render_button($text, $link, $class, $icon_html)`
  - Video: `lx_render_video_embed($video)`
- Thêm widget mới vào `widget-library.md` (TRỪ: Menu, Footer, Archive, Single, 404, WooCommerce).
- **Mô tả Widget**: Khi thêm widget mới hoặc cập nhật `widget-library.md`, bắt buộc phải cung cấp mô tả chi tiết **không quá 160 ký tự** cho mỗi widget, giải thích rõ cấu trúc hiển thị, layout (Grid, Slider, Marquee...) và các phần tử UI chính để Gemini AI đọc hiểu cấu trúc tốt nhất.



# WooCommerce (LX Woo Framework)
- Logic: Code xử lý đặt tại `inc-woo/` (vd: `hooks-core.php`, `hooks-shop.php`, v.v.).
- Style (SCSS): Đặt tại `assets/scss/woocommerce/` (vd: `_shop.scss`, `_single-product.scss`).
- Elementor Widgets cho Woo: Đặt file ở `widgets/template/lx_woocommerce/`. Category: `lx_woocommerce`. Đăng ký & nạp tại `widgets/index.php` (chỉ nạp/đăng ký khi WooCommerce active để tránh lỗi).
- Override Templates: Chép template từ plugin gốc sang thư mục `woocommerce/` ở root theme để tùy chỉnh.

# Advanced Custom Fields (ACF)
- **Repeater Layout**: Cấm dùng layout `table`, bắt buộc dùng layout `block` để tối ưu giao diện.
- **Theme Options**: Dùng `lx_get_field('field', 'option')` để đồng bộ đa ngôn ngữ. Post/page vẫn dùng `get_field()`.
- **Đồng bộ hóa**: Bắt buộc bật tính năng đồng bộ đa ngôn ngữ (Polylang Sync) khi tạo mới Field Group.

# Đa ngôn ngữ (Polylang)
- **Chuỗi tĩnh**: Dùng cú pháp dịch tiêu chuẩn `__('Text', 'lx-landing')` cho các chuỗi ký tự tĩnh để hệ thống dịch của Polylang có thể quét và ghi nhận.
- **Xử lý chuỗi dịch có biến ở Frontend**: Đối với các chuỗi dịch cần truyền biến động (như số lượng, tên...) hiển thị ngoài Frontend, tuyệt đối KHÔNG sử dụng ký tự định dạng (`%s`, `%d`, `%1$s`...) kết hợp với `sprintf`/`printf` để dịch. Bắt buộc phải tách riêng phần chữ tĩnh cố định và biến ra để dịch riêng, sau đó sử dụng kiểu nối chuỗi bằng PHP (toán tử `.`). Quy tắc này chỉ áp dụng đối với giao diện người dùng Frontend, không bắt buộc đối với code quản trị Admin hoặc cấu hình control của Elementor.
- **Truy vấn tùy biến (Custom Query)**: Khi khởi tạo `WP_Query` độc lập, cần chú ý kiểm tra và lấy đúng tham số ngôn ngữ hiện tại để tránh hiển thị sai lệch nội dung giữa các ngôn ngữ.

# Tài liệu bổ sung (Bắt buộc đọc)
- **Bối cảnh dự án (Context)**: Xem chi tiết cấu trúc CPT, ACF Fields và Elementor Widgets tại [.agents/CONTEXT.md](./CONTEXT.md).
- **Quy trình làm việc (Workflows)**: Xem quy trình tạo Widget (Elementor/WooCommerce), thêm module PHP và debug tại [.agents/WORKFLOWS.md](./WORKFLOWS.md).

# Agent Rules

## Scroll Animations for Widgets
When creating or modifying widgets, always proactively add the `lx_wow_item` class to content elements to enable scroll animations.
Follow these strict rules for `lx_wow_item` placement:
1. **Target Inner Elements Only**: Only add `lx_wow_item` to specific text and content elements (e.g., titles `<h1>`-`<h6>`, paragraphs `<p>`, descriptions, individual buttons, author info, small text spans).
2. **NO Outer Wrappers**: Tuyệt đối KHÔNG gắn `lx_wow_item` vào các thẻ bao ngoài (outer containers), thẻ card (như `<article>`, `.lx_post_card`), hoặc các thẻ `<div>` bao bọc nhiều item bên trong. Việc này sẽ khiến toàn bộ khối lớn nhảy lên cùng lúc gây lỗi giao diện.
3. **NO Images**: Crucially, DO NOT add the `lx_wow_item` class to images (`<img>` or image wrappers). Images must remain static (without animation classes) to prevent layout shifts and misalignment.

## Device Compatibility (iOS & Android)
- **iOS 18+ Support**: Always ensure layout compatibility for Safari on iOS 18+. Flexbox containers must be carefully structured, and aspect-ratio styling must strictly use the theme `@mixin aspect-ratio(W, H)` instead of raw CSS properties to prevent height collapsing.
- **Android Display Compatibility (360px Screen)**: Ensure all page components, widgets, grids, and tables adjust correctly on low-end Android mobile devices with screen widths down to 360px without content clipping, overflow, or design breakage. Use responsive padding, flex wrapping, and relative sizing where necessary.

