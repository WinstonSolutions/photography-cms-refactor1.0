// 动画序列控制
document.addEventListener('DOMContentLoaded', function() {
    const mainTitle = document.getElementById('mainTitle');
    const bgImage = document.getElementById('bgImage');
    const cmsEnter = document.getElementById('cmsEnter');

    // 显示主标题
    setTimeout(() => {
        mainTitle.style.opacity = '1';
    }, 500);

    // 淡出主标题
    setTimeout(() => {
        mainTitle.style.opacity = '0';
    }, 3000);

    // 显示背景图片
    setTimeout(() => {
        bgImage.style.opacity = '1';
    }, 4000);

    // 显示CMS入口链接
    setTimeout(() => {
        cmsEnter.style.opacity = '1';
    }, 5000);
}); 