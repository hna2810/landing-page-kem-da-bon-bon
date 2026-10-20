# Landing Page — Kem Bôi Chàm Sữa Bon Bon (Home Care Việt Nam)

Landing page tối ưu chuyển đổi cao (High Conversion Rate) dành cho chiến dịch chạy quảng cáo Facebook Ads, Google Ads, TikTok Ads.

## ✨ Điểm nổi bật
- **Màu chủ đạo**: `#4cc0d3` kết hợp bảng màu pastel baby cute (hồng phấn, vàng nhạt, xanh bạc hà).
- **Hiệu ứng mượt mà & Awwwards-style**: Sử dụng **GSAP 3** + **ScrollTrigger** + **ScrollToPlugin**.
- **Social Proof Real-time**: Popup thông báo khách đặt hàng tự động theo thời gian thực.
- **Marquee Ticker**: Dải chứng nhận, cam kết, số công bố mỹ phẩm chạy liên tục.
- **Đếm ngược giới hạn**: Countdown timer & thông báo số lượng suất ưu đãi còn lại tạo tính cấp bách (Urgency).
- **Form đăng ký chuẩn UX**: Kiểm tra dữ liệu (Validation) tức thì, lựa chọn sản phẩm đơn lẻ (49k) hoặc Combo giảm 50%, hiệu ứng bắn pháo hoa Confetti khi đăng ký thành công.
- **Lightbox**: Phóng to xem ảnh feedback và giấy chứng nhận kiểm định.
- **Tối ưu Mobile First**: Sticky CTA bar cố định bên dưới màn hình, nút gọi Hotline / Chat Zalo tiện lợi.

## 🚀 Cấu trúc file
- `index.html`: Toàn bộ mã nguồn Landing Page (HTML5, CSS3, JavaScript) chạy độc lập, không phụ thuộc build tool phức tạp.

## 🛠️ Công nghệ sử dụng
- HTML5 / CSS3 / Vanilla JavaScript
- GSAP 3 & ScrollTrigger (CDN)
- Google Fonts (Quicksand & Nunito)

---

# Landing Page — Home Care Luxury Mỹ Đình (Trung tâm ở cữ 5 sao)

File: `home-care-luxury-my-dinh/index.html` — landing page chuyển đổi cho dịch vụ ở cữ / chăm sóc mẹ và bé sau sinh, chuyển thể từ nội dung trang homecareocu.vn.

- **Phong cách**: luxury — xanh rừng `#1E3A36`, vàng champagne `#B88A4A`, nền ngà, hồng phấn; font Playfair Display + Be Vietnam Pro.
- **Phễu chuyển đổi**: Hero (USP + giá từ 699K + 2 CTA) → số liệu → nỗi đau ở cữ tại nhà → giải pháp → không gian phòng → chăm sóc Mẹ/Bé (tab) → một ngày tại Home Care → gói dịch vụ → ưu đãi (đếm ngược đến cuối tháng) → quy trình 4 bước → đánh giá → FAQ → form đặt lịch + bản đồ.
- **Form**: kiểm tra họ tên & SĐT Việt Nam, chọn gói (nút ở thẻ giá tự chọn sẵn gói). Điền `FORM_ENDPOINT` (Google Apps Script/webhook) trong `<script>` để nhận lead; bỏ comment `fbq`/`gtag` để tracking.
- **Ảnh**: các khung `.photo` đang là placeholder — thêm `style="--img:url('anh.jpg')"` để thay ảnh thật.
- **Đánh giá**: 2 ô review mẫu cần thay bằng review thật của khách.
