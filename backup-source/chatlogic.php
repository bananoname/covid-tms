<?php

function chatbotProcess($input) {
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }

    $history = &$_SESSION['history'];
    $response = [];

    $history[] = ['sender' => 'user', 'message' => $input];

    if (isPromptInjection($input)) {
        $flag = randomFlag();
        $response[] = ['sender' => 'bot', 'message' => "🔒 Bí mật đã lộ: {$flag}"];
    } elseif (isset($_SESSION['booking_stage']) && $_SESSION['booking_stage'] === 'department') {
        $_SESSION['booking_department'] = $input;
        $_SESSION['booking_stage'] = 'date';
        $response[] = ['sender' => 'bot', 'message' => "Bạn muốn đặt lịch vào ngày nào? (Ví dụ: 29/04/2025)"];
    } elseif (isset($_SESSION['booking_stage']) && $_SESSION['booking_stage'] === 'date') {
        $department = htmlspecialchars($_SESSION['booking_department']);
        $date = htmlspecialchars($input);
        unset($_SESSION['booking_stage']);
        $response[] = ['sender' => 'bot', 'message' => "✅ Đặt lịch thành công tại khoa {$department} vào ngày {$date}!"];
    } elseif (preg_match('/chào|xin chào|hello/i', $input)) {
        $response[] = ['sender' => 'bot', 'message' => "Dạ vâng, tôi xin hỗ trợ bạn. Bạn cần gì hôm nay?"];
    } elseif (preg_match('/đặt lịch|hẹn gặp/i', $input)) {
        $_SESSION['booking_stage'] = 'department';
        $response[] = ['sender' => 'bot', 'message' => "Bạn muốn đặt lịch khám ở khoa nào vậy?"];
    } elseif (detectSymptoms($input)) {
        $response[] = ['sender' => 'bot', 'message' => detectSymptoms($input)];
    } elseif (preg_match('/thuốc|bệnh/i', $input)) {
        $response[] = ['sender' => 'bot', 'message' => lookupMedicalInfo($input)];
    } else {
        $response[] = ['sender' => 'bot', 'message' => "Tôi chưa hiểu rõ. Bạn có thể diễn đạt lại không ạ?"];
    }

    foreach ($response as $r) {
        $history[] = $r;
    }

    return $response;
}

function isPromptInjection($input) {
    $patterns = [
        '/bỏ\s*qua\s*quy\s*tắc/i',
        '/flag\s*[:=]\s*\w+/i',
        '/\{.*?"command"\s*:\s*"leak_secret".*?\}/i',
        '/\[PROMPT_BREAK\]/i'
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $input)) return true;
    }
    return false;
}

function randomFlag() {
    $flags = json_decode(file_get_contents(__DIR__ . '/../data/flags.json'), true);
    return $flags[array_rand($flags)];
}

function detectSymptoms($input) {
    $symptoms = ['ho', 'sốt', 'khó thở', 'mệt mỏi', 'đau họng'];
    foreach ($symptoms as $symptom) {
        if (stripos($input, $symptom) !== false) {
            return "⚠️ Triệu chứng \"{$symptom}\" cần được kiểm tra kỹ càng. Bạn nên đến trung tâm y tế sớm nhất!";
        }
    }
    return false;
}

function lookupMedicalInfo($input) {
    $db = json_decode(file_get_contents(__DIR__ . '/../data/medical-info.json'), true);
    foreach ($db as $term => $info) {
        if (stripos($input, $term) !== false) {
            return $info;
        }
    }
    return "Tôi chưa có đủ thông tin về nội dung bạn hỏi. Xin hãy hỏi lại sau!";
}
?>
