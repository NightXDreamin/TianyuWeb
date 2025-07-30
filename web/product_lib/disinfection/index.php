<?php
    // --- 诊断探头 ---
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // --- 页面配置区 ---
    $category_title = "消毒·杀菌设备";
    $category_subtitle = "自产次氯酸钠、臭氧、紫外线系列设备，现场制剂现场投加，安全高效。";
    $hero_image_url = "/assets/img/products/消毒桶.png";

    $seo_title = "水处理消毒杀菌设备 | 次氯酸钠·臭氧·紫外线发生器 - 河南天昱环保";
    $seo_description = "选购天昱环保高效水处理消毒杀菌设备。我们提供次氯酸钠发生器、臭氧发生器、紫外线(UV)消毒装置等，适用于饮用水、循环水及污水处理，现场制备，安全可靠。";

    // --- 智能数据提取 ---
    $products = [];
    $html_files = glob('*.html');
    $dom_ext_loaded = class_exists('DOMDocument');

    foreach ($html_files as $file) {
        $file_content = file_get_contents($file);
        $product_name = '未知产品';
        $thumbnail_url = '/assets/img/products/placeholder.png'; // 根相对路径

        // 提取产品名称
        if (preg_match('/<title>(.*?)<\/title>/i', $file_content, $matches)) {
            $product_name = trim(str_replace(['| 河南天昱环保', '- 废气治理', '- 污水处理'], '', $matches[1]));
        }
        
        // 提取缩略图
        if ($dom_ext_loaded) {
            $dom = new DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $file_content);
            $xpath = new DOMXPath($dom);
            $image_nodes = $xpath->query('//section[contains(@class, "device-images")]//img');
            if ($image_nodes->length > 0) {
                $first_image_src = $image_nodes[0]->getAttribute('src');
                if (!empty($first_image_src)) { $thumbnail_url = $first_image_src; }
            }
        } else {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title><?php echo htmlspecialchars($seo_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seo_description); ?>">
    
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body>

    <script src="/assets/js/header.js"></script>

    <main class="product-category-page">
        <section class="hero-about" style="background-image: url('<?php echo htmlspecialchars($hero_image_url); ?>');">
            <div class="container" data-aos="fade-in">
                <h1 class="hero-about__title"><?php echo htmlspecialchars($category_title); ?></h1>
                <p class="hero-about__subtitle"><?php echo htmlspecialchars($category_subtitle); ?></p>
            </div>
        </section>

        <?php if (!$dom_ext_loaded): ?>
            <div style="background-color: #fff3cd; color: #856404; padding: 15px; text-align: center; border: 1px solid #ffeeba;">
                <b>开发者提示：</b> 服务器未安装PHP的 "DOM" 扩展，当前正在使用备用方案提取缩略图。为了达到最佳效果，建议在服务器上安装 <b>php-dom</b> 或 <b>php-xml</b> 扩展。
            </div>
        <?php endif; ?>

        <section class="in-page-search-section">
            <div class="container">
                <input type="text" id="page-search-input" placeholder="在“<?php echo htmlspecialchars($category_title); ?>”分类下搜索设备...">
            </div>
        </section>

        <section class="product-list-section">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h2>设备列表</h2>
                </div>
                
                <div class="product-grid">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $index => $product): ?>
                            <a href="<?php echo htmlspecialchars($product['link']); ?>" class="product-card" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 50; ?>">
                                <img src="<?php echo htmlspecialchars($product['thumbnail']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <p><?php echo htmlspecialchars($product['name']); ?></p>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; width: 100%;">该分类下暂无产品，敬请期待。</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <script src="/assets/js/footer.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>