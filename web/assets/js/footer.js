document.write(`
<footer id="site-footer" class="footer">
  <div class="footer__inner container">

    <div class="footer__col footer__col--brand">
      <img src="/assets/img/Logo带文字窄.png" alt="天昱环保 Logo" class="footer__logo" />
      <h4 class="footer__company">河南天昱环保工程有限公司</h4>
      <p class="footer__tagline">美好明天，你我共建！</p>

      <ul class="footer__contact">
        <li><span class="label">电话：</span><a href="tel:13007606387">13007606387</a></li>
        <li><span class="label">邮箱：</span><a href="mailto:henantianyuhuanbao@163.com">henantianyuhuanbao@163.com</a></li>
        <li><span class="label">地址：</span>河南省郑州市中原区桐柏路与陇海路交叉口凯旋门大厦1406</li>
      </ul>
    </div>

    <div class="footer__col">
      <h5 class="footer__title">关于天昱</h5>
      <ul class="footer__nav">
        <li><a href="/about.html#team">团队</a></li>
        <li><a href="/cases.html">案例</a></li>
        <li><a href="/about.html#career">职业</a></li>
      </ul>
    </div>

    <div class="footer__col">
      <h5 class="footer__title">服务类项目</h5>
      <ul class="footer__nav">
        <li><a href="/services.html#consult">咨询</a></li>
        <li><a href="/services.html#test">检测</a></li>
        <li><a href="/services.html#train">培训</a></li>
      </ul>
    </div>

    <div class="footer__col">
      <h5 class="footer__title">工程设备</h5>
      <ul class="footer__nav">
        <li><a href="/products.html#water">水处理</a></li>
        <li><a href="/products.html#gas">气处理</a></li>
        <li><a href="/products.html#noise">噪音控制</a></li>
      </ul>
    </div>

  </div><div class="footer__bottom">
    <p>
      &copy;2025 河南天昱环保工程有限公司 |
      <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer">豫ICP备2023015121号-1</a>
    </p>
  </div>
</footer>
`);

document.write(`
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "河南天昱环保工程有限公司",
  "url": "https://www.tianyuhuanbao.com/",
  "logo": "https://www.tianyuhuanbao.com/assets/img/Logo带文字窄.png",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+86-130-0760-6387",
    "contactType": "customer service"
  },
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "桐柏路与陇海路交叉口凯旋门大厦1406",
    "addressLocality": "郑州市",
    "addressRegion": "河南省",
    "postalCode": "450000",

    "addressCountry": "CN"
  }
}
</script>
`);