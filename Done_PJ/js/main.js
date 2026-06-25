$(document).ready(function(){
    $('#eye').click(function(){
        $(this).toggleClass('open');
        $(this).children('i').toggleClass('fa-eye-slash fa-eye');
        if($(this).hasClass('open')){
            $(this).prev().attr('type', 'text');
        }else{
            $(this).prev().attr('type', 'password');
        }
    });
});

//Element của trang
const formLogin = document.getElementById("form-login");
const usernameElement = document.getElementById("username");
const passwordElement = document.getElementById("password");
const usernameError = document.getElementById("usernameError");
const passwordError = document.getElementById("passwordError");
//Lắng nghe sự kiện submit form đăng nhập tài khoản
formLogin.addEventListener("submit", function(e){
    //Ngăn chặn sự kiện load lại trang
    e.preventDefault();

    //Validate dữ liệu vào
    if (!usernameElement.value){
        //Hiển thị lỗi
        usernameError.style.display ="block";
    } else {
        //Ẩn lỗi
        usernameError.style.display="none";
    }

    //Validate dữ liệu vào
    if (!passwordElement.value){
        //Hiển thị lỗi
        passwordError.style.display ="block";
    } else {
        //Ẩn lỗi
        passwordError.style.display="none";
    }

    //Lấy dữ liệu từ local về
    const userLocal= JSON.parse(localStorage.getItem("users")) || [];

    //Tìm kiếm tên đăng nhập và mật khẩu người dùng có tồn tại trên local không
    const findUser = userLocal.find(
        (user) => 
            user.userName === usernameElement.value && 
            user.userPassword  === passwordElement.value);
     console.log(findUser);
    if(!findUser){
        alert("Tên đăng nhập hoặc mật khẩu không đúng");
    } else{
        //Nếu có thì đăng nhập thành công và chuyển hướng về trang chủ
        window.location.href = "trangchu.html";
    }
});