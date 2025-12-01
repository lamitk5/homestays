<?php
session_start();

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: dangnhapqtv.php");
    exit();
}

// Lấy thông tin user từ session
$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';
$admin_role = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Administrator';

// 2. KẾT NỐI CSDL
$conn = new mysqli("localhost", "root", "", "homestays");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 3. LẤY THỐNG KÊ
// Tổng homestays
$total_homestays_result = $conn->query("SELECT COUNT(*) as count FROM homestays");
$total_homestays = $total_homestays_result ? $total_homestays_result->fetch_assoc()['count'] : 0;

// Tổng khách hàng (từ bảng users)
$total_customers_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role IN ('customer', 'vip')");
$total_customers = $total_customers_result ? $total_customers_result->fetch_assoc()['count'] : 0;

// Tổng admin
$total_admins_result = $conn->query("SELECT COUNT(*) as count FROM qtrivien");
$total_admins = $total_admins_result ? $total_admins_result->fetch_assoc()['count'] : 0;

// Homestay đang hoạt động
$active_homestays_result = $conn->query("SELECT COUNT(*) as count FROM homestays WHERE status = 'available'");
$active_homestays = $active_homestays_result ? $active_homestays_result->fetch_assoc()['count'] : 0;

// Tính tổng doanh thu (từ bookings đã thanh toán)
$revenue_result = $conn->query("SELECT SUM(total_price) as total FROM bookings WHERE payment_status = 'paid'");
$total_revenue = $revenue_result ? $revenue_result->fetch_assoc()['total'] : 0;
if ($total_revenue === null) $total_revenue = 0;

// Khách hàng mới trong 30 ngày
$new_customers_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$new_customers = $new_customers_result ? $new_customers_result->fetch_assoc()['count'] : 0;

// VIP customers
$vip_customers_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'vip'");
$vip_customers = $vip_customers_result ? $vip_customers_result->fetch_assoc()['count'] : 0;

// Tổng booking
$total_bookings_result = $conn->query("SELECT COUNT(*) as count FROM bookings");
$total_bookings = $total_bookings_result ? $total_bookings_result->fetch_assoc()['count'] : 0;

// Lấy homestays gần đây
$recent_homestays = $conn->query("SELECT id, name, location, price, rating, status, created_at FROM homestays ORDER BY created_at DESC LIMIT 5");

// Lấy khách hàng mới nhất
$recent_customers = $conn->query("SELECT id, username, fullname, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");

// Lấy thống kê theo tháng (giả lập cho biểu đồ)
$monthly_stats = [];
for ($i = 3; $i >= 0; $i--) {
    $month_start = date('Y-m-01', strtotime("-$i months"));
    $month_end = date('Y-m-t', strtotime("-$i months"));
    $month_name = date('M', strtotime("-$i months"));
    
    $month_revenue = $conn->query("SELECT SUM(total_price) as total FROM bookings WHERE payment_status='paid' AND created_at BETWEEN '$month_start' AND '$month_end'");
    $monthly_stats[$month_name] = $month_revenue ? $month_revenue->fetch_assoc()['total'] : 0;
}

$conn->close();
?>
<!DOCTYPE html>
<html class="light" lang="vi">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Bảng điều khiển - Quản lý Homestay</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
    }
  </style>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#13ecc8",
            "background-light": "#f6f8f8",
            "background-dark": "#10221f",
          },
          fontFamily: {
            "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
        },
      },
    }
  </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark">
  <div class="relative flex min-h-screen w-full flex-col">
    <div class="flex h-full grow">
      <!-- SideNavBar -->
      <aside class="flex w-64 flex-col gap-8 border-r border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-background-dark sticky top-0 h-screen">
        <div class="flex items-center gap-3 px-2">
          <span class="material-symbols-outlined text-primary text-3xl">home_work</span>
          <h1 class="text-xl font-bold text-slate-800 dark:text-white">Homestay Pro</h1>
        </div>
        <div class="flex flex-col gap-4">
          <div class="flex gap-3 items-center">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-12 bg-gradient-to-br from-primary to-green-400 flex items-center justify-center">
              <span class="material-symbols-outlined text-white text-2xl">person</span>
            </div>
            <div class="flex flex-col">
              <h1 class="text-slate-900 dark:text-slate-100 text-base font-medium leading-normal"><?php echo htmlspecialchars($admin_name); ?></h1>
              <p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal"><?php echo htmlspecialchars($admin_role); ?></p>
            </div>
          </div>
          <nav class="flex flex-col gap-2 mt-4">
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 text-primary font-bold" href="dashboard.php">
              <span class="material-symbols-outlined">dashboard</span>
              <p class="text-sm leading-normal">Dashboard</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="qly_datphong.php">
              <span class="material-symbols-outlined">book_online</span>
              <p class="text-sm font-medium leading-normal">Quản lý Đặt phòng</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="homestays.php">
              <span class="material-symbols-outlined">villa</span>
              <p class="text-sm font-medium leading-normal">Quản lý Homestay</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="quanlykh.php">
              <span class="material-symbols-outlined">group</span>
              <p class="text-sm font-medium leading-normal">Khách hàng</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="reports.php">
              <span class="material-symbols-outlined">assessment</span>
              <p class="text-sm font-medium leading-normal">Báo cáo</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="settings.php">
              <span class="material-symbols-outlined">settings</span>
              <p class="text-sm font-medium leading-normal">Cài đặt</p>
            </a>
          </nav>
        </div>
        <div class="mt-auto">
          <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 transition-colors" href="logoutqtv.php">
            <span class="material-symbols-outlined">logout</span>
            <p class="text-sm font-medium leading-normal">Đăng xuất</p>
          </a>
        </div>
      </aside>
      
      <!-- Main Content -->
      <main class="flex-1 p-8 overflow-y-auto">
        <!-- PageHeading -->
        <div class="flex flex-wrap justify-between gap-4 items-center mb-8">
          <div class="flex flex-col gap-1">
            <p class="text-slate-900 dark:text-slate-50 text-3xl font-bold leading-tight tracking-tight">Bảng điều khiển</p>
            <p class="text-slate-500 dark:text-slate-400 text-base font-normal leading-normal">Chào mừng trở lại, <?php echo htmlspecialchars($admin_name); ?>! 👋</p>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-sm text-slate-500 dark:text-slate-400"><?php echo date('d/m/Y H:i'); ?></span>
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-slate-50 dark:hover:bg-slate-700">
              <span class="material-symbols-outlined text-lg">calendar_month</span>
              <span class="truncate">30 ngày qua</span>
              <span class="material-symbols-outlined text-lg">expand_more</span>
            </button>
          </div>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
          <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <p class="text-slate-600 dark:text-slate-400 text-base font-medium leading-normal">Tổng doanh thu</p>
              <span class="material-symbols-outlined text-green-500 text-2xl">payments</span>
            </div>
            <p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight"><?php echo number_format($total_revenue, 0, ',', '.'); ?>đ</p>
            <p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1">
              <span class="material-symbols-outlined text-base">trending_up</span>
              Từ giá homestay
            </p>
          </div>
          
          <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <p class="text-slate-600 dark:text-slate-400 text-base font-medium leading-normal">Tổng Homestay</p>
              <span class="material-symbols-outlined text-blue-500 text-2xl">villa</span>
            </div>
            <p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight"><?php echo $total_homestays; ?></p>
            <p class="text-blue-500 text-sm font-medium leading-normal flex items-center gap-1">
              <span class="material-symbols-outlined text-base">info</span>
              Tổng số homestay
            </p>
          </div>
          
          <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <p class="text-slate-600 dark:text-slate-400 text-base font-medium leading-normal">Homestay hoạt động</p>
              <span class="material-symbols-outlined text-primary text-2xl">check_circle</span>
            </div>
            <p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight"><?php echo $active_homestays; ?></p>
            <p class="text-primary text-sm font-medium leading-normal flex items-center gap-1">
              <span class="material-symbols-outlined text-base">trending_up</span>
              Đang có sẵn
            </p>
          </div>
          
          <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <p class="text-slate-600 dark:text-slate-400 text-base font-medium leading-normal">Tổng Khách hàng</p>
              <span class="material-symbols-outlined text-purple-500 text-2xl">group</span>
            </div>
            <p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight"><?php echo $total_customers; ?></p>
            <p class="text-purple-500 text-sm font-medium leading-normal flex items-center gap-1">
              <span class="material-symbols-outlined text-base">person</span>
              <?php echo $new_customers; ?> mới trong 30 ngày
            </p>
          </div>
        </div>
        
        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          <div class="lg:col-span-2 flex flex-col gap-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark p-6">
            <p class="text-slate-900 dark:text-white text-base font-medium leading-normal">Doanh thu theo thời gian</p>
            <p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight truncate"><?php echo number_format($total_revenue, 0, ',', '.'); ?>đ</p>
            <div class="flex gap-2">
              <p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal">30 ngày qua</p>
              <p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1">
                <span class="material-symbols-outlined text-base">trending_up</span>
                Tổng giá trị
              </p>
            </div>
            <div class="flex min-h-[240px] flex-1 flex-col gap-8 pt-4">
              <svg fill="none" height="100%" preserveaspectratio="none" viewbox="0 0 475 150" width="100%" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25V149H0V109Z" fill="url(#paint0_linear_chart)"></path>
                <path d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25" stroke="#13ecc8" stroke-linecap="round" stroke-width="3"></path>
                <defs>
                  <lineargradient gradientunits="userSpaceOnUse" id="paint0_linear_chart" x1="236" x2="236" y1="1" y2="149">
                    <stop stop-color="#13ecc8" stop-opacity="0.3"></stop>
                    <stop offset="1" stop-color="#13ecc8" stop-opacity="0"></stop>
                  </lineargradient>
                </defs>
              </svg>
              <div class="flex justify-around">
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal tracking-wide">Tuần 1</p>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal tracking-wide">Tuần 2</p>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal tracking-wide">Tuần 3</p>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal tracking-wide">Tuần 4</p>
              </div>
            </div>
          </div>
          
          <div class="flex flex-col gap-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark p-6">
            <p class="text-slate-900 dark:text-white text-base font-medium leading-normal">Tỷ lệ homestay</p>
            <p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight truncate">
              <?php echo $total_homestays > 0 ? round(($active_homestays / $total_homestays) * 100) : 0; ?>%
            </p>
            <div class="flex gap-2">
              <p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal">Homestay có sẵn</p>
              <p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1">
                <span class="material-symbols-outlined text-base">check</span>
                <?php echo $active_homestays; ?>/<?php echo $total_homestays; ?>
              </p>
            </div>
            <div class="grid min-h-[240px] grid-flow-col gap-6 grid-rows-[1fr_auto] items-end justify-items-center px-3 pt-4">
              <?php
              $bar_height = $total_homestays > 0 ? ($active_homestays / $total_homestays) * 100 : 0;
              ?>
              <div class="bg-primary w-full rounded-t-lg" style="height: <?php echo $bar_height; ?>%;"></div>
              <p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal">Có sẵn</p>
              <div class="bg-primary/20 w-full rounded-t-lg" style="height: <?php echo 100 - $bar_height; ?>%;"></div>
              <p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal">Khác</p>
            </div>
          </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Homestay gần đây -->
          <div class="flex flex-col rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark">
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">
              <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight">Homestay gần đây</h2>
              <a href="homestays.php" class="text-primary text-sm font-medium hover:underline">Xem tất cả →</a>
            </div>
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
              <?php
              if ($recent_homestays && $recent_homestays->num_rows > 0) {
                while ($homestay = $recent_homestays->fetch_assoc()) {
                  $time_ago = time() - strtotime($homestay['created_at']);
                  $time_display = '';
                  if ($time_ago < 3600) {
                    $time_display = floor($time_ago / 60) . ' phút trước';
                  } elseif ($time_ago < 86400) {
                    $time_display = floor($time_ago / 3600) . ' giờ trước';
                  } else {
                    $time_display = floor($time_ago / 86400) . ' ngày trước';
                  }
                  
                  $status_color = $homestay['status'] == 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                  $status_text = $homestay['status'] == 'available' ? 'Có sẵn' : ucfirst($homestay['status']);
                  
                  echo '<div class="flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">';
                  echo '  <div class="p-3 bg-primary/20 rounded-lg flex-shrink-0">';
                  echo '    <span class="material-symbols-outlined text-primary text-xl">villa</span>';
                  echo '  </div>';
                  echo '  <div class="flex-1 min-w-0">';
                  echo '    <div class="flex items-start justify-between gap-2">';
                  echo '      <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">' . htmlspecialchars($homestay['name']) . '</h3>';
                  echo '      <span class="px-2 py-1 text-xs font-medium rounded-full ' . $status_color . ' whitespace-nowrap">' . $status_text . '</span>';
                  echo '    </div>';
                  echo '    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">';
                  echo '      <span class="material-symbols-outlined text-xs align-middle">location_on</span> ';
                  echo htmlspecialchars($homestay['location']);
                  echo '    </p>';
                  echo '    <div class="flex items-center justify-between mt-2">';
                  echo '      <span class="text-sm font-bold text-primary">' . number_format($homestay['price'], 0, ',', '.') . 'đ<span class="text-xs font-normal text-slate-500">/đêm</span></span>';
                  echo '      <span class="text-xs text-slate-400">' . $time_display . '</span>';
                  echo '    </div>';
                  echo '  </div>';
                  echo '</div>';
                }
              } else {
                echo '<div class="text-center py-12">';
                echo '  <span class="material-symbols-outlined text-slate-300 text-5xl">villa</span>';
                echo '  <p class="text-slate-500 dark:text-slate-400 mt-3">Chưa có homestay nào</p>';
                echo '  <a href="homestays.php?action=add" class="inline-block mt-3 text-primary text-sm font-medium hover:underline">+ Thêm homestay mới</a>';
                echo '</div>';
              }
              ?>
            </div>
          </div>
          
          <!-- Khách hàng mới -->
          <div class="flex flex-col rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark">
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">
              <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight">Khách hàng mới</h2>
              <a href="customers.php" class="text-primary text-sm font-medium hover:underline">Xem tất cả →</a>
            </div>
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
              <?php
              if ($recent_customers && $recent_customers->num_rows > 0) {
                while ($customer = $recent_customers->fetch_assoc()) {
                  $time_ago = time() - strtotime($customer['created_at']);
                  $time_display = '';
                  if ($time_ago < 3600) {
                    $time_display = floor($time_ago / 60) . ' phút trước';
                  } elseif ($time_ago < 86400) {
                    $time_display = floor($time_ago / 3600) . ' giờ trước';
                  } else {
                    $time_display = floor($time_ago / 86400) . ' ngày trước';
                  }
                  
                  $role_badge = $customer['role'] == 'vip' 
                    ? '<span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">⭐ VIP</span>'
                    : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Customer</span>';
                  
                  echo '<div class="flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">';
                  echo '  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold flex-shrink-0">';
                  echo strtoupper(substr($customer['fullname'], 0, 1));
                  echo '  </div>';
                  echo '  <div class="flex-1 min-w-0">';
                  echo '    <div class="flex items-start justify-between gap-2">';
                  echo '      <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">' . htmlspecialchars($customer['fullname']) . '</h3>';
                  echo '      ' . $role_badge;
                  echo '    </div>';
                  echo '    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 truncate">';
                  echo '      <span class="material-symbols-outlined text-xs align-middle">mail</span> ';
                  echo htmlspecialchars($customer['email']);
                  echo '    </p>';
                  echo '    <p class="text-xs text-slate-400 mt-1">@' . htmlspecialchars($customer['username']) . ' • ' . $time_display . '</p>';
                  echo '  </div>';
                  echo '</div>';
                }
              } else {
                echo '<div class="text-center py-12">';
                echo '  <span class="material-symbols-outlined text-slate-300 text-5xl">group</span>';
                echo '  <p class="text-slate-500 dark:text-slate-400 mt-3">Chưa có khách hàng nào</p>';
                echo '</div>';
              }
              ?>
            </div>
          </div>
        </div>
        
        <!-- Quick Stats & Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Thống kê nhanh -->
          <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-600 text-3xl">analytics</span>
                <div>
                  <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Tỷ lệ available</p>
                  <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                    <?php echo $total_homestays > 0 ? round(($active_homestays / $total_homestays) * 100) : 0; ?>%
                  </p>
                </div>
              </div>
            </div>
            
            <div class="p-4 rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-green-600 text-3xl">workspace_premium</span>
                <div>
                  <p class="text-xs text-green-600 dark:text-green-400 font-medium">VIP Customers</p>
                  <p class="text-2xl font-bold text-green-900 dark:text-green-100"><?php echo $vip_customers; ?></p>
                </div>
              </div>
            </div>
            
            <div class="p-4 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-purple-600 text-3xl">admin_panel_settings</span>
                <div>
                  <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Tổng Admin</p>
                  <p class="text-2xl font-bold text-purple-900 dark:text-purple-100"><?php echo $total_admins; ?></p>
                </div>
              </div>
            </div>
            
            <div class="p-4 rounded-xl bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border border-yellow-200 dark:border-yellow-800">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-yellow-600 text-3xl">star</span>
                <div>
                  <p class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">Rating TB</p>
                  <?php
                  $avg_rating_result = $conn->query("SELECT AVG(rating) as avg FROM homestays WHERE rating > 0");
                  $avg_rating = $avg_rating_result ? number_format($avg_rating_result->fetch_assoc()['avg'], 1) : '0.0';
                  ?>
                  <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100"><?php echo $avg_rating; ?></p>
                </div>
              </div>
            </div>
            
            <div class="p-4 rounded-xl bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600 text-3xl">trending_up</span>
                <div>
                  <p class="text-xs text-red-600 dark:text-red-400 font-medium">Growth</p>
                  <p class="text-2xl font-bold text-red-900 dark:text-red-100">+<?php echo $new_customers; ?></p>
                </div>
              </div>
            </div>
            
            <div class="p-4 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 border border-indigo-200 dark:border-indigo-800">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-indigo-600 text-3xl">bookmark</span>
                <?php
                $booked_result = $conn->query("SELECT COUNT(*) as count FROM homestays WHERE status = 'booked'");
                $booked_count = $booked_result ? $booked_result->fetch_assoc()['count'] : 0;
                ?>
                <div>
                  <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Đã đặt</p>
                  <p class="text-2xl font-bold text-indigo-900 dark:text-indigo-100"><?php echo $booked_count; ?></p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Quick Actions -->
          <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark p-6">
            <h2 class="text-slate-900 dark:text-white text-lg font-bold mb-4">Thao tác nhanh</h2>
            <div class="space-y-3">
              <a href="homestays.php?action=add" class="flex items-center gap-3 p-3 rounded-lg bg-primary/10 hover:bg-primary/20 transition-colors group">
                <span class="material-symbols-outlined text-primary text-xl">add_circle</span>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-primary">Thêm Homestay mới</span>
              </a>
              <a href="bookings.php?action=add" class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                <span class="material-symbols-outlined text-blue-500 text-xl">book_online</span>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600">Tạo đặt phòng</span>
              </a>
              <a href="reports.php" class="flex items-center gap-3 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                <span class="material-symbols-outlined text-green-500 text-xl">assessment</span>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-green-600">Xem báo cáo</span>
              </a>
              <a href="settings.php" class="flex items-center gap-3 p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors group">
                <span class="material-symbols-outlined text-purple-500 text-xl">settings</span>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-purple-600">Cài đặt hệ thống</span>
              </a>
            </div>
            
            <div class="mt-6 p-4 bg-gradient-to-br from-primary/10 to-primary/5 rounded-lg border border-primary/20">
              <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">💡 Mẹo</p>
              <p class="text-xs text-slate-600 dark:text-slate-400">Cập nhật thông tin homestay thường xuyên để tăng độ tin cậy với khách hàng!</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>

</html>