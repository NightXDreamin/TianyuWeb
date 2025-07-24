<?php
// /api_handlers/products_handler.php (V2 - 图片上传感知型)

$action = $_POST['action'] ?? '';
$response = ['status' => 'error', 'message' => '无效的产品操作。'];

if ($action == 'save_products') {
    // --- 会话验证 ---
    $session_key = $_POST['key'] ?? '';
    $lock_file = __DIR__ . '/session_lock.json';
    if (empty($session_key) || !file_exists($lock_file) || json_decode(file_get_contents($lock_file), true)['session_key'] !== $session_key) {
        $response['message'] = '未授权的访问或会话已过期。';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // --- 数据处理 ---
    $newProductsJson = $_POST['data'] ?? '';
    $newProductsData = json_decode($newProductsJson, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($newProductsData)) {
        // --- [核心升级] 我们不再手动拼接字符串，而是直接将PHP数组编码为JSON ---
        // 这更安全，也完美地保留了imageUrls作为数组的结构
        $newFileContent = json_encode($newProductsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if (json_last_error() === JSON_ERROR_NONE && is_array($newProductsData)) {
        $newFileContent = json_encode($newProductsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // 步骤1：保存数据文件
        if (file_put_contents(__DIR__ . '/../data/products_data.json', $newFileContent) !== false) {
            
            // --- [新功能] 步骤2：为每个产品生成或更新静态HTML页面 ---
            $template_path = __DIR__ . '/../data/product_template.html';
            $template_content = file_get_contents($template_path);

            foreach ($newProductsData as $product) {
                // 为每个产品确定一个唯一的、URL友好的文件名
                $safe_filename = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $product['name'])) . '.html';
                
                // 确定该产品所属分类的目录
                // (这是一个简化的映射，您可以根据需要扩展)
                $category_map = [
                    '污水·废水处理' => 'water',
                    '纯水·给水系统' => 'water_supply',
                    '废气·恶臭治理' => 'gas',
                    '除尘·烟气治理' => 'dust',
                    '消毒·杀菌设备' => 'disinfection',
                    '填料·配件耗材' => 'parts'
                ];
                $category_folder = $category_map[$product['category']] ?? 'others';
                
                $output_dir = __DIR__ . '/../../product_lib/' . $category_folder . '/';
                if (!is_dir($output_dir)) {
                    mkdir($output_dir, 0775, true);
                }
                $output_path = $output_dir . $safe_filename;

                // --- 开始替换模板中的占位符 ---
                $page_content = $template_content;
                $page_content = str_replace('__PRODUCT_NAME__', htmlspecialchars($product['name']), $page_content);
                $page_content = str_replace('__PRODUCT_CATEGORY__', htmlspecialchars($product['category']), $page_content);
                $page_content = str_replace('__PRODUCT_DESCRIPTION__', nl2br(htmlspecialchars($product['description'])), $page_content);

                // 处理图片
                $main_image_url = !empty($product['imageUrls']) ? htmlspecialchars($product['imageUrls'][0]) : '/assets/img/placeholder.jpg';
                $page_content = str_replace('__MAIN_IMAGE_URL__', $main_image_url, $page_content);
                
                $thumbnails_html = '';
                if (!empty($product['imageUrls'])) {
                    foreach ($product['imageUrls'] as $url) {
                        $thumbnails_html .= '<li><img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($product['name']) . ' 缩略图"></li>';
                    }
                }
                $page_content = str_replace('', $thumbnails_html, $page_content);

                // 写入最终的HTML文件
                file_put_contents($output_path, $page_content);
            }
            
            $response = ['status' => 'success', 'message' => '产品信息已成功更新，并已生成/更新静态页面！'];

        } else {
            $response['message'] = '产品数据文件写入失败。';
        }
    } else {
        $response['message'] = '接收到的产品数据格式无效。';
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>