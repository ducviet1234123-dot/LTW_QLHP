
document.addEventListener('DOMContentLoaded', function() {

  const searchIcon = document.querySelector('.search-icon'); // Icon kính lúp
  const searchInput = document.getElementById('search'); // Ô input tìm kiếm

 
  function executeSearch() {
      const searchTerm = searchInput.value.trim();

      if (searchTerm) {
          console.log('Thực hiện tìm kiếm cho:', searchTerm);

      } else {
          console.log('Ô tìm kiếm trống.');
      }
  }

  if (searchIcon) {
      searchIcon.addEventListener('click', function() {
          executeSearch(); 
      });
  }

  if (searchInput) {
      searchInput.addEventListener('keypress', function(event) {
      
          if (event.key === 'Enter') {
              event.preventDefault(); // Ngăn hành động mặc định của Enter (thường là submit form nếu có)
              executeSearch(); // Gọi hàm thực hiện tìm kiếm
          }
      });
  }

}); // Kết thúc sự kiện DOMContentLoaded

document.querySelectorAll('.slider-container').forEach((sliderContainer) => {
    const sliderWrapper = sliderContainer.querySelector('.slider-wrapper');
    const slides = sliderContainer.querySelectorAll('.slide');
    const prevButton = sliderContainer.querySelector('.prev-button');
    const nextButton = sliderContainer.querySelector('.next-button');
    const dots = sliderContainer.querySelectorAll('.dot');
  
    let slideIndex = 0;
  
    function showSlide(index) {
      sliderWrapper.style.transform = `translateX(-${index * 100}%)`;
      dots.forEach(dot => dot.classList.remove('active'));
      dots[index].classList.add('active');
    }
  
    function nextSlide() {
      slideIndex = (slideIndex + 1) % slides.length;
      showSlide(slideIndex);
    }
  
    function prevSlide() {
      slideIndex = (slideIndex - 1 + slides.length) % slides.length;
      showSlide(slideIndex);
    }
  
    nextButton.addEventListener('click', nextSlide);
    prevButton.addEventListener('click', prevSlide);
  
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        slideIndex = index;
        showSlide(slideIndex);
      });
    });
  });
  
  const addCartButtons = document.querySelectorAll(".btn-buy");

  addCartButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const item = button.closest(".product-item");
      const productId = item.getAttribute("data-id");
      const productName = item.getAttribute("data-name");
      const productPrice = item.getAttribute("data-price");
      const productImage = item.querySelector(".item_img").src;
  
    const product = {
      id: productId,
      name: productName,
      price: productPrice,
      image: productImage,
      quantity: 1,
    };
  
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
  
    const existingProductIndex = cart.findIndex(
      (item) => item.id === productId
    );
  
    if (existingProductIndex >= 0) {
      cart[existingProductIndex].quantity += 1;
    } else {
      cart.push(product);
    }
  
    localStorage.setItem("cart", JSON.stringify(cart));
  
    updateCartCount();
  
    alert("Sản phẩm đã được thêm vào giỏ hàng");
    });
  });
  
  function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const cartCount = document.querySelector(".cart-count");
  cartCount.textContent = cart.reduce(
    (total, product) => total + product.quantity,
    0
  );
  }
  
  updateCartCount();

