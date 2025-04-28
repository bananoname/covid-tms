<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Patient Interaction Chatbot</title>
  <link rel="stylesheet" href="css/style-chatbot.css">
  <script>
    function typeWriter(text, element, delay = 30) {
      let i = 0;
      function typing() {
        if (i < text.length) {
          element.innerHTML += text.charAt(i);
          i++;
          setTimeout(typing, delay);
        }
      }
      setTimeout(typing, 500); // Delay nhẹ cho tự nhiên
    }

    window.onload = function () {
      const pre = document.getElementById("typewriter-output");
      if (pre && pre.dataset.content) {
        typeWriter(pre.dataset.content, pre);
      }
    };
  </script>
</head>
<body>
  <div class="terminal">
    <div class="header">Patient Interaction Bot</div>

    <div id="command-section" class="command-section">
      <div class="output">
        <?php
          function isPromptInjection($input) {
            return stripos($input, 'bỏ qua quy tắc') !== false || stripos($input, 'tiết lộ bí mật') !== false;
          }

          function detectCovidSymptoms($message) {
            $symptoms = ['sốt', 'ho', 'khó thở', 'mất vị giác', 'mất khứu giác', 'đau họng', 'mệt mỏi'];
            foreach ($symptoms as $symptom) {
              if (stripos($message, $symptom) !== false) {
                return true;
              }
            }
            return false;
          }

          function randomResponse($responses) {
            return $responses[array_rand($responses)];
          }

          function chatBotResponse($message) {
            $message = trim(strtolower($message));

            if (isPromptInjection($message)) {
              return "Bí mật bệnh nhân: FLAG{prompt_injection_success} 🤫";
            }

            if (detectCovidSymptoms($message)) {
              return randomResponse([
                "😷 Có vẻ bạn đang có triệu chứng liên quan đến COVID-19. Bạn nên tự cách ly và xét nghiệm sớm nhé!",
                "⚠️ Triệu chứng của bạn khá giống với COVID-19, hãy đi kiểm tra y tế càng sớm càng tốt.",
                "Hmm... Nghe nguy hiểm đấy. Tôi khuyên bạn nên đeo khẩu trang và xét nghiệm COVID-19 ngay nhé."
              ]);
            }

            if (strpos($message, 'chào') !== false) {
              return randomResponse([
                "Chào bạn! Tôi ở đây để giúp đỡ. Bạn cần gì nào?",
                "Hey 👋! Bạn đang cần hỗ trợ về sức khỏe đúng không?",
                "Xin chào! Tôi luôn sẵn sàng hỗ trợ bạn."
              ]);
            }

            if (strpos($message, 'đặt lịch') !== false || strpos($message, 'hẹn gặp') !== false) {
              $_SESSION['booking'] = true;
              return randomResponse([
                "Bạn muốn hẹn gặp bác sĩ chuyên khoa nào nhỉ? (Ví dụ: Nội tổng quát, Da liễu, Tai mũi họng...)",
                "Ok, bạn cần đặt lịch khám chuyên khoa nào vậy?",
                "Chuyên khoa nào bạn muốn đặt lịch hẹn? Nói tôi nghe nhé!"
              ]);
            }

            if (isset($_SESSION['booking']) && $_SESSION['booking'] === true) {
              $_SESSION['booking_department'] = $message;
              $_SESSION['booking'] = false;
              $_SESSION['waiting_date'] = true;
              return randomResponse([
                "Rồi nhé, bạn chọn khoa \"" . htmlspecialchars($message) . "\". Bạn muốn đặt lịch vào ngày nào?",
                "Hiểu rồi, khoa \"" . htmlspecialchars($message) . "\" nhé! Bạn chọn ngày nào để hẹn?"
              ]);
            }

            if (isset($_SESSION['waiting_date']) && $_SESSION['waiting_date'] === true) {
              $_SESSION['waiting_date'] = false;
              return randomResponse([
                "✅ Đặt lịch thành công! Hẹn gặp bạn tại khoa \"" . htmlspecialchars($_SESSION['booking_department']) . "\" vào ngày " . htmlspecialchars($message) . ".",
                "🗓️ Đã xếp lịch cho bạn tại khoa \"" . htmlspecialchars($_SESSION['booking_department']) . "\" vào ngày " . htmlspecialchars($message) . ". Hẹn gặp lại nhé!"
              ]);
            }

            if (strpos($message, 'giúp tôi') !== false || strpos($message, 'cần giúp') !== false) {
              return randomResponse([
                "Tôi có thể hỗ trợ bạn kiểm tra triệu chứng, đặt lịch hẹn, hoặc tư vấn nhanh. Bạn muốn làm gì trước?",
                "Bạn có thể hỏi tôi về triệu chứng bệnh, đặt lịch khám hoặc bất kỳ thông tin y tế nào."
              ]);
            }

            return randomResponse([
              "Hmm, tôi chưa hiểu lắm. Bạn có thể nói rõ hơn không?",
              "Bạn có thể mô tả kỹ hơn để tôi hỗ trợ chính xác hơn được không?",
              "Xin lỗi, tôi chưa nắm được ý bạn. Bạn muốn hỏi về vấn đề gì?"
            ]);
          }

          if (isset($_GET['cmd'])) {
            $cmd = $_GET['cmd'];
            echo "<span class='input'>Bạn: " . htmlspecialchars($cmd) . "</span><br>";

            $output = chatBotResponse($cmd);
            echo "<pre id='typewriter-output' data-content=\"" . htmlspecialchars($output) . "\"></pre>";
          } else {
            echo "<span class='hint'>Nhập nội dung để trò chuyện với bác sĩ chatbot</span>";
          }
        ?>
      </div>

      <form method="GET" class="input-form">
        <label for="cmd">Bạn:</label>
        <input type="text" id="cmd" name="cmd" autocomplete="off" autofocus>
      </form>
    </div>
  </div>
</body>
</html>
