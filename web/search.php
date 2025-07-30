<?php
    $search_query = $_GET['query'] ?? '';
    $results = [];
    $total_found = 0;

    if (!empty($search_query)) {
        $index_file = __DIR__ . '/data/search_index.json';
        if (file_exists($index_file)) {
            $full_index = json_decode(file_get_contents($index_file), true);
            
            // 强大的筛选逻辑
            $results = array_filter($full_index, function($item) use ($search_query) {
                // 不区分大小写地在标题和描述中搜索
                return stripos($item['title'], $search_query) !== false || 
                       stripos($item['description'], $search_query) !== false;
            });
            $total_found = count($results);
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>搜索结果 for "<?php echo htmlspecialchars($search_query); ?>"</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="search-results-page">
    <script src="/assets/js/header.js"></script>
    <main>
        <header class="search-header">
            <div class="container">
                <h1>搜索结果</h1>
                <p>为您找到 <?php echo $total_found; ?> 条关于 “<strong class="query-highlight"><?php echo htmlspecialchars($search_query); ?></strong>” 的内容</p>
            </div>
        </header>

        <section class="search-results-list">
            <div class="container">
                <?php if ($total_found > 0): ?>
                    <?php foreach ($results as $item): ?>
                        <article class="search-result-item">
                            <h2 class="search-result-item__title">
                                <a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo htmlspecialchars($item['title']); ?></a>
                            </h2>
                            <div class="search-result-item__meta">
                                <span class="type"><?php echo $item['type'] === 'products' ? '产品' : '案例'; ?></span>
                                <span> - 分类: <?php echo htmlspecialchars($item['category']); ?></span>
                            </div>
                            <p class="search-result-item__description">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>抱歉，没有找到与 “<?php echo htmlspecialchars($search_query); ?>” 相关的内容。请尝试更换关键词。</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script src="/assets/js/footer.js"></script>
</body>
</html>