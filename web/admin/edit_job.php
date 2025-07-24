<?php
    session_start();
    // 检查是否已登录
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit;
    }

    // --- 数据准备 ---
    $jobs_data_path = __DIR__ . '/../data/jobs_data.json';
    $all_jobs = [];
    if (file_exists($jobs_data_path)) {
        $all_jobs = json_decode(file_get_contents($jobs_data_path), true);
    }

    $page_mode = 'add'; // 默认是“添加”模式
    $job_index = -1;
    $current_job = [ // 空的职位模板
        'title' => '',
        'quota' => '',
        'salaryStart' => '',
        'salaryEnd' => '',
        'requirements' => ''
    ];

    // 检查URL中是否有'index'参数，如果有，则切换到“编辑”模式
    if (isset($_GET['index']) && is_numeric($_GET['index'])) {
        $job_index = intval($_GET['index']);
        if (isset($all_jobs[$job_index])) {
            $page_mode = 'edit';
            $current_job = $all_jobs[$job_index];
        } else {
            // 如果index无效，则重置为-1
            $job_index = -1;
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_mode === 'edit' ? '编辑职位' : '添加新职位'; ?> - CMS</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/pages/_admin.css">
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>

    <main class="admin-main">
        <div class="container">
            <h1><?php echo $page_mode === 'edit' ? '编辑职位' : '添加新职位'; ?></h1>

            <form id="job-form" class="admin-form">
                <input type="hidden" id="job-index" value="<?php echo $job_index; ?>">

                <div class="form__group">
                    <label for="title" class="form__label">职位名称</label>
                    <input type="text" id="title" class="form__input" value="<?php echo htmlspecialchars($current_job['title']); ?>" required>
                </div>

                <div class="form__group">
                    <label for="quota" class="form__label">招聘名额</label>
                    <input type="text" id="quota" class="form__input" value="<?php echo htmlspecialchars($current_job['quota']); ?>" required>
                </div>

                <div class="form__group form__group--half">
                    <div>
                        <label for="salaryStart" class="form__label">起始薪资</label>
                        <input type="number" id="salaryStart" class="form__input" value="<?php echo htmlspecialchars($current_job['salaryStart']); ?>" required>
                    </div>
                    <div>
                        <label for="salaryEnd" class="form__label">结束薪资</label>
                        <input type="number" id="salaryEnd" class="form__input" value="<?php echo htmlspecialchars($current_job['salaryEnd']); ?>" required>
                    </div>
                </div>

                <div class="form__group">
                    <label for="requirements" class="form__label">招聘条件 (每行一条)</label>
                    <textarea id="requirements" class="form__textarea" rows="6" required><?php echo htmlspecialchars($current_job['requirements']); ?></textarea>
                </div>

                <div class="form__actions">
                    <button type="submit" class="btn"><?php echo $page_mode === 'edit' ? '保存更改' : '添加职位'; ?></button>
                    <a href="manage_jobs.php" class="btn btn-secondary">取消</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('job-form').addEventListener('submit', function(event) {
            event.preventDefault();

            // 1. 收集表单数据
            const jobData = {
                title: document.getElementById('title').value,
                quota: document.getElementById('quota').value,
                salaryStart: document.getElementById('salaryStart').value,
                salaryEnd: document.getElementById('salaryEnd').value,
                requirements: document.getElementById('requirements').value
            };
            const jobIndex = document.getElementById('job-index').value;

            // 2. 准备要发送给API的数据
            // 这个逻辑完美匹配你现有的 jobs_handler.php 的 'update' 功能
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('index', jobIndex); // 告诉API要操作的索引
            formData.append('jobData', JSON.stringify(jobData)); // 将新数据作为JSON字符串发送

            // 3. 发送请求
            fetch('/admin/api/jobs_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('操作成功！');
                    window.location.href = 'manage_jobs.php'; // 操作成功后返回列表页
                } else {
                    alert('操作失败：' + data.message);
                }
            })
            .catch(error => {
                console.error('请求失败:', error);
                alert('发生网络错误，请稍后重试。');
            });
        });
    </script>
</body>
</html>