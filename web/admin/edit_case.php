<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) { header('Location: login.php'); exit; }
    $page_mode = 'add';
    $category = $_GET['category'] ?? 'water'; // 默认是水处理
    $index = -1;
    $case = ['title' => '', 'image' => '', 'link' => '', 'content' => ''];
    if (isset($_GET['index'])) {
        $page_mode = 'edit';
        $index = intval($_GET['index']);
        $cases = json_decode(file_get_contents(__DIR__ . '/../cases_lib/' . $category . '/cases.json'), true);
        if (isset($cases[$index])) { $case = $cases[$index]; }
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title><?php echo $page_mode === 'edit' ? '编辑案例' : '添加新案例'; ?> - CMS</title>
    </head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    <main class="admin-main">
        <div class="container">
            <h1><?php echo $page_mode === 'edit' ? '编辑案例' : '添加新案例'; ?></h1>
            <form id="case-form" class="admin-form">
                <input type="hidden" id="category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="hidden" id="index" value="<?php echo $index; ?>">
                <div class="form__group"><label>案例标题 (title)</label><input type="text" id="title" value="<?php echo htmlspecialchars($case['title']); ?>" required></div>
                <div class="form__group"><label>案例文件名 (link, 例如: case-zhengzhou.html)</label><input type="text" id="link" value="<?php echo htmlspecialchars($case['link']); ?>" required></div>
                <div class="form__group"><label>主图 (image, 相对路径)</label><input type="text" id="image" value="<?php echo htmlspecialchars($case['image']); ?>"></div>
                <div class="form__group"><label>案例详情 (content)</label><textarea id="content" rows="10"><?php echo htmlspecialchars($case['content']); ?></textarea></div>
                <button type="submit" class="btn">保存</button>
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