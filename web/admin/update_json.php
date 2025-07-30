<?php
// /web/admin/update_json.php - 你的“一键更新神器”

session_start();
// 安全检查：只有登录的管理员才能运行此脚本
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die('错误：请先登录CMS后台！');
}

// --- 配置区 ---
$product_lib_dir = __DIR__ . '/../product_lib';
$categories = ['water', 'gas', 'dust', 'water_supply', 'disinfection', 'parts'];
$log = [];

// --- 核心逻辑 ---
foreach ($categories as $category) {
    $category_path = $product_lib_dir . '/' . $category;
    if (!is_dir($category_path)) {
        $log[] = "🟡 分类目录 '{$category}' 不存在，已跳过。";
        continue;
    }

    $products_data = [];
    $html_files = glob($category_path . '/*.html');
    
    foreach ($html_files as $file_path) {
        $file_name = basename($file_path);
        if (strtolower($file_name) === 'index.html') continue;

        $file_content = file_get_contents($file_path);
        $product_name = '未知产品 - ' . $file_name;
        $thumbnail_url = '/assets/img/products/placeholder.png'; // 默认图

        // 1. 提取标题
        if (preg_match('/<title>(.*?)<\/title>/i', $file_content, $matches)) {
            $product_name = trim(preg_replace('/–|—|-|\|.*$/', '', $matches[1]));
        }

        // 2. 提取缩略图 (使用更可靠的DOM解析)
        if (class_exists('DOMDocument')) {
            $dom = new DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $file_content);
            $xpath = new DOMXPath($dom);
            $image_nodes = $xpath->query('(//section[contains(@class, "device-images")]//img)[1]');
            if ($image_nodes && $image_nodes->length > 0) {
                $src = $image_nodes[0]->getAttribute('src');
                if (!empty($src)) { $thumbnail_url = $src; }
            }
        }
        
        $products_data[] = ['name' => $product_name, 'thumbnail' => $thumbnail_url, 'link' => $file_name];
    }
    
    // 3. 将更新后的数据写回 products.json
    $json_path = $category_path . '/products.json';
    $result = file_put_contents($json_path, json_encode($products_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result !== false) {
        $log[] = "✅ 成功更新分类 '{$category}' 的 products.json，共找到 " . count($products_data) . " 个产品。";
    } else {
        $log[] = "❌ 错误：无法写入 '{$json_path}'，请检查文件夹权限！";
    }
}

// --- 输出操作结果 ---
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>更新产品索引</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .log { border: 1px solid #ccc; padding: 15px; border-radius: 5px; background: #f5f5f5; }
        .log div { margin-bottom: 8px; }
        a { margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>
    <h1>产品索引更新完成！</h1>
    <div class="log">
        <?php foreach ($log as $message): ?>
            <div><?php echo $message; ?></div>
        <?php endforeach; ?>
    </div>
    <a href="/admin/manage_products.php">返回产品管理</a>
</body>
</html>