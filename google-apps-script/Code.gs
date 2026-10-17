/**
 * ============================================================================
 * GOOGLE APPS SCRIPT - TỰ ĐỘNG LƯU ĐƠN HÀNG VÀ TÁCH URL TRACKING (BON BON)
 * ============================================================================
 * 
 * HƯỚNG DẪN CẬP NHẬT TRONG GOOGLE SHEET:
 * 1. Mở file Google Sheet đang nhận đơn của bạn.
 * 2. Trên thanh menu chọn: Tiện ích mở rộng (Extensions) > Apps Script.
 * 3. Xóa toàn bộ mã cũ trong file Code.gs, dán toàn bộ đoạn code này vào.
 * 4. Bấm icon Lưu (Save) hoặc nhấn Ctrl + S.
 * 5. Bấm nút "Triển khai" (Deploy) màu xanh ở góc trên bên phải:
 *    - Chọn "Quản lý tùy chọn triển khai" (Manage deployments).
 *    - Bấm icon cây bút (Edit) ở bản triển khai hiện tại.
 *    - Tại mục "Phiên bản" (Version), chọn "Phiên bản mới" (New version).
 *    - Bấm "Triển khai" (Deploy).
 * 6. (TÙY CHỌN) Để sửa lại các dòng cũ đã lỡ bị dính link dài như trong ảnh:
 *    - Quay lại Google Sheet, tải lại trang (F5).
 *    - Trên thanh menu sẽ xuất hiện mục: "⚡ Tiện ích Bon Bon" > bấm chọn "🔄 Tách URL các dòng cũ sang đúng cột".
 */

// 1. Cấu hình email nhận thông báo
var NOTIFY_EMAIL = 'lyhrhomecare@gmail.com';

// 2. Tên tab Google Sheet chứa đơn hàng
var SHEET_NAME = 'Đơn hàng';

// 3. Danh sách cấu trúc cột mặc định (dùng khi tạo sheet mới tinh)
var DEFAULT_COLUMNS = [
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
  ['referrer', 'Referrer'],
  ['page', 'Trang đặt hàng'],
  ['user_agent', 'Thiết bị']
];

/**
 * Xử lý POST request từ form Landing Page
 */
function doPost(e) {
  var lock = LockService.getScriptLock();
  lock.waitLock(15000);
  try {
    var p = (e && e.parameter) || {};

    // Hỗ trợ cả payload gửi dạng JSON
    if ((!p.name && !p.phone) && e && e.postData && e.postData.contents) {
      try {
        var jsonBody = JSON.parse(e.postData.contents);
        p = Object.assign({}, p, jsonBody);
      } catch (err) {}
    }

    // Bỏ qua bot spam (honeypot field website) hoặc dữ liệu rỗng
    if (!p.name || !p.phone || p.website) {
      return jsonResponse({ ok: false, error: 'invalid_data' });
    }

    // Chuẩn hóa tên gói mua & sản phẩm
    if (!p.package && p.product) {
      p.package = p.product;
    }

    // Tự động phân tích & cắt URL để lấy các trường UTM, fbclid, gclid, ttclid
    var rawUrl = p.page || p.url || p['Trang đặt hàng'] || '';
    parseAndExtractTracking(rawUrl, p);

    // Ghi vào Google Sheet theo đúng tên cột của Sheet
    var sheet = getSheet();
    var lastRow = insertLeadByHeaders(sheet, p);

    // Gửi email thông báo về lyhrhomecare@gmail.com
    if (NOTIFY_EMAIL) {
      sendOrderNotification(p);
    }

    return jsonResponse({ ok: true, message: 'Đã lưu đơn và tách cột thành công', row: lastRow });
  } catch (err) {
    return jsonResponse({ ok: false, error: err.toString() });
  } finally {
    lock.releaseLock();
  }
}

/**
 * GET request kiểm tra webhook hoạt động
 */
function doGet() {
  return jsonResponse({
    ok: true,
    message: 'Bon Bon Lead Webhook & URL Splitter is active.',
    notify_email: NOTIFY_EMAIL,
    version: '2.0.0'
  });
}

/**
 * Hàm phân tích và tách URL query params ra các biến tương ứng
 */
function parseAndExtractTracking(rawUrl, p) {
  if (!rawUrl) return;
  var urlStr = String(rawUrl).trim();

  // 1. Phân tích query string nếu có dấu ?
  if (urlStr.indexOf('?') !== -1) {
    var qParts = urlStr.split('?');
    p.page = qParts[0]; // Giữ lại URL sạch (không kèm param)
    var queryString = qParts.slice(1).join('?');

    var pairs = queryString.split('&');
    for (var i = 0; i < pairs.length; i++) {
      var pair = pairs[i];
      if (!pair) continue;
      var eq = pair.indexOf('=');
      var k = '';
      var v = '';
      if (eq !== -1) {
        k = decodeURIComponent(pair.substring(0, eq));
        v = decodeURIComponent(pair.substring(eq + 1));
      } else {
        var decoded = decodeURIComponent(pair);
        if (decoded.indexOf('?') !== -1) {
          parseAndExtractTracking(decoded, p);
          continue;
        }
      }
      k = k.trim().toLowerCase();
      if (k && v && (!p[k] || p[k] === '')) {
        p[k] = v.trim();
      }
    }
  } else {
    p.page = urlStr;
  }

  // 2. Regex dự phòng để bóc tách chính xác các ID quảng cáo ngay cả khi URL bị lồng nhau hoặc encode đặc biệt
  var trackingKeys = ['fbclid', 'gclid', 'ttclid', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];
  for (var j = 0; j < trackingKeys.length; j++) {
    var key = trackingKeys[j];
    if (!p[key] || p[key] === '') {
      var regex = new RegExp('(?:[?&]|%26)' + key + '=([^&#%]+)', 'i');
      var match = urlStr.match(regex);
      if (match && match[1]) {
        try {
          p[key] = decodeURIComponent(match[1]);
        } catch (e) {
          p[key] = match[1];
        }
      }
    }
  }

  // 3. Đảm bảo p.page là link gốc sạch sẽ hoàn toàn
  if (p.page && p.page.indexOf('?') !== -1) {
    p.page = p.page.split('?')[0];
  }
}

/**
 * Ghi dữ liệu vào sheet dựa trên TÊN CỘT THẬT ở Hàng 1
 * (Bảo đảm không bị lệch cột dù người dùng có thêm/chèn/đổi thứ tự cột)
 */
function insertLeadByHeaders(sheet, p) {
  var lastCol = sheet.getLastColumn();
  if (lastCol === 0) {
    // Nếu sheet rỗng, tạo hàng tiêu đề mặc định
    sheet.appendRow(DEFAULT_COLUMNS.map(function (c) { return c[1]; }));
    styleHeader(sheet, DEFAULT_COLUMNS.length);
    lastCol = DEFAULT_COLUMNS.length;
  }

  var headerRow = sheet.getRange(1, 1, 1, lastCol).getValues()[0];
  var rowData = [];

  for (var c = 0; c < headerRow.length; c++) {
    var h = String(headerRow[c] || '').trim().toLowerCase();
    var val = '';

    // Khớp tên cột linh hoạt (hỗ trợ cả tiếng Việt có dấu, không dấu, tiếng Anh)
    if (h.indexOf('thời gian') !== -1 || h === 'time' || h === 'ngày' || h === 'ngay' || h === 'date') {
      val = p.time || Utilities.formatDate(new Date(), 'Asia/Ho_Chi_Minh', 'dd/MM/yyyy HH:mm:ss');
    } else if (h.indexOf('họ tên') !== -1 || h.indexOf('ho ten') !== -1 || h.indexOf('tên') !== -1 || h === 'name') {
      val = p.name || p.fullName || p.ho_ten || '';
    } else if (h.indexOf('số điện thoại') !== -1 || h.indexOf('so dien thoai') !== -1 || h.indexOf('sđt') !== -1 || h === 'sdt' || h === 'phone') {
      val = p.phone || p.sdt || p.so_dien_thoai || '';
      if (val) val = "'" + String(val).trim(); // Giữ số 0 ở đầu
    } else if (h.indexOf('gói chọn mua') !== -1 || h.indexOf('goi') !== -1 || h.indexOf('sản phẩm') !== -1 || h.indexOf('san pham') !== -1 || h === 'package' || h === 'product') {
      val = p.package || p.product || p.san_pham || '';
    } else if (h.indexOf('giá') !== -1 || h.indexOf('gia') !== -1 || h === 'price') {
      val = p.price || p.gia || '';
    } else if (h.indexOf('địa chỉ') !== -1 || h.indexOf('dia chi') !== -1 || h === 'address') {
      val = p.address || p.dia_chi || '';
    } else if (h.indexOf('ghi chú') !== -1 || h.indexOf('ghi chu') !== -1 || h.indexOf('tình trạng') !== -1 || h === 'note') {
      val = p.note || p.ghi_chu || p.condition || '';
    } else if (h === 'utm_source' || h === 'tm_source') {
      val = p.utm_source || '';
    } else if (h === 'utm_medium' || h === 'tm_medium') {
      val = p.utm_medium || '';
    } else if (h === 'utm_campaign' || h === 'tm_campaign') {
      val = p.utm_campaign || '';
    } else if (h === 'utm_content' || h === 'tm_content') {
      val = p.utm_content || '';
    } else if (h === 'utm_term' || h === 'tm_term') {
      val = p.utm_term || '';
    } else if (h === 'fbclid') {
      val = p.fbclid || '';
    } else if (h === 'gclid') {
      val = p.gclid || '';
    } else if (h === 'ttclid') {
      val = p.ttclid || '';
    } else if (h === 'referrer' || h.indexOf('nguồn') !== -1) {
      val = p.referrer || '';
    } else if (h.indexOf('trang đặt hàng') !== -1 || h.indexOf('trang dat hang') !== -1 || h === 'trang' || h === 'page' || h === 'url') {
      val = p.page || '';
    } else if (h.indexOf('thiết bị') !== -1 || h.indexOf('thiet bi') !== -1 || h === 'user_agent' || h === 'device') {
      val = p.user_agent || '';
    } else {
      // Trường hợp cột tùy chỉnh trùng key trong object p
      val = p[h] || '';
    }

    // Chặn lỗi injection formula
    if (typeof val === 'string' && /^[=+\-@]/.test(val) && !val.startsWith("'")) {
      val = "'" + val;
    }

    rowData.push(val);
  }

  sheet.appendRow(rowData);
  return sheet.getLastRow();
}

/**
 * Gửi email thông báo đơn mới về lyhrhomecare@gmail.com
 */
function sendOrderNotification(p) {
  try {
    var subject = '[Bon Bon] Đơn hàng mới: ' + (p.name || '') + ' - ' + (p.phone || '');
    var body =
      '🎉 CÓ ĐƠN HÀNG MỚI TỪ LANDING PAGE BON BON\n' +
      '==================================================\n' +
      '1. THÔNG TIN KHÁCH HÀNG:\n' +
      '• Họ và tên: ' + (p.name || '') + '\n' +
      '• Số điện thoại: ' + (p.phone || '') + '\n' +
      '• Địa chỉ nhận hàng: ' + (p.address || '') + '\n' +
      '• Gói đặt mua: ' + (p.package || p.product || '') + '\n' +
      '• Giá tiền: ' + (p.price || '') + '\n' +
      '• Ghi chú / Tình trạng: ' + (p.note || '') + '\n' +
      '--------------------------------------------------\n' +
      '2. NGUỒN QUẢNG CÁO & THEO DÕI (ĐÃ TÁCH):\n' +
      '• utm_source: ' + (p.utm_source || '(trực tiếp / không có)') + '\n' +
      '• utm_medium: ' + (p.utm_medium || '') + '\n' +
      '• utm_campaign: ' + (p.utm_campaign || '') + '\n' +
      '• utm_content: ' + (p.utm_content || '') + '\n' +
      '• utm_term: ' + (p.utm_term || '') + '\n' +
      '• Facebook Click ID (fbclid): ' + (p.fbclid || '') + '\n' +
      '• Google Click ID (gclid): ' + (p.gclid || '') + '\n' +
      '• TikTok Click ID (ttclid): ' + (p.ttclid || '') + '\n' +
      '• Trang đặt hàng: ' + (p.page || '') + '\n' +
      '• Thời gian: ' + (p.time || Utilities.formatDate(new Date(), 'Asia/Ho_Chi_Minh', 'dd/MM/yyyy HH:mm:ss')) + '\n' +
      '==================================================\n' +
      'Email này được gửi tự động đến: ' + NOTIFY_EMAIL;

    MailApp.sendEmail(NOTIFY_EMAIL, subject, body);
  } catch (err) {
    Logger.log('Lỗi gửi mail: ' + err.toString());
  }
}

/**
 * Tự động tạo menu trong Google Sheet khi mở
 */
function onOpen() {
  try {
    var ui = SpreadsheetApp.getUi();
    ui.createMenu('⚡ Tiện ích Bon Bon')
      .addItem('🔄 Tách URL các dòng cũ sang đúng cột', 'fixExistingRows')
      .addToUi();
  } catch (e) {}
}

/**
 * TIỆN ÍCH: Quét các dòng cũ trong Google Sheet, tự động cắt URL và điền vào các cột utm, fbclid...
 */
function fixExistingRows() {
  var sheet = getSheet();
  var lastRow = sheet.getLastRow();
  var lastCol = sheet.getLastColumn();
  if (lastRow <= 1 || lastCol <= 1) {
    SpreadsheetApp.getUi().alert('Không có dữ liệu để xử lý.');
    return;
  }

  var headerRow = sheet.getRange(1, 1, 1, lastCol).getValues()[0];
  var colMap = {};
  for (var c = 0; c < headerRow.length; c++) {
    var h = String(headerRow[c] || '').trim().toLowerCase();
    colMap[h] = c + 1;
  }

  var pageColIdx = colMap['trang đặt hàng'] || colMap['trang dat hang'] || colMap['trang'] || colMap['page'];
  if (!pageColIdx) {
    SpreadsheetApp.getUi().alert('Không tìm thấy cột "Trang đặt hàng"!');
    return;
  }

  var range = sheet.getRange(2, 1, lastRow - 1, lastCol);
  var values = range.getValues();
  var updatedCount = 0;

  for (var r = 0; r < values.length; r++) {
    var row = values[r];
    var currentUrl = String(row[pageColIdx - 1] || '').trim();

    if (currentUrl && (currentUrl.indexOf('?') !== -1 || currentUrl.indexOf('fbclid=') !== -1 || currentUrl.indexOf('utm_') !== -1)) {
      var dummyP = {};
      parseAndExtractTracking(currentUrl, dummyP);

      // Cập nhật lại link sạch vào ô Trang đặt hàng
      row[pageColIdx - 1] = dummyP.page || currentUrl.split('?')[0];

      // Đưa giá trị tracking vào các cột tương ứng nếu ô đó còn trống
      var trackingKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'ttclid'];
      for (var k = 0; k < trackingKeys.length; k++) {
        var key = trackingKeys[k];
        var cIdx = colMap[key] || colMap['t' + key.slice(1)];
        if (cIdx && dummyP[key]) {
          if (!row[cIdx - 1] || row[cIdx - 1] === '') {
            row[cIdx - 1] = dummyP[key];
          }
        }
      }
      updatedCount++;
    }
  }

  if (updatedCount > 0) {
    range.setValues(values);
    SpreadsheetApp.getUi().alert('Thành công! Đã xử lý và tách URL cho ' + updatedCount + ' dòng dữ liệu.');
  } else {
    SpreadsheetApp.getUi().alert('Tất cả các dòng hiện tại đã sạch sẽ và đúng cột!');
  }
}

/**
 * Lấy Sheet đơn hàng
 */
function getSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName(SHEET_NAME) || ss.getActiveSheet();
  return sheet;
}

function styleHeader(sheet, colCount) {
  var range = sheet.getRange(1, 1, 1, colCount);
  range.setFontWeight('bold');
  range.setBackground('#4cc0d3');
  range.setFontColor('#ffffff');
  range.setHorizontalAlignment('center');
  sheet.setFrozenRows(1);
  sheet.autoResizeColumns(1, colCount);
}

function jsonResponse(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
