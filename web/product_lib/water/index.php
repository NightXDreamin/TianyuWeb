<?php
    // --- 诊断探头 ---
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // --- 页面配置区 ---
    $category_title = "污水·废水处理";
    $category_subtitle = "覆盖生活与工业污水全流程：从预处理到达标排放，一体化模块安装快、运维省。";
    $hero_image_url = "/assets/img/products/污水站池子.png";

    $seo_title = "污水·废水处理设备 | 产品中心 - 河南天昱环保";
    $seo_description = "查看天昱环保全系列污水与废水处理设备，包括机械格栅、刮泥机、气浮机、MBR膜生物反应器、一体化处理设备等，为各类水质提供可靠解决方案。";

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