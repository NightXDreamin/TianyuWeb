<?php
// /api_handlers/jobs_handler.php (V2 - 写入JSON)

$action = $_POST['action'] ?? '';
$response = ['status' => 'error', 'message' => '无效的职位操作。'];

if ($action == 'save_jobs') {
    $session_key = $_POST['key'] ?? '';
    $lock_file = __DIR__ . '/session_lock.json';
    if (empty($session_key) || !file_exists($lock_file)) {
        $response['message'] = '未授权的访问或会话已过期。';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }
    $lock_data = json_decode(file_get_contents($lock_file), true);
    if ($lock_data['session_key'] !== $session_key) {
        $response['message'] = '会话密钥不匹配，操作被拒绝。';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    $newJobsJson = $_POST['data'] ?? '';
    $newJobsData = json_decode($newJobsJson, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($newJobsData)) {
        // --- 核心修改：将数据编码为JSON并写入 .json 文件 ---
        $newFileContent = json_encode($newJobsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if (file_put_contents(__DIR__ . '/../data/jobs_data.json', $newFileContent) !== false) {
            $response = ['status' => 'success', 'message' => '职位信息已成功更新到服务器！'];
        } else {
            $response['message'] = '数据文件写入失败，请检查服务器文件权限。';
        }
    } else {
        $response['message'] = '接收到的职位数据格式无效。';
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>