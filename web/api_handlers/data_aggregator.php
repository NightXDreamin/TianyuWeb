<?php
// /api_handlers/data_aggregator.php (V3 - 读取JSON)

$all_data = [
    'jobs' => [],
    'products' => [],
    'cases' => [],
    'stats' => []
];

// 1. 从 jobs_data.json 文件获取招聘数据
$jobs_file_path = __DIR__ . '/../data/jobs_data.json';
if (file_exists($jobs_file_path)) {
    $json_content = file_get_contents($jobs_file_path);
    $jobs_data = json_decode($json_content, true);
    // 确认JSON解码成功且是一个数组
    if (json_last_error() === JSON_ERROR_NONE && is_array($jobs_data)) {
        $all_data['jobs'] = $jobs_data;
    }
}

// ... 未来在这里添加读取 products_data.json 和 cases_data.json 的逻辑 ...
$products_file_path = __DIR__ . '/../data/products_data.json';
if (file_exists($products_file_path)) {
    $json_content = file_get_contents($products_file_path);
    $products_data = json_decode($json_content, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($products_data)) {
        $all_data['products'] = $products_data;
    }
}



// 计算统计数据
$all_data['stats']['total_jobs_count'] = count($all_data['jobs']);
$all_data['stats']['total_products_count'] = count($all_data['products']);
$all_data['stats']['total_cases_count'] = count($all_data['cases']);
// ... 其他统计逻辑 ...
$all_data['stats']['server_time'] = date('Y-m-d H:i:s');


// 构建最终的成功响应
$response = ['status' => 'success', 'data' => $all_data];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>