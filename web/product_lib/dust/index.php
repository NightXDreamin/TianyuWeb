<?php
    // --- 诊断探头 ---
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // --- 页面配置区 ---
    $category_title = "除尘·烟气治理";
    $category_subtitle = "袋式、旋风、湿式多技术可选；捕集效率 ≥99%，满足锅炉窑炉等高粉尘场景。";
    $hero_image_url = "/assets/img/products/除尘塔.png";

    $seo_title = "工业除尘与烟气脱硫脱硝设备 | 河南天昱环保";
    $seo_description = "专业提供袋式、旋风、湿式等多种工业除尘器及烟气脱硫脱硝解决方案。我们的设备捕集效率高，运行稳定，适用于锅炉、窑炉等高粉尘及复杂烟气场景。";

    // --- 
    // 关键升级：实时扫描文件夹，并从HTML文件中智能提取信息
    // ---
    $products = [];
    $html_files = glob('*.html'); 

    foreach ($html_files as $file) {
        $file_content = file_get_contents($file);
        $product_name = '未知产品';
        $thumbnail_url = '/assets/img/products/placeholder.png'; // 默认缩略图

        // 1. 智能提取产品名称 (从<title>标签)
        if (preg_match('/<title>(.*?)<\/title>/i', $file_content, $matches)) {
            $product_name = trim(str_replace(['| 河南天昱环保', '- 废气治理', '- 污水处理'], '', $matches[1]));
        }
        
        // 2. 智能提取缩略图 (从详情页的第一张产品图片)
        // 使用DOMDocument解析HTML，这比正则表达式更可靠
        $dom = new DOMDocument();
        // @符号抑制因HTML不规范而产生的警告
        @$dom->loadHTML($file_content);
        $xpath = new DOMXPath($dom);
        // 寻找 class="device-images" 区块里的第一张 <img>
        $image_nodes = $xpath->query('//section[contains(@class, "device-images")]//img');
        
        if ($image_nodes->length > 0) {
            $first_image_src = $image_nodes[0]->getAttribute('src');
            // 确保URL是正确的相对或绝对路径
            if (!empty($first_image_src)) {
                $thumbnail_url = $first_image_src;
            }
        }

        $products[] = [
            'name' => $product_name,
            'thumbnail' => $thumbnail_url,
            'link' => $file
        ];
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    </head>
<body>
    </body>
</html>