<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit;
    }
    // 产品分类
    $categories = [
        "water" => "污水·废水处理",
        "water_supply" => "纯水·给水系统",
        "gas" => "废气·恶臭治理",
        "dust" => "除尘·烟气治理",
        "disinfection" => "消毒·杀菌设备",
        "parts" => "填料·配件耗材"
    ];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>产品信息管理 - CMS</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/pages/_admin.css">
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>

    <main class="admin-main">
        <div class="container">
            <div class="action-bar">
                <h1>产品信息管理</h1>
                <a href="edit_product.php" class="btn">添加新产品</a>
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
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th>产品名称</th>
                        <th>缩略图</th>
                        <th style="width: 20%;">操作</th>
                    </tr>
                </thead>
                <tbody id="products-tbody">
                    </tbody>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('products-tbody');
            const categorySelector = document.getElementById('category-selector');

            // 1. 加载指定分类的产品
            function loadProducts(category) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">正在加载...</td></tr>';
                // 我们调用API来获取指定分类的产品列表
                fetch(`/admin/api/products_handler.php?action=get_products&category=${category}`)
                    .then(response => response.json())
                    .then(data => {
                        tbody.innerHTML = ''; // 清空
                        if (data.status === 'success' && data.products) {
                            if (data.products.length === 0) {
                                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">该分类下暂无产品。</td></tr>';
                                return;
                            }
                            // 遍历返回的产品数据，并创建表格行
                            data.products.forEach((product, index) => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${index}</td>
                                    <td>${escapeHtml(product.name)}</td>
                                    <td><img src="${escapeHtml(product.thumbnail)}" alt="${escapeHtml(product.name)}"></td>
                                    <td class="actions">
                                        <a href="edit_product.php?category=${category}&index=${index}" class="btn">编辑</a>
                                        <button class="btn btn-danger" data-category="${category}" data-index="${index}" data-name="${escapeHtml(product.name)}">删除</button>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        } else {
                            tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;">加载产品失败: ${data.message || '未知错误'}</td></tr>`;
                        }
                    })
                    .catch(error => {
                        console.error("加载产品失败:", error);
                        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">网络错误，无法加载产品。</td></tr>';
                    });
            }

            // 2. 当下拉菜单变化时，重新加载产品
            categorySelector.addEventListener('change', function() {
                loadProducts(this.value);
            });
            
            // 3. (待办) 删除逻辑
            tbody.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-danger')) {
                    const btn = event.target;
                    const category = btn.dataset.category;
                    const index = btn.dataset.index;
                    const name = btn.dataset.name;

                    if (confirm(`确定要删除产品 “${name}” 吗？这将同时删除对应的HTML页面！`)) {
                        fetch(`/admin/api/products_handler.php?action=delete_product&category=${category}&index=${index}`)
                        .then(res => res.json())
                        .then(data => {
                            if(data.status === 'success') {
                                alert('删除成功！');
                                loadProducts(category); // 重新加载当前分类
                            } else {
                                alert('删除失败：' + data.message);
                            }
                        });
                    }
                }
            });

            function escapeHtml(unsafe) {
                if (typeof unsafe !== 'string') return '';
                return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
            }

            // 页面首次加载时，自动加载第一个分类的产品
            loadProducts(categorySelector.value);
        });
    </script>
</body>
</html>