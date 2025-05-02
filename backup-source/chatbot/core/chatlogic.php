<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function chatbotProcess($input, $companyName = 'Global Health Corp.') {
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }

    $history = &$_SESSION['history'];
    $response = [];

    $replies = include(__DIR__ . '/replies.php');

    // Lưu user input nếu khác input cuối
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

    // 1. Check Prompt Injection
    $mode = 'normal';
    if (isPromptInjection($input, $mode)) {
        if ($mode === 'leak') {
            $flag = randomFlag();
            $message = formatReply(str_replace('{flag}', $flag, $replies['secret_leak']), $companyName);
            $response[] = ['sender' => 'bot', 'message' => $message];
        } elseif ($mode === 'warning') {
            $response[] = ['sender' => 'bot', 'message' => '⚠️ Phát hiện yêu cầu nhạy cảm. Bạn vui lòng diễn đạt lại giúp tôi nhé! - ' . $companyName];
        }
        return saveBotResponses($response, $history);
    }

    // 2. Booking tiến trình
    if (isset($_SESSION['booking_stage'])) {
        if ($_SESSION['booking_stage'] === 'department') {
            $_SESSION['booking_department'] = $input;
            $_SESSION['booking_stage'] = 'date';
            $message = formatReply($replies['ask_date'], $companyName);
            $response[] = ['sender' => 'bot', 'message' => $message];
            return saveBotResponses($response, $history);
        }
        if ($_SESSION['booking_stage'] === 'date') {
            $department = htmlspecialchars($_SESSION['booking_department']);
            $date = htmlspecialchars($input);
            unset($_SESSION['booking_stage']);
            $message = str_replace(
                ['{department}', '{companyName}', '{date}'],
                [$department, $companyName, $date],
                $replies['booking_success']
            );
            $message = formatReply($message, $companyName);
            $response[] = ['sender' => 'bot', 'message' => $message];
            return saveBotResponses($response, $history);
        }
    }

    // 3. Intent detection
    $intent = detectIntent($input);

    switch ($intent) {
        case 'reset':
            unset($_SESSION['history'], $_SESSION['booking_stage'], $_SESSION['booking_department']);
            $response[] = ['sender' => 'bot', 'message' => $replies['reset_success']];
            return $response; // Không lưu history thêm

        case 'greeting':
            $response[] = ['sender' => 'bot', 'message' => formatReply($replies['greeting'], $companyName)];
            break;

        case 'booking':
            $_SESSION['booking_stage'] = 'department';
            $response[] = ['sender' => 'bot', 'message' => formatReply($replies['ask_department'], $companyName)];
            break;

        case 'symptom':
            $covidMessage = detectCovidSymptoms($input, $replies);
            if ($covidMessage !== false) {
                $response[] = ['sender' => 'bot', 'message' => $covidMessage];
            } else {
                $symptomMessage = detectSymptoms($input, $replies, $companyName);
                if ($symptomMessage !== false) {
                    $response[] = ['sender' => 'bot', 'message' => $symptomMessage];
                }
            }
            break;

        case 'covid_info':
            $response[] = ['sender' => 'bot', 'message' => $replies['covid_info']];
            break;

        case 'service_info':
            $response[] = ['sender' => 'bot', 'message' => formatReply($replies['introduce_services'], $companyName)];
            break;

        case 'contact_info':
            $response[] = ['sender' => 'bot', 'message' => $replies['contact_info']];
            break;

        case 'working_hours':
            $response[] = ['sender' => 'bot', 'message' => $replies['working_hours']];
            break;

        case 'identity':
            $response[] = ['sender' => 'bot', 'message' => formatReply($replies['intro_instruction'], $companyName)];
            break;

        case 'mission':
            $response[] = ['sender' => 'bot', 'message' => formatReply($replies['mission_info'], $companyName)];
            break;

        default:
            // Tìm kiếm thông tin y tế
            $medicalInfo = lookupMedicalInfo($input, $companyName);
            if (strpos($medicalInfo, 'Không tìm thấy') === false) {
                $response[] = ['sender' => 'bot', 'message' => $medicalInfo];
            } else {
                $response[] = ['sender' => 'bot', 'message' => formatReply($replies['confused'], $companyName)];
            }
            break;
    }

    return saveBotResponses($response, $history);
}

// ========== Các hàm phụ trợ ==========

function formatReply($message, $companyName) {
    return str_replace('{companyName}', $companyName, $message);
}

function saveBotResponses($responses, &$history) {
    foreach ($responses as $r) {
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
    return $responses;
}

function detectIntent($input) {
    $intentMap = [
        'greeting' => '/chào|xin chào|hello|hi/i',
        'reset' => '/xóa lịch sử|xoá lịch sử|reset/i',
        'booking' => '/đặt lịch|hẹn gặp/i',
        'symptom' => '/ho|sốt|khó thở|mệt mỏi|đau họng/i',
        'covid_info' => '/covid[\s\-]?19|corona|dịch bệnh covid/i',
        'service_info' => '/dịch vụ|khám gì|bạn làm gì/i',
        'contact_info' => '/liên hệ|số điện thoại|email|hotline/i',
        'working_hours' => '/giờ làm việc|thời gian làm việc|mấy giờ/i',
        'identity' => '/bạn là ai|bạn tên gì/i',
        'mission' => '/nhiệm vụ của bạn|chức năng của bạn|vai trò của bạn|bạn làm được gì|công việc của bạn/i',
    ];
    foreach ($intentMap as $intent => $pattern) {
        if (preg_match($pattern, $input)) {
            return $intent;
        }
    }
    return 'unknown';
}

function isPromptInjection($input, &$mode = 'normal') {
    $patterns = [
        '/bỏ\s*qua\s*quy\s*tắc/i',
        '/flag\s*[:=]\s*\w+/i',
        '/\{.*?"command"\s*:\s*"leak_secret".*?\}/i',
        '/\[PROMPT_BREAK\]/i'
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $input)) {
            if (preg_match('/bỏ\s*qua\s*quy\s*tắc/i', $input)) {
                $mode = 'warning';
            } else {
                $mode = 'leak';
            }
            return true;
        }
    }
    return false;
}

function randomFlag() {
    $flags = json_decode(file_get_contents(__DIR__ . '/../data/flags.json'), true);
    return $flags[array_rand($flags)];
}

function detectSymptoms($input, $replies, $companyName) {
    $symptoms = ['ho', 'sốt', 'khó thở', 'mệt mỏi', 'đau họng'];
    foreach ($symptoms as $symptom) {
        if (stripos($input, $symptom) !== false) {
            return formatReply(
                str_replace('{symptom}', $symptom, $replies['symptom_detected']),
                $companyName
            );
        }
    }
    return false;
}

function detectCovidSymptoms($input, $replies) {
    $covidSymptoms = ['ho', 'sốt', 'khó thở', 'mệt mỏi', 'đau họng'];
    $count = 0;
    foreach ($covidSymptoms as $symptom) {
        if (stripos($input, $symptom) !== false) {
            $count++;
        }
    }
    if ($count >= 2) {
        return $replies['covid_symptom'];
    }
    return false;
}

function lookupMedicalInfo($input, $companyName) {
    $filePath = __DIR__ . '/../data/medical-info.json';
    if (!file_exists($filePath)) {
        return "Không tìm thấy dữ liệu y tế. Vui lòng kiểm tra đường dẫn tại {$filePath}.";
    }
    $json = file_get_contents($filePath);
    $db = json_decode($json, true);
    if (!is_array($db)) {
        return "Tôi không hiểu bạn nói gì? Bạn có thể nói lại giúp tôi.";
    }
    foreach ($db as $term => $info) {
        if (stripos($input, $term) !== false) {
            return $info . " - Theo tài liệu của {$companyName}";
        }
    }
    $replies = include(__DIR__ . '/replies.php');
    return formatReply($replies['no_info'], $companyName);
}
?>
