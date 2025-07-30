<?php
// build_search_index.php (V2 - mbstring fallback)

$startTime = microtime(true);
$webDir = __DIR__ . '/web';
$outputFile = $webDir . '/data/search_index.json';

$scanDirectories = [
    'products' => $webDir . '/product_lib',
    'cases'    => $webDir . '/case_lib'
];

$finalIndex = [];
// 检查 mbstring 扩展是否加载
$mbstring_loaded = extension_loaded('mbstring');
if (!$mbstring_loaded) {
    echo "🟡 警告: PHP 'mbstring' 扩展未启用，摘要长度计算可能不完全准确。\n";
}

echo "🚀 开始构建全站搜索索引...\n";

function process_directory($dirPath, $type) {
    global $finalIndex, $mbstring_loaded;
    $count = 0;
    
    if (!is_dir($dirPath)) {
        echo "🟡 警告: 目录不存在，已跳过: $dirPath\n";
        return;
    }

    $categories = new DirectoryIterator($dirPath);
    foreach ($categories as $category) {
        if ($category->isDir() && !$category->isDot()) {
            $categoryName = $category->getFilename();
            $files = glob($category->getPathname() . '/*.html');

            foreach ($files as $file) {
                if (strtolower(basename($file)) === 'index.html') continue;

                $fileContent = file_get_contents($file);
                $title = '未知页面';
                $description = '暂无简介';

                if (preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $fileContent, $matches)) {
                    $title = trim(strip_tags($matches[1]));
                } elseif (preg_match('/<title>(.*?)<\/title>/i', $fileContent, $matches)) {
                    $title = trim(preg_replace('/[—-–|].*$/', '', $matches[1]));
                }

                if (preg_match('/<meta name="description" content="(.*?)"/i', $fileContent, $matches)) {
                    $description = trim($matches[1]);
                } elseif (preg_match('/<p[^>]*>(.*?)<\/p>/i', $fileContent, $matches)) {
                    $description = trim(strip_tags($matches[1]));
                    // 使用智能的长度截取
                    if ($mbstring_loaded) {
                        if (mb_strlen($description) > 150) {
                            $description = mb_substr($description, 0, 150) . '...';
                        }
                    } else {
                        if (strlen($description) > 450) { // Fallback for non-mbstring
                            $description = substr($description, 0, 450) . '...';
                        }
                    }
                }
                
                $link = str_replace($GLOBALS['webDir'], '', $file);
                
                $finalIndex[] = [
                    'title' => $title,
                    'description' => $description,
                    'link' => str_replace('\\', '/', $link),
                    'type' => $type,
                    'category' => $categoryName
                ];
                $count++;
            }
        }
    }
    echo "✅ 在 {$type} 分类下找到并索引了 {$count} 个页面。\n";
}

foreach ($scanDirectories as $type => $path) {
    process_directory($path, $type);
}

if (file_put_contents($outputFile, json_encode($finalIndex, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 2);
    echo "✨ 索引构建成功！共索引 " . count($finalIndex) . " 个条目。\n";
    echo "✅ 文件已保存至: {$outputFile}\n";
    echo "⏱️  耗时: {$duration} 秒。\n";
} else {
    echo "❌ 错误: 无法写入索引文件，请检查目录权限！\n";
}
?>