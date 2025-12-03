<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: dangnhapqtv.php");
    exit();
}

$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';

// Kết nối database
$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Lấy thống kê doanh thu theo tháng (CHỈ TÍNH ĐƠN CONFIRMED)
$current_year = date('Y');
$revenue_by_month = [];
for ($i = 1; $i <= 12; $i++) {
    $month = str_pad($i, 2, '0', STR_PAD_LEFT);
    $query = "SELECT COALESCE(SUM(total_price), 0) as total 
    FROM bookings 
    WHERE YEAR(check_in) = $current_year 
    AND MONTH(check_in) = $i
    AND deleted_at IS NULL
    AND status = 'confirmed'"; // ✅ Thêm điều kiện status
    $result = $conn->query($query);
    
    if ($result === false) {
        $revenue_by_month[$i] = 0;
    } else {
        $row = $result->fetch_assoc();
        $revenue_by_month[$i] = $row['total'];
    }
}

// Thống kê homestay phổ biến nhất (CHỈ TÍNH ĐƠN CONFIRMED)
$popular_homestays = $conn->query("
    SELECT h.name, h.district, COUNT(b.id) as total_bookings, 
           SUM(b.total_price) as total_revenue
    FROM homestays h
    LEFT JOIN bookings b ON h.homestay_id = b.homestay_id AND b.deleted_at IS NULL AND b.status = 'confirmed' -- ✅ Thêm điều kiện status
    WHERE h.deleted_at IS NULL
    GROUP BY h.homestay_id
    ORDER BY total_bookings DESC
    LIMIT 5
");

// Thống kê khách hàng top (CHỈ TÍNH ĐƠN CONFIRMED)
$top_customers = $conn->query("
    SELECT u.fullname, u.email, u.phone, 
           COUNT(b.id) as total_bookings,
           SUM(b.total_price) as total_spent
    FROM users u 
    LEFT JOIN bookings b ON u.user_id = b.user_id AND b.deleted_at IS NULL AND b.status = 'confirmed' -- ✅ Thêm điều kiện status
    WHERE u.deleted_at IS NULL
    GROUP BY u.user_id
    ORDER BY total_bookings DESC
    LIMIT 5
");

// Tổng quan (CHỈ TÍNH ĐƠN CONFIRMED)
$total_revenue = array_sum($revenue_by_month);
$total_bookings_result = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE deleted_at IS NULL AND status = 'confirmed'"); // ✅ Thêm điều kiện status
$total_bookings = $total_bookings_result ? $total_bookings_result->fetch_assoc()['count'] : 0;
$avg_booking_value = $total_bookings > 0 ? $total_revenue / $total_bookings : 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Báo cáo - Homestay Pro</title>
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
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Báo cáo doanh thu</h1>
          <p class="text-gray-500 mt-1">Thống kê chi tiết dựa trên các đơn đã xác nhận (Confirmed)</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
              <span class="text-gray-500 text-sm font-medium">Tổng doanh thu năm <?php echo $current_year; ?></span>
              <span class="material-symbols-outlined text-green-500">payments</span>
            </div>
            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_revenue, 0, ',', '.'); ?>đ</p>
            <p class="text-sm text-gray-500 mt-2">
              Chỉ tính các đơn đã hoàn tất
            </p>
          </div>

          <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
              <span class="text-gray-500 text-sm font-medium">Tổng số đơn thành công</span> 
              <span class="material-symbols-outlined text-blue-500">calendar_month</span>
            </div>
            <p class="text-3xl font-bold text-gray-900"><?php echo $total_bookings; ?></p>
            <p class="text-sm text-gray-500 mt-2">
              Đơn hàng thực tế
            </p>
          </div>

          <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
              <span class="text-gray-500 text-sm font-medium">Giá trị TB/Đơn</span>
              <span class="material-symbols-outlined text-purple-500">trending_up</span>
            </div>
            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($avg_booking_value, 0, ',', '.'); ?>đ</p>
            <p class="text-sm text-gray-500 mt-2">
              Trung bình mỗi đơn
            </p>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Doanh thu theo tháng (<?php echo $current_year; ?>)</h2>
          <canvas id="revenueChart" height="80"></canvas>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Top 5 Homestay phổ biến</h2>
            <div class="space-y-4">
              <?php if($popular_homestays && $popular_homestays->num_rows > 0): ?>
                <?php $rank = 1; while($hs = $popular_homestays->fetch_assoc()): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div class="flex items-center gap-3">
                    <span class="w-8 h-8 flex items-center justify-center bg-primary text-gray-900 rounded-full font-bold text-sm">
                      <?php echo $rank++; ?>
                    </span>
                    <div>
                      <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($hs['name']); ?></p>
                      <p class="text-sm text-gray-500"><?php echo $hs['total_bookings']; ?> đặt phòng thành công</p>
                    </div>
                  </div>
                  <span class="font-bold text-gray-900"><?php echo number_format($hs['total_revenue'], 0, ',', '.'); ?>đ</span>
                </div>
                <?php endwhile; ?>
              <?php else: ?>
                <p class="text-gray-500 text-center py-8">Chưa có dữ liệu</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Top 5 Khách hàng VIP</h2>
            <div class="space-y-4">
              <?php if($top_customers && $top_customers->num_rows > 0): ?>
                <?php $rank = 1; while($customer = $top_customers->fetch_assoc()): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div class="flex items-center gap-3">
                    <span class="w-8 h-8 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full font-bold text-sm">
                      <?php echo $rank++; ?>
                    </span>
                    <div>
                      <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($customer['fullname']); ?></p>
                      <p class="text-sm text-gray-500"><?php echo $customer['total_bookings']; ?> lần đặt thành công</p>
                    </div>
                  </div>
                  <span class="font-bold text-gray-900"><?php echo number_format($customer['total_spent'], 0, ',', '.'); ?>đ</span>
                </div>
                <?php endwhile; ?>
              <?php else: ?>
                <p class="text-gray-500 text-center py-8">Chưa có dữ liệu</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
      </div>
    </main>
  </div>

  <script>
    const ctx = document.getElementById('revenueChart');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: <?php echo json_encode(array_values($revenue_by_month)); ?>,
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
          legend: {
            display: false
          },
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