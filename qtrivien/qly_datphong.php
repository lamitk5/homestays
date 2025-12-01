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

// XỬ LÝ XÓA BOOKING
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($conn->query("DELETE FROM bookings WHERE id = $id")) {
        $message = "Xóa booking thành công!";
        $message_type = "success";
    }
}

// XỬ LÝ CẬP NHẬT TRẠNG THÁI
if (isset($_GET['action']) && $_GET['action'] == 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];
    if ($conn->query("UPDATE bookings SET status = '$status' WHERE id = $id")) {
        $message = "Cập nhật trạng thái thành công!";
        $message_type = "success";
    }
}

// XỬ LÝ THÊM/SỬA BOOKING
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $homestay_id = intval($_POST['homestay_id']);
    $user_id = intval($_POST['user_id']);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = intval($_POST['guests']);
    $total_price = floatval($_POST['total_price']);
    $status = $_POST['status'];
    $payment_status = $_POST['payment_status'];
    $payment_method = $_POST['payment_method'];
    $notes = trim($_POST['notes']);
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // CẬP NHẬT
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE bookings SET homestay_id=?, user_id=?, check_in=?, check_out=?, guests=?, total_price=?, status=?, payment_status=?, payment_method=?, notes=? WHERE id=?");
        $stmt->bind_param("iissidsssi", $homestay_id, $user_id, $check_in, $check_out, $guests, $total_price, $status, $payment_status, $payment_method, $notes, $id);
        if ($stmt->execute()) {
            $message = "Cập nhật booking thành công!";
            $message_type = "success";
        }
    } else {
        // THÊM MỚI
        $stmt = $conn->prepare("INSERT INTO bookings (homestay_id, user_id, check_in, check_out, guests, total_price, status, payment_status, payment_method, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissidssss", $homestay_id, $user_id, $check_in, $check_out, $guests, $total_price, $status, $payment_status, $payment_method, $notes);
        if ($stmt->execute()) {
            $message = "Thêm booking thành công!";
            $message_type = "success";
        }
    }
    $stmt->close();
}

// TÌM KIẾM & LỌC
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$payment_filter = isset($_GET['payment']) ? $_GET['payment'] : '';

$where = [];
if ($search) $where[] = "(h.name LIKE '%$search%' OR u.fullname LIKE '%$search%')";
if ($status_filter) $where[] = "b.status = '$status_filter'";
if ($payment_filter) $where[] = "b.payment_status = '$payment_filter'";

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

// TRUY VẤN LẤY DANH SÁCH (ĐÃ SỬA)
$sql_query = "
    SELECT b.*, 
           h.name as homestay_name, 
           h.district as homestay_district,
           u.fullname as customer_name, 
           u.email as customer_email, 
           u.phone as customer_phone
    FROM bookings b
    LEFT JOIN homestays h ON b.homestay_id = h.homestay_id
    LEFT JOIN users u ON b.user_id = u.user_id
    $where_sql
    ORDER BY b.created_at DESC
";

$bookings = $conn->query($sql_query);

if (!$bookings) {
    die("LỖI TRUY VẤN: " . $conn->error);
}

// Thống kê
$total_result = $conn->query("SELECT COUNT(*) as c FROM bookings");
$total = $total_result ? $total_result->fetch_assoc()['c'] : 0;

$confirmed_result = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE status='confirmed'");
$confirmed = $confirmed_result ? $confirmed_result->fetch_assoc()['c'] : 0;

$pending_result = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE status='pending'");
$pending = $pending_result ? $pending_result->fetch_assoc()['c'] : 0;

$revenue_result = $conn->query("SELECT SUM(total_price) as r FROM bookings WHERE payment_status='paid'");
$revenue = $revenue_result ? ($revenue_result->fetch_assoc()['r'] ?? 0) : 0;

// Lấy danh sách homestays và users cho form
$homestays_list = $conn->query("SELECT homestay_id, name, location, price FROM homestays WHERE status='available' ORDER BY name");
$users_list = $conn->query("SELECT user_id, fullname, email FROM users ORDER BY fullname");

// Lấy booking để edit
$edit_booking = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM bookings WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $edit_booking = $result->fetch_assoc();
    }
}
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
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Quản lý Đặt phòng</h1>
            <p class="text-gray-500 mt-1">Quản lý các booking và đặt phòng</p>
          </div>
          <button onclick="document.getElementById('formModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-primary text-gray-900 font-bold rounded-lg hover:opacity-90">
            <span class="material-symbols-outlined">add</span>
            Thêm Booking
          </button>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $message_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
          <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-6 mb-6">
          <div class="bg-white rounded-xl p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600 font-medium">Tổng Booking</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $total; ?></p>
          </div>
          <div class="bg-white rounded-xl p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-600 font-medium">Đã xác nhận</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $confirmed; ?></p>
          </div>
          <div class="bg-white rounded-xl p-6 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-600 font-medium">Chờ xác nhận</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $pending; ?></p>
          </div>
          <div class="bg-white rounded-xl p-6 border-l-4 border-purple-500">
            <p class="text-sm text-gray-600 font-medium">Doanh thu</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo number_format($revenue, 0, ',', '.'); ?>đ</p>
          </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
          <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm theo homestay hoặc khách hàng..." class="flex-1 px-4 py-2 border rounded-lg">
            <select name="status" class="px-4 py-2 border rounded-lg">
              <option value="">Tất cả trạng thái</option>
              <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
              <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
              <option value="checked_in" <?php echo $status_filter == 'checked_in' ? 'selected' : ''; ?>>Checked In</option>
              <option value="checked_out" <?php echo $status_filter == 'checked_out' ? 'selected' : ''; ?>>Checked Out</option>
              <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            <select name="payment" class="px-4 py-2 border rounded-lg">
              <option value="">Tất cả thanh toán</option>
              <option value="paid" <?php echo $payment_filter == 'paid' ? 'selected' : ''; ?>>Paid</option>
              <option value="unpaid" <?php echo $payment_filter == 'unpaid' ? 'selected' : ''; ?>>Unpaid</option>
              <option value="refunded" <?php echo $payment_filter == 'refunded' ? 'selected' : ''; ?>>Refunded</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-medium rounded-lg">Tìm</button>
            <?php if ($search || $status_filter || $payment_filter): ?>
            <a href="qly_datphong.php" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg">Reset</a>
            <?php endif; ?>
          </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Booking</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Homestay</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Khách hàng</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Ngày</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Tổng tiền</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Trạng thái</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Thao tác</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <?php if ($bookings && $bookings->num_rows > 0): ?>
                <?php while($row = $bookings->fetch_assoc()): 
                  $nights = (strtotime($row['check_out']) - strtotime($row['check_in'])) / (60 * 60 * 24);
                  
                  // Xử lý an toàn các giá trị có thể null
                  $payment_status = $row['payment_status'] ?? 'unpaid';
                  $status = $row['status'] ?? 'pending';
                  $guests = $row['guests'] ?? 1;
                ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4">
                    <div class="text-sm font-semibold text-gray-900">#<?php echo $row['id'] ?? ''; ?></div>
                    <div class="text-xs text-gray-500"><?php echo isset($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : ''; ?></div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['homestay_name'] ?? 'N/A'); ?></div>
                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['location'] ?? ''); ?></div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($row['customer_name'] ?? 'N/A'); ?></div>
                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['customer_email'] ?? ''); ?></div>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <div class="text-gray-900"><?php echo date('d/m/Y', strtotime($row['check_in'])); ?> → <?php echo date('d/m/Y', strtotime($row['check_out'])); ?></div>
                    <div class="text-xs text-gray-500"><?php echo $nights; ?> đêm • <?php echo $guests; ?> khách</div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="font-bold text-gray-900"><?php echo number_format($row['total_price'] ?? 0, 0, ',', '.'); ?>đ</div>
                    <?php
                    $payment_colors = ['paid' => 'text-green-600', 'unpaid' => 'text-red-600', 'refunded' => 'text-gray-600'];
                    $payment_labels = ['paid' => 'Đã thanh toán', 'unpaid' => 'Chưa thanh toán', 'refunded' => 'Đã hoàn'];
                    ?>
                    <div class="text-xs <?php echo $payment_colors[$payment_status] ?? 'text-gray-600'; ?>">
                      <?php echo $payment_labels[$payment_status] ?? 'Chưa rõ'; ?>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <?php
                    $status_colors = [
                      'pending' => 'bg-yellow-100 text-yellow-800',
                      'confirmed' => 'bg-green-100 text-green-800',
                      'checked_in' => 'bg-blue-100 text-blue-800',
                      'checked_out' => 'bg-gray-100 text-gray-800',
                      'cancelled' => 'bg-red-100 text-red-800'
                    ];
                    $status_labels = [
                      'pending' => 'Chờ xác nhận',
                      'confirmed' => 'Đã xác nhận',
                      'checked_in' => 'Đã check-in',
                      'checked_out' => 'Đã check-out',
                      'cancelled' => 'Đã hủy'
                    ];
                    ?>
                    <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo $status_colors[$status] ?? 'bg-gray-100 text-gray-800'; ?>">
                      <?php echo $status_labels[$status] ?? 'Không rõ'; ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <a href="?action=edit&id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium mr-3">Sửa</a>
                    <a href="?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa booking này?')" class="text-red-600 hover:text-red-800 font-medium">Xóa</a>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">Không tìm thấy booking nào</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal Form -->
  <div id="formModal" class="<?php echo $edit_booking ? '' : 'hidden'; ?> fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-3xl w-full my-8">
      <div class="p-6 border-b flex items-center justify-between sticky top-0 bg-white rounded-t-2xl">
        <h2 class="text-2xl font-bold"><?php echo $edit_booking ? 'Sửa' : 'Thêm'; ?> Booking</h2>
        <button onclick="window.location.href='qly_datphong.php'" class="text-gray-400 hover:text-gray-600">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <form method="POST" class="p-6 space-y-4">
        <?php if ($edit_booking): ?>
        <input type="hidden" name="id" value="<?php echo $edit_booking['id']; ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Homestay *</label>
            <select name="homestay_id" id="homestay_select" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white" onchange="updatePrice(this)">
              <option value="">Chọn homestay</option>
              <?php 
              if ($homestays_list && $homestays_list->num_rows > 0) {
                $homestays_list->data_seek(0);
                while($h = $homestays_list->fetch_assoc()): 
              ?>
              <option value="<?php echo $h['homestay_id']; ?>" data-price="<?php echo $h['price']; ?>" <?php echo ($edit_booking && $edit_booking['homestay_id'] == $h['homestay_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($h['name']); ?> - <?php echo htmlspecialchars($h['location']); ?> (<?php echo number_format($h['price'], 0, ',', '.'); ?>đ)
              </option>
              <?php 
                endwhile;
              } else {
                echo '<option value="" disabled>Không có homestay nào</option>';
              }
              ?>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Khách hàng *</label>
            <select name="user_id" id="user_select" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white">
              <option value="">Chọn khách hàng</option>
              <?php 
              if ($users_list && $users_list->num_rows > 0) {
                $users_list->data_seek(0);
                while($u = $users_list->fetch_assoc()): 
              ?>
              <option value="<?php echo $u['user_id']; ?>" <?php echo ($edit_booking && $edit_booking['user_id'] == $u['user_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($u['fullname']); ?> (<?php echo htmlspecialchars($u['email']); ?>)
              </option>
              <?php 
                endwhile;
              } else {
                echo '<option value="" disabled>Không có khách hàng nào</option>';
              }
              ?>
            </select>
          </div>
        </div>
        
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Check-in *</label>
            <input type="date" name="check_in" id="check_in" required value="<?php echo $edit_booking ? $edit_booking['check_in'] : ''; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" onchange="calculateTotal()">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Check-out *</label>
            <input type="date" name="check_out" id="check_out" required value="<?php echo $edit_booking ? $edit_booking['check_out'] : ''; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" onchange="calculateTotal()">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Số khách *</label>
            <input type="number" name="guests" min="1" required value="<?php echo $edit_booking ? ($edit_booking['guests'] ?? 1) : '1'; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Tổng tiền (VNĐ) *</label>
          <input type="number" name="total_price" id="total_price" required value="<?php echo $edit_booking ? ($edit_booking['total_price'] ?? 0) : ''; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" min="0" step="1000">
        </div>
        
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái *</label>
            <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
              <option value="pending" <?php echo ($edit_booking && ($edit_booking['status'] ?? '') == 'pending') ? 'selected' : ''; ?>>Pending</option>
              <option value="confirmed" <?php echo ($edit_booking && ($edit_booking['status'] ?? '') == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
              <option value="checked_in" <?php echo ($edit_booking && ($edit_booking['status'] ?? '') == 'checked_in') ? 'selected' : ''; ?>>Checked In</option>
              <option value="checked_out" <?php echo ($edit_booking && ($edit_booking['status'] ?? '') == 'checked_out') ? 'selected' : ''; ?>>Checked Out</option>
              <option value="cancelled" <?php echo ($edit_booking && ($edit_booking['status'] ?? '') == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Thanh toán *</label>
            <select name="payment_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
              <option value="unpaid" <?php echo ($edit_booking && ($edit_booking['payment_status'] ?? '') == 'unpaid') ? 'selected' : ''; ?>>Unpaid</option>
              <option value="paid" <?php echo ($edit_booking && ($edit_booking['payment_status'] ?? '') == 'paid') ? 'selected' : ''; ?>>Paid</option>
              <option value="refunded" <?php echo ($edit_booking && ($edit_booking['payment_status'] ?? '') == 'refunded') ? 'selected' : ''; ?>>Refunded</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Phương thức</label>
            <select name="payment_method" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
              <option value="">Chọn phương thức</option>
              <option value="cash" <?php echo ($edit_booking && ($edit_booking['payment_method'] ?? '') == 'cash') ? 'selected' : ''; ?>>Tiền mặt</option>
              <option value="bank_transfer" <?php echo ($edit_booking && ($edit_booking['payment_method'] ?? '') == 'bank_transfer') ? 'selected' : ''; ?>>Chuyển khoản</option>
              <option value="credit_card" <?php echo ($edit_booking && ($edit_booking['payment_method'] ?? '') == 'credit_card') ? 'selected' : ''; ?>>Thẻ tín dụng</option>
              <option value="momo" <?php echo ($edit_booking && ($edit_booking['payment_method'] ?? '') == 'momo') ? 'selected' : ''; ?>>MoMo</option>
            </select>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Ghi chú</label>
          <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nhập ghi chú (nếu có)..."><?php echo $edit_booking ? htmlspecialchars($edit_booking['notes'] ?? '') : ''; ?></textarea>
        </div>
        
        <div class="flex gap-3 pt-4">
          <button type="submit" class="flex-1 py-3 bg-primary text-gray-900 font-bold rounded-lg hover:opacity-90">
            <?php echo $edit_booking ? 'Cập nhật' : 'Thêm mới'; ?>
          </button>
          <button type="button" onclick="window.location.href='qly_datphong.php'" class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg">Hủy</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function updatePrice(select) {
      const price = select.options[select.selectedIndex]?.getAttribute('data-price');
      if (price) {
        document.getElementById('total_price').value = price;
        calculateTotal();
      }
    }
    
    function calculateTotal() {
      const checkIn = document.getElementById('check_in').value;
      const checkOut = document.getElementById('check_out').value;
      const homestaySelect = document.getElementById('homestay_select');
      const pricePerNight = homestaySelect.options[homestaySelect.selectedIndex]?.getAttribute('data-price');
      
      if (checkIn && checkOut && pricePerNight) {
        const nights = (new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24);
        if (nights > 0) {
          document.getElementById('total_price').value = Math.round(nights * pricePerNight);
        }
      }
    }
    
    // Đảm bảo select hoạt động bình thường
    document.addEventListener('DOMContentLoaded', function() {
      const homestaySelect = document.getElementById('homestay_select');
      const userSelect = document.getElementById('user_select');
      
      if (homestaySelect) {
        homestaySelect.addEventListener('click', function(e) {
          e.stopPropagation();
        });
      }
      
      if (userSelect) {
        userSelect.addEventListener('click', function(e) {
          e.stopPropagation();
        });
      }
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>