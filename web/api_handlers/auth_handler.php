<?php
// /api_handlers/auth_handler.php (V3 - 功能完整最终版)

require_once __DIR__ . '/db_config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => '无效的认证操作。'];

// --- 完整、可用的 'login' 处理逻辑 ---
if ($action == 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = '登录请求必须使用POST方法。';
    } else {
        $password = $_POST['password'] ?? '';
        if (empty($password)) {
            $response['message'] = '密码不能为空。';
        } else {
            $conn = get_db_connection();
            if (!$conn) {
                $response['message'] = '数据库连接失败。';
            } else {
                $admin_username_to_check = 'tianyuadmin'; // 确保这里的用户名是您在数据库中创建的那个
                $sql = "SELECT username, password_hash FROM users WHERE username = ? LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $admin_username_to_check);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password_hash'])) {
                        $lock_file = __DIR__ . '/session_lock.json';
                        if (file_exists($lock_file) && (time() - filemtime($lock_file) < 3600)) {
                            $response['message'] = '登录失败：系统当前正由另一位用户使用中。';
                        } else {
                            $session_key = bin2hex(random_bytes(32));
                            file_put_contents($lock_file, json_encode([
                                'session_key' => $session_key,
                                'username' => $user['username'],
                                'timestamp' => time()
                            ]));
                            $response = [
                                'status' => 'success',
                                'message' => '登录成功！',
                                'username' => $user['username'],
                                'session_key' => $session_key
                            ];
                        }
                    } else {
                        $response['message'] = '密码错误。';
                    }
                } else {
                    $response['message'] = '管理员账户未找到。';
                }
                $stmt->close();
                $conn->close();
            }
        }
    }
}

if ($action == 'logout') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $session_key_to_clear = $_POST['key'] ?? '';
        $lock_file = __DIR__ . '/session_lock.json';

        // 只有当接收到的密钥非空，且锁文件存在时，才进行比对和删除
        if (!empty($session_key_to_clear) && file_exists($lock_file)) {
            $lock_data = json_decode(file_get_contents($lock_file), true);
            // 确认要登出的密钥和当前锁定的密钥是同一个
            if (isset($lock_data['session_key']) && $lock_data['session_key'] === $session_key_to_clear) {
                unlink($lock_file); // 删除锁定文件
            }
        }
    }
    // 登出操作无论如何都静默成功，不返回给客户端任何信息，直接退出
    exit();
}

// --- 完整的 'force_clear_lock' 处理逻辑 ---
if ($action == 'force_clear_lock') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = '此操作必须使用POST方法。';
    } else {
        $password = $_POST['password'] ?? '';
        if (empty($password)) {
            $response['message'] = '需要提供管理员密码才能执行此操作。';
        } else {
            $conn = get_db_connection();
            if (!$conn) {
                $response['message'] = '数据库连接失败。';
            } else {
                $admin_username_to_check = 'tianyuadmin';
                $sql = "SELECT password_hash FROM users WHERE username = ? LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $admin_username_to_check);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password_hash'])) {
                        $lock_file = __DIR__ . '/session_lock.json';
                        if (file_exists($lock_file)) {
                            if (unlink($lock_file)) {
                                $response = ['status' => 'success', 'message' => '服务器上的会话锁已被强制清除！现在可以尝试重新登录。'];
                            } else {
                                $response['message'] = '清除锁失败：无法删除 session_lock.json 文件。';
                            }
                        } else {
                            $response = ['status' => 'success', 'message' => '服务器上没有活动的会话锁，无需清除。'];
                        }
                    } else {
                        $response['message'] = '管理员密码错误，操作被拒绝。';
                    }
                } else {
                    $response['message'] = '管理员账户未找到。';
                }
                $stmt->close();
                $conn->close();
            }
        }
    }
}

// 将最终的响应数组编码为JSON字符串并输出
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>