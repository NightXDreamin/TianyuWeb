<?php
// /admin/api/cases_handler.php

header('Content-Type: application/json');
session_start();

$response = ['status' => 'error', 'message' => '无效的操作。'];
$caseLibDir = __DIR__ . '/../../cases_lib';
$templatePath = __DIR__ . '/../../../case_template.html'; // 案例模板在项目根目录

// 安全检查
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response['message'] = '未授权的访问。';
    echo json_encode($response);
    exit;
}

// --- 数据读写函数 ---
function get_cases_from_category($category) {
    global $caseLibDir;
    $json_file = $caseLibDir . '/' . basename($category) . '/cases.json';
    if (!file_exists($json_file)) return [];
    return json_decode(file_get_contents($json_file), true) ?: [];
}

function save_cases_to_category($category, $data) {
    global $caseLibDir;
    $json_file = $caseLibDir . '/' . basename($category) . '/cases.json';
    $data = array_values($data);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($json_file, $json);
}

// --- HTML文件生成函数 (简化版) ---
function generate_case_html($template_content, $case_data, $output_path) {
    $html = $template_content;
    $html = str_replace('{{CASE_TITLE}}', htmlspecialchars($case_data['title']), $html);
    $html = str_replace('{{CASE_IMAGE}}', htmlspecialchars($case_data['image']), $html);
    $html = str_replace('{{CASE_CONTENT}}', nl2br(htmlspecialchars($case_data['content'])), $html);
    return file_put_contents($output_path, $html);
}

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'get_cases':
        if (isset($_GET['category'])) {
            $category = basename($_GET['category']);
            $cases = get_cases_from_category($category);
            $response = ['status' => 'success', 'cases' => $cases];
        }
        break;

    case 'save_case':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = basename($_POST['category']);
            $index = intval($_POST['index']);
            $case_data = json_decode($_POST['caseData'], true);
            
            if ($case_data && $category) {
                $cases = get_cases_from_category($category);
                $html_filename = $case_data['link'];
                
                if ($index == -1) { $cases[] = $case_data; } 
                else { 
                    if(isset($cases[$index])) {
                        $cases[$index] = $case_data;
                    }
                }
                
                if (save_cases_to_category($category, $cases)) {
                    $template_content = file_get_contents($templatePath);
                    $output_path = $caseLibDir . '/' . $category . '/' . $html_filename;
                    generate_case_html($template_content, $case_data, $output_path);
                    $response = ['status' => 'success', 'message' => '案例已保存。'];
                } else {
                    $response['message'] = '无法写入数据文件。';
                }
            }
        }
        break;

    case 'delete_case':
        if (isset($_GET['category'], $_GET['index'])) {
            $category = basename($_GET['category']);
            $index = intval($_GET['index']);
            $cases = get_cases_from_category($category);
            if (isset($cases[$index])) {
                $case_to_delete = $cases[$index];
                array_splice($cases, $index, 1);
                if (save_cases_to_category($category, $cases)) {
                    $html_path = $caseLibDir . '/' . $category . '/' . $case_to_delete['link'];
                    if (file_exists($html_path)) unlink($html_path);
                    $response = ['status' => 'success', 'message' => '案例已删除。'];
                }
            }
        }
        break;
}

echo json_encode($response);
?>