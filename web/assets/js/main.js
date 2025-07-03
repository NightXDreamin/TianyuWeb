// main.js

document.addEventListener('DOMContentLoaded', () => {

    // --- 功能一：汉堡菜单 ---
    const hamburger = document.querySelector('.hamburger-menu');
    const nav = document.querySelector('.main-nav');

    if (hamburger && nav) {
        hamburger.addEventListener('click', () => {
            // 点击汉堡菜单时，给导航菜单添加/移除 'is-active' 类
            nav.classList.toggle('is-active');
        });
    }
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

    const slider = document.querySelector('.hero-slider');
    const slides = document.querySelectorAll('.slide');
    let currentIndex = 0;
    const slideIntervalTime = 5000; // 轮播间隔时间 (5秒)
    let intervalId;

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSliderPosition();
    }

    function updateSliderPosition() {
        slider.style.transform = `translateX(-${currentIndex * (100 / slides.length)}%)`;
    }

    function startSlider() {
        intervalId = setInterval(nextSlide, slideIntervalTime);
    }

    function stopSlider() {
        clearInterval(intervalId);
    }

    if (slider && slides.length > 0) {
        startSlider();

        // (可选) 如果需要鼠标悬停停止轮播，可以添加以下代码
        /*
        slider.addEventListener('mouseenter', stopSlider);
        slider.addEventListener('mouseleave', startSlider);
        */
    }

    // --- 新增：初始化 AOS 动画 ---
    AOS.init({
        duration: 800, // 动画持续时间（毫秒）
        once: true,    // 动画是否只播放一次
    });
});