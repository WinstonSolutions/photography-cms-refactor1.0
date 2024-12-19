

<div class="admin-dashboard">
    <h1>CMS Administration</h1>
    
    <div class="admin-section">
        <div class="stats-grid">
            <!-- 照片统计 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-image"></i>
                </div>
                <div class="stat-number"><?php echo number_format($imagesCount); ?></div>
                <div class="stat-label">Photos</div>
            </div>
            
            <!-- 相册统计 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="stat-number"><?php echo number_format($albumsCount); ?></div>
                <div class="stat-label">Albums</div>
            </div>
            
            <!-- 用户组统计 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo number_format($usersCount); ?></div>
                <div class="stat-label">Users</div>
            </div>
            
            <!-- 首张照片时间 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $firstPhotoTime; ?></div>
                <div class="stat-label">First photo added</div>
            </div>
        </div>
    </div>
</div>