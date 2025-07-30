<?php
    // --- 诊断探头 ---
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // --- 页面配置区 ---
    $category_title = "除尘·烟气治理";
    $category_subtitle = "袋式、旋风、湿式多技术可选；捕集效率 ≥99%，满足锅炉窑炉等高粉尘场景。";
    $hero_image_url = "/assets/img/products/除尘塔.webp";

    $seo_title = "工业除尘与烟气脱硫脱硝设备 | 河南天昱环保";
    $seo_description = "专业提供袋式、旋风、湿式等多种工业除尘器及烟气脱硫脱硝解决方案。我们的设备捕集效率高，运行稳定，适用于锅炉、窑炉等高粉尘及复杂烟气场景。";

    // --- 
    // 关键升级：实时扫描文件夹，并从HTML文件中智能提取信息
    // ---
    $products = [];
    // glob('*.html') 会找到当前目录下所有的.html文件 (不包括子目录)
    $html_files = glob('*.html'); 
    
    // 检查DOM扩展是否可用，决定使用哪个“引擎”
    $dom_ext_loaded = class_exists('DOMDocument');

    foreach ($html_files as $file) {
        // 防止把分类页自身也加进去
        if (strtolower($file) === 'index.html') {
            continue;
        }

        $file_content = file_get_contents($file);
        $product_name = '未知产品 - ' . $file; // 默认名称
        $thumbnail_url = '/assets/img/products/placeholder.webp'; // 默认缩略图

        // --- 关键修正：优先从 <h1> 标签中抓取产品名称 ---
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $file_content, $matches)) {
            // strip_tags是为了防止<h1>标签里有其他HTML标签，比如<span>
            $product_name = trim(strip_tags($matches[1]));
        }
        
        // 2. 终极提取缩略图 (寻找页面里的第一张图片)
        // 这个正则表达式会寻找第一个 src="..." 属性，无论它在哪个标签里
        if (preg_match('/<img[^>]+src\s*=\s*["\'](.*?)["\']/i', $file_content, $img_matches)) {
            $first_image_src = $img_matches[1];
            // 修正相对路径：如果图片路径不是以'/'或'http'开头，就假定它和HTML文件在同一个目录下
            if (!preg_match('/^(?:\/|https?:\/\/)/i', $first_image_src)) {
                $thumbnail_url = $first_image_src; // 保持相对路径，由浏览器解析
            } else {
                $thumbnail_url = $first_image_src;
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
            <div style="background-color: #fff3cd; color: #856404; padding: 15px; text-align: center; border: 1px solid #ffeeba; margin: 20px;">
                <b>开发者提示：</b> 服务器未安装PHP的 "DOM" 扩展，当前正在使用备用方案提取缩略图。为了达到最佳效果和性能，建议在服务器上安装 <b>php-dom</b> 或 <b>php-xml</b> 扩展。
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