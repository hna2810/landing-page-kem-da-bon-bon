/**
 * GOOGLE APPS SCRIPT - TỰ ĐỘNG LƯU ĐƠN HÀNG TỪ LANDING PAGE BON BON
 *
 * HƯỚNG DẪN CÀI ĐẶT 3 BƯỚC:
 * 1. Mở trang Google Trang tính (Google Sheets) mới tại: https://sheets.new
 * 2. Trên thanh menu, chọn: Tiện ích mở rộng (Extensions) > Apps Script.
 * 3. Xóa hết mã cũ trong trình soạn thảo, dán toàn bộ nội dung file này vào và bấm icon Lưu (Save).
 * 4. Bấm nút "Triển khai" (Deploy) ở góc trên bên phải > "Tùy chọn triển khai mới" (New deployment).
 *    - Loại: "Ứng dụng web" (Web app).
 *    - Thực thi dưới dạng (Execute as): "Tôi" (Me).
 *    - Ai có quyền truy cập (Who has access): "Bất kỳ ai" (Anyone).
 * 5. Bấm "Triển khai" (Deploy), cấp quyền truy cập tài khoản Google của bạn.
 * 6. Sao chép "URL ứng dụng web" (Web App URL có đuôi /exec) và dán vào:
 *    Trang quản trị WordPress > Cài Đặt Landing Page > Mục "Google Sheet Web App URL".
 */

// Tên tab chứa đơn trong Google Sheet (tự tạo nếu chưa có)
var SHEET_NAME = 'Đơn hàng';

// (Tuỳ chọn) Điền email nhận thông báo mỗi khi có khách đặt đơn mới. Để trống nếu không cần.
var NOTIFY_EMAIL = '';

// Danh sách các cột lưu thông tin
var COLUMNS = [
  ['time', 'Thời gian'],
  ['name', 'Họ tên'],
  ['phone', 'Số điện thoại'],
  ['package', 'Gói chọn mua'],
  ['price', 'Giá tiền'],
  ['address', 'Địa chỉ'],
  ['note', 'Ghi chú'],
  ['utm_source', 'utm_source'],
  ['utm_medium', 'utm_medium'],
  ['utm_campaign', 'utm_campaign'],
  ['utm_content', 'utm_content'],
  ['utm_term', 'utm_term'],
  ['fbclid', 'fbclid'],
  ['gclid', 'gclid'],
  ['ttclid', 'ttclid'],
  ['page', 'Trang đặt hàng'],
  ['user_agent', 'Thiết bị']
];

function doPost(e) {
  var lock = LockService.getScriptLock();
  lock.waitLock(15000);
  try {
    var p = (e && e.parameter) || {};

    // Hỗ trợ cả payload JSON nếu gửi qua application/json
    if ((!p.name && !p.phone) && e && e.postData && e.postData.contents) {
      try {
        var jsonBody = JSON.parse(e.postData.contents);
        p = Object.assign({}, p, jsonBody);
      } catch (err) {}
    }

    // Bỏ qua request rỗng hoặc bot spam
    if (!p.name || !p.phone || p.website) {
      return json({ ok: false, error: 'invalid_data' });
    }

    // Chuẩn hóa gói mua & sản phẩm
    if (!p.package && p.product) {
      p.package = p.product;
    }

    // Tự động làm sạch link trang đặt hàng và trích xuất tracking nếu chưa có
    if (p.page && p.page.indexOf('?') !== -1) {
      var parts = p.page.split('?');
      p.page = parts[0];
      var queryString = parts.slice(1).join('?');
      queryString.split('&').forEach(function (pair) {
        var kv = pair.split('=');
        var k = decodeURIComponent(kv[0] || '');
        var v = decodeURIComponent(kv.slice(1).join('=') || '');
        if (k && v && !p[k]) {
          p[k] = v;
        }
      });
    }

    var sheet = getSheet();
    var row = COLUMNS.map(function (c) {
      var v = String(p[c[0]] || '').slice(0, 1000);
      // Giữ số 0 đầu số điện thoại và chặn công thức độc hại
      if (/^[=+\-@]/.test(v) || c[0] === 'phone') {
        v = "'" + v;
      }
      return v;
    });

    sheet.appendRow(row);

    // Gửi email thông báo nếu có cấu hình
    if (NOTIFY_EMAIL) {
      try {
        MailApp.sendEmail(
          NOTIFY_EMAIL,
          '[Bon Bon] Đơn hàng mới: ' + p.name + ' - ' + p.phone,
          COLUMNS.map(function (c) { return c[1] + ': ' + (p[c[0]] || ''); }).join('\n')
        );
      } catch (mailErr) {}
    }

    return json({ ok: true, message: 'Đã lưu đơn hàng thành công' });
  } catch (err) {
    return json({ ok: false, error: err.toString() });
  } finally {
    lock.releaseLock();
  }
}

function doGet() {
  return json({ ok: true, message: 'Bon Bon Google Sheet Webhook is active and running.' });
}

function getSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName(SHEET_NAME) || ss.insertSheet(SHEET_NAME);
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(COLUMNS.map(function (c) { return c[1]; }));
    var headerRange = sheet.getRange(1, 1, 1, COLUMNS.length);
    headerRange.setFontWeight('bold');
    headerRange.setBackground('#4cc0d3');
    headerRange.setFontColor('#ffffff');
    headerRange.setHorizontalAlignment('center');
    sheet.setFrozenRows(1);
    sheet.autoResizeColumns(1, COLUMNS.length);
  }
  return sheet;
}

function json(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
