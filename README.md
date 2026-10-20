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

# Landing Page — Trung Tâm Ở Cữ Tây Hồ Hạng A (Home Care Luxury)

File: `o-cu-tay-ho/index.html` — trang độc lập (HTML/CSS/JS thuần). Toàn bộ nội dung trích (rút gọn) từ bài viết "Trung Tâm Ở Cữ Tây Hồ Hạng A: 990K/ Ngày Trải Nghiệm Tĩnh Dưỡng Thượng Lưu Cho Mẹ & Bé".

- **Mục tiêu**: khách điền form (mẹ bầu, mẹ sau sinh, người thân). Không có header/footer. Đầu trang là video chính (16:9) + nút đăng ký; form đặt ở cuối trang (`#dang-ky`), mọi nút CTA và thanh CTA cố định trên mobile đều dẫn tới form.
- **Font & giao diện**: một font duy nhất Nunito, cỡ chữ lớn, tương phản cao; màu chủ đạo #4cc0d3 (xanh ngọc) + xanh đậm, viền vàng mảnh, họa tiết phân cách, khung ảnh vòm, form có ruy băng ưu đãi.
- **Form**: Họ tên, Số điện thoại, 6 lựa chọn đúng như bài viết (dạng nút bấm). Gắn API gửi dữ liệu tại dòng `TODO` trong script.
- **Thay ảnh thật**: điền URL ảnh vào `data-src` của các khối `.media` (ví dụ `<div class="media" data-scene="room" data-src="https://.../phong-suite.jpg">`) — ảnh thật sẽ tự thay hình minh họa. Chú thích ảnh đã dùng đúng chú thích trong bài.
- **Video review**: video chính ngang (16:9) ở đầu trang + khu `#video` là slider video dọc (9:16, 4 video/hàng trên desktop). Khu không gian cũng là slider ảnh. Thêm ảnh/video chỉ cần thêm phần tử vào `.slider-track`, mũi tên và chấm tự cập nhật. Dán link vào `data-video` của từng `.vcard` — hỗ trợ YouTube, YouTube Shorts, TikTok, Facebook/Reels, file .mp4. Bấm vào sẽ mở popup phát video; YouTube tự lấy ảnh bìa. Muốn thêm video: nhân bản một `<button class="vcard v">` (dọc) hoặc `class="vcard h"` (ngang).
- **Hình ảnh review** (`#hinh-anh-review`): slider ảnh feedback 3:4, bấm để phóng to. Dán link ảnh vào `data-src` của từng `.pcard .media`.
- **Text review** (`#text-review`): slider thẻ chia sẻ. Hiện dùng đúng các cảm nhận bài viết nêu (không gắn tên); khi có review thật thì thay chữ và tên khách trong từng `.tcard`.
- **FAQ** (`#faq`): 8 câu hỏi, câu trả lời lấy từ thông tin trong bài.
