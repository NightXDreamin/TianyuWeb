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

});