<?php
// /api_handlers/db_config.php
// 数据库连接配置文件

// --- 请在此处填写您自己的数据库信息 ---
define('DB_HOST', '127.0.0.1');       // 数据库主机，通常是 127.0.0.1 或 localhost
define('DB_NAME', 'wskpr6wsgxwmrhes');      // 您创建的数据库名
define('DB_USER', 'WSkPr6wsGxwmRHEs');  // 您创建的数据库用户名
define('DB_PASS', 'arTG7RMXemfjNaYj');      // 您复制的数据库密码

/**
 * 创建并返回一个数据库连接对象。
 * @return mysqli|null 返回一个 mysqli 连接对象，如果失败则返回 null。
 */
function get_db_connection() {
    // 使用 define 定义的常量来连接
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // 检查连接是否成功
    if ($conn->connect_error) {
        // 在实际生产环境中，不应直接暴露错误信息
        // 但在开发阶段，这有助于我们调试
        error_log("Database connection failed: " . $conn->connect_error);
        return null;
    }

    // 设置字符集为 utf8mb4，以支持包括Emoji在内的所有Unicode字符
    $conn->set_charset("utf8mb4");

    return $conn;
}
?>