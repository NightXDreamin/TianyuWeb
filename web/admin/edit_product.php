<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { header('Location: login.php'); exit; }
    
    // 获取模式 (添加/编辑) 和数据
    $page_mode = 'add';
    $category = $_GET['category'] ?? '';
    $index = -1;
    $product = [
        'name' => '', 'thumbnail' => '', 'link' => '', 'category' => $category,
        'images' => [], 'seo' => ['title'=>'', 'description'=>''], 'content' => ''
    ];
    
    if (isset($_GET['index'])) {
        $page_mode = 'edit';
        $index = intval($_GET['index']);
        $products = json_decode(file_get_contents(__DIR__ . '/../product_lib/' . $category . '/products.json'), true);
        if (isset($products[$index])) {
            $product = $products[$index];
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title><?php echo $page_mode === 'edit' ? '编辑产品' : '添加新产品'; ?> - CMS</title>
    </head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    <main class="admin-main">
        <div class="container">
            <h1><?php echo $page_mode === 'edit' ? '编辑产品 - ' . htmlspecialchars($product['name']) : '添加新产品到 ' . htmlspecialchars($category); ?></h1>
            
            <form id="product-form" class="admin-form">
                <input type="hidden" id="category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="hidden" id="index" value="<?php echo $index; ?>">

                <div class="form__group">
                    <label>产品名称 (productName)</label>
                    <input type="text" id="productName" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <div class="form__group">
                    <label>产品文件名 (link, 例如: my-product.html)</label>
                    <input type="text" id="link" value="<?php echo htmlspecialchars($product['link']); ?>" required>
                </div>

                <div class="form__group">
                    <label>缩略图 (thumbnail, 相对路径)</label>
                    <input type="text" id="thumbnail" value="<?php echo htmlspecialchars($product['thumbnail']); ?>">
                </div>
                
                <div class="form__group">
                    <label>SEO 标题 (seo.title)</label>
                    <input type="text" id="seo-title" value="<?php echo htmlspecialchars($product['seo']['title']); ?>">
                </div>

                <div class="form__group">
                    <label>SEO 描述 (seo.description)</label>
                    <textarea id="seo-description" rows="3"><?php echo htmlspecialchars($product['seo']['description']); ?></textarea>
                </div>

                <div class="form__group">
                    <label>详情页图片 (images, 每行一个相对路径)</label>
                    <textarea id="images" rows="5"><?php echo implode("\n", $product['images']); ?></textarea>
                </div>
                
                <div class="form__group">
                    <label>产品详细介绍 (content, 支持Markdown)</label>
                    <textarea id="content" rows="10"><?php echo htmlspecialchars($product['content']); ?></textarea>
                </div>
                
                <button type="submit" class="btn">保存</button>
            </form>
        </div>
    </main>
    <script>
    document.getElementById('product-form').addEventListener('submit', function(event) {
        event.preventDefault();
        
        // 1. 收集数据
        const productData = {
            name: document.getElementById('productName').value,
            thumbnail: document.getElementById('thumbnail').value,
            link: document.getElementById('link').value,
            category: document.getElementById('category').value,
            images: document.getElementById('images').value.split('\\n').filter(Boolean),
            seo: {
                title: document.getElementById('seo-title').value,
                description: document.getElementById('seo-description').value
            },
            content: document.getElementById('content').value
        };
        
        // 2. 发送API请求
        const formData = new FormData();
        formData.append('action', 'save_product');
        formData.append('category', productData.category);
        formData.append('index', document.getElementById('index').value);
        formData.append('productData', JSON.stringify(productData));
        
        fetch('/admin/api/products_handler.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert('保存成功！');
                window.location.href = 'manage_products.php';
            } else {
                alert('保存失败：' + data.message);
            }
        });
    });
    </script>
</body>
</html>