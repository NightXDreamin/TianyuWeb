<?php
// /api_handlers/upload_handler.php

header('Content-Type: application/json; charset=utf-8');

// 引入会话密钥验证逻辑 (复用jobs_handler中的代码)
$session_key = $_POST['key'] ?? '';
$lock_file = __DIR__ . '/session_lock.json';
if (empty($session_key) || !file_exists($lock_file) || json_decode(file_get_contents($lock_file), true)['session_key'] !== $session_key) {
    echo json_encode(['status' => 'error', 'message' => '未授权的访问。']);
    exit();
}

// 决定上传到哪个子目录 (products 还是 cases)
$upload_type = $_POST['type'] ?? 'general';
$target_dir = "";
if ($upload_type === 'product') {
    $target_dir = __DIR__ . '/../../uploads/products/'; // 注意路径是 ../../
} else if ($upload_type === 'case') {
    $target_dir = __DIR__ . '/../../uploads/cases/';
} else {
    echo json_encode(['status' => 'error', 'message' => '无效的上传类型。']);
    exit();
}

// 检查上传文件是否存在且无误
if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => '文件上传失败或没有文件被上传。']);
    exit();
}

$file = $_FILES['image_file'];

// 安全检查：检查文件大小和类型
if ($file['size'] > 5 * 1024 * 1024) { // 限制最大5MB
    echo json_encode(['status' => 'error', 'message' => '文件过大，请上传小于5MB的图片。']);
    exit();
}
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['status' => 'error', 'message' => '无效的文件类型，请上传 JPG, PNG, 或 GIF 格式的图片。']);
    exit();
}

// 创建一个安全、唯一的文件名
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$new_filename = uniqid('img_', true) . '.' . strtolower($extension);
$target_path = $target_dir . $new_filename;

// 将文件从临时位置移动到我们的目标目录
if (move_uploaded_file($file['tmp_name'], $target_path)) {
    // 成功！构建并返回图片的完整公开URL
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $public_url = $protocol . $domain . '/uploads/' . ($upload_type === 'product' ? 'products' : 'cases') . '/' . $new_filename;

    echo json_encode([
        'status' => 'success',
        'message' => '图片上传成功！',
        'url' => $public_url
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => '保存文件失败，请检查服务器目录权限。']);
}
?>