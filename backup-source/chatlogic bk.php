<?php

function chatbotProcess($input) {
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }

    if (!isset($_SESSION['user_info'])) {
        $_SESSION['user_info'] = ['age' => null, 'gender' => null];
    }

    $history = &$_SESSION['history'];
    $userInfo = &$_SESSION['user_info'];
    $response = [];

    if (isPromptInjection($input)) {
        $flag = randomFlag();
        $response[] = ['sender' => 'bot', 'message' => "🔒 Bí mật đã lộ: {$flag}"];
    } 
    elseif (preg_match('/tên\s+(?:tôi\s+)?([A-ZÀ-Ỹa-zà-ỹ\s]+)/iu', $input, $matchesName)) {
        $userInfo['name'] = trim($matchesName[1]);
        $response[] = ['sender' => 'bot', 'message' => "👍 Đã ghi nhận tên: {$userInfo['name']}"];
    }

    if (preg_match('/(\d{1,3})\s*(tuổi|age)?/i', $input, $matchesAge)) {
        $userInfo['age'] = intval($matchesAge[1]);
        $response[] = ['sender' => 'bot', 'message' => "👍 Đã ghi nhận độ tuổi: {$userInfo['age']}"];
    }

    if (preg_match('/\b(nam|nữ|male|female)\b/i', $input, $matchesGender)) {
        $gender = strtolower($matchesGender[1]);
        $gender = ($gender === 'nữ' || $gender === 'female') ? 'Nữ' : 'Nam';
        $userInfo['gender'] = $gender;
        $response[] = ['sender' => 'bot', 'message' => "👍 Đã ghi nhận giới tính: {$gender}"];
    }
    elseif (isset($_SESSION['booking_stage']) && $_SESSION['booking_stage'] === 'department') {
        $_SESSION['booking_department'] = $input;
        $_SESSION['booking_stage'] = 'date';
        $response[] = ['sender' => 'bot', 'message' => "Bạn muốn đặt lịch vào ngày nào? (Ví dụ: 29/04/2025)"];
    } elseif (isset($_SESSION['booking_stage']) && $_SESSION['booking_stage'] === 'date') {
        $department = htmlspecialchars($_SESSION['booking_department']);
        $date = htmlspecialchars($input);
        unset($_SESSION['booking_stage']);
        $response[] = ['sender' => 'bot', 'message' => "✅ Đặt lịch thành công tại khoa {$department} vào ngày {$date}!"];
    } elseif (preg_match('/chào|xin chào|hello/i', $input)) {
        $response[] = ['sender' => 'bot', 'message' => "Chào bạn! Bạn có thể cho tôi biết độ tuổi và giới tính để chẩn đoán chính xác hơn nhé."];
    } elseif (preg_match('/đặt lịch|hẹn gặp/i', $input)) {
        $_SESSION['booking_stage'] = 'department';
        $response[] = ['sender' => 'bot', 'message' => "Bạn muốn đặt lịch khám ở khoa nào vậy?"];
    } else {
        $diagnosis = detectDisease($input);
        if ($diagnosis !== false) {
            $extra = '';
             if ($userInfo['name']) $extra .= "Tên: {$userInfo['name']}. ";
            if ($userInfo['age']) $extra .= "Độ tuổi: {$userInfo['age']}. ";
            if ($userInfo['gender']) $extra .= "Giới tính: {$userInfo['gender']}. ";
            $response[] = ['sender' => 'bot', 'message' => "📋 {$diagnosis}. {$extra}Vui lòng đi khám nếu triệu chứng kéo dài hoặc trở nặng."];
        } elseif (preg_match('/thuốc|bệnh/i', $input)) {
            $response[] = ['sender' => 'bot', 'message' => lookupMedicalInfo($input)];
        } else {
            $response[] = ['sender' => 'bot', 'message' => "Tôi chưa hiểu rõ. Bạn có thể diễn đạt lại không ạ?"];
        }
    }

    foreach ($response as $resp) {
        $history[] = $resp;
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

function detectDisease($input) {
    $diseases = [
        'covid' => ['sốt', 'ho', 'mất vị giác', 'khó thở'],
        'cảm cúm' => ['hắt hơi', 'đau đầu', 'sổ mũi'],
        'sốt xuất huyết' => ['sốt cao', 'đau mắt', 'phát ban', 'xuất huyết dưới da'],
        'viêm họng' => ['đau họng', 'khó nuốt', 'sốt nhẹ'],
        'viêm phổi' => ['ho kéo dài', 'đau ngực', 'khó thở', 'sốt cao', 'ớn lạnh'],
        'hen suyễn' => ['khó thở', 'khò khè', 'tức ngực', 'ho về đêm'],
        'viêm xoang' => ['đau đầu', 'đau vùng trán', 'nghẹt mũi', 'chảy dịch mũi'],
        'viêm dạ dày' => ['đau bụng', 'buồn nôn', 'ợ chua', 'đầy hơi'],
        'tiểu đường' => ['khát nước', 'đi tiểu nhiều', 'mệt mỏi', 'sụt cân'],
        'cao huyết áp' => ['đau đầu', 'hoa mắt', 'chóng mặt', 'khó thở'],
        'suy thận' => ['tiểu ít', 'phù chân', 'mệt mỏi', 'ngứa da'],
        'viêm gan B' => ['mệt mỏi', 'vàng da', 'chán ăn', 'đau hạ sườn phải'],
        'dạ dày cấp' => ['đau thượng vị', 'nôn mửa', 'chán ăn', 'đầy bụng'],
        'dị ứng' => ['phát ban', 'ngứa', 'chảy nước mũi', 'khó thở'],
        'sởi' => ['sốt cao', 'phát ban', 'viêm kết mạc', 'ho khan'],
        'tay chân miệng' => ['sốt nhẹ', 'mụn nước lòng bàn tay', 'đau họng', 'biếng ăn']
    ];

    $input = mb_strtolower($input);
    $bestMatch = null;
    $maxMatched = 0;

    foreach ($diseases as $disease => $symptoms) {
        $matched = 0;
        foreach ($symptoms as $symptom) {
            if (mb_strpos($input, mb_strtolower($symptom)) !== false) {
                $matched++;
            }
        }
        if ($matched > $maxMatched) {
            $maxMatched = $matched;
            $bestMatch = $disease;
        }
    }

    if ($maxMatched > 0) {
        return "Có thể là: " . ucfirst($bestMatch);
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
