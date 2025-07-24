<?php
// api.php (最终生产级 - 带输出缓冲)

// 1. 开启输出缓冲。从现在开始，所有输出都将进入内部缓冲区。
ob_start();

// 2. 设置所有响应的默认头信息
header('Content-Type: application/json; charset=utf-8');

// 3. 定义一个用于统一响应的函数
function send_json_response($data) {
    // 在发送最终响应前，清空所有可能存在的意外输出（如PHP警告）
    ob_end_clean();
    // 输出我们想要的、干净的JSON数据
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // 立即停止脚本执行，确保没有后续内容被输出
    exit();
}

// 4. 获取并处理action
$action = trim(strip_tags($_POST['action'] ?? $_GET['action'] ?? ''));

// 5. 路由分发 (保持不变)
switch ($action) {
    case 'login':

    case 'logout':

    case 'force_clear_lock':
        require_once __DIR__ . '/api_handlers/auth_handler.php';
        break;

    case 'get_all_data':
        require_once __DIR__ . '/api_handlers/data_aggregator.php';
        break;

    case 'save_jobs':
        require_once __DIR__ . '/api_handlers/jobs_handler.php';
        break;

    case 'save_products':
        require_once __DIR__ . '/api_handlers/products_handler.php';
        break;

    case 'upload_image':
        require_once __DIR__ . '/api_handlers/upload_handler.php';
        break;

    default:
        // 如果action未知，也使用我们的标准响应函数
        send_json_response(['status' => 'error', 'message' => '未知的操作 (Unknown Action): ' . htmlspecialchars($action)]);
        break;
}

// 6. 脚本末尾的保险措施：如果上面的逻辑都没有调用exit()，则清理缓冲区
ob_end_flush();
?>