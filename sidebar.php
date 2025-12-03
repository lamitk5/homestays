<?php
$current_page = basename($_SERVER['PHP_SELF']);
$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';
$admin_role = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Administrator';
?>
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col sticky top-0 h-screen">
  <!-- Logo -->
  <div class="p-6 border-b">
    <div class="flex items-center gap-3">
      <span class="material-symbols-outlined text-primary text-3xl">home_work</span>
      <h1 href="trang_chu.php"text-xl font-bold text-gray-900">Homestay Deluxe</h1>
    </div>
  </div>

  <!-- Admin Info -->
  <div class="p-4 border-b">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-green-400 flex items-center justify-center text-white font-bold">
        <?php echo strtoupper(substr($admin_name, 0, 1)); ?>
      </div>
      <div>
        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($admin_name); ?></p>
        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($admin_role); ?></p>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
    <a href="dashboard.php" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo $current_page == 'dashboard.php' ? 'bg-primary/20 text-primary font-bold' : 'text-gray-700 hover:bg-gray-100'; ?>">
      <span class="material-symbols-outlined">dashboard</span>
      <span class="text-sm">Dashboard</span>
    </a>
    
    <a href="qly_home.php" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo $current_page == 'qly_home.php' ? 'bg-primary/20 text-primary font-bold' : 'text-gray-700 hover:bg-gray-100'; ?>">
      <span class="material-symbols-outlined">villa</span>
      <span class="text-sm">Quản lý Homestay</span>
    </a>
    
    <a href="qly_datphong.php" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo $current_page == 'qly_datphong.php' ? 'bg-primary/20 text-primary font-bold' : 'text-gray-700 hover:bg-gray-100'; ?>">
      <span class="material-symbols-outlined">book_online</span>
      <span class="text-sm">Đặt phòng</span>
    </a>
    
    <a href="quanlykh.php" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo $current_page == 'quanlykh.php' ? 'bg-primary/20 text-primary font-bold' : 'text-gray-700 hover:bg-gray-100'; ?>">
      <span class="material-symbols-outlined">group</span>
      <span class="text-sm">Khách hàng</span>
    </a>
    
    <a href="reports.php" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo $current_page == 'reports.php' ? 'bg-primary/20 text-primary font-bold' : 'text-gray-700 hover:bg-gray-100'; ?>">
      <span class="material-symbols-outlined">assessment</span>
      <span class="text-sm">Báo cáo</span>
    </a>
    
    <a href="settings.php" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo $current_page == 'settings.php' ? 'bg-primary/20 text-primary font-bold' : 'text-gray-700 hover:bg-gray-100'; ?>">
      <span class="material-symbols-outlined">settings</span>
      <span class="text-sm">Cài đặt</span>
    </a>
  </nav>

  <!-- Logout -->
  <div class="p-4 border-t">
    <a href="logout.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
      <span class="material-symbols-outlined">logout</span>
      <span class="text-sm font-medium">Đăng xuất</span>
    </a>
  </div>
</aside>