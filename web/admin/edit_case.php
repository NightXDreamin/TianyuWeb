<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) { header('Location: login.php'); exit; }
    
    $page_mode = 'add';
    $category = $_GET['category'] ?? 'water';
    $index = -1;
    $case = ['title' => '', 'image' => '', 'link' => '', 'content' => ''];

    if (isset($_GET['index']) && is_numeric($_GET['index'])) {
        $page_mode = 'edit';
        $index = intval($_GET['index']);
        $json_path = __DIR__ . '/../cases_lib/' . basename($category) . '/cases.json';
        if (file_exists($json_path)) {
            $cases = json_decode(file_get_contents($json_path), true);
            if (isset($cases[$index])) { $case = $cases[$index]; }
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_mode === 'edit' ? '编辑案例' : '添加新案例'; ?> - CMS</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/pages/_admin.css">
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    <main class="admin-main">
        <div class="container">
            <h1><?php echo $page_mode === 'edit' ? '编辑案例 - ' . htmlspecialchars($case['title']) : '添加新案例到 ' . htmlspecialchars($category); ?></h1>
            
            <form id="case-form" class="admin-form">
                <input type="hidden" id="category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="hidden" id="index" value="<?php echo $index; ?>">

                <div class="form__group">
                    <label for="title" class="form__label">案例标题 (title)</label>
                    <input type="text" id="title" class="form__input" value="<?php echo htmlspecialchars($case['title']); ?>" required>
                </div>
                
                <div class="form__group">
                    <label for="link" class="form__label">案例文件名 (link, 例如: case-zhengzhou.html)</label>
                    <input type="text" id="link" class="form__input" value="<?php echo htmlspecialchars($case['link']); ?>" required>
                </div>

                <div class="form__group">
                    <label for="image" class="form__label">主图 (image, 相对路径)</label>
                    <input type="text" id="image" class="form__input" value="<?php echo htmlspecialchars($case['image']); ?>">
                </div>
                
                <div class="form__group">
                    <label for="content" class="form__label">案例详情 (content)</label>
                    <textarea id="content" class="form__textarea" rows="10"><?php echo htmlspecialchars($case['content']); ?></textarea>
                </div>
                
                <div class="form__actions">
                    <button type="submit" class="btn">保存案例</button>
                    <a href="manage_cases.php" class="btn btn-secondary">取消</a>
                </div>
            </form>
        </div>
    </main>
    <script>
    document.getElementById('case-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const caseData = {
            title: document.getElementById('title').value,
            image: document.getElementById('image').value,
            link: document.getElementById('link').value,
            content: document.getElementById('content').value
        };
        const formData = new FormData();
        formData.append('action', 'save_case');
        formData.append('category', document.getElementById('category').value);
        formData.append('index', document.getElementById('index').value);
        formData.append('caseData', JSON.stringify(caseData));
        fetch('/admin/api/cases_handler.php', { method: 'POST', body: formData })
        .then(res => res.json()).then(data => {
            if (data.status === 'success') {
                alert('保存成功！');
                window.location.href = 'manage_cases.php';
            } else { alert('保存失败：' + data.message); }
        });
    });
    </script>
</body>
</html>