<?php
// /admin/api/products_handler.php (V3 - Full CRUD & Category Management)

header('Content-Type: application/json');
session_start();

$response = ['status' => 'error', 'message' => '无效的操作。'];
$productLibDir = __DIR__ . '/../../product_lib';
$templatePath = __DIR__ . '/../../data/product_template.html'; // 模板文件在项目根目录

// 安全检查
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response['message'] = '未授权的访问。';
    echo json_encode($response);
    exit;
}

// --- 数据读写函数 ---
function get_products_from_category($category) {
    global $productLibDir;
    $json_file = $productLibDir . '/' . basename($category) . '/products.json';
    if (!file_exists($json_file)) return [];
    $json = file_get_contents($json_file);
    return json_decode($json, true) ?: [];
}

function save_products_to_category($category, $data) {
    global $productLibDir;
    $json_file = $productLibDir . '/' . basename($category) . '/products.json';
    $data = array_values($data);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($json_file, $json);
}

// --- HTML文件生成函数 ---
function generate_html_file($template_content, $product_data, $output_path) {
    $html = $template_content;
    $html = str_replace('{{SEO_TITLE}}', htmlspecialchars($product_data['seo']['title'] ?? ''), $html);
    $html = str_replace('{{SEO_DESCRIPTION}}', htmlspecialchars($product_data['seo']['description'] ?? ''), $html);
    $html = str_replace('{{PRODUCT_NAME}}', htmlspecialchars($product_data['name']), $html);
    
    $image_html = '';
    if (!empty($product_data['images'])) {
        foreach($product_data['images'] as $img) {
            $image_html .= '<img src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($product_data['name']) . '">';
        }
    }
    $html = str_replace('{{IMAGE_GALLERY}}', $image_html, $html);
    
    // 这是一个简化的Markdown->HTML转换，实际项目中建议用库
    $content_html = nl2br(htmlspecialchars($product_data['content']));
    $html = str_replace('{{PRODUCT_CONTENT}}', $content_html, $html);

    return file_put_contents($output_path, $html);
}


$action = $_REQUEST['action'] ?? '';

switch ($action) {
    // 👇 这个就是我们缺失的关键指令！
    case 'get_categories':
        $categories = [];
        $items = scandir($productLibDir);
        foreach ($items as $item) {
            if ($item[0] !== '.' && is_dir($productLibDir . '/' . $item)) {
                $categories[] = ['id' => $item, 'name' => $item];
            }
        }
        $response = ['status' => 'success', 'categories' => $categories];
        break;

    case 'get_products':
        if (isset($_GET['category'])) {
            $category = basename($_GET['category']);
            $products = get_products_from_category($category);
            $response = ['status' => 'success', 'products' => $products];
        }
        break;
    
    case 'get_product':
        if (isset($_GET['category'], $_GET['index'])) {
            $category = basename($_GET['category']);
            $index = intval($_GET['index']);
            $products = get_products_from_category($category);
            if (isset($products[$index])) {
                $response = ['status' => 'success', 'product' => $products[$index]];
            } else {
                $response['message'] = '找不到指定的产品。';
            }
        }
        break;

    case 'save_product':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = basename($_POST['category']);
            $index = intval($_POST['index']);
            $product_data = json_decode($_POST['productData'], true);
            
            if ($product_data && $category) {
                $products = get_products_from_category($category);
                $html_filename = $product_data['link'];
                
                if ($index == -1) { $products[] = $product_data; } 
                else { 
                    if(isset($products[$index])) {
                        if($products[$index]['link'] !== $html_filename && file_exists($productLibDir . '/' . $category . '/' . $products[$index]['link'])){
                            unlink($productLibDir . '/' . $category . '/' . $products[$index]['link']);
                        }
                        $products[$index] = $product_data;
                    }
                }
                
                if (save_products_to_category($category, $products)) {
                    $template_content = file_get_contents($templatePath);
                    $output_path = $productLibDir . '/' . $category . '/' . $html_filename;
                    generate_html_file($template_content, $product_data, $output_path);
                    $response = ['status' => 'success', 'message' => '产品已保存。'];
                } else {
                    $response['message'] = '无法写入数据文件。';
                }
            }
        }
        break;

    case 'delete_product':
        if (isset($_GET['category'], $_GET['index'])) {
            $category = basename($_GET['category']);
            $index = intval($_GET['index']);
            $products = get_products_from_category($category);

            if (isset($products[$index])) {
                $product_to_delete = $products[$index];
                array_splice($products, $index, 1);
                
                if (save_products_to_category($category, $products)) {
                    $html_path = $productLibDir . '/' . $category . '/' . $product_to_delete['link'];
                    if (file_exists($html_path)) {
                        unlink($html_path);
                    }
                    $response = ['status' => 'success', 'message' => '产品已删除。'];
                } else {
                     $response['message'] = '无法写入数据文件。';
                }
            }
        }
        break;
}

echo json_encode($response);
?>