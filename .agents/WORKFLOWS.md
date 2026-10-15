# Workflows & Slash Commands

Bạn có thể gọi các lệnh này trong chat để bắt đầu một quy trình chuẩn xác.

## `/create-widget`
**Quy trình tạo Widget Elementor mới:**
1. Copy template mẫu từ `widgets/template/_samples/duplicate_widget.php`
2. Đổi tên class thành `LX_{Category}_V{Version}_Widget` (vd: `LX_Loi_Ich_V8_Widget`)
3. Lưu file PHP vào `widgets/template/{category_slug}/{category_slug}_v{version}.php` (Dùng snake_case)
4. Tạo file SCSS tương ứng mang tiền tố `_` (vd: `_{category_slug}_v{version}.scss`) và import vào file SCSS tổng
5. Nếu widget cần JavaScript, tạo file `.js` độc lập tại `assets/js/widgets/`, đăng ký file tại `inc/widget-assets.php`, sau đó nạp vào widget bằng phương thức `get_script_depends()`.
6. Require và Register widget vào file `widgets/index.php`
6. Cập nhật ID và mô tả vào `widgets/widget-library.md`

## `/create-woo-widget`
**Quy trình tạo Widget Elementor cho WooCommerce:**
1. Copy template mẫu từ `widgets/template/_samples/duplicate_widget.php`
2. Đổi tên class thành `LX_Woo_{Name}_V{Version}_Widget` (vd: `LX_Woo_Products_V1_Widget`)
3. Lưu file PHP widget vào thư mục `widgets/template/lx_woocommerce/` (vd: `lx_woo_products_v1.php`).
4. Đảm bảo category của widget sử dụng `lx_woocommerce`.
5. Tạo file SCSS mang tiền tố `_` vào thư mục `assets/scss/woocommerce/widgets/` và import vào file tổng.
6. Nếu widget cần JavaScript, tạo file `.js` độc lập tại `assets/js/widgets/`, đăng ký file tại `inc/widget-assets.php`, sau đó nạp vào widget bằng phương thức `get_script_depends()`.
7. Require và Register widget động vào file `widgets/index.php` trong điều kiện `class_exists('WooCommerce')`.

## `/add-inc-module`
**Quy trình thêm logic PHP mới:**
1. Tạo file mới trong `/inc/{module_name}.php`
2. Thêm file header `/** @package LXLanding */`
3. Khai báo file trong mảng `$child_theme_inc_files` tại `inc/index.php` kèm comment mô tả chức năng
4. Viết các hook với tiền tố `lx_`

## `/debug`
**Quy trình gỡ lỗi PHP:**
1. Dùng hàm `write_log($data, 'Label');` thay vì `var_dump` hay `echo`.
2. Kiểm tra file `wp-content/debug.log`.
3. Xóa `write_log` sau khi hoàn tất.
