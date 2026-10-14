# Landing Page — Kem Hăm Cho Bé Bon Bon (Mobile First + PC)

Landing page bán hàng ưu tiên điện thoại cho **Kem Hăm Cho Bé Bon Bon 5g** (Home Care), dùng chạy quảng cáo Facebook / TikTok / Google.

## Cấu trúc trang (theo wireframe)
1. Hero: "Da bé ửng đỏ vì hăm" — text bên trái, ảnh **trước / sau** bên phải, giá flash sale, nút MUA NGAY CHO BÉ
2. Nỗi lo của mẹ + slider ảnh các vị trí bé bị hăm
3. Bon Bon – Nhẹ nhàng chăm sóc vùng da bé bị hăm + ảnh mẹ ôm bé
4. Thành phần dịu nhẹ + 5 Không + ảnh cận cảnh chất kem
5. An tâm sử dụng: giấy công bố, tem chống hàng giả
6. Hàng ngàn mẹ tin dùng: ảnh feedback + video các mẹ chia sẻ
7. Bon Bon trên khắp 3 miền: slider ảnh hội thảo + nút đặt mua
8. 3 bước chăm sóc + video hướng dẫn
9. Thông tin sản phẩm + khối CTA giá
10. **Form "Ưu đãi tặng riêng cho mẹ"**: giá, đồng hồ đếm ngược 10 phút 30 giây, họ tên, số điện thoại, chọn 1 / 2 / 3 hũ, nút Đặt hàng ngay
11. Hỏi đáp
12. Yêu con – Mẹ chọn Bon Bon
13. Đánh giá sản phẩm

Mọi nút MUA NGAY đều cuộn tới form. Hiệu ứng: thông báo "vừa đặt hàng", nút gọi điện rung.

**Bố cục:** điện thoại / máy tính bảng hiển thị 1 cột (thanh mua dính dưới màn hình); máy tính (≥ 960px) chuyển sang bố cục 2 cột tràn rộng, header có menu + nút Mua ngay, nút gọi / Zalo nổi góc phải.

## ⚙️ Cấu hình — chỉ sửa 1 chỗ
Mở `index.html`, tìm `window.BONBON_CONFIG`:

| Trường | Ý nghĩa |
|---|---|
| `hotline`, `hotlineDisplay`, `zalo` | Số liên hệ, tự áp cho mọi nút gọi / Zalo |
| `price`, `listPrice` | Giá flash sale và giá niêm yết |
| `packages` | Các gói trong form (số hũ, giá, giá gốc) |
| `offerMinutes` | Thời gian đồng hồ ưu đãi (mặc định 10.5 = 10 phút 30 giây, hết giờ tự chạy lại) |
| `sheetEndpoint` | URL Web App Google Apps Script nhận đơn (trống = demo, đơn chỉ lưu trên trình duyệt) |
| `facebookPixelId`, `googleAnalyticsId`, `tiktokPixelId` | ID pixel, để trống thì không tải |
| `media` | **Link ảnh / video** cho từng khung. Ô nào trống sẽ hiện khung chờ có ghi chú. Video nhận link YouTube (kể cả Shorts) hoặc file `.mp4` |

Ảnh có thể để trong thư mục `assets/` rồi điền đường dẫn dạng `assets/ten-anh.jpg`.

## 📥 Nhận đơn về Google Sheet
1. Tạo Google Sheet → **Tiện ích mở rộng → Apps Script**, dán nội dung `google-apps-script/Code.gs`, Lưu.
2. **Triển khai → Tùy chọn triển khai mới → Ứng dụng web**: Thực thi với tư cách **Tôi**, quyền truy cập **Bất kỳ ai**.
3. Dán URL `https://script.google.com/macros/s/.../exec` vào `sheetEndpoint`.

Mỗi đơn gồm: thời gian, họ tên, SĐT, số lượng hũ, sản phẩm, thành tiền, UTM / fbclid / gclid / ttclid.

## 🛠️ Công nghệ
HTML / CSS / JavaScript thuần, không cần build. Không dùng thư viện ngoài (nhẹ, tải nhanh trên 4G).
