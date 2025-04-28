let inactivityTimeout;

function resetChat() {
    // Xóa nội dung chat hiện tại
    document.getElementById('chat-box').innerHTML = '';
    // Tạo một thông báo chào đón lại
    const botMessage = document.createElement('div');
    botMessage.classList.add('bot-msg');
    botMessage.innerText = "💬 Chào bạn! Cuộc trò chuyện đã được khởi động lại vì không có hoạt động trong 5 phút.";
    document.getElementById('chat-box').appendChild(botMessage);
}

function resetInactivityTimer() {
    // Clear previous timeout nếu có
    clearTimeout(inactivityTimeout);
    // Đặt lại timeout sau 5 phút (300000ms)
    inactivityTimeout = setTimeout(resetChat, 300000); // 5 minutes
}

// Lắng nghe sự kiện của người dùng (nhập tin nhắn, di chuyển chuột, v.v.)
document.addEventListener('mousemove', resetInactivityTimer);
document.addEventListener('keydown', resetInactivityTimer);
document.addEventListener('click', resetInactivityTimer);

// Bắt đầu timer khi trang được load
window.onload = function () {
    resetInactivityTimer();
};

