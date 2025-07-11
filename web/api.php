<?php
// api.php - 招聘信息编辑器 v2.0 的后端接口

// --- 安全设置 ---
// 设置一个密钥，确保只有我们的C++程序能调用保存功能
// 请务必修改成一个只有您自己知道的复杂字符串！
$secret_key = "JingJing8859!";

// --- 响应头设置 ---
// 告诉客户端我们返回的是JSON格式的数据，并且编码是UTF-8
header('Content-Type: application/json; charset=utf-8');


// --- 主逻辑 ---

// 默认的错误响应
$response = ['status' => 'error', 'message' => '无效的请求或操作。'];

// 确定请求的操作类型 (获取get 还是 保存save)
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else if (isset($_POST['action'])) {
    $action = $_POST['action'];
}


// =============== 获取职位数据 (GET请求) ===============
if ($action == 'get_jobs') {
    // 引入 career.php 文件，这样我们就可以直接使用里面的 $jobs 数组
    if (file_exists('career.php')) {
        include 'jobs_data.php';
        if (isset($jobs)) {
            $response = [
                'status' => 'success',
                'data' => $jobs
            ];
        } else {
            $response = ['status' => 'error', 'message' => '在 career.php 中未找到 $jobs 数组。'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'career.php 文件不存在。'];
    }
}


// =============== 保存职位数据 (POST请求) ===============
if ($action == 'save_jobs') {
    // 安全检查：验证密钥是否匹配
    if (!isset($_POST['key']) || $_POST['key'] !== $secret_key) {
        $response = ['status' => 'error', 'message' => '安全密钥无效，禁止操作！'];
    } 
    // 安全检查：验证数据是否存在
    else if (!isset($_POST['data'])) {
        $response = ['status' => 'error', 'message' => '没有收到任何职位数据。'];
    } 
    else {
        $newJobsJson = $_POST['data'];
        $newJobsData = json_decode($newJobsJson, true); // 将接收到的JSON字符串解码成PHP数组

        if (json_last_error() === JSON_ERROR_NONE && is_array($newJobsData)) {
            // 数据有效，开始重建 career.php 文件内容
            
            // 1. 使用 var_export 生成PHP数组的字符串表示，它比手动拼接更安全、更准确
            $newJobsArrayString = var_export($newJobsData, true);

            // 2. 读取原始 career.php 文件内容
            $originalContent = file_get_contents('career.php');

            // 3. 使用正则表达式，精确地只替换 $jobs = [...] 这部分内容
            $newContent = preg_replace(
                '/(\$jobs\s*=\s*)\[.*?\]\s*;/s',
                "$1" . $newJobsArrayString . ';',
                $originalContent,
                1 // 只替换一次
            );

            // 4. 将新内容写回 career.php 文件
            if (file_put_contents('career.php', $newContent) !== false) {
                $response = ['status' => 'success', 'message' => '职位信息已成功更新到服务器！'];
            } else {
                $response = ['status' => 'error', 'message' => '文件写入失败，请检查服务器文件权限。'];
            }
        } else {
            $response = ['status' => 'error', 'message' => '接收到的数据格式无效 (非JSON或JSON解码失败)。'];
        }
    }
}

// --- 最终输出 ---
// 将最终的响应数组编码为 JSON 字符串并“打印”出来
// 我们的C++客户端收到的就是这个JSON字符串
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

?>