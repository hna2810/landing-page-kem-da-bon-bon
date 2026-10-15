<?php
/**
 * Template part: Registration Form
 *
 * @package LXLanding
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<section class="section form-section" id="form-section">
    <div class="container">
        <div class="form-layout">
            <aside class="form-perks reveal">
                <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-puppy.webp" alt="" class="perks-mascot" loading="lazy" aria-hidden="true">
                <h3>Quyền lợi khi mẹ đăng ký hôm nay</h3>
                <ul class="perks-list">
                    <li><span class="perk-icon">🎁</span><div><b>Chỉ 49.000đ</b><small>Tiết kiệm 231K so với giá gốc 280K</small></div></li>
                    <li><span class="perk-icon">💰</span><div><b>Thanh toán khi nhận hàng</b><small>Không cần chuyển khoản trước</small></div></li>
                    <li><span class="perk-icon">🚚</span><div><b>Giao hàng toàn quốc</b><small>Nhận hàng tận nhà, tiện cho mẹ bỉm</small></div></li>
                    <li><span class="perk-icon">👩‍⚕️</span><div><b>Tư vấn chăm da bé miễn phí</b><small>Chuyên viên hỗ trợ mẹ 24/7</small></div></li>
                    <li><span class="perk-icon">✅</span><div><b>Hàng chính hãng 100%</b><small>Có tem chống hàng giả Bộ Công An</small></div></li>
                </ul>
                <div class="perks-contact">Cần đặt nhanh? Gọi <a href="tel:0901234567" class="js-hotline">0901.234.567</a></div>
            </aside>
            <div class="form-col">
                <img src="<?php echo LX_THEME_URI; ?>/assets/mascot-kitten.webp" alt="" class="form-peek" loading="lazy" aria-hidden="true">
            <div class="form-wrapper reveal-scale">
                <span class="form-deco-left">🍼</span>
                    
                <div id="formContent">
                    <div class="form-header">
                        <h2>ĐĂNG KÝ NHẬN ƯU ĐÃI 49K NGAY! 🎉</h2>
                        <p>Điền thông tin để nhận ưu đãi độc quyền từ Bon Bon</p>
                    </div>
                    
                    <form id="registrationForm" novalidate>
                        <div class="form-group">
                            <label for="fullName">Họ và tên <span class="req">*</span></label>
                            <input type="text" class="form-input" id="fullName" name="fullName" placeholder="VD: Nguyễn Thị Lan" autocomplete="name" maxlength="80" required>
                            <div class="error-msg" id="nameError">Vui lòng nhập họ và tên</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Số điện thoại <span class="req">*</span></label>
                            <input type="tel" class="form-input" id="phone" name="phone" placeholder="VD: 0901 234 567" autocomplete="tel" inputmode="tel" maxlength="16" required>
                            <div class="error-msg" id="phoneError">Vui lòng nhập số điện thoại hợp lệ</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-input" id="email" name="email" placeholder="VD: mama@email.com" autocomplete="email" maxlength="120">
                            <div class="error-msg" id="emailError">Email chưa đúng định dạng</div>
                        </div>
                        
                        <div class="form-group">
                            <label>Chọn sản phẩm <span class="req">*</span></label>
                            <div class="radio-group">
                                <label class="radio-option active">
                                    <input type="radio" name="product" value="kem-49k" checked>
                                    <span class="radio-label">🧴 Kem Bon Bon (Mỗi người 1 lần)</span>
                                    <span class="radio-price">49K</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="product" value="combo-50">
                                    <span class="radio-label">🎁 Combo 3 SP (Giảm 50%)</span>
                                    <span class="radio-price">420K</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="note">Ghi chú (tuỳ chọn)</label>
                            <textarea class="form-input" id="note" name="note" rows="3" maxlength="500" placeholder="VD: Bé 3 tháng, bị chàm sữa ở má..."></textarea>
                        </div>
                        
                        <!-- Chống spam: bot sẽ điền ô ẩn này -->
                        <div class="hp-field" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <button type="submit" class="btn-submit" id="submitBtn">
                            🚀 GỬI ĐĂNG KÝ NGAY
                        </button>
                        <div class="form-alert" id="formAlert" role="alert"></div>
                    </form>
                    
                    <p class="form-security">🔒 Thông tin của mẹ được bảo mật tuyệt đối</p>
                </div>
                
                <div class="form-success" id="formSuccess" role="status" aria-live="polite">
                    <div class="success-emoji">🎉</div>
                    <h3>Chúc Mừng Mẹ! Đăng Ký Thành Công!</h3>
                    <p>Cảm ơn mẹ đã tin tưởng Bon Bon. Chúng tôi sẽ liên hệ với mẹ trong vòng <strong>30 phút</strong> để xác nhận đơn hàng và gửi ưu đãi nhé! 🌸</p>
                    <div class="order-summary" id="orderSummary"></div>
                    <div class="success-actions">
                        <a href="https://zalo.me/0901234567" target="_blank" rel="noopener" class="sa-zalo">💬 Nhắn Zalo ngay</a>
                        <a href="tel:0901234567" class="sa-phone">📞 Gọi <span class="js-hotline">0901.234.567</span></a>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>

<!-- CẦU NỐI -->
<div class="bridge">
    <a href="#faq-section" class="bridge-link">
        <span class="bridge-icon" aria-hidden="true">❓</span>
        <span>Mẹ còn thắc mắc? Xem giải đáp nhé</span>
        <span class="bridge-arrow" aria-hidden="true">↓</span>
    </a>
</div>

<!-- ============================================
     FAQ SECTION
     ============================================ -->
