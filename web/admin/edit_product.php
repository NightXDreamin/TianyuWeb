<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) { header('Location: login.php'); exit; }
    
    // --- 数据准备 ---
    $page_mode = 'add';
    $category = $_GET['category'] ?? 'gas';
    $index = -1;
    $product = [
        'name' => '', 'thumbnail' => '', 'link' => '', 'category' => $category,
        'images' => [], 'seo' => ['title'=>'', 'description'=>''], 'content' => ''
    ];
    
    if (isset($_GET['index']) && is_numeric($_GET['index'])) {
        $page_mode = 'edit';
        $index = intval($_GET['index']);
        $json_path = __DIR__ . '/../product_lib/' . basename($category) . '/products.json';
        if (file_exists($json_path)) {
            $products = json_decode(file_get_contents($json_path), true);
            if (isset($products[$index])) { $product = $products[$index]; }
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_mode === 'edit' ? '编辑产品' : '添加新产品'; ?> - CMS</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/pages/_admin.css">
    <style> /* 专属上传样式 */
        .image-uploader { border: 2px dashed var(--border-color); padding: 20px; border-radius: 8px; text-align: center; cursor: pointer; transition: background-color .3s ease; }
        .image-uploader:hover { background-color: #f5f8fb; }
        .image-preview { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px; }
        .preview-item { position: relative; width: 150px; height: 150px; }
        .preview-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
        .preview-item .remove-btn { position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-weight: bold; }
        #form-message { margin-top: 20px; padding: 15px; border-radius: 8px; display: none; }
        #form-message.success { background-color: #e8f5e9; color: #2e7d32; }
        #form-message.error { background-color: #ffebee; color: #c62828; }
    </style>
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
                    <label for="productName" class="form__label">产品名称 (name)</label>
                    <input type="text" id="productName" class="form__input" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="form__group">
                    <label for="link" class="form__label">产品文件名 (link, 例如: my-product.html)</label>
                    <input type="text" id="link" class="form__input" value="<?php echo htmlspecialchars($product['link']); ?>" required>
                </div>

                <div class="form__group">
                    <label class="form__label">缩略图 (thumbnail)</label>
                    <input type="file" id="thumbnail-input" style="display: none;">
                    <div id="thumbnail-uploader" class="image-uploader">点击上传或拖拽图片</div>
                    <div id="thumbnail-preview" class="image-preview">
                        <?php if(!empty($product['thumbnail'])): ?>
                            <div class="preview-item" data-url="<?php echo htmlspecialchars($product['thumbnail']); ?>">
                                <img src="<?php echo htmlspecialchars($product['thumbnail']); ?>">
                                <button type="button" class="remove-btn">×</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form__group">
                    <label class="form__label">详情页图片 (images, 可多选)</label>
                    <input type="file" id="images-input" multiple style="display: none;">
                    <div id="images-uploader" class="image-uploader">点击上传或拖拽图片</div>
                    <div id="images-preview" class="image-preview">
                        <?php if(!empty($product['images'])): foreach($product['images'] as $img): ?>
                            <div class="preview-item" data-url="<?php echo htmlspecialchars($img); ?>">
                                <img src="<?php echo htmlspecialchars($img); ?>">
                                <button type="button" class="remove-btn">×</button>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>

                <div class="form__group">
                    <label for="seo-title" class="form__label">SEO 标题</label>
                    <input type="text" id="seo-title" class="form__input" value="<?php echo htmlspecialchars($product['seo']['title'] ?? ''); ?>">
                </div>
                <div class="form__group">
                    <label for="seo-description" class="form__label">SEO 描述</label>
                    <textarea id="seo-description" class="form__textarea" rows="3"><?php echo htmlspecialchars($product['seo']['description'] ?? ''); ?></textarea>
                </div>
                <div class="form__group">
                    <label for="content" class="form__label">产品详细介绍 (支持Markdown)</label>
                    <textarea id="content" class="form__textarea" rows="10"><?php echo htmlspecialchars($product['content']); ?></textarea>
                </div>
                
                <div id="form-message"></div>

                <div class="form__actions">
                    <button type="submit" class="btn">保存产品</button>
                    <a href="manage_products.php" class="btn btn-secondary">返回列表</a>
                </div>
            </form>
        </div>
    </main>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 核心JS逻辑 ---
        
        // 上传函数：接收一个文件和一个类型，返回一个包含URL的Promise
        function uploadFile(file, type) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('type', type); // 告诉API上传到哪个子目录

            return fetch('/admin/api/upload_handler.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json());
        }

        // 初始化单个上传区域
        function initUploader(inputId, uploaderId, previewId, isMultiple = false) {
            const fileInput = document.getElementById(inputId);
            const uploader = document.getElementById(uploaderId);
            const preview = document.getElementById(previewId);

            uploader.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', (event) => {
                handleFiles(event.target.files);
            });

            function handleFiles(files) {
                if (!isMultiple) {
                    preview.innerHTML = ''; // 单图上传，先清空
                }
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.innerHTML = `<img src="${e.target.result}"><button type="button" class="remove-btn">×</button>`;
                        div.file = file; // 将文件对象附加到DOM元素上
                        preview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
            
            preview.addEventListener('click', function(event) {
                if(event.target.classList.contains('remove-btn')) {
                    event.target.parentElement.remove();
                }
            });
        }

        initUploader('thumbnail-input', 'thumbnail-uploader', 'thumbnail-preview', false);
        initUploader('images-input', 'images-uploader', 'images-preview', true);

        // 表单提交
        document.getElementById('product-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            const submitBtn = event.target.querySelector('button[type="submit"]');
            const messageDiv = document.getElementById('form-message');
            submitBtn.disabled = true;
            submitBtn.textContent = '正在保存...';
            messageDiv.style.display = 'none';

            try {
                // 1. 上传所有新图片
                const thumbnailPreviewItems = document.querySelectorAll('#thumbnail-preview .preview-item');
                const imagesPreviewItems = document.querySelectorAll('#images-preview .preview-item');
                
                let thumbnailUrl = thumbnailPreviewItems.length > 0 ? (thumbnailPreviewItems[0].dataset.url || null) : '';
                
                // 如果有新文件，上传它
                if (thumbnailPreviewItems.length > 0 && thumbnailPreviewItems[0].file) {
                    const uploadResult = await uploadFile(thumbnailPreviewItems[0].file, 'products');
                    if(uploadResult.status !== 'success') throw new Error('缩略图上传失败: ' + uploadResult.message);
                    thumbnailUrl = uploadResult.url;
                }

                const imagesUrls = [];
                for(const item of imagesPreviewItems) {
                    if (item.dataset.url) { // 如果是旧图片
                        imagesUrls.push(item.dataset.url);
                    } else if (item.file) { // 如果是新图片
                        const uploadResult = await uploadFile(item.file, 'products');
                        if(uploadResult.status !== 'success') throw new Error('详情图上传失败: ' + uploadResult.message);
                        imagesUrls.push(uploadResult.url);
                    }
                }

                // 2. 收集所有数据
                const productData = {
                    name: document.getElementById('productName').value,
                    link: document.getElementById('link').value,
                    category: document.getElementById('category').value,
                    thumbnail: thumbnailUrl,
                    images: imagesUrls,
                    seo: {
                        title: document.getElementById('seo-title').value,
                        description: document.getElementById('seo-description').value
                    },
                    content: document.getElementById('content').value
                };

                // 3. 发送给产品API
                const apiFormData = new FormData();
                apiFormData.append('action', 'save_product');
                apiFormData.append('category', productData.category);
                apiFormData.append('index', document.getElementById('index').value);
                apiFormData.append('productData', JSON.stringify(productData));

                const saveResponse = await fetch('/admin/api/products_handler.php', { method: 'POST', body: apiFormData });
                const saveData = await saveResponse.json();

                if (saveData.status === 'success') {
                    messageDiv.textContent = '产品保存成功！即将跳转...';
                    messageDiv.className = 'success';
                    messageDiv.style.display = 'block';
                    setTimeout(() => window.location.href = 'manage_products.php', 1500);
                } else {
                    throw new Error(saveData.message);
                }

            } catch (error) {
                messageDiv.textContent = '发生错误：' + error.message;
                messageDiv.className = 'error';
                messageDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = '保存产品';
            }
        });
    });
    </script>
</body>
</html>