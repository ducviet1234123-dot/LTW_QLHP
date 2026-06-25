// Lấy ra element của trang

const formRegister = document.getElementById("formRegister");
const userNameElement = document.getElementById("userName");
const usernameElement = document.getElementById("username");
const emailElement = document.getElementById("email");
const passwordElement = document.getElementById("password");
const repasswordElement = document.getElementById("repassword");
const addressElement = document.getElementById("address");
//Element lieên qun đến lỗi
const userNameError = document.getElementById("userNameError");
const usernameError = document.getElementById("usernameError");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");
const repasswordError = document.getElementById("repasswordError");

//Lấy dữ liệu từ localStorage
const userLocal= JSON.parse(localStorage.getItem("users")) || [];

//Lắng nghe sự kiện submit đăng ký tài khoản
formRegister.addEventListener("submit", function(e){
    //Ngăn chặn sự kiện load lại trang
    e.preventDefault()

    //Validate dữ liệu vào
    if (!userNameElement.value){
        //Hiển thị lỗi
        userNameError.style.display ="block";
    } else {
        //Ẩn lỗi
        userNameError.style.display="none";
    }

    //Validate dữ liệu vào
    if (!usernameElement.value){
        //Hiển thị lỗi
        usernameError.style.display ="block";
    } else {
        //Ẩn lỗi
        usernameError.style.display="none";
    }

    //Validate dữ liệu vào
    if (!emailElement.value){
        //Hiển thị lỗi
        emailError.style.display ="block";
    } else {
        //Ẩn lỗi
        emailError.style.display="none";
    }

    //Validate dữ liệu vào
    if (!passwordElement.value){
        //Hiển thị lỗi
        passwordError.style.display ="block";
    } else {
        //Ẩn lỗi
        passwordError.style.display="none";
    }

    //Validate dữ liệu vào
    if (!repasswordElement.value){
        //Hiển thị lỗi
        repasswordError.style.display ="block";
    } else {
        //Ẩn lỗi
        repasswordError.style.display="none";
    }

    //kiểm tra mật khẩu với nhập lại mật khẩu
    if(passwordElement.value !== repasswordElement.value){
        repasswordError.style.display ="block";
        repasswordError.innerHTML = "Mật khẩu không trùng khớp";
    }

    //Gửi dữ liệu từ form lên localStorage
    if(
        userNameElement.value && 
        usernameElement.value && 
        emailElement.value &&
        passwordElement.value &&
        repasswordElement.value &&
        passwordElement.value == repasswordElement.value
    ){
        //Lấy dữ liệu từ form và gộp thành đối tượng user
        const user ={
            userId: Math.ceil(Math.random() * 100000000),
            userName: usernameElement.value,
            userEmail: emailElement.value,
            userPassword: passwordElement.value,
        }

        // console.log(user);

        //Push user vào trong mảng userlocal
        userLocal.push(user);

        //Lưu trưc dữu liệu lên Local
        localStorage.setItem("users",JSON.stringify(userLocal));

        //Chuyển hướng về trang đăng nhập
        window.location.href ="dangnhap.html";
    }
});
