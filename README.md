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

## ⚙️ Cấu hình (chỉ sửa 1 chỗ)
Mở `index.html`, tìm `window.BONBON_CONFIG` ở đầu file và điền:

| Trường | Ý nghĩa |
|---|---|
| `hotline`, `hotlineDisplay`, `zalo`, `email` | Thông tin liên hệ — tự áp dụng cho mọi nút gọi / Zalo / footer |
| `sheetEndpoint` | URL Web App Google Apps Script để nhận đơn (xem bên dưới). Để trống = chế độ demo, đơn chỉ lưu tạm trên trình duyệt |
| `facebookPixelId`, `googleAnalyticsId`, `tiktokPixelId` | ID pixel. Để trống thì không tải script đó |

Sự kiện được bắn tự động: `PageView`, `ViewContent`, `InitiateCheckout` (khi bắt đầu điền form), `Contact` (bấm gọi / Zalo), `Lead` (gửi form thành công, kèm giá trị đơn VND).

## 📥 Kết nối form với Google Sheet
1. Tạo 1 Google Sheet mới → **Tiện ích mở rộng → Apps Script**.
2. Dán nội dung file `google-apps-script/Code.gs` vào, bấm Lưu. (Tuỳ chọn: điền `NOTIFY_EMAIL` để nhận email khi có đơn.)
3. **Triển khai → Tùy chọn triển khai mới → Ứng dụng web**: *Thực thi với tư cách*: **Tôi**, *Ai có quyền truy cập*: **Bất kỳ ai**.
4. Copy URL dạng `https://script.google.com/macros/s/.../exec` dán vào `sheetEndpoint`.

Mỗi đơn ghi 1 dòng gồm: thời gian, họ tên, SĐT (chuẩn hoá về dạng 0xxx), email, sản phẩm, giá, ghi chú, UTM (`utm_source/medium/campaign/content/term`), `fbclid/gclid/ttclid`, referrer, thiết bị — để đo chính xác hiệu quả từng chiến dịch quảng cáo.

Form có chống spam (honeypot), chống bấm gửi 2 lần, báo lỗi + gợi ý gọi/Zalo khi mạng lỗi, và luôn giữ bản sao đơn trên trình duyệt khách.
