<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: dangnhapqtv.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) die("Kết nối thất bại");
$conn->set_charset("utf8mb4");

$message = "";
$message_type = "";

// ===== XỬ LÝ XÓA ĐƠN (SOFT DELETE) =====
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);
    
    // Khi xóa mềm cập nhật luôn status thành 'cancelled'
    $stmt = $conn->prepare("UPDATE bookings SET deleted_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        $message = "✅ Đã xóa đơn đặt phòng!";
        $message_type = "success";
    }
    $stmt->close();
}

// ===== KHÔI PHỤC ĐƠN =====
if (isset($_GET['action']) && $_GET['action'] == 'restore' && isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("UPDATE bookings SET deleted_at = NULL WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        $message = "✅ Đã khôi phục đơn đặt phòng!";
        $message_type = "success";
    }
    $stmt->close();
}

// TÌM KIẾM VÀ LỌC
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$show_deleted = isset($_GET['show_deleted']) && $_GET['show_deleted'] == '1';

$where = [];

// ✅ Chỉ hiển thị đơn chưa xóa
if (!$show_deleted) {
    $where[] = "b.deleted_at IS NULL";
} else {
    $where[] = "b.deleted_at IS NOT NULL";
}

if ($search) {
    $search_safe = $conn->real_escape_string($search);
    $where[] = "(u.fullname LIKE '%$search_safe%' OR h.name LIKE '%$search_safe%' OR b.id LIKE '%$search_safe%')";
}

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

// TRUY VẤN DANH SÁCH
$bookings = $conn->query("
    SELECT b.*, h.name as homestay_name, h.district, u.fullname as customer_name, u.email, u.phone
    FROM bookings b
    LEFT JOIN homestays h ON b.homestay_id = h.homestay_id AND h.deleted_at IS NULL
    LEFT JOIN users u ON b.user_id = u.user_id AND u.deleted_at IS NULL
    $where_sql
    ORDER BY b.created_at DESC
");

// THỐNG KÊ
$total = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE deleted_at IS NULL")->fetch_assoc()['c'];
$total_revenue = $conn->query("SELECT SUM(total_price) as total FROM bookings WHERE deleted_at IS NULL AND status = 'confirmed'")->fetch_assoc()['total'] ?? 0; // Chỉ tính tiền đơn đã xác nhận
$this_month = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND deleted_at IS NULL")->fetch_assoc()['c'];
$deleted_count = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE deleted_at IS NOT NULL")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Đặt phòng</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
  </style>
  <script>tailwind.config = { theme: { extend: { colors: { primary: "#13ecc8" } } } }</script>
</head>
<body class="bg-gray-50">
  <div class="flex h-screen">
    <?php include 'sidebar.php'; ?>
    
    <main class="flex-1 overflow-y-auto p-8">
      <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Quản lý Đặt phòng</h1>
            <p class="text-gray-500 mt-1">Quản lý tất cả đơn đặt phòng</p>
          </div>
          
          <?php if (!$show_deleted): ?>
          <a href="?show_deleted=1" class="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <span class="material-symbols-outlined">delete</span>
            Thùng rác (<?php echo $deleted_count; ?>)
          </a>
          <?php else: ?>
          <a href="qly_datphong.php" class="flex items-center gap-2 px-4 py-2 bg-primary text-gray-900 font-bold rounded-lg hover:opacity-90">
            <span class="material-symbols-outlined">arrow_back</span>
            Quay lại danh sách
          </a>
          <?php endif; ?>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $message_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
          <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <?php if (!$show_deleted): ?>
        <div class="grid grid-cols-3 gap-6 mb-6">
          <div class="bg-white rounded-xl p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 font-medium">Tổng đơn đặt phòng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $total; ?></p>
              </div>
              <span class="material-symbols-outlined text-blue-500 text-5xl">receipt_long</span>
            </div>
          </div>
          
          <div class="bg-white rounded-xl p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 font-medium">Tổng doanh thu</p>
                <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo number_format($total_revenue, 0, ',', '.'); ?>₫</p>
              </div>
              <span class="material-symbols-outlined text-green-500 text-5xl">payments</span>
            </div>
          </div>
          
          <div class="bg-white rounded-xl p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 font-medium">Đơn tháng này</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $this_month; ?></p>
              </div>
              <span class="material-symbols-outlined text-purple-500 text-5xl">calendar_month</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
          <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm kiếm theo tên khách hàng, homestay, mã đơn..." class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800">Tìm kiếm</button>
            <?php if ($search): ?>
            <a href="qly_datphong.php" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">Reset</a>
            <?php endif; ?>
          </form>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">STT</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Khách hàng</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Homestay</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Check-in / Out</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Tổng tiền</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Trạng thái</th>
                
                <?php if ($show_deleted): ?>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Ngày xóa</th>
                <?php endif; ?>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Thao tác</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <?php if ($bookings && $bookings->num_rows > 0): ?>
                <?php 
                $stt = 1; 
                while($row = $bookings->fetch_assoc()): 
                ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4">
                    <span class="font-bold text-gray-900"><?php echo $stt++; ?></span>
                    <p class="text-xs text-gray-500">Mã: #<?php echo $row['id']; ?></p>
                  </td>

                  <td class="px-6 py-4">
                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['customer_name'] ?? 'N/A'); ?></div>
                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['phone'] ?? ''); ?></div>
                  </td>

                  <td class="px-6 py-4">
                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['homestay_name'] ?? 'N/A'); ?></div>
                    <?php 
                    $date1 = new DateTime($row['check_in']);
                    $date2 = new DateTime($row['check_out']);
                    $nights = $date1->diff($date2)->days;
                    ?>
                    <div class="text-xs text-gray-500 mt-1">
                        <?php echo $nights; ?> đêm | <?php echo $row['guests_count']; ?> khách
                    </div>
                  </td>

                  <td class="px-6 py-4 text-sm text-gray-700">
                    <div><span class="text-xs text-gray-500">In:</span> <?php echo date('d/m/Y', strtotime($row['check_in'])); ?></div>
                    <div><span class="text-xs text-gray-500">Out:</span> <?php echo date('d/m/Y', strtotime($row['check_out'])); ?></div>
                  </td>
                  
                  <td class="px-6 py-4 text-sm font-bold text-green-600">
                    <?php echo number_format($row['total_price'], 0, ',', '.'); ?>₫
                  </td>

                  <td class="px-6 py-4">
                    <?php 
                        // Kiểm tra status từ DB
                        $status = isset($row['status']) ? $row['status'] : 'confirmed';
                        
                        // Logic hiển thị Badge
                        if ($status == 'confirmed' || $status == 'Đã đặt') {
                            echo '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Đã đặt
                                  </span>';
                        } elseif ($status == 'cancelled' || $status == 'Đã hủy') {
                            echo '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Đã hủy
                                  </span>';
                        }
                    ?>
                  </td>

                  <?php if ($show_deleted): ?>
                  <td class="px-6 py-4 text-sm text-gray-600">
                    <?php echo date('d/m/Y H:i', strtotime($row['deleted_at'])); ?>
                  </td>
                  <?php endif; ?>

                  <td class="px-6 py-4 text-right">
                    <?php if ($show_deleted): ?>
                      <a href="?action=restore&id=<?php echo $row['id']; ?>" 
                         onclick="return confirm('Khôi phục đơn đặt phòng này?')" 
                         class="text-green-600 hover:text-green-800 font-medium inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">restore</span>
                        Khôi phục
                      </a>
                    <?php else: ?>
                      <a href="?action=delete&id=<?php echo $row['id']; ?>" 
                         onclick="return confirm('⚠️ Xóa đơn đặt phòng này?')" 
                         class="text-red-600 hover:text-red-800 font-medium inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">delete</span>
                        Xóa
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="<?php echo $show_deleted ? '8' : '7'; ?>" class="px-6 py-12 text-center text-gray-500">
                  <?php echo $show_deleted ? 'Thùng rác trống' : 'Không tìm thấy đơn đặt phòng'; ?>
                </td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
<?php $conn->close(); ?>