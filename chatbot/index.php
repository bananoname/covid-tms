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

include_once('includes/header.php'); // Bao gồm header
?>

<div class="chat-container">
    <div id="chat-box" class="chat-box">
        <?php
        $lastIndex = count($_SESSION['history']) - 1;

        foreach ($_SESSION['history'] as $index => $entry) {
            $cssClass = $entry['sender'] === 'user' ? 'user-msg' : 'bot-msg';
            $msg = htmlspecialchars($entry['message']);

            if ($entry['sender'] === 'bot' && $index === $lastIndex) {
                echo "<div id='bot-typewriter' class='{$cssClass}'></div>";
                echo "<script>
                    const msg = " . json_encode($msg) . ";
                    let i = 0;
                    const target = document.getElementById('bot-typewriter');
                    function typeBotMessage() {
                        if (i < msg.length) {
                            target.innerHTML += msg.charAt(i);
                            i++;
                            setTimeout(typeBotMessage, 50);
                        }
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        if (!document.getElementById('bot-typewriter').innerHTML) {
                            typeBotMessage();
                        }
                    });
                </script>";
            } else {
                echo "<div class='{$cssClass}'>{$msg}</div>";
            }
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

<?php include_once('includes/footer.php'); ?>

<script>
  // Tự động cuộn
  window.onload = function() {
    var chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
  };

  const input = document.getElementById('user_input');
  const form = document.getElementById('chat-form');
  const sendBtn = document.getElementById('send-btn');

  // Gửi bằng Enter
  input.addEventListener("keypress", function(e) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      form.submit();
    }
  });

  // Disable nút gửi nếu trống
  input.addEventListener("input", () => {
    sendBtn.disabled = input.value.trim() === '';
  });

  // Chặn gửi nhiều lần
  let isSubmitting = false;
  form.addEventListener('submit', function(e) {
    if (isSubmitting) {
      e.preventDefault();
      return;
    }
    isSubmitting = true;
    sendBtn.textContent = "Đang gửi...";
    sendBtn.classList.add("loading");
  });
</script>

</body>
</html>
