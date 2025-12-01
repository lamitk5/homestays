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

// XỬ LÝ XÓA KHÁCH HÀNG
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $message = "Xóa khách hàng thành công!";
        $message_type = "success";
    }
    $stmt->close();
}

// XỬ LÝ CẬP NHẬT TRẠNG THÁI
if (isset($_GET['action']) && $_GET['action'] == 'toggle_status' && isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $conn->query("UPDATE users SET status = IF(status='active', 'inactive', 'active') WHERE user_id = $user_id");
    $message = "Đã cập nhật trạng thái!";
    $message_type = "success";
}

// TÌM KIẾM VÀ LỌC
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$where = [];
if ($search) $where[] = "(fullname LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%')";
if ($role_filter) $where[] = "role = '$role_filter'";
if ($status_filter) $where[] = "status = '$status_filter'";

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";
$customers = $conn->query("SELECT * FROM users $where_sql ORDER BY created_at DESC");

// Thống kê
$total = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$active = $conn->query("SELECT COUNT(*) as c FROM users WHERE status='active'")->fetch_assoc()['c'];
$vip = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='vip'")->fetch_assoc()['c'];
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
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Quản lý Khách hàng</h1>
          <p class="text-gray-500 mt-1">Quản lý thông tin khách hàng và tài khoản</p>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $message_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
          <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <!-- Stats -->
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

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
          <form method="GET" class="flex gap-4">
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
            <a href="customers.php" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">Reset</a>
            <?php endif; ?>
          </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Khách hàng</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Email</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Điện thoại</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Loại</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Trạng thái</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Ngày tạo</th>
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
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($row['email']); ?></td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo $row['phone'] ?: '-'; ?></td>
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
                    echo '<span class="px-3 py-1 text-xs font-bold rounded-full ' . $colors[$row['status']] . '">' . ucfirst($row['status']) . '</span>';
                    ?>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                  <td class="px-6 py-4 text-right">
                    <a href="?action=toggle_status&id=<?php echo $row['user_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium mr-3">Toggle Status</a>
                    <a href="?action=delete&id=<?php echo $row['user_id']; ?>" onclick="return confirm('Xóa khách hàng này?')" class="text-red-600 hover:text-red-800 font-medium">Xóa</a>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">Không tìm thấy khách hàng</td></tr>
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