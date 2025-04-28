<?php
session_start();
include_once('core/chatlogic.php');

// Cấu hình tên công ty
$companyName = 'Global Health Corp.';

// Khởi tạo lịch sử trò chuyện nếu chưa có
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [
        ['sender' => 'bot', 'message' => "Chào bạn! Tôi là AI hỗ trợ y tế từ {$companyName}. Bạn cần giúp gì hôm nay?"]
    ];
}

// Nếu người dùng gửi tin nhắn mới
if (isset($_POST['user_input'])) {
    $user_input = trim($_POST['user_input']);

    // Thêm tin nhắn người dùng vào lịch sử
    $_SESSION['history'][] = ['sender' => 'user', 'message' => $user_input];

    // Xử lý phản hồi từ chatbot
    $bot_responses = chatbotProcess($user_input, $companyName); // truyền tên công ty nếu muốn

    // Thêm từng phản hồi của chatbot vào lịch sử
    foreach ($bot_responses as $entry) {
        $_SESSION['history'][] = ['sender' => 'bot', 'message' => $entry['message']];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Global Health Corp. - Trung Tâm Y Tế AI</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/chat-ui.js" defer></script>
</head>
<body>
  <div class="chat-container">
    <div class="chat-header">🤖 Trung Tâm Y Tế AI - Global Health Corp.</div>

    <div id="chat-box" class="chat-box">
      <?php
        // Hiển thị toàn bộ lịch sử tin nhắn
        foreach ($_SESSION['history'] as $entry) {
            $cssClass = $entry['sender'] === 'user' ? 'user-msg' : 'bot-msg';
            echo "<div class='{$cssClass}'>" . htmlspecialchars($entry['message']) . "</div>";
        }
      ?>
    </div>

    <form method="POST" id="chat-form" autocomplete="off">
      <input type="text" name="user_input" id="user_input" placeholder="Nhập tin nhắn..." required>
      <button type="submit">Gửi</button>
    </form>
  </div>
</body>
</html>
