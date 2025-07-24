<?php
// /admin/api/jobs_handler.php (V2 - 支持单条记录的增/改/删)

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => '无效的操作。'];
$dataFile = __DIR__ . '/../../data/jobs_data.json';

// --- 安全检查：确保用户已登录 ---
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response['message'] = '未授权的访问。';
    echo json_encode($response);
    exit;
}

// --- 数据读取 ---
function getJobs($path) {
    if (!file_exists($path)) return [];
    $json = file_get_contents($path);
    return json_decode($json, true);
}

// --- 数据写入 ---
function saveJobs($path, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($path, $json);
}


// --- 根据 action 参数执行不同操作 ---
$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'get':
        $jobs = getJobs($dataFile);
        $response = ['status' => 'success', 'jobs' => $jobs];
        break;

    case 'delete':
        if (isset($_GET['index'])) {
            $index_to_delete = intval($_GET['index']);
            $jobs = getJobs($dataFile);
            if (isset($jobs[$index_to_delete])) {
                array_splice($jobs, $index_to_delete, 1);
                if (saveJobs($dataFile, $jobs)) {
                    $response = ['status' => 'success', 'message' => '职位已删除。'];
                } else {
                    $response['message'] = '无法写入数据文件。';
                }
            } else {
                $response['message'] = '无效的索引。';
            }
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $index_to_update = intval($_POST['index']);
            $new_job_data = json_decode($_POST['jobData'], true);
            
            if ($new_job_data) {
                $jobs = getJobs($dataFile);
                if ($index_to_update == -1) {
                    // 新增职位
                    $jobs[] = $new_job_data;
                } elseif (isset($jobs[$index_to_update])) {
                    // 更新现有职位
                    $jobs[$index_to_update] = $new_job_data;
                }

                if (saveJobs($dataFile, $jobs)) {
                    $response = ['status' => 'success', 'message' => '数据已保存。'];
                } else {
                    $response['message'] = '无法写入数据文件。';
                }
            } else {
                $response['message'] = '提交的数据格式无效。';
            }
        }
        break;
}

echo json_encode($response);
?>