document.addEventListener('DOMContentLoaded', () => {
  initSearch();
  showFlashToast();
});

function initSearch() {
  const input = document.getElementById('searchInput');
  if (!input) return;

  input.addEventListener('input', () => {
    const keyword = input.value.trim().toLowerCase();
    document.querySelectorAll('.roadmap .course-card[data-name]').forEach(card => {
      const name = card.dataset.name || '';
      card.style.display = name.includes(keyword) ? '' : 'none';
    });
  });
}

function showFlashToast() {
  const container = document.querySelector('.khoahoc-container');
  if (!container) return;

  const type = container.dataset.flashType;
  const msg  = container.dataset.flashMsg;
  if (!type || !msg) return;

  showToast(msg, type);
}

function showToast(msg, type = 'success') {
  const toast = document.getElementById('toast');
  if (!toast) return;

  toast.textContent = msg;
  toast.className = 'toast show ' + type;

  clearTimeout(showToast._timer);
  showToast._timer = setTimeout(() => {
    toast.className = 'toast';
  }, 3000);
}

// XỬ LÝ SỔ CHI TIẾT THEO TỪNG CẶP Ô TRÊN GRID NHIỀU HÀNG
let currentOpenId = null;

function toggleDetail(panelId, makh) {
  const panel = document.getElementById('detail-' + panelId);
  if (!panel) return;

  // Nếu click lại vào ô đang mở -> Thu gọn đóng lại
  if (currentOpenId === panelId) {
    panel.classList.remove('open');
    setTimeout(() => {
      if (!panel.classList.contains('open')) panel.innerHTML = '';
    }, 350);
    currentOpenId = null;
    return;
  }

  // Đóng toàn bộ các bảng chi tiết khác đang mở trên giao diện trước
  document.querySelectorAll('.detail-panel').forEach(p => {
    p.classList.remove('open');
    p.innerHTML = '';
  });

  const course = ALL_COURSES.find(c => String(c.makh) === String(makh));
  if (!course) return;

  panel.innerHTML = renderDetailHTML(course);
  
  // Kích hoạt animation thông qua requestAnimationFrame
  requestAnimationFrame(() => {
    panel.classList.add('open');
  });
  currentOpenId = panelId;

  setTimeout(() => {
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }, 150);
}

// Bấm click ra ngoài khoảng trống không thuộc card/panel để thu gọn layout lại
document.addEventListener('click', function(e) {
  if (!e.target.closest('.course-card') && !e.target.closest('.detail-panel')) {
    document.querySelectorAll('.detail-panel').forEach(p => {
      p.classList.remove('open');
      setTimeout(() => {
        if (!p.classList.contains('open')) p.innerHTML = '';
      }, 350);
    });
    currentOpenId = null;
  }
});

function renderDetailHTML(course) {
  const noidung = Array.isArray(course.noidung) ? course.noidung : [];
  const ketqua  = Array.isArray(course.ketqua)  ? course.ketqua  : [];
  const isRegistered = REGISTERED_IDS.map(String).includes(String(course.makh));

  const noidungHTML = noidung.length
    ? '<ul>' + noidung.map(nd => `<li>${escapeHTML(nd)}</li>`).join('') + '</ul>'
    : '<p>Đang cập nhật nội dung khóa học.</p>';

  const ketquaHTML = ketqua.length
    ? `<table><thead><tr><th>Đầu ra</th></tr></thead><tbody>${
        ketqua.map(kq => `<tr><td>${escapeHTML(kq)}</td></tr>`).join('')
      }</tbody></table>`
    : '';

  const enrollButtonHTML = isRegistered
    ? `<span class="tag-registered">Bạn đã đăng ký khóa học này</span>`
    : `<button type="button" class="btn-enroll" onclick="openEnrollModal('${course.makh}')">Đăng ký khóa học</button>`;

  return `
    <h3>${escapeHTML(course.tenkh)}</h3>
    <p><strong>Giảng viên:</strong> ${escapeHTML(course.giangvien)}</p>
    <p><strong>Học phí:</strong> ${Number(course.gia).toLocaleString('vi-VN')}đ</p>
    <p>${escapeHTML(course.mota || '')}</p>
    <h4>Nội dung khóa học</h4>
    ${noidungHTML}
    ${ketquaHTML ? '<h4>Kết quả đầu ra</h4>' + ketquaHTML : ''}
    ${enrollButtonHTML}
  `;
}

function escapeHTML(str) {
  const div = document.createElement('div');
  div.textContent = str ?? '';
  return div.innerHTML;
}

function openEnrollModal(makh) {
  if (!IS_LOGGED_IN) {
    showToast('Vui lòng đăng nhập trước khi đăng ký khóa học.', 'error');
    return;
  }

  const course = ALL_COURSES.find(c => String(c.makh) === String(makh));
  if (!course) return;

  const price = Number(course.gia).toLocaleString('vi-VN');
  const noiDungCK = `DK ${course.makh} ${Date.now().toString().slice(-6)}`;

  const modalHTML = `
    <div class="modal-overlay" id="enrollModalOverlay">
      <div class="modal-box">
        <button type="button" class="modal-close" onclick="closeEnrollModal()">&times;</button>
        <h3>Đăng ký khóa học</h3>
        <p class="modal-course-name">${escapeHTML(course.tenkh)}</p>
        <p class="modal-price">Học phí: <strong>${price}đ</strong></p>

        <div class="bank-info">
          <h4>Thông tin chuyển khoản</h4>
          <p>Ngân hàng: <strong>Vietcombank</strong></p>
          <p>Số tài khoản: <strong>0123456789</strong></p>
          <p>Chủ tài khoản: <strong>TRUNG TAM ANH NGU</strong></p>
          <p>Số tiền: <strong>${price}đ</strong></p>
          <p>Nội dung CK: <strong>${noiDungCK}</strong></p>
        </div>

        <form id="enrollForm" method="POST" action="khoahoc.php">
          <input type="hidden" name="action" value="enroll">
          <input type="hidden" name="course_id" value="${escapeHTML(course.makh)}">

          <div class="form-group">
            <label for="ma_gd">Mã giao dịch / Mã tham chiếu</label>
            <input type="text" id="ma_gd" name="ma_giao_dich" required placeholder="VD: FT23189xxxxxxx">
          </div>

          <div class="form-group">
            <label for="so_tien">Số tiền đã chuyển (đ)</label>
            <input type="number" id="so_tien" name="so_tien" required value="${course.gia}">
          </div>

          <label class="confirm-checkbox">
            <input type="checkbox" required>
            Tôi xác nhận đã chuyển khoản đúng nội dung và số tiền ở trên.
          </label>

          <button type="submit" class="btn-submit-enroll">Xác nhận đăng ký</button>
        </form>
      </div>
    </div>
  `;

  document.body.insertAdjacentHTML('beforeend', modalHTML);
  document.body.style.overflow = 'hidden';
}

function closeEnrollModal() {
  const overlay = document.getElementById('enrollModalOverlay');
  if (overlay) overlay.remove();
  document.body.style.overflow = '';
}



// TRANG CHỦ



// TRANG CHỦ