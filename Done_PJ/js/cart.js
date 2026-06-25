// Lấy các phần tử trong DOM
const buyButtons = document.querySelectorAll(".btn-buy");
const cartContainer = document.querySelector(".cart-container");
const cartSummary = document.getElementById("total");
const removeAllButton = document.querySelector(".removeAll");

// Giỏ hàng từ localStorage
let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Hàm định dạng tiền tệ
function formatCurrency(amount) {
  const formatter = new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  });
  return formatter.format(amount);
}

// Hàm hiển thị giỏ hàng
function renderCart() {
  cartContainer.innerHTML = "";
  let total = 0;

  cart.forEach((product) => {
    total += product.price * product.quantity;

    cartContainer.innerHTML += `
      <div class="cart-part">
        <div class="cart-img">
          <img src="${product.image}" alt="${product.name}" />
        </div>
        <div class="cart-desc">
          <h3>${product.name}</h3>
        </div>
        <div class="cart-quantity">
          <input type="number" value="${product.quantity}" min="1" onchange="updateQuantity('${product.id}', this.value)" />
        </div>
        <div class="cart-price">
          <h4>${formatCurrency(product.price)}</h4>
        </div>
        <div class="cart-total">
          <h4>${formatCurrency(product.price * product.quantity)}</h4>
        </div>
        <div class="cart-remove">
          <button onclick="removeFromCart('${product.id}')">Xóa sản phẩm</button>
        </div>
      </div>`;
  });

  cartSummary.textContent = formatCurrency(total);
}

// Hàm cập nhật số lượng sản phẩm
function updateQuantity(id, quantity) {
  const product = cart.find((item) => item.id === id);
  if (product) {
    product.quantity = parseInt(quantity);
    localStorage.setItem("cart", JSON.stringify(cart));
    renderCart();
    updateCartCount();
  }
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeFromCart(id) {
  cart = cart.filter((item) => item.id !== id);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
  updateCartCount();
}

// Hàm cập nhật số lượng sản phẩm trong biểu tượng giỏ hàng
function updateCartCount() {
  const cartCount = document.querySelector(".cart-count");
  cartCount.textContent = cart.reduce(
    (total, product) => total + product.quantity,
    0
  );
}

// Hàm xóa toàn bộ giỏ hàng
removeAllButton.addEventListener("click", function () {
  localStorage.removeItem("cart");
  cart = [];
  renderCart();
  updateCartCount();
  cartSummary.textContent = formatCurrency(0);
});

// Hiển thị giỏ hàng khi tải trang
renderCart();
updateCartCount();
