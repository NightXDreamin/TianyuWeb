<?php
    // --- 页面配置区 ---
    // 你只需要修改这里的信息，就可以把这个文件复用到任何一个分类！
    $category_title = "废气·恶臭治理";
    $category_subtitle = "PP/FRP喷淋塔联合光氧吸附，多段工艺应对VOCs、异味及酸碱废气，排放轻松达标。";
    $hero_image_url = "/assets/img/products/废气管道.png";

    $seo_title = "废气·恶臭治理设备 | 产品中心 - 河南天昱环保";
    $seo_description = "天昱环保提供多种废气及恶臭治理解决方案，包括废气吸收塔、铅烟净化器、活性炭吸附装置等，技术成熟，应用广泛。";

    // --- 数据读取区 ---
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