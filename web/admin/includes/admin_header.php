<?php
    // 这个脚本会获取当前页面的文件名，用于导航高亮
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="admin-header">
    <div class="admin-header__logo">
        <a href="/admin/dashboard.php"><h2>天昱CMS</h2></a>
    </div>
    <nav class="admin-nav">
        <a href="/admin/dashboard.php" 
           class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
           主面板
        </a>
        <a href="/admin/manage_jobs.php" 
           class="<?php echo ($current_page == 'manage_jobs.php' || $current_page == 'edit_job.php') ? 'active' : ''; ?>">
           招聘管理
        </a>
        <a href="/admin/manage_products.php" 
           class="<?php echo ($current_page == 'manage_products.php' || $current_page == 'edit_product.php') ? 'active' : ''; ?>">
           产品管理
        </a>
        <a href="/admin/manage_cases.php" 
           class="<?php echo ($current_page == 'manage_cases.php' || $current_page == 'edit_case.php') ? 'active' : ''; ?>">
           案例管理
        </a>
    </nav>
    <div class="admin-header__user">
        <span>欢迎, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        <a href="/admin/logout.php" class="logout-link">退出</a>
    </div>
</header>