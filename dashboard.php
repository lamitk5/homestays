<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: dangnhap.php");
    exit();
}

$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';

// Kết nối database
$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ===== THỐNG KÊ TỔNG QUAN (CHỈ TÍNH ĐƠN CONFIRMED) =====
$total_homestays_result = $conn->query("SELECT COUNT(*) as count FROM homestays WHERE deleted_at IS NULL");
$total_homestays = $total_homestays_result ? $total_homestays_result->fetch_assoc()['count'] : 0;

// Chỉ đếm đơn đã xác nhận
$total_bookings_result = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE deleted_at IS NULL AND status = 'confirmed'");
$total_bookings = $total_bookings_result ? $total_bookings_result->fetch_assoc()['count'] : 0;

$total_customers_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE deleted_at IS NULL");
$total_customers = $total_customers_result ? $total_customers_result->fetch_assoc()['count'] : 0;

// Tính tổng doanh thu (Chỉ tính đơn đã xác nhận)
$total_revenue_result = $conn->query("SELECT COALESCE(SUM(total_price), 0) as total FROM bookings WHERE deleted_at IS NULL AND status = 'confirmed'");
if ($total_revenue_result) {
    $row = $total_revenue_result->fetch_assoc();
    $total_revenue = $row['total'];
} else {
    $total_revenue = 0;
}

// Đơn tháng này (Chỉ tính đơn đã xác nhận)
$this_month_result = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND deleted_at IS NULL AND status = 'confirmed'");
$this_month_bookings = $this_month_result ? $this_month_result->fetch_assoc()['count'] : 0;

// ===== THỐNG KÊ THEO THÁNG (12 tháng gần nhất - CHỈ CONFIRMED) =====
$bookings_by_month = [];
$revenue_by_month = [];

try {
    for ($i = 11; $i >= 0; $i--) {
        $month = date('Y-m', strtotime("-$i month"));
        $month_label = date('M Y', strtotime("-$i month"));
        
        // Số đơn đặt phòng (Confirmed)
        $query_bookings = "SELECT COUNT(*) as count FROM bookings WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month' AND deleted_at IS NULL AND status = 'confirmed'";
        $result = $conn->query($query_bookings);
        $bookings_by_month[$month_label] = $result ? $result->fetch_assoc()['count'] : 0;        
        
        // Doanh thu (Confirmed)
        $query_revenue = "SELECT COALESCE(SUM(total_price), 0) as total FROM bookings WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month' AND deleted_at IS NULL AND status = 'confirmed'";
        $result = $conn->query($query_revenue);
        $revenue_by_month[$month_label] = $result ? $result->fetch_assoc()['total'] : 0;
    }
} catch (Exception $e) {
    for ($i = 11; $i >= 0; $i--) {
        $month_label = date('M Y', strtotime("-$i month"));
        $bookings_by_month[$month_label] = 0;
        $revenue_by_month[$month_label] = 0;
    }
}

// ===== TOP 5 HOMESTAY PHỔ BIẾN (Dựa trên đơn Confirmed) =====
$top_homestays_query = "
    SELECT h.name, h.district, COUNT(b.id) as total_bookings, COALESCE(SUM(b.total_price), 0) as total_revenue
    FROM homestays h
    LEFT JOIN bookings b ON h.homestay_id = b.homestay_id AND b.deleted_at IS NULL AND b.status = 'confirmed'
    WHERE h.deleted_at IS NULL
    GROUP BY h.homestay_id
    ORDER BY total_bookings DESC
    LIMIT 5
";
$top_homestays = $conn->query($top_homestays_query);

// ===== ĐƠN ĐẶT PHÒNG GẦN ĐÂY (Chỉ Confirmed) =====
$recent_bookings_query = "
    SELECT b.*, h.name as homestay_name, u.fullname as customer_name
    FROM bookings b
    LEFT JOIN homestays h ON b.homestay_id = h.homestay_id AND h.deleted_at IS NULL
    LEFT JOIN users u ON b.user_id = u.user_id AND u.deleted_at IS NULL
    WHERE b.deleted_at IS NULL AND b.status = 'confirmed'
    ORDER BY b.created_at DESC
    LIMIT 5
";
$recent_bookings = $conn->query($recent_bookings_query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Homestay Deluxe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
  </style>
  <script>
    tailwind.config = { theme: { extend: { colors: { primary: "#13ecc8" } } } }
  </script>
</head>
<body class="bg-gray-50">
  <div class="flex h-screen">
    <?php include 'sidebar.php'; ?>
    
    <main class="flex-1 overflow-y-auto p-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header with Back to Home Button -->
        <div class="mb-8 flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500 mt-1">Chào mừng trở lại, <?php echo htmlspecialchars($admin_name); ?>!</p>
          </div>
          
          <!-- Back to Home Button -->
          <a href="trang_chu.php" 
             class="flex items-center gap-2 bg-gradient-to-r from-[#13ecc8] to-[#10d4b4] hover:from-[#10d4b4] hover:to-[#0fc4a4] text-white font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
            <span class="material-symbols-outlined">home</span>
            <span>Về Trang Chủ</span>
          </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Tổng Homestay -->
          <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600 text-2xl">home</span>
              </div>
            </div>
            <p class="text-gray-500 text-sm font-medium">Tổng Homestay</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo $total_homestays; ?></p>
          </div>

          <!-- Tổng Đặt phòng -->
          <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600 text-2xl">calendar_month</span>
              </div>
            </div>
            <p class="text-gray-500 text-sm font-medium">Tổng Đặt phòng (Đã xác nhận)</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo $total_bookings; ?></p>
          </div>

          <!-- Tổng Đặt phòng tháng này -->
          <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-purple-600 text-2xl">event</span>
              </div>
              <span class="text-xs font-bold bg-purple-50 text-purple-600 px-2 py-1 rounded">Tháng này</span>
            </div>
            <p class="text-gray-500 text-sm font-medium">Đơn mới (Đã xác nhận)</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo $this_month_bookings; ?></p>
          </div>

          <!-- Doanh thu -->
          <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-yellow-600 text-2xl">payments</span>
              </div>
            </div>
            <p class="text-gray-500 text-sm font-medium">Tổng Doanh thu</p>
            <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo number_format($total_revenue, 0, ',', '.'); ?>₫</p>
          </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Biểu đồ Đặt phòng theo tháng -->
          <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Đặt phòng theo tháng (Đã xác nhận)</h2>
            <div style="position: relative; height: 300px;">
              <canvas id="bookingsChart"></canvas>
            </div>
          </div>

          <!-- Biểu đồ Doanh thu theo tháng -->
          <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Doanh thu theo tháng</h2>
            <div style="position: relative; height: 300px;">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Top Homestays -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
          <h2 class="text-lg font-bold text-gray-900 mb-4">Top 5 Homestay phổ biến (Theo doanh thu thực)</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
              <?php 
              $rank = 1;
              if ($top_homestays && $top_homestays->num_rows > 0) {
                while($hs = $top_homestays->fetch_assoc()): 
              ?>
              <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition text-center">
                <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center bg-primary text-gray-900 rounded-full font-bold text-lg">
                  <?php echo $rank++; ?>
                </div>
                <p class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2" title="<?php echo htmlspecialchars($hs['name']); ?>">
                  <?php echo htmlspecialchars(substr($hs['name'], 0, 30)) . (strlen($hs['name']) > 30 ? '...' : ''); ?>
                </p>
                <p class="text-xs text-gray-500 mb-2"><?php echo $hs['total_bookings']; ?> đơn</p>
                <p class="text-sm font-bold text-green-600"><?php echo number_format($hs['total_revenue'], 0, ',', '.'); ?>₫</p>
              </div>
              <?php 
                endwhile;
              } else {
                echo '<p class="col-span-5 text-center text-gray-500 py-8">Chưa có dữ liệu</p>';
              }
              ?>
            </div>
          </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Đơn đặt phòng gần đây (Đã xác nhận)</h2>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Mã đơn</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Khách hàng</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Homestay</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Check-in</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tổng tiền</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php 
                if ($recent_bookings && $recent_bookings->num_rows > 0) {
                  while($booking = $recent_bookings->fetch_assoc()): 
                ?>
                <tr class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 text-sm font-medium text-gray-900">#<?php echo $booking['id']; ?></td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></td>
                  <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($booking['homestay_name'] ?? 'N/A'); ?></td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('d/m/Y', strtotime($booking['check_in'])); ?></td>
                  <td class="px-6 py-4 text-sm font-semibold text-green-600"><?php echo number_format($booking['total_price'], 0, ',', '.'); ?>₫</td>
                </tr>
                <?php 
                  endwhile;
                } else {
                  echo '<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Chưa có đơn đặt phòng nào</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Data từ PHP
    const bookingsData = <?php echo json_encode(array_values($bookings_by_month)); ?>;
    const bookingsLabels = <?php echo json_encode(array_keys($bookings_by_month)); ?>;
    const revenueData = <?php echo json_encode(array_values($revenue_by_month)); ?>;
    const revenueLabels = <?php echo json_encode(array_keys($revenue_by_month)); ?>;

    // Chart 1: Đặt phòng theo tháng
    const ctxBookings = document.getElementById('bookingsChart');
    new Chart(ctxBookings, {
      type: 'line',
      data: {
        labels: bookingsLabels,
        datasets: [{
          label: 'Số đơn (Confirmed)',
          data: bookingsData,
          borderColor: '#13ecc8',
          backgroundColor: 'rgba(19, 236, 200, 0.1)',
          tension: 0.4,
          fill: true,
          pointBackgroundColor: '#13ecc8',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });

    // Chart 2: Doanh thu theo tháng
    const ctxRevenue = document.getElementById('revenueChart');
    new Chart(ctxRevenue, {
      type: 'bar',
      data: {
        labels: revenueLabels,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: revenueData,
          backgroundColor: 'rgba(59, 130, 246, 0.8)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 2,
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(context) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(value);
              }
            }
          }
        }
      }
    });
  </script>

</body>
</html>
<?php $conn->close(); ?>