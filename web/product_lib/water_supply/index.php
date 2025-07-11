<?php
    // --- 诊断探头 ---
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // --- 页面配置区 ---
    $category_title = "纯水·给水系统";
    $category_subtitle = "RO、EDI 及软水装置按需组合，出水电导率 <10 µS/cm，适配锅炉补水与工艺用水。";
    $hero_image_url = "/assets/img/products/蓄水池.png";

    $seo_title = "纯水·给水系统 | 反渗透(RO)·软化水·过滤器 - 河南天昱环保";
    $seo_description = "天昱环保提供全套纯水及给水系统解决方案，包括反渗透(RO)脱盐装置、全自动软水器、精密过滤器及不锈钢水箱。满足锅炉补水、工业工艺用水等高标准需求。";

    // --- 数据读取区 (无需修改) ---
    $products_json_path = __DIR__ . '/products.json';
    $products = [];
    if (file_exists($products_json_path)) {
        $products = json_decode(file_get_contents($products_json_path), true);
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
                
                <div class="product-grid" id="product-grid-container">
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