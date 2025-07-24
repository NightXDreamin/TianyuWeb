<?php
// /admin/auth.php (数据库认证版)
session_start();
require_once __DIR__ . '/includes/db_config.php';

header('Content-Type: application/json'); // 声明返回的是JSON

$response = ['status' => 'error', 'message' => '无效的请求。'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $response['message'] = '用户名和密码不能为空。';
    } else {
        $conn = get_db_connection();
        if (!$conn) {
            $response['message'] = '数据库连接失败，请检查配置。';
        } else {
            $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password_hash'])) {
                    // 登录成功
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    $response = ['status' => 'success', 'message' => '登录成功！'];
                } else {
                    $response['message'] = '密码错误。';
                }
            } else {
                $response['message'] = '用户名不存在。';
            }
            $stmt->close();
            $conn->close();
        }
    }
}

echo json_encode($response);
?>