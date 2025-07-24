<?php
// /web/admin/api/upload_handler.php (V2 - More Robust & Better Error Handling)

header('Content-Type: application/json');
session_start();

$response = ['status' => 'error', 'message' => '无效的请求。'];

// --- 预检查：确保PHP扩展和目录权限正常 ---
if (!function_exists('finfo_open')) {
    $response['message'] = '服务器错误：PHP fileinfo 扩展未启用，无法安全地检查文件类型。';
    echo json_encode($response);
    exit;
}

// 安全检查：确保用户已登录
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response['message'] = '未授权的访问。';
    echo json_encode($response);
    exit;
}

// 检查是否有文件被上传
if (isset($_FILES['image'])) {
    $file = $_FILES['image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = '文件上传失败，错误码：' . $file['error'];
        echo json_encode($response);
        exit;
    }

    $upload_type = $_POST['type'] ?? 'products';
    $valid_types = ['products', 'cases'];
    if (!in_array($upload_type, $valid_types)) {
        $response['message'] = '无效的上传类型。';
        echo json_encode($response);
        exit;
    }
    
    $upload_dir = __DIR__ . '/../../uploads/' . $upload_type . '/';

    // 关键修正：检查目标目录是否存在且可写
    if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
        $response['message'] = "服务器错误：上传目录 '{$upload_dir}' 不存在或不可写。请检查文件夹权限。";
        echo json_encode($response);
        exit;
    }

    // 安全性检查
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($file_info, $file['tmp_name']);
    finfo_close($file_info);

    if (!in_array($mime_type, $allowed_mime_types)) {
        $response['message'] = '文件类型不允许，请上传 JPG, PNG, GIF, WEBP 格式的图片。';
        echo json_encode($response);
        exit;
    }

    $max_size = 5 * 1024 * 1024; 
    if ($file['size'] > $max_size) {
        $response['message'] = '文件过大，请上传小于 5MB 的图片。';
        echo json_encode($response);
        exit;
    }

    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file_extension;
    $destination = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $file_url = '/uploads/' . $upload_type . '/' . $new_filename;
        $response = ['status' => 'success', 'url' => $file_url, 'message' => '上传成功！'];
    } else {
        $response['message'] = '移动文件失败，可能是服务器内部错误。';
    }

} else {
    $response['message'] = '没有收到上传的文件。';
}

echo json_encode($response);
?>