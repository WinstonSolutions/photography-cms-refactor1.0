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
function openModal(imageSrc) {
    var modal = document.getElementById('modal');
    var modalImg = document.getElementById('modal-img');
    var captionText = document.getElementById('caption');

    modal.style.display = "block"; // Show the modal
    modalImg.src = imageSrc; // Set the image source
    captionText.innerHTML = imageSrc.split('/').pop(); // Set the caption to the image filename
}

/**
 * 关闭模态框
 */
function closeModal() {
    var modal = document.getElementById('modal');
    modal.style.display = "none"; // Hide the modal
} 