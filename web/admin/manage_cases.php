<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { header('Location: login.php'); exit; }
    // 案例分类
    $categories = ["water" => "水处理", "gas" => "气体处理", "noise" => "噪音治理"];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>工程案例管理 - CMS</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/pages/_admin.css">
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    <main class="admin-main">
        <div class="container">
            <div class="action-bar">
                <h1>工程案例管理</h1>
                <a href="edit_case.php" class="btn">添加新案例</a>
            </div>
            <div class="filter-bar">
                <label for="category-selector" class="form__label">按分类筛选:</label>
                <select id="category-selector" class="form__input" style="max-width: 300px;">
                    <?php foreach ($categories as $key => $name): ?>
                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <table class="admin-table">
                <thead>
                    <tr><th>案例标题</th><th>主图</th><th>操作</th></tr>
                </thead>
                <tbody id="cases-tbody"></tbody>
            </table>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('cases-tbody');
            const categorySelector = document.getElementById('category-selector');

            function loadCases(category) {
                tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;">正在加载...</td></tr>';
                fetch(`/admin/api/cases_handler.php?action=get_cases&category=${category}`)
                    .then(response => response.json())
                    .then(data => {
                        tbody.innerHTML = '';
                        if (data.status === 'success' && data.cases.length > 0) {
                            data.cases.forEach((caseItem, index) => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${caseItem.title}</td>
                                    <td><img src="${caseItem.image}" alt="${caseItem.title}"></td>
                                    <td class="actions">
                                        <a href="edit_case.php?category=${category}&index=${index}" class="btn">编辑</a>
                                        <button class="btn btn-danger" data-category="${category}" data-index="${index}" data-name="${caseItem.title}">删除</button>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        } else {
                            tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;">该分类下暂无案例。</td></tr>';
                        }
                    });
            }
            categorySelector.addEventListener('change', () => loadCases(categorySelector.value));
            
            tbody.addEventListener('click', function(event){
                if(event.target.classList.contains('btn-danger')){
                    const btn = event.target;
                    const category = btn.dataset.category;
                    const index = btn.dataset.index;
                    const name = btn.dataset.name;
                    if (confirm(`确定要删除案例 “${name}” 吗？`)) {
                        fetch(`/admin/api/cases_handler.php?action=delete_case&category=${category}&index=${index}`)
                        .then(res => res.json()).then(data => {
                            if(data.status === 'success') {
                                loadCases(category);
                            } else { alert('删除失败：' + data.message); }
                        });
                    }
                }
            });

            loadCases(categorySelector.value);
        });
    </script>
</body>
</html>