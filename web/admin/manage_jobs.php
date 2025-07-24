<?php
    session_start();
    // 检查是否已登录
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>招聘信息管理 - CMS</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/pages/_admin.css"> </head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; // 引入后台通用页眉 ?>

    <main class="admin-main">
        <div class="container">
            <h1>招聘信息管理</h1>
            <div class="action-bar">
                <a href="edit_job.php" class="btn">添加新职位</a>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>职位名称</th>
                        <th>招聘名额</th>
                        <th>薪资范围</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody id="jobs-tbody">
                    </tbody>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('jobs-tbody');

            // 1. 加载职位列表
            function loadJobs() {
                fetch('/admin/api/jobs_handler.php?action=get')
                    .then(response => response.json())
                    .then(data => {
                        tbody.innerHTML = ''; // 清空旧数据
                        if (data.status === 'success' && data.jobs && data.jobs.length > 0) {
                            data.jobs.forEach((job, index) => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${escapeHtml(job.title)}</td>
                                    <td>${escapeHtml(job.quota)}</td>
                                    <td>${escapeHtml(job.salaryStart)} - ${escapeHtml(job.salaryEnd)}</td>
                                    <td class="actions">
                                        <a href="edit_job.php?index=${index}" class="btn">编辑</a>
                                        <button class="btn btn-danger" data-index="${index}">删除</button>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                        } else {
                            tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">暂无招聘职位。</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error("加载职位失败:", error);
                        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">加载职位列表失败，请检查网络或联系管理员。</td></tr>';
                    });
            }

            // 2. 处理删除操作
            tbody.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-danger')) {
                    const jobIndex = event.target.dataset.index;
                    const jobTitle = event.target.closest('tr').cells[0].textContent;

                    if (confirm(`确定要删除职位 “${jobTitle}” 吗？`)) {
                        fetch(`/admin/api/jobs_handler.php?action=delete&index=${jobIndex}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                alert('删除成功！');
                                loadJobs(); // 重新加载列表
                            } else {
                                alert('删除失败：' + data.message);
                            }
                        });
                    }
                }
            });

            // 防止XSS攻击的辅助函数
            function escapeHtml(unsafe) {
                if (typeof unsafe !== 'string') return '';
                return unsafe
                     .replace(/&/g, "&amp;")
                     .replace(/</g, "&lt;")
                     .replace(/>/g, "&gt;")
                     .replace(/"/g, "&quot;")
                     .replace(/'/g, "&#039;");
            }

            // 页面加载时执行
            loadJobs();
        });
    </script>
</body>
</html>