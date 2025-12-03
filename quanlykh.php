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

// 1. XỬ LÝ XÓA KHÁCH HÀNG (SOFT DELETE)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    // Thay vì DELETE, ta dùng UPDATE deleted_at
    $stmt = $conn->prepare("UPDATE users SET deleted_at = NOW() WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $message = "Đã chuyển khách hàng vào thùng rác!";
        $message_type = "success";
    } else {
        $message = "Lỗi: " . $conn->error;
        $message_type = "error";
    }
    $stmt->close();
}

// 2. XỬ LÝ KHÔI PHỤC KHÁCH HÀNG
if (isset($_GET['action']) && $_GET['action'] == 'restore' && isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $stmt = $conn->prepare("UPDATE users SET deleted_at = NULL WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $message = "Đã khôi phục khách hàng thành công!";
        $message_type = "success";
    }
    $stmt->close();
}

// 3. XỬ LÝ CẬP NHẬT TRẠNG THÁI (Chỉ áp dụng cho user chưa xóa)
if (isset($_GET['action']) && $_GET['action'] == 'toggle_status' && isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $conn->query("UPDATE users SET status = IF(status='active', 'inactive', 'active') WHERE user_id = $user_id");
    $message = "Đã cập nhật trạng thái!";
    $message_type = "success";
}

// 4. TÌM KIẾM VÀ LỌC
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$show_deleted = isset($_GET['show_deleted']) && $_GET['show_deleted'] == '1';

$where = [];

// Logic lọc theo trạng thái xóa
if ($show_deleted) {
    $where[] = "deleted_at IS NOT NULL";
} else {
    $where[] = "deleted_at IS NULL";
}

if ($search) {
    $search_safe = $conn->real_escape_string($search);
    $where[] = "(fullname LIKE '%$search_safe%' OR email LIKE '%$search_safe%' OR username LIKE '%$search_safe%')";
}
if ($role_filter) $where[] = "role = '$role_filter'";
if ($status_filter) $where[] = "status = '$status_filter'";

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Truy vấn danh sách
$query = "SELECT * FROM users $where_sql ORDER BY created_at DESC";
$customers = $conn->query($query);

// 5. THỐNG KÊ (Chỉ đếm các user chưa xóa)
$total = $conn->query("SELECT COUNT(*) as c FROM users WHERE deleted_at IS NULL")->fetch_assoc()['c'];
$active = $conn->query("SELECT COUNT(*) as c FROM users WHERE status='active' AND deleted_at IS NULL")->fetch_assoc()['c'];
$vip = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='vip' AND deleted_at IS NULL")->fetch_assoc()['c'];
$deleted_count = $conn->query("SELECT COUNT(*) as c FROM users WHERE deleted_at IS NOT NULL")->fetch_assoc()['c'];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Khách hàng</title>
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
            <h1 class="text-3xl font-bold text-gray-900">Quản lý Khách hàng</h1>
            <p class="text-gray-500 mt-1">Quản lý thông tin khách hàng và tài khoản</p>
          </div>

          <?php if (!$show_deleted): ?>
          <a href="?show_deleted=1" class="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <span class="material-symbols-outlined">delete</span>
            Thùng rác (<?php echo $deleted_count; ?>)
          </a>
          <?php else: ?>
          <a href="quanlykh.php" class="flex items-center gap-2 px-4 py-2 bg-primary text-gray-900 font-bold rounded-lg hover:opacity-90">
            <span class="material-symbols-outlined">arrow_back</span>
            Quay lại danh sách
          </a>
          <?php endif; ?>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $message_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined"><?php echo $message_type == 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo $message; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!$show_deleted): ?>
        <div class="grid grid-cols-3 gap-6 mb-6">
          <div class="bg-white rounded-xl p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 font-medium">Tổng khách hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $total; ?></p>
              </div>
              <span class="material-symbols-outlined text-blue-500 text-5xl">group</span>
            </div>
          </div>
          
          <div class="bg-white rounded-xl p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 font-medium">Đang hoạt động</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $active; ?></p>
              </div>
              <span class="material-symbols-outlined text-green-500 text-5xl">check_circle</span>
            </div>
          </div>
          
          <div class="bg-white rounded-xl p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 font-medium">VIP Members</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $vip; ?></p>
              </div>
              <span class="material-symbols-outlined text-yellow-500 text-5xl">workspace_premium</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
          <form method="GET" class="flex gap-4">
            <?php if($show_deleted) echo '<input type="hidden" name="show_deleted" value="1">'; ?>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm kiếm theo tên, email, username..." class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
            <select name="role" class="px-4 py-2 border rounded-lg">
              <option value="">Tất cả loại</option>
              <option value="customer" <?php echo $role_filter == 'customer' ? 'selected' : ''; ?>>Customer</option>
              <option value="vip" <?php echo $role_filter == 'vip' ? 'selected' : ''; ?>>VIP</option>
            </select>
            <select name="status" class="px-4 py-2 border rounded-lg">
              <option value="">Tất cả trạng thái</option>
              <option value="active" <?php echo $status_filter == 'active' ? 'selected' : ''; ?>>Active</option>
              <option value="inactive" <?php echo $status_filter == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
              <option value="banned" <?php echo $status_filter == 'banned' ? 'selected' : ''; ?>>Banned</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800">Tìm</button>
            <?php if ($search || $role_filter || $status_filter): ?>
            <a href="quanlykh.php<?php echo $show_deleted ? '?show_deleted=1' : ''; ?>" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">Reset</a>
            <?php endif; ?>
          </form>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Khách hàng</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Email/SĐT</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Loại</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Trạng thái</th>
                <?php if ($show_deleted): ?>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Ngày xóa</th>
                <?php else: ?>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Ngày tạo</th>
                <?php endif; ?>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Thao tác</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <?php if ($customers && $customers->num_rows > 0): ?>
                <?php while($row = $customers->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['fullname']); ?></div>
                        <div class="text-xs text-gray-500">@<?php echo htmlspecialchars($row['username']); ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-600"><?php echo htmlspecialchars($row['email']); ?></div>
                    <div class="text-xs text-gray-500"><?php echo $row['phone'] ?: 'No phone'; ?></div>
                  </td>
                  <td class="px-6 py-4">
                    <?php if ($row['role'] == 'vip'): ?>
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">⭐ VIP</span>
                    <?php else: ?>
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">Customer</span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4">
                    <?php
                    $colors = ['active' => 'bg-green-100 text-green-800', 'inactive' => 'bg-gray-100 text-gray-800', 'banned' => 'bg-red-100 text-red-800'];
                    $status_label = ucfirst($row['status']);
                    $status_class = isset($colors[$row['status']]) ? $colors[$row['status']] : 'bg-gray-100';
                    echo '<span class="px-3 py-1 text-xs font-bold rounded-full ' . $status_class . '">' . $status_label . '</span>';
                    ?>
                  </td>
                  
                  <?php if ($show_deleted): ?>
                  <td class="px-6 py-4 text-sm text-gray-500">
                    <?php echo date('d/m/Y H:i', strtotime($row['deleted_at'])); ?>
                  </td>
                  <?php else: ?>
                  <td class="px-6 py-4 text-sm text-gray-600">
                    <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
                  </td>
                  <?php endif; ?>

                  <td class="px-6 py-4 text-right">
                    <?php if ($show_deleted): ?>
                      <a href="?action=restore&user_id=<?php echo $row['user_id']; ?>" 
                         onclick="return confirm('Khôi phục khách hàng này?')"
                         class="text-green-600 hover:text-green-800 font-medium inline-flex items-center gap-1">
                         <span class="material-symbols-outlined text-sm">restore</span> Khôi phục
                      </a>
                    <?php else: ?>
                      <a href="?action=toggle_status&user_id=<?php echo $row['user_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium mr-3 text-sm">Đổi trạng thái</a>
                      <a href="?action=delete&user_id=<?php echo $row['user_id']; ?>" 
                         onclick="return confirm('Bạn có chắc muốn chuyển khách hàng này vào thùng rác?')" 
                         class="text-red-600 hover:text-red-800 font-medium text-sm inline-flex items-center gap-1">
                         <span class="material-symbols-outlined text-sm">delete</span> Xóa
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <?php echo $show_deleted ? 'Thùng rác trống' : 'Không tìm thấy khách hàng nào'; ?>
                    </td>
                </tr>
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