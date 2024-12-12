// 当文档加载完成时执行
document.addEventListener('DOMContentLoaded', function() {
    // 获取所有图片元素
    const images = document.querySelectorAll('.image-item img');
    
    // 为每个图片添加加载事件监听器
    images.forEach(img => {
        // 设置懒加载
        img.loading = 'lazy';
        
        // 添加加载事件监听器
        img.addEventListener('load', function() {
            // 图片加载完成后的处理
            img.style.display = 'block';
            const title = img.nextElementSibling;
            if (title && title.classList.contains('image-title')) {
                title.style.display = 'block';
            }
        });
    });
});

/**
 * 打开模态框显示图片
 * @param {string} src - 图片源路径
 */
function openModal(src) {
    document.getElementById("modal").style.display = "block";
    document.getElementById("modal-img").src = src;
}

/**
 * 关闭模态框
 */
function closeModal() {
    document.getElementById("modal").style.display = "none";
} 