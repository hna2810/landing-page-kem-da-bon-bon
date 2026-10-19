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

# Landing Page — Sữa Tắm Gội Bon Bon (Lead Generation)

File: `sua-tam-goi-bon-bon/index.html`. Đây là trang **Lead Generation dạng long-form**, mục tiêu là khách điền form. Trang dùng để chạy Facebook / TikTok / Google Ads.

## Các section tương tác
1. **Hero**: bóp bong bóng (có bộ đếm), kéo thả lọ Bon Bon có mặt cười, mắt đi theo chuột, chú vịt bấm vào kêu "quạc".
2. **Thẻ lật**: các nỗi lo của mẹ, chạm vào để xem giải pháp.
3. **Slider so sánh**: kéo để so sánh "Sữa tắm thông thường" với "Bon Bon".
4. **Mini game pha chế**: kéo (hoặc chạm) 3 thành phần chính (dầu hạnh nhân, vitamin E, Aqua & SLES dịu nhẹ) vào lọ. Nếu kéo "Paraben" vào, lọ sẽ lắc đầu từ chối.
5. **5 lợi ích**: cuộn ngang, ghim màn hình trên desktop; vuốt ngang trên mobile.
7. **Cách tắm bé 3 bước**: đường nối tự vẽ theo scroll.
8. **Vòng quay may mắn**: mỗi trình duyệt quay 1 lượt, quà được gắn vào form.
9. **Thông tin sản phẩm**: tab Thông tin / Bảo quản / Lưu ý (500ml, HSD 24 tháng, số công bố 64/26/CBMP-NB).
10. **FAQ** dạng accordion.
11. **Form đăng ký**: đếm ngược đến cuối ngày, chip chọn nhanh, validate SĐT Việt Nam, confetti khi gửi thành công.

## Cấu hình (đầu thẻ `<script>`, object `CONFIG`)
- `hotline`, `zalo`: số điện thoại (hiện đang là số mẫu `0901234567`).
- `webhookUrl`: URL nhận lead. Ví dụ dùng Google Apps Script để ghi lead vào Google Sheet:
  ```js
  function doPost(e) {
    const d = JSON.parse(e.postData.contents);
    SpreadsheetApp.getActiveSpreadsheet().getSheets()[0].appendRow([
      d.time, d.name, d.phone, d.baby_age, d.concern, d.call_time, d.note, d.gift, d.source, d.page
    ]);
    return ContentService.createTextOutput('ok');
  }
  ```
  Deploy dạng **Web app**, quyền truy cập "Anyone", rồi dán URL vào `webhookUrl`.
- `gifts`: danh sách quà của vòng quay và tỉ lệ trúng (`weight`).
- Pixel: bỏ comment và điền ID cho FB Pixel / TikTok Pixel / GA trong `<head>`. Khi gửi form thành công, trang tự bắn sự kiện `Lead` (FB), `SubmitForm` (TikTok), `generate_lead` (GA) và push `lead_submit` vào `dataLayer`.
- UTM, fbclid, ttclid, gclid được lưu tự động vào trường `source`.

## Cần kiểm tra trước khi chạy ads
- Có thể bổ sung section feedback thật của khách (ảnh chụp hoặc lời chia sẻ đã được khách đồng ý).
- Quà tặng và ưu đãi: khớp với chương trình khuyến mãi thực tế.
