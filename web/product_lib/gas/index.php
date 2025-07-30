<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    // --- 页面配置区 ---
    // 你只需要修改这里的信息，就可以把这个文件复用到任何一个分类！
    $category_title = "废气·恶臭治理";
    $category_subtitle = "PP/FRP喷淋塔联合光氧吸附，多段工艺应对VOCs、异味及酸碱废气，排放轻松达标。";
    $hero_image_url = "/assets/img/products/废气管道.png";

    $seo_title = "废气·恶臭治理设备 | 产品中心 - 河南天昱环保";
    $seo_description = "天昱环保提供多种废气及恶臭治理解决方案，包括废气吸收塔、铅烟净化器、活性炭吸附装置等，技术成熟，应用广泛。";

    // --- 智能数据提取 ---
    $products = [];
    $html_files = glob('*.html');
    
    // 检查DOM扩展是否可用
    $dom_ext_loaded = class_exists('DOMDocument');

    foreach ($html_files as $file) {
        $file_content = file_get_contents($file);
        $product_name = '未知产品';
        $thumbnail_url = '/assets/img/products/placeholder.png'; // 默认缩略图

        // 1. 提取产品名称 (通用)
        if (preg_match('/<title>(.*?)<\/title>/i', $file_content, $matches)) {
            $product_name = trim(str_replace(['| 河南天昱环保', '- 废气治理', '- 污水处理'], '', $matches[1]));
        }
        
        // 2. 提取缩略图 (双引擎模式)
        if ($dom_ext_loaded) {
            // 主引擎：使用DOMDocument，精准可靠
            $dom = new DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $file_content); // 增加XML头防止中文乱码
            $xpath = new DOMXPath($dom);
            $image_nodes = $xpath->query('//section[contains(@class, "device-images")]//img');
            if ($image_nodes->length > 0) {
                $first_image_src = $image_nodes[0]->getAttribute('src');
                if (!empty($first_image_src)) { $thumbnail_url = $first_image_src; }
            }
        } else {
            // 备用引擎：使用正则表达式，兼容性强
            if (preg_match('/<section class=".*?device-images.*?">.*?<img.*?src="(.*?)".*?>/is', $file_content, $img_matches)) {
                if (!empty($img_matches[1])) { $thumbnail_url = $img_matches[1]; }
            }
        }

        $products[] = ['name' => $product_name, 'thumbnail' => $thumbnail_url, 'link' => $file];
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($seo_title); ?></title>
    </head>
<body>
    <script src="/assets/js/header.js"></script>

    <main class="product-category-page">
        <section class="hero-about" style="background-image: url('<?php echo htmlspecialchars($hero_image_url); ?>');">
            </section>

        <?php if (!$dom_ext_loaded): ?>
            <div style="background-color: #fff3cd; color: #856404; padding: 15px; text-align: center; border: 1px solid #ffeeba;">
                <b>开发者提示：</b> 服务器未安装PHP的 "DOM" 扩展，当前正在使用备用方案提取缩略图。为了达到最佳效果，建议在服务器上安装 <b>php-dom</b> 或 <b>php-xml</b> 扩展。
            </div>
        <?php endif; ?>

        <section class="in-page-search-section">
            </section>

        <section class="product-list-section">
            </section>
    </main>

    <script src="/assets/js/footer.js"></script>
    </body>
</html>