let inactivityTimeout;



// Lắng nghe sự kiện của người dùng (nhập tin nhắn, di chuyển chuột, v.v.)
document.addEventListener('mousemove', resetInactivityTimer);
document.addEventListener('keydown', resetInactivityTimer);
document.addEventListener('click', resetInactivityTimer);

// Bắt đầu timer khi trang được load
window.onload = function () {
    resetInactivityTimer();
};

