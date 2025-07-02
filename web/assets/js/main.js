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

});