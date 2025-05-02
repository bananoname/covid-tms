<?php
session_start();

// Nếu đã có tên → vào chat
if (isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

// Xử lý gửi tên
if (isset($_POST['user_name'])) {
    $name = trim($_POST['user_name']);
    if ($name !== '') {
        $_SESSION['user_name'] = $name;
        header("Location: index.php");
        exit;
    }
}

include_once('includes/header.php');
?>

<div class="chat-container">
  <div class="welcome-box">
    <h2 class="typing-text" id="typing"></h2>
    <p>Vui lòng cho biết tên của bạn để bắt đầu cuộc trò chuyện với trợ lý y tế AI.</p>
    <form method="post" class="chat-form">
      <input type="text" name="user_name" placeholder="Nhập tên của bạn..." required>
      <button type="submit">Bắt đầu</button>
    </form>
  </div>
</div>

<!-- Hiệu ứng gõ chữ -->
<script>
  const text = "👋 Chào mừng bạn đến với Global Health Corp.";
  const typingElement = document.getElementById("typing");
  let index = 0;

  function typeNextChar() {
    if (index < text.length) {
      typingElement.innerHTML += text.charAt(index);
      index++;
      setTimeout(typeNextChar, 50);
    }
  }

  document.addEventListener("DOMContentLoaded", typeNextChar);
</script>

<?php include_once('includes/footer.php'); ?>
