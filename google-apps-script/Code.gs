/**
 * Nhận đơn đăng ký từ landing page Bon Bon và ghi vào Google Sheet.
 *
 * Cách cài đặt: xem mục "Kết nối form với Google Sheet" trong README.md.
 */

// Tên sheet (tab) chứa đơn. Tự tạo nếu chưa có.
var SHEET_NAME = 'Don hang';

// (Tuỳ chọn) Email nhận thông báo mỗi khi có đơn mới. Để trống để tắt.
var NOTIFY_EMAIL = '';

var COLUMNS = [
  ['time', 'Thời gian'],
  ['name', 'Họ tên'],
  ['phone', 'Số điện thoại'],
  ['address', 'Địa chỉ'],
  ['quantity', 'Số lượng'],
  ['email', 'Email'],
  ['product', 'Sản phẩm'],
  ['price', 'Giá'],
  ['note', 'Ghi chú'],
  ['utm_source', 'utm_source'],
  ['utm_medium', 'utm_medium'],
  ['utm_campaign', 'utm_campaign'],
  ['utm_content', 'utm_content'],
  ['utm_term', 'utm_term'],
  ['fbclid', 'fbclid'],
  ['gclid', 'gclid'],
  ['ttclid', 'ttclid'],
  ['referrer', 'Referrer'],
  ['page', 'Trang'],
  ['user_agent', 'Thiết bị']
];

function doPost(e) {
  var lock = LockService.getScriptLock();
  lock.waitLock(10000);
  try {
    var p = (e && e.parameter) || {};

    // Bỏ qua request rỗng / spam
    if (!p.name || !p.phone || p.website) {
      return json({ ok: false, error: 'invalid' });
    }

    var sheet = getSheet();
    var row = COLUMNS.map(function (c) {
      var v = String(p[c[0]] || '').slice(0, 1000);
      // Chặn công thức độc hại (CSV/formula injection) và giữ số 0 đầu SĐT
      if (/^[=+\-@]/.test(v) || c[0] === 'phone') v = "'" + v;
      return v;
    });
    sheet.appendRow(row);

    if (NOTIFY_EMAIL) {
      MailApp.sendEmail(NOTIFY_EMAIL,
        '[Bon Bon] Đơn mới: ' + p.name + ' - ' + p.phone,
        COLUMNS.map(function (c) { return c[1] + ': ' + (p[c[0]] || ''); }).join('\n'));
    }

    return json({ ok: true });
  } finally {
    lock.releaseLock();
  }
}

function doGet() {
  return json({ ok: true, message: 'Bon Bon lead endpoint is running' });
}

function getSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName(SHEET_NAME) || ss.insertSheet(SHEET_NAME);
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(COLUMNS.map(function (c) { return c[1]; }));
    sheet.getRange(1, 1, 1, COLUMNS.length).setFontWeight('bold').setBackground('#e8f7fa');
    sheet.setFrozenRows(1);
  }
  return sheet;
}

function json(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
