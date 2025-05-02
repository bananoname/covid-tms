<?php
session_start();
include_once('core/chatlogic.php');

// Cấu hình tên công ty
$companyName = 'Global Health Corp.';

// Reset session nếu có yêu cầu
if (isset($_GET['reset']) && $_GET['reset'] == 1) {
    unset($_SESSION['history']);
    header("Location: index.php");
    exit;
}

// Khởi tạo lịch sử trò chuyện nếu chưa có
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [
        ['sender' => 'bot', 'message' => "Chào bạn! Tôi là AI hỗ trợ y tế từ {$companyName}. Bạn cần giúp gì hôm nay?"]
    ];
}

// Nếu người dùng gửi tin nhắn mới
if (isset($_POST['user_input'])) {
    $user_input = trim($_POST['user_input']);

    if (strlen($user_input) > 500) {
        $user_input = substr($user_input, 0, 500); // Giới hạn đầu vào
    }

    $_SESSION['history'][] = ['sender' => 'user', 'message' => $user_input];

    $bot_responses = chatbotProcess($user_input, $companyName);

    if (is_array($bot_responses)) {
        foreach ($bot_responses as $entry) {
            $_SESSION['history'][] = ['sender' => 'bot', 'message' => $entry['message']];
        }
    } else {
        $_SESSION['history'][] = ['sender' => 'bot', 'message' => "Xin lỗi, tôi gặp chút trục trặc. Bạn thử lại sau nhé!"];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Global Health Corp. - Trợ lý Y Tế AI</title>
  <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<header class="site-header">
  <div class="container">
    <h1>🌐 Global Health Corp.</h1>
    <p>Trợ Lý Y Tế AI - Chatbot hỗ trợ</p>
  </div>
</header>

<main class="main-content">
  <div class="chat-container">
    <div id="chat-box" class="chat-box">
      <?php
        foreach ($_SESSION['history'] as $entry) {
            $cssClass = $entry['sender'] === 'user' ? 'user-msg' : 'bot-msg';
            echo "<div class='{$cssClass}'>" . htmlspecialchars($entry['message']) . "</div>";
        }
      ?>
    </div>

    <form method="POST" id="chat-form" autocomplete="off" class="chat-form">
      <input type="text" name="user_input" id="user_input" placeholder="Nhập tin nhắn..." required>
      <button type="submit" id="send-btn">Gửi</button>
    </form>

    <div class="reset-btn">
      <form method="GET">
        <button type="submit" name="reset" value="1">🔄 Bắt đầu lại</button>
      </form>
    </div>
  </div>
</main>

<footer class="site-footer">
  <div class="container">
    <p>&copy; <?php echo date('Y'); ?> Global Health Corp. | All rights reserved.</p>
  </div>
</footer>

<script>
  // Tự động cuộn
  window.onload = function() {
    var chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
  };

  // Gửi bằng Enter
  const input = document.getElementById('user_input');
  const form = document.getElementById('chat-form');
  input.addEventListener("keypress", function(e) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      form.submit();
    }
  });

  // Disable nút gửi nếu trống
  const sendBtn = document.getElementById('send-btn');
  input.addEventListener("input", () => {
    sendBtn.disabled = input.value.trim() === '';
  });

  // Loading (giả lập)
  form.addEventListener('submit', () => {
    sendBtn.textContent = "Đang gửi...";
    sendBtn.classList.add("loading");
  });
</script>

</body>
</html>
