<?php
    session_start();
    // 检查是否已登录
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit;
    }

    // --- 服务器信息获取 ---
    
    // 1. 基础信息
    $server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'N/A';
    $php_version = phpversion();
    $server_ip = $_SERVER['SERVER_ADDR'] ?? 'N/A';
    $document_root = $_SERVER['DOCUMENT_ROOT'] ?? 'N/A';

    // 2. 数据库信息 (尝试连接)
    include_once __DIR__ . '/includes/db_config.php';
    $conn = get_db_connection();
    if ($conn) {
        $db_version = $conn->server_info;
        $db_status = '<span class="status-ok">连接正常</span>';
        $conn->close();
    } else {
        $db_version = 'N/A';
        $db_status = '<span class="status-error">连接失败</span>';
    }

    // 3. 磁盘空间信息
    $disk_total_space = disk_total_space('/');
    $disk_free_space = disk_free_space('/');
    $disk_used_space = $disk_total_space - $disk_free_space;
    $disk_usage_percent = ($disk_used_space / $disk_total_space) * 100;

    // 格式化函数，把字节转换成GB
    function format_bytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    // 4. PHP核心配置
    $upload_max_filesize = ini_get('upload_max_filesize');
    $post_max_size = ini_get('post_max_size');
    $memory_limit = ini_get('memory_limit');

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS管理后台</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/pages/_admin.css"> </head>
<body>

    <?php include __DIR__ . '/includes/admin_header.php'; ?>

    <main class="admin-main">
        <div class="container">
            <h1>后台主面板</h1>
            <p>欢迎回来！以下是当前服务器的关键状态信息。</p>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <h3>服务器概览</h3>
                    <ul>
                        <li><span class="label">Web服务器:</span> <?php echo htmlspecialchars($server_software); ?></li>
                        <li><span class="label">PHP版本:</span> <?php echo htmlspecialchars($php_version); ?></li>
                        <li><span class="label">服务器IP:</span> <?php echo htmlspecialchars($_SERVER['SERVER_ADDR'] ?? 'N/A'); ?></li>
                        <li><span class="label">网站根目录:</span> <?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'N/A'); ?></li>
                    </ul>
                </div>

                <div class="stat-card">
                    <h3>数据库状态</h3>
                     <ul>
                        <li><span class="label">连接状态:</span> <?php echo $db_status; ?></li>
                        <li><span class="label">数据库版本:</span> <?php echo htmlspecialchars($db_version); ?></li>
                    </ul>
                </div>

                <div class="stat-card">
                    <h3>磁盘空间</h3>
                    <ul>
                        <li><span class="label">总空间:</span> <?php echo format_bytes($disk_total_space); ?></li>
                        <li><span class="label">已使用:</span> <?php echo format_bytes($disk_used_space); ?></li>
                        <li><span class="label">可用空间:</span> <?php echo format_bytes($disk_free_space); ?></li>
                    </ul>
                    <div class="progress-bar">
                        <div class="progress-bar__fill" style="width: <?php echo round($disk_usage_percent); ?>%;"></div>
                    </div>
                </div>

                 <div class="stat-card">
                    <h3>PHP核心配置</h3>
                     <ul>
                        <li><span class="label">上传限制:</span> <?php echo htmlspecialchars($upload_max_filesize); ?></li>
                        <li><span class="label">POST限制:</span> <?php echo htmlspecialchars($post_max_size); ?></li>
                        <li><span class="label">内存限制:</span> <?php echo htmlspecialchars($memory_limit); ?></li>
                    </ul>
                </div>

            </div>
        </div>
    </main>

</body>
</html>