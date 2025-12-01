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

$message = "";
$message_type = "";

// XỬ LÝ XÓA HOMESTAY
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM homestays WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Xóa homestay thành công!";
        $message_type = "success";
    } else {
        $message = "Lỗi khi xóa: " . $conn->error;
        $message_type = "error";
    }
    $stmt->close();
}

// XỬ LÝ THÊM/SỬA HOMESTAY
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $price = floatval($_POST['price']);
    $rating = floatval($_POST['rating']);
    $image_url = trim($_POST['image_url']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // CẬP NHẬT
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE homestays SET name=?, location=?, price=?, rating=?, image_url=?, description=?, status=? WHERE id=?");
        $stmt->bind_param("ssdisssi", $name, $location, $price, $rating, $image_url, $description, $status, $id);
        if ($stmt->execute()) {
            $message = "Cập nhật homestay thành công!";
            $message_type = "success";
        } else {
            $message = "Lỗi: " . $conn->error;
            $message_type = "error";
        }
    } else {
        // THÊM MỚI
        $stmt = $conn->prepare("INSERT INTO homestays (name, location, price, rating, image_url, description, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdisss", $name, $location, $price, $rating, $image_url, $description, $status);
        if ($stmt->execute()) {
            $message = "Thêm homestay mới thành công!";
            $message_type = "success";
        } else {
            $message = "Lỗi: " . $conn->error;
            $message_type = "error";
        }
    }
    $stmt->close();
}

// LẤY DANH SÁCH HOMESTAYS
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$where_conditions = [];
if (!empty($search)) {
    $where_conditions[] = "(name LIKE '%$search%' OR location LIKE '%$search%')";
}
if (!empty($status_filter)) {
    $where_conditions[] = "status = '$status_filter'";
}

$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
$homestays = $conn->query("SELECT * FROM homestays $where_sql ORDER BY created_at DESC");

// Lấy homestay để edit nếu có
$edit_homestay = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM homestays WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $edit_homestay = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Homestay</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
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
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Quản lý Homestay</h1>
            <p class="text-gray-500 mt-1">Quản lý danh sách homestay của bạn</p>
          </div>
          <button onclick="document.getElementById('formModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-primary text-gray-900 font-bold rounded-lg hover:opacity-90">
            <span class="material-symbols-outlined">add</span>
            Thêm Homestay
          </button>
        </div>

        <!-- Message -->
        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $message_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined"><?php echo $message_type == 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo $message; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
          <form method="GET" class="flex gap-4">
            <div class="flex-1">
              <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm kiếm theo tên hoặc địa điểm..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
              <option value="">Tất cả trạng thái</option>
              <option value="available" <?php echo $status_filter == 'available' ? 'selected' : ''; ?>>Available</option>
              <option value="booked" <?php echo $status_filter == 'booked' ? 'selected' : ''; ?>>Booked</option>
              <option value="maintenance" <?php echo $status_filter == 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800">Tìm kiếm</button>
            <?php if ($search || $status_filter): ?>
            <a href="homestays.php" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">Reset</a>
            <?php endif; ?>
          </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">ID</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Homestay</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Địa điểm</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Giá</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Rating</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Trạng thái</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Thao tác</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <?php if ($homestays && $homestays->num_rows > 0): ?>
                <?php while($row = $homestays->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 text-sm text-gray-900">#<?php echo $row['id']; ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="w-12 h-12 rounded-lg object-cover" onerror="this.src='https://via.placeholder.com/100'">
                      <span class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['name']); ?></span>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($row['location']); ?></td>
                  <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?php echo number_format($row['price'], 0, ',', '.'); ?>đ</td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-1">
                      <span class="material-symbols-outlined text-yellow-500 text-sm" style="font-variation-settings: 'FILL' 1">star</span>
                      <span class="text-sm font-medium"><?php echo $row['rating']; ?></span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <?php
                    $status_colors = [
                      'available' => 'bg-green-100 text-green-800',
                      'booked' => 'bg-blue-100 text-blue-800',
                      'maintenance' => 'bg-gray-100 text-gray-800'
                    ];
                    $color = $status_colors[$row['status']] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo $color; ?>"><?php echo ucfirst($row['status']); ?></span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <a href="?action=edit&id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium mr-3">Sửa</a>
                    <a href="?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa homestay này?')" class="text-red-600 hover:text-red-800 font-medium">Xóa</a>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="px-6 py-12 text-center text-gray-500">Không tìm thấy homestay nào</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal Form -->
  <div id="formModal" class="<?php echo $edit_homestay ? '' : 'hidden'; ?> fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6 border-b flex items-center justify-between sticky top-0 bg-white">
        <h2 class="text-2xl font-bold"><?php echo $edit_homestay ? 'Sửa' : 'Thêm'; ?> Homestay</h2>
        <button onclick="window.location.href='homestays.php'" class="text-gray-400 hover:text-gray-600">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <form method="POST" class="p-6 space-y-4">
        <?php if ($edit_homestay): ?>
        <input type="hidden" name="id" value="<?php echo $edit_homestay['id']; ?>">
        <?php endif; ?>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Tên Homestay</label>
          <input type="text" name="name" required value="<?php echo $edit_homestay ? htmlspecialchars($edit_homestay['name']) : ''; ?>" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Địa điểm</label>
          <input type="text" name="location" required value="<?php echo $edit_homestay ? htmlspecialchars($edit_homestay['location']) : ''; ?>" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Giá (VNĐ/đêm)</label>
            <input type="number" name="price" required value="<?php echo $edit_homestay ? $edit_homestay['price'] : ''; ?>" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Rating (0-5)</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" value="<?php echo $edit_homestay ? $edit_homestay['rating'] : '0'; ?>" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary">
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">URL Hình ảnh</label>
          <input type="url" name="image_url" value="<?php echo $edit_homestay ? htmlspecialchars($edit_homestay['image_url']) : ''; ?>" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
          <textarea name="description" rows="3" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary"><?php echo $edit_homestay ? htmlspecialchars($edit_homestay['description']) : ''; ?></textarea>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái</label>
          <select name="status" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary">
            <option value="available" <?php echo ($edit_homestay && $edit_homestay['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
            <option value="booked" <?php echo ($edit_homestay && $edit_homestay['status'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
            <option value="maintenance" <?php echo ($edit_homestay && $edit_homestay['status'] == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
          </select>
        </div>
        
        <div class="flex gap-3 pt-4">
          <button type="submit" class="flex-1 py-3 bg-primary text-gray-900 font-bold rounded-lg hover:opacity-90">
            <?php echo $edit_homestay ? 'Cập nhật' : 'Thêm mới'; ?>
          </button>
          <button type="button" onclick="window.location.href='homestays.php'" class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
            Hủy
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>