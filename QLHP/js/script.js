document.addEventListener('DOMContentLoaded', () => {
  initSearch();
  showFlashToast();
  initAnchorCenterScroll();
});

// CUỘN DANH MỤC KHÓA HỌC RA GIỮA MÀN HÌNH 
function initAnchorCenterScroll() {
  document.querySelectorAll('a[href*="khoahoc.php#"]').forEach(link => {
    link.addEventListener('click', (e) => {
      const url = new URL(link.href, window.location.href);
      if (url.pathname !== window.location.pathname || !url.hash) return;

      const target = document.querySelector(url.hash);
      if (!target) return;

      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'center' });
      history.pushState(null, '', url.hash);
    });
  });
}
// TÌM KIẾM: GỢI Ý + LỊCH SỬ (không ẩn khóa học khác trong danh sách)

const SEARCH_HISTORY_KEY = 'khoahoc_search_history';
const SEARCH_HISTORY_MAX = 5;

function getSearchHistory() {
  try {
    return JSON.parse(localStorage.getItem(SEARCH_HISTORY_KEY)) || [];
  } catch {
    return [];
  }
}

function addSearchHistory(keyword) {
  keyword = keyword.trim();
  if (!keyword) return;
  let history = getSearchHistory().filter(k => k.toLowerCase() !== keyword.toLowerCase());
  history.unshift(keyword);
  history = history.slice(0, SEARCH_HISTORY_MAX);
  localStorage.setItem(SEARCH_HISTORY_KEY, JSON.stringify(history));
}

function removeSearchHistory(keyword) {
  const history = getSearchHistory().filter(k => k !== keyword);
  localStorage.setItem(SEARCH_HISTORY_KEY, JSON.stringify(history));
}

function initSearch() {
  const input = document.getElementById('searchInput');
  const box = document.getElementById('searchSuggestions');
  if (!input || !box) return;

  input.addEventListener('input', () => renderSearchSuggestions(input.value));
  input.addEventListener('focus', () => renderSearchSuggestions(input.value));

  input.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter') return;
    const firstItem = box.querySelector('.search-suggestion-item[data-makh]');
    if (firstItem) {
      firstItem.click();
    } else if (input.value.trim()) {
      addSearchHistory(input.value);
      renderSearchSuggestions(input.value);
    }
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-wrap')) {
      box.classList.remove('open');
    }
  });
}

function renderSearchSuggestions(rawKeyword) {
  const box = document.getElementById('searchSuggestions');
  if (!box) return;
  const keyword = rawKeyword.trim().toLowerCase();

  if (!keyword) {
    const history = getSearchHistory();
    if (!history.length) {
      box.classList.remove('open');
      box.innerHTML = '';
      return;
    }
    box.innerHTML = '<div class="search-suggestions-label">Tìm kiếm gần đây</div>' +
      history.map(k => `
        <div class="search-suggestion-item search-history-item" data-history="${escapeHTML(k)}">
          <span class="sug-name">🕓 ${escapeHTML(k)}</span>
          <span class="remove-history" data-remove="${escapeHTML(k)}">&times;</span>
        </div>
      `).join('');
    box.classList.add('open');
    bindSuggestionEvents();
    return;
  }

  const matches = ALL_COURSES.filter(c => c.tenkh.toLowerCase().includes(keyword)).slice(0, 8);

  if (!matches.length) {
    box.innerHTML = '<div class="search-suggestion-empty">Không tìm thấy khóa học phù hợp</div>';
    box.classList.add('open');
    return;
  }

  box.innerHTML = matches.map(c => `
    <div class="search-suggestion-item" data-makh="${c.makh}" data-name="${escapeHTML(c.tenkh)}">
      <span class="sug-name">${escapeHTML(c.tenkh)}</span>
      <span class="sug-price">${Number(c.gia).toLocaleString('vi-VN')}đ</span>
    </div>
  `).join('');
  box.classList.add('open');
  bindSuggestionEvents();
}

function bindSuggestionEvents() {
  const box = document.getElementById('searchSuggestions');
  if (!box) return;

  box.querySelectorAll('.search-suggestion-item[data-makh]').forEach(item => {
    item.addEventListener('click', () => {
      addSearchHistory(item.dataset.name);
      goToCourse(item.dataset.makh);
    });
  });

  box.querySelectorAll('.search-history-item').forEach(item => {
    item.addEventListener('click', (e) => {
      if (e.target.closest('.remove-history')) return;
      const keyword = item.dataset.history;
      document.getElementById('searchInput').value = keyword;
      renderSearchSuggestions(keyword);
    });
  });

  box.querySelectorAll('.remove-history').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      removeSearchHistory(btn.dataset.remove);
      renderSearchSuggestions(document.getElementById('searchInput').value);
    });
  });
}

function goToCourse(makh) {
  const box = document.getElementById('searchSuggestions');
  const input = document.getElementById('searchInput');
  const card = document.querySelector(`.roadmap .course-card[data-makh="${makh}"]`);

  box.classList.remove('open');
  input.value = '';

  if (!card) return;
  card.scrollIntoView({ behavior: 'smooth', block: 'center' });
  setTimeout(() => toggleDetail(card, makh), 300);
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

// XỬ LÝ SỔ CHI TIẾT: 1 PANEL DÙNG CHUNG, DI CHUYỂN THEO HÀNG ĐANG BẤM

function getRowCards(roadmap, clickedCard) {
  const cards = Array.from(roadmap.querySelectorAll(':scope > .course-card'));
  const top = clickedCard.offsetTop;
  return cards.filter(c => Math.abs(c.offsetTop - top) < 3);
}

function toggleDetail(cardEl, makh) {
  const roadmap = cardEl.closest('.roadmap');
  const panel = roadmap.querySelector('.detail-panel');
  if (!panel) return;

  const isSameOpen = panel.dataset.openId === String(makh) && panel.classList.contains('open');

  // Đóng panel hiện tại trước (dù đang mở ở khóa nào, kể cả ở danh mục khác)
  document.querySelectorAll('.detail-panel.open').forEach(p => {
    p.classList.remove('open');
    p.dataset.openId = '';
  });

  if (isSameOpen) {
    // Bấm lại đúng khóa đang mở -> đóng lại, không mở nữa
    setTimeout(() => {
      if (!panel.classList.contains('open')) panel.innerHTML = '';
    }, 350);
    return;
  }

  const course = ALL_COURSES.find(c => String(c.makh) === String(makh));
  if (!course) return;

  panel.innerHTML = renderDetailHTML(course);
  panel.dataset.openId = String(makh);

  // Dời panel tới ngay sau card cuối cùng của HÀNG chứa card vừa bấm
  const rowCards = getRowCards(roadmap, cardEl);
  const lastCardInRow = rowCards[rowCards.length - 1];
  lastCardInRow.insertAdjacentElement('afterend', panel);

  requestAnimationFrame(() => {
    panel.classList.add('open');
  });

  setTimeout(() => {
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }, 150);
}

// Bấm ra ngoài để đóng hết panel đang mở
document.addEventListener('click', function (e) {
  if (
    !e.target.closest('.course-card') &&
    !e.target.closest('.detail-panel') &&
    !e.target.closest('.modal-overlay')
  ) {
    document.querySelectorAll('.detail-panel.open').forEach(p => {
      p.classList.remove('open');
      p.dataset.openId = '';
      setTimeout(() => {
        if (!p.classList.contains('open')) p.innerHTML = '';
      }, 350);
    });
  }
});

// Khi resize màn hình, số cột mỗi hàng có thể đổi -> dời lại panel đang mở cho đúng vị trí
window.addEventListener('resize', debounce(() => {
  document.querySelectorAll('.detail-panel.open').forEach(panel => {
    const roadmap = panel.closest('.roadmap');
    const openId = panel.dataset.openId;
    if (!roadmap || !openId) return;
    const card = roadmap.querySelector(`.course-card[data-makh="${openId}"]`);
    if (!card) return;
    const rowCards = getRowCards(roadmap, card);
    rowCards[rowCards.length - 1].insertAdjacentElement('afterend', panel);
  });
}, 200));

function debounce(fn, wait) {
  let t;
  return (...args) => {
    clearTimeout(t);
    t = setTimeout(() => fn(...args), wait);
  };
}

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