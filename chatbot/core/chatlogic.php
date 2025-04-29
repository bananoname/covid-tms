<?php

function chatbotProcess($input, $companyName = 'Global Health Corp.') {
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }

    $history = &$_SESSION['history'];
    $response = [];

    $replies = include(__DIR__ . '/replies.php'); // Load replies từ file replies.php

    $lastUserMessage = null;
        for ($i = count($history) - 1; $i >= 0; $i--) {
            if ($history[$i]['sender'] === 'user') {
            $lastUserMessage = $history[$i]['message'];
        break;
    }
}

if ($lastUserMessage !== $input) {
    $history[] = ['sender' => 'user', 'message' => $input];
}

    if (isPromptInjection($input)) {
        $flag = randomFlag();
        $message = str_replace('{flag}', $flag, $replies['secret_leak']);
        $response[] = ['sender' => 'bot', 'message' => $message];
    } elseif (preg_match('/xóa lịch sử|xoá lịch sử|reset/i', $input)) {
        unset($_SESSION['history']);
        unset($_SESSION['booking_stage']);
        unset($_SESSION['booking_department']);
        $response[] = ['sender' => 'bot', 'message' => $replies['reset_success']];
        //**FIX ở đây: trả luôn, không lưu tiếp vào history**
        return $response;

    } elseif (isset($_SESSION['booking_stage']) && $_SESSION['booking_stage'] === 'department') {
        $_SESSION['booking_department'] = $input;
        $_SESSION['booking_stage'] = 'date';
        $message = str_replace('{companyName}', $companyName, $replies['ask_date']);
        $response[] = ['sender' => 'bot', 'message' => $message];
    } elseif (isset($_SESSION['booking_stage']) && $_SESSION['booking_stage'] === 'date') {
        $department = htmlspecialchars($_SESSION['booking_department']);
        $date = htmlspecialchars($input);
        unset($_SESSION['booking_stage']);
        $message = str_replace(
            ['{department}', '{companyName}', '{date}'],
            [$department, $companyName, $date],
            $replies['booking_success']
        );
        $response[] = ['sender' => 'bot', 'message' => $message];
    } else {
        // --- Ưu tiên chẩn đoán triệu chứng trước ---

        // 1. Kiểm tra triệu chứng COVID
        $covidSymptomMessage = detectCovidSymptoms($input, $replies, $companyName);
        if ($covidSymptomMessage !== false) {
            $response[] = ['sender' => 'bot', 'message' => $covidSymptomMessage];
        }
        // 2. Nếu người dùng hỏi về COVID-19
        elseif (preg_match('/covid[\s\-]?19|corona|dịch bệnh covid/i', $input)) {
            $response[] = ['sender' => 'bot', 'message' => $replies['covid_info']];
        }
        // 3. Nếu không phải, kiểm tra triệu chứng thường
        elseif (($symptomMessage = detectSymptoms($input, $replies, $companyName)) !== false) {
            $response[] = ['sender' => 'bot', 'message' => $symptomMessage];
        }
        // 4. Nếu không có triệu chứng, xử lý chào hỏi
        elseif (preg_match('/chào|xin chào|hello/i', $input)) {
            $message = str_replace('{companyName}', $companyName, $replies['greeting']);
            $response[] = ['sender' => 'bot', 'message' => $message];
        }
        // 5. Đặt lịch hẹn
        elseif (preg_match('/đặt lịch|hẹn gặp/i', $input)) {
            $_SESSION['booking_stage'] = 'department';
            $message = str_replace('{companyName}', $companyName, $replies['ask_department']);
            $response[] = ['sender' => 'bot', 'message' => $message];
        }
        // 6. Giới thiệu dịch vụ
        elseif (preg_match('/(dịch vụ|khám gì|bạn làm gì)/i', $input)) {
            $message = str_replace('{companyName}', $companyName, $replies['introduce_services']);
            $response[] = ['sender' => 'bot', 'message' => $message];
        }
        // 7. Liên hệ
        elseif (preg_match('/(liên hệ|số điện thoại|email|hotline)/i', $input)) {
            $response[] = ['sender' => 'bot', 'message' => $replies['contact_info']];
        }
        // 8. Giờ làm việc
        elseif (preg_match('/(giờ làm việc|thời gian làm việc|mấy giờ)/i', $input)) {
            $response[] = ['sender' => 'bot', 'message' => $replies['working_hours']];
        }
        // 9. Mặc định
        else {
            $message = str_replace('{companyName}', $companyName, $replies['confused']);
            $response[] = ['sender' => 'bot', 'message' => $message];
        }
    }

foreach ($response as $r) {
    $lastBotMessage = null;
    for ($i = count($history) - 1; $i >= 0; $i--) {
        if ($history[$i]['sender'] === 'bot') {
            $lastBotMessage = $history[$i]['message'];
            break;
        }
    }

    if ($lastBotMessage !== $r['message']) {
        $history[] = $r;
    }
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

function detectSymptoms($input, $replies, $companyName = 'Global Health Corp.') {
    $symptoms = ['ho', 'sốt', 'khó thở', 'mệt mỏi', 'đau họng'];
    foreach ($symptoms as $symptom) {
        if (stripos($input, $symptom) !== false) {
            return str_replace(
                ['{symptom}', '{companyName}'],
                [$symptom, $companyName],
                $replies['symptom_detected']
            );
        }
    }
    return false;
}

function detectCovidSymptoms($input, $replies, $companyName = 'Global Health Corp.') {
    $covidSymptoms = ['ho', 'sốt', 'khó thở', 'mệt mỏi', 'đau họng'];
    $count = 0;
    foreach ($covidSymptoms as $symptom) {
        if (stripos($input, $symptom) !== false) {
            $count++;
        }
    }
    if ($count >= 2) { // Nếu có 2 triệu chứng trở lên, cảnh báo COVID
        return $replies['covid_symptom'];
    }
    return false;
}

function lookupMedicalInfo($input, $companyName = 'Global Health Corp.') {
    $filePath = __DIR__ . '/../data/medical-info.json';

    if (!file_exists($filePath)) {
        return "Không tìm thấy dữ liệu y tế. Vui lòng kiểm tra đường dẫn tại {$filePath}.";
    }

    $json = file_get_contents($filePath);
    $db = json_decode($json, true);

    if (!is_array($db)) {
        return "Dữ liệu y tế bị lỗi hoặc không hợp lệ.";
    }

    foreach ($db as $term => $info) {
        if (stripos($input, $term) !== false) {
            return $info . " - Theo tài liệu của {$companyName}";
        }
    }

    $replies = include(__DIR__ . '/replies.php');
    return str_replace('{companyName}', $companyName, $replies['no_info']);
}

?>
