// main.js

document.addEventListener('DOMContentLoaded', () => {

    // --- 功能一：汉堡菜单 ---
    const hamburger = document.querySelector('.hamburger-menu');
    const nav = document.querySelector('.header__nav'); 

    if (hamburger && nav) {
        hamburger.addEventListener('click', () => {
            nav.classList.toggle('is-active');
            hamburger.classList.toggle('active'); 
        });
    }

    const dropdownItems = document.querySelectorAll('.nav__item--has-dropdown');

    // --- 功能二：平滑滚动到锚点 ---
    const backToTopButton = document.querySelector('#back-to-top');

    if (backToTopButton) {
        window.addEventListener('scroll', () => {
            // 如果页面向下滚动超过 300px，就显示按钮
            if (window.scrollY > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        // 点击按钮时，平滑滚动到页面顶部
        backToTopButton.addEventListener('click', (e) => {
            e.preventDefault(); // 阻止a标签的默认跳转行为
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // 这就是平滑滚动的关键
            });
        });
    }

    // --- 首页 Hero 轮播图 (升级版) ---
    const slider = document.querySelector('.hero-slider');
    if (slider) { // 确保只在有轮播图的页面执行
        const slides = document.querySelectorAll('.slide');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const dotsContainer = document.querySelector('.slider-dots');

        let currentIndex = 0;
        const slideIntervalTime = 5000; // 轮播间隔时间 (5秒)
        let intervalId;

        // 根据幻灯片数量创建小圆点
        function createDots() {
            slides.forEach((_, index) => {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                dot.dataset.index = index;
                dotsContainer.appendChild(dot);
            });
        }

        // 更新小圆点的激活状态
        function updateDots() {
            const dots = document.querySelectorAll('.dot');
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentIndex].classList.add('active');
        }

        // 更新滑块位置
        function updateSliderPosition() {
            slider.style.transform = `translateX(-${currentIndex * (100 / slides.length)}%)`;
            updateDots();
        }

        // 切换到下一张
        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            updateSliderPosition();
        }

        // 切换到上一张
        function prevSlide() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            updateSliderPosition();
        }
        
        // 重置并启动自动播放
        function startSlider() {
            clearInterval(intervalId); // 先清除已有的定时器
            intervalId = setInterval(nextSlide, slideIntervalTime);
        }

        // 初始化函数
        function initSlider() {
            if (slides.length > 0) {
                createDots();
                updateSliderPosition();
                startSlider();

                // 绑定事件监听
                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    startSlider(); // 用户点击后重置自动播放计时
                });

                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    startSlider(); // 用户点击后重置自动播放计时
                });

                dotsContainer.addEventListener('click', (e) => {
                    if (e.target.classList.contains('dot')) {
                        currentIndex = parseInt(e.target.dataset.index);
                        updateSliderPosition();
                        startSlider(); // 用户点击后重置自动播放计时
                    }
                });
            }
        }

        initSlider(); // 启动轮播图
    }

    // --- 新增：初始化 AOS 动画 ---
    AOS.init({
        duration: 800, // 动画持续时间（毫秒）
        once: true,    // 动画是否只播放一次
    });

    // --- 页内产品搜索功能 ---
    const searchInput = document.getElementById('page-search-input');
    
    // 只有当页面上存在搜索框时，才执行这段逻辑
    if (searchInput) {
        const productGrid = document.querySelector('.product-grid');
        const allProducts = productGrid ? Array.from(productGrid.querySelectorAll('.product-card')) : [];

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();

            allProducts.forEach(card => {
                const productName = card.textContent.toLowerCase();
                if (productName.includes(searchTerm)) {
                    card.style.display = 'block'; // 或者 'flex' 等，取决于你的卡片布局
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
});