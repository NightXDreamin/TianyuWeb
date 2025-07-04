// assets/js/header.js (V3 with Full-width fix)

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
            <li><a href="/product_lib/water/index.html">污水·废水处理</a></li>
            <li><a href="/product_lib/water_supply/index.html">纯水·给水系统</a></li>
            <li><a href="/product_lib/organic_air/organic-air-treatment.html">废气·恶臭治理</a></li>
            <li><a href="/product_lib/dust/index.html">除尘·烟气治理</a></li>
            <li><a href="/product_lib/disinfection/index.html">消毒·杀菌设备</a></li>
            <li><a href="/product_lib/parts/index.html">填料·配件耗材</a></li>
          </ul>
        </li>

        <li class="nav__item nav__item--has-dropdown">
          <a href="/services.html" class="nav__link">服务项目</a>
          <ul class="dropdown-menu">
            <li><a href="/services.html#consult">咨询</a></li>
            <li><a href="/services.html#test">检测</a></li>
            <li><a href="/services.html#train">培训</a></li>
            <li><a href="/services.html#construction">施工</a></li>
            <li><a href="/services.html#manufacturing">制造</a></li>
            <li><a href="/services.html#installation">安装</a></li>
          </ul>
        </li>

        <li class="nav__item nav__item--has-dropdown">
          <a href="/cases.html" class="nav__link">工程案例</a>
          <ul class="dropdown-menu">
            <li><a href="/cases.html#water">水处理案例</a></li>
            <li><a href="/cases.html#gas">气体处理案例</a></li>
            <li><a href="/cases.html#noise">噪音控制案例</a></li>
          </ul>
        </li>

        <li class="nav__item nav__item--has-dropdown">
          <a href="/news.html" class="nav__link">新闻资讯</a>
          <ul class="dropdown-menu">
            <li><a href="/news.html#company">企业动态</a></li>
            <li><a href="/news.html#industry">行业动态</a></li>
          </ul>
        </li>

        <li class="nav__item nav__item--has-dropdown">
          <a href="/contact.html" class="nav__link">联系我们</a>
          <ul class="dropdown-menu">
            <li><a href="/contact.html#cooperation">咨询合作</a></li>
            <li><a href="/contact.html#career">工作机会</a></li>
          </ul>
        </li>
      </ul>
    </nav>

    <div class="header__cta">
      <a href="tel:13007606387" class="btn phone-btn">立刻咨询 · 13007606387</a>
    </div>

    <button class="hamburger-menu" aria-label="打开导航菜单">☰</button>
  </div>
</header>
`);