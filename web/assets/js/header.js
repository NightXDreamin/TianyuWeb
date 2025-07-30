// assets/js/header.js (V5 - Click-to-expand Search)

document.write(`
<header id="site-header" class="header">
  <div class="header__container">
    <div class="header__logo logo">
      <a href="/index.html">
        <img src="/assets/img/Logo带文字窄.png" alt="天昱环保 LOGO">
      </a>
    </div>

    <nav class="header__nav main-nav" aria-label="主导航">
        <ul class="nav__list">
            <li class="nav__item"><a href="/index.html" class="nav__link">首页</a></li>
            <li class="nav__item"><a href="/about.html" class="nav__link">关于天昱</a></li>
            <li class="nav__item nav__item--has-dropdown">
                <a href="/products.html" class="nav__link">产品中心</a>
                <ul class="dropdown-menu">
                    <li><a href="/product_lib/water/index.php">污水·废水处理</a></li>
                    <li><a href="/product_lib/water_supply/index.php">纯水·给水系统</a></li>
                    <li><a href="/product_lib/gas/index.php">废气·恶臭治理</a></li>
                    <li><a href="/product_lib/dust/index.php">除尘·烟气治理</a></li>
                    <li><a href="/product_lib/disinfection/index.php">消毒·杀菌设备</a></li>
                    <li><a href="/product_lib/parts/index.php">填料·配件耗材</a></li>
                </ul>
            </li>
            <li class="nav__item"><a href="/services.html" class="nav__link">服务项目</a></li>
            <li class="nav__item nav__item--has-dropdown">
                <a href="/cases.html" class="nav__link">工程案例</a>
                <ul class="dropdown-menu">
                    <li><a href="/cases_lib/water/index.php">水处理案例</a></li>
                    <li><a href="/cases_lib/gas/index.php">气体处理案例</a></li>
                    <li><a href="/cases_lib/noise/index.php">噪音控制案例</a></li>
                </ul>
            </li>
            <li class="nav__item nav__item--has-dropdown">
                <a href="/contact.html" class="nav__link">联系我们</a>
                <ul class="dropdown-menu">
                    <li><a href="/contact.html">咨询合作</a></li>
                    <li><a href="/career.php">工作机会</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div class="header__search">
        <button id="search-toggle-btn" class="search-toggle-btn" aria-label="展开搜索">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        </button>
        <form action="/search.php" method="GET" class="search-form is-collapsed">
            <input type="search" name="query" class="search-form__input" placeholder="搜索全站..." required>
            <button type="submit" class="search-form__button" aria-label="搜索">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
            </button>
        </form>
    </div>

    <div class="header__cta">
      <a href="tel:13007606387" class="btn phone-btn">立刻咨询 · 13007606387</a>
    </div>

    <button class="hamburger-menu" aria-label="打开导航菜单">☰</button>
  </div>
</header>
`);