<?php
    session_start();
    // 检查是否已登录
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit;
    }
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

    <?php include __DIR__ . '/includes/admin_header.php'; // 引入后台通用页眉 ?>

    <main class="admin-main">
        <div class="container">
            <h1>后台主面板</h1>
            <p>请从上方导航选择您要管理的项目。</p>
        </div>
    </main>

</body>
</html>