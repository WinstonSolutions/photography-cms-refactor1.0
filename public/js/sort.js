// 处理排序功能
document.addEventListener('DOMContentLoaded', function() {
    // 获取排序下拉框
    const sortSelect = document.getElementById('sort_by');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            // 获取当前选中的排序方式
            const sortBy = this.value;
            // 获取当前相册ID
            const albumId = this.getAttribute('data-album-id');
            
            // 构建请求URL
            const url = `${BASE_URL}public/index.php?action=sort&album_id=${albumId}&sort_by=${sortBy}`;
            
            // 发送AJAX请求
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // 获取图片容器
                        const galleryContainer = document.querySelector('.image-gallery');
                        // 清空当前内容
                        galleryContainer.innerHTML = '';
                        
                        // 重新渲染图片
                        data.images.forEach(img => {
                            // 构建完整的图片路径
                            const fullImagePath = `${BASE_URL}storage/${img.file_path}`;
                            
                            const imageHtml = `
                                <div class="image-item" onclick="openModal('${fullImagePath}')">
                                    <img 
                                        src="${fullImagePath}" 
                                        alt="${img.filename}"
                                        loading="lazy"
                                    />
                                    <p class="image-title">${img.filename}</p>
                                </div>
                            `;
                            galleryContainer.innerHTML += imageHtml;
                        });
                    } else {
                        console.error('Failed to sort images');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }
}); 