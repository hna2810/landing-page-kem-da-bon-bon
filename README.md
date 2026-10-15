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

# Landing Page — Xịt Khử Mùi Gừng Cam Mây Mây 30ml

File: `xit-khu-mui-may-may/index.html` (HTML/CSS/JS thuần, không cần build).

- **Khuyến mãi**: Flash sale 49.000đ (niêm yết 89.000đ) + freeship toàn quốc.
- **Concept**: năng động, thảo mộc mát — primary `#fbdfc7`, nút CTA `#ffa059`, chữ `#000000`, điểm nhấn xanh bạc hà để cân lại tông ấm.
- **Cấu hình nhanh**: sửa object `CONFIG` ở đầu thẻ `<script>`:
  - `showPlaceholders`: `true` = hiện khung "Chờ nội dung" để xem bố cục; đổi thành `false` trước khi chạy ads (phần nào chưa có nội dung sẽ tự ẩn)
  - `productImages`: mảng ảnh sản phẩm (ảnh đầu là ảnh chính, các ảnh sau thành thumbnail)
  - `usageImage`: ảnh lifestyle cho phần Công dụng
  - `videos`: video review `{ url, title, author, poster }` (TikTok / YouTube / .mp4)
  - `reviews`: đánh giá thật `{ name, location, date, rating, tag, content, images, avatar, verified }` – điểm trung bình, biểu đồ sao, bộ lọc tự tính
  - `feedbackImages`: ảnh chụp tin nhắn / feedback
  - `certificateImages`: ảnh giấy công bố, phiếu kiểm nghiệm
  - `commitments`: danh sách cam kết
  - `orderEndpoint`: URL nhận đơn (Google Apps Script Web App, webhook…)
  - `packages`: các lựa chọn số lượng trong form
