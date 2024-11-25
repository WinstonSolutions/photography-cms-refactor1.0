<?php
// 获取统计数据
$stats = [
    'photos' => 0,      // 从数据库获取照片总数
    'albums' => 0,      // 从数据库获取相册总数
    'keywords' => 0,    // 从数据库获取关键词总数
    'groups' => 0,      // 从数据库获取用户组总数
    'pages_seen' => 0,  // 从数据库获取页面访问量
    'plugins' => 8,     // 插件数量
    'storage' => '0GB', // 存储使用量
    'first_photo' => '0 months' // 第一张照片上传时间
];
?>

<div class="admin-dashboard">
    <h1>CMS Administration</h1>
    
    <div class="admin-section">
        <h2>Administration Home</h2>
        
        <div class="stats-grid">
            <!-- 照片统计 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-image"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['photos']); ?></div>
                <div class="stat-label">Photos</div>
            </div>
            
            <!-- 相册统计 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['albums']); ?></div>
                <div class="stat-label">Albums</div>
            </div>
            
            <!-- 关键词统计 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['keywords']); ?></div>
                <div class="stat-label">Keywords</div>
            </div>
            
            <!-- 用户组统计 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['groups']); ?></div>
                <div class="stat-label">Groups</div>
            </div>
            
            <!-- 页面访问量 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['pages_seen']); ?>k</div>
                <div class="stat-label">Pages seen</div>
            </div>
            
            <!-- 插件数量 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-puzzle-piece"></i>
                </div>
                <div class="stat-number"><?php echo $stats['plugins']; ?></div>
                <div class="stat-label">Plugins</div>
            </div>
            
            <!-- 存储使用量 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stat-number"><?php echo $stats['storage']; ?></div>
                <div class="stat-label">Storage used</div>
            </div>
            
            <!-- 首张照片时间 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $stats['first_photo']; ?></div>
                <div class="stat-label">First photo added</div>
            </div>
        </div>
    </div>
</div> 