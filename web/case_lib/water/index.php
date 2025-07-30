<?php
    // --- 诊断探头 ---
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // --- 页面配置区 ---
    $category_id = basename(__DIR__); // 自动获取当前文件夹名
    // 分类信息地图，你可以在这里统一管理所有分类的标题、Banner图等
    $categories_map = [
        "water" => [
            "title" => "水处理案例",
            "subtitle" => "我们在工业废水、医疗污水与市政污水治理方面拥有多年EPC经验。",
            "image" => "/assets/img/cases/污水站例子.webp"
        ],
        "gas" => [
            "title" => "气体处理案例",
            "subtitle" => "专注VOCs、粉尘及脱硫脱硝等复杂废气治理，技术成熟。",
            "image" => "/assets/img/cases/除尘例子.webp"
        ],
        "noise" => [
            "title" => "噪音治理案例",
            "subtitle" => "为工厂车间及配套设施提供一体化降噪解决方案。",
            "image" => "/assets/img/cases/噪声例子.webp"
        ]
    ];
    $current_category = $categories_map[$category_id] ?? ['title' => '工程案例', 'subtitle' => '', 'image' => '/assets/img/cases/远景脱硫塔.webp'];
    $seo_title = $current_category['title'] . " | 工程案例 - 河南天昱环保";
    $seo_description = "精选河南天昱环保在" . $current_category['title'] . "领域的成功项目，展示我们从设计到交付的综合实力。";

    // --- 智能数据提取 ---
    $cases = [];
    $html_files = glob('*.html'); 

    foreach ($html_files as $file) {
        if (strtolower($file) === 'index.html') continue;

        $file_content = file_get_contents($file);
        $case_title = '未知案例 - ' . $file;
        $case_image = '/assets/img/placeholder.webp'; // 默认封面图

        // 1. 智能提取案例标题 (从<h1>标签)
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $file_content, $matches)) {
            $case_title = trim(strip_tags($matches[1]));
        }
        
        // 2. 智能提取封面图 (寻找页面里的第一张图片)
        if (preg_match('/<img[^>]+src\s*=\s*["\'](.*?)["\']/i', $file_content, $img_matches)) {
            $case_image = $img_matches[1];
        }

        $cases[] = ['title' => $case_title, 'image' => $case_image, 'link' => $file];
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

    <main class="case-category-page">
        <section class="hero-about" style="background-image: url('<?php echo htmlspecialchars($current_category['image']); ?>');">
            <div class="container" data-aos="fade-in">
                <h1 class="hero-about__title"><?php echo htmlspecialchars($current_category['title']); ?></h1>
                <p class="hero-about__subtitle"><?php echo htmlspecialchars($current_category['subtitle']); ?></p>
            </div>
        </section>

        <section class="featured-cases-section" style="padding: 80px 0;">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h2>案例列表</h2>
                </div>
                
                <div class="featured-case-grid">
                    <?php if (!empty($cases)): ?>
                        <?php foreach ($cases as $index => $case): ?>
                            <a href="<?php echo htmlspecialchars($case['link']); ?>" class="featured-case-card" data-aos="fade-up">
                                <img src="<?php echo htmlspecialchars($case['image']); ?>" alt="<?php echo htmlspecialchars($case['title']); ?>" loading="lazy">
                                <div class="featured-case-card__overlay">
                                    <h3 class="featured-case-card__title"><?php echo htmlspecialchars($case['title']); ?></h3>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; width: 100%;">该分类下暂无案例，敬请期待。</p>
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