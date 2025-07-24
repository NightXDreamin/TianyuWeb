<?php
    session_start();
    // 如果已经登录，直接跳转到后台主面板
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header('Location: dashboard.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>天昱环保CMS - 登录</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="login-page">

    <div class="login-container">
        <h1>CMS登录</h1>
        
        <div id="login-error-container" class="login-error" style="display: none;"></div>

        <form id="login-form" class="login-form">
            <div class="form__group">
                <label for="username" class="form__label">用户名</label>
                <input type="text" id="username" name="username" class="form__input" required>
            </div>
            <div class="form__group">
                <label for="password" class="form__label">密码</label>
                <input type="password" id="password" name="password" class="form__input" required>
            </div>
            <button type="submit" class="btn">登录</button>
        </form>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault(); // 阻止表单默认提交
            
            const form = event.target;
            const formData = new FormData(form);
            const errorContainer = document.getElementById('login-error-container');

            errorContainer.style.display = 'none'; // 先隐藏旧的错误信息

            fetch('auth.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // 登录成功，跳转到后台主面板
                    window.location.href = 'dashboard.php';
                } else {
                    // 登录失败，显示错误信息
                    errorContainer.textContent = data.message;
                    errorContainer.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('登录请求失败:', error);
                errorContainer.textContent = '发生网络错误，请稍后重试。';
                errorContainer.style.display = 'block';
            });
        });
    </script>
</body>
</html>