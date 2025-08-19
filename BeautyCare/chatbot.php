<?php
header('Content-Type: application/json');

// Kết nối đến cơ sở dữ liệu
include 'php/database.php';

// Thay thế bằng API Key THẬT của bạn từ Google AI Studio
define('GOOGLE_GEMINI_API_KEY', 'AIzaSyAjWareZNfTbLZQiTQPVQ2J0aGlt-MtEiw'); // Sửa API Key tại đây

// Hàm gửi yêu cầu đến API của Google Gemini sử dụng cURL
function getGeminiResponse($prompt) {
    // Thay đổi v1beta thành v1 nếu cần
    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . GOOGLE_GEMINI_API_KEY;

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Ghi lại lỗi để debug nếu có
    if ($http_code != 200) {
        error_log("Gemini API Error: HTTP code {$http_code}, Response: " . $response);
    }
    
    return json_decode($response, true);
}

// Hàm kiểm tra xem tin nhắn có liên quan đến mỹ phẩm hay không
function isCosmeticRelated($message) {
    $keywords = ['mỹ phẩm', 'son môi', 'kem chống nắng', 'dưỡng da', 'skincare', 'trang điểm', 'kem', 'làm đẹp', 'sản phẩm', 'da', 'chăm sóc da', 'tóc', 'chăm sóc tóc'];
    foreach ($keywords as $keyword) {
        if (stripos($message, $keyword) !== false) {
            return true;
        }
    }
    return false;
}

// Xử lý yêu cầu POST từ người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_message'])) {
    $user_message = $_POST['user_message'];
    $ai_response = "";
    
    // Lấy toàn bộ dữ liệu sản phẩm để cung cấp cho AI khi cần
    $product_list_string = "";
    $products_array = [];
    $sql = "SELECT id, ten_san_pham, thuong_hieu, phan_loai, mo_ta, thanh_phan, gia FROM san_pham";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products_array[] = $row;
            $product_list_string .= " - Tên: {$row['ten_san_pham']}, Thương hiệu: {$row['thuong_hieu']}, Phân loại: {$row['phan_loai']}, Mô tả: {$row['mo_ta']}, Thành phần: {$row['thanh_phan']}, Giá: {$row['gia']} VNĐ.\n";
        }
    }
    
    // Định nghĩa các biến prompt trước khi sử dụng
    $prompt_header = "Bạn là một trợ lý ảo AI đa năng, thân thiện và hữu ích. Bạn có thể trả lời các câu hỏi về mọi lĩnh vực trong cuộc sống.";
    $prompt_footer = "\n\nCâu hỏi của khách hàng: " . $user_message;
    $prompt = ""; // Khởi tạo biến prompt

    // Xây dựng prompt cho AI dựa trên loại câu hỏi của người dùng
    if (isCosmeticRelated($user_message)) {
        // Nếu câu hỏi liên quan đến mỹ phẩm, thêm thông tin sản phẩm vào prompt
        $prompt_products = "\n\nBạn có kiến thức chuyên sâu về mỹ phẩm và có thể tư vấn dựa trên danh sách sản phẩm sau:\n" . $product_list_string;
        $prompt = $prompt_header . $prompt_products . $prompt_footer;
    } else {
        // Nếu không, chỉ sử dụng prompt chung
        $prompt = $prompt_header . $prompt_footer;
    }

    // Gửi prompt đến Google Gemini API
    $gemini_output = getGeminiResponse($prompt);
    
    // Xử lý phản hồi từ API
    if (isset($gemini_output['candidates'][0]['content']['parts'][0]['text'])) {
        $ai_response = $gemini_output['candidates'][0]['content']['parts'][0]['text'];
        
        // Thêm liên kết sản phẩm nếu AI đề cập đến
        if (isCosmeticRelated($user_message)) {
            foreach ($products_array as $product) {
                $product_name = htmlspecialchars($product['ten_san_pham']);
                // Sử dụng biểu thức chính quy để tìm tên sản phẩm và thêm link
                if (preg_match("/\b" . preg_quote($product_name, '/') . "\b/i", $ai_response)) {
                    $product_id = $product['id'];
                    $ai_response = str_ireplace($product_name, "<a href='product-detail.php?id={$product_id}'>{$product_name}</a>", $ai_response);
                }
            }
        }
    } else {
        $ai_response = "Xin lỗi, mình không thể xử lý yêu cầu của bạn lúc này. Vui lòng thử lại sau.";
        // Thêm dòng này để debug lỗi từ API
        $ai_response .= " (Lỗi: " . json_encode($gemini_output) . ")";
    }

    echo json_encode(["response" => $ai_response]);
}

$conn->close();
?>
