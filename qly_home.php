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

// --- XỬ LÝ CÁC HÀNH ĐỘNG (XÓA MỀM, KHÔI PHỤC, XÓA VĨNH VIỄN) ---
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    
    // 1. XÓA MỀM (Soft Delete) - Đưa vào thùng rác
    if ($_GET['action'] == 'delete') {
        $stmt = $conn->prepare("UPDATE homestays SET deleted_at = NOW() WHERE homestay_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Đã chuyển homestay vào thùng rác!";
            $message_type = "success";
        } else {
            $message = "Lỗi: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }
    
    // 2. KHÔI PHỤC (Restore) - Lấy lại từ thùng rác
    elseif ($_GET['action'] == 'restore') {
        $stmt = $conn->prepare("UPDATE homestays SET deleted_at = NULL WHERE homestay_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Khôi phục homestay thành công!";
            $message_type = "success";
        } else {
            $message = "Lỗi: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }

    // 3. XÓA VĨNH VIỄN (Hard Delete) - Xóa hẳn khỏi database
    elseif ($_GET['action'] == 'force_delete') {
        $stmt = $conn->prepare("DELETE FROM homestays WHERE homestay_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Đã xóa vĩnh viễn homestay!";
            $message_type = "success";
            // TODO: Code xóa ảnh vật lý nếu cần
        } else {
            $message = "Lỗi: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// --- XÁC ĐỊNH CHẾ ĐỘ XEM (DANH SÁCH HAY THÙNG RÁC) ---
$view_mode = isset($_GET['view']) && $_GET['view'] == 'trash' ? 'trash' : 'list';
$is_trash_view = ($view_mode == 'trash');

// 🎯 LẤY DANH SÁCH HOMESTAYS
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_conditions = [];

// 💡 LOGIC LỌC DỮ LIỆU QUAN TRỌNG
if ($is_trash_view) {
    // Nếu xem thùng rác: Lấy bản ghi ĐÃ CÓ ngày xóa
    $where_conditions[] = "h.deleted_at IS NOT NULL"; 
} else {
    // Nếu xem danh sách: Lấy bản ghi CHƯA CÓ ngày xóa (NULL)
    $where_conditions[] = "h.deleted_at IS NULL"; 
}

if (!empty($search)) {
    $search_safe = $conn->real_escape_string($search);
    $where_conditions[] = "(h.name LIKE '%$search_safe%' OR h.district LIKE '%$search_safe%')";
}

$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
$current_date = date("Y-m-d");

$sql_query = "
    SELECT 
        h.*, 
        i.image_path AS main_image_path,
        b.check_out AS next_available,
        u.fullname AS booked_by_name
    FROM homestays h
    LEFT JOIN images i ON h.homestay_id = i.homestay_id AND i.is_primary = 1
    LEFT JOIN (
        SELECT homestay_id, check_out, user_id 
        FROM bookings 
        WHERE check_out > '$current_date' 
        AND status = 'confirmed'
        ORDER BY check_out DESC 
        LIMIT 1
    ) AS b ON h.homestay_id = b.homestay_id
    LEFT JOIN users u ON b.user_id = u.user_id
    $where_sql 
    ORDER BY h.homestay_id DESC
";
$homestays = $conn->query($sql_query);
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
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">
                <?php echo $is_trash_view ? 'Thùng rác Homestay' : 'Quản lý Homestay'; ?>
            </h1>
            <p class="text-gray-500 mt-1">
                <?php echo $is_trash_view ? 'Danh sách homestay đã xóa' : 'Quản lý danh sách homestay của bạn'; ?>
            </p>
          </div>
          
          <div class="flex gap-3">
            <?php if ($is_trash_view): ?>
                <a href="qly_home.php" class="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Quay lại danh sách
                </a>
            <?php else: ?>
                <a href="?view=trash" class="flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 font-bold rounded-lg hover:bg-red-200">
                    <span class="material-symbols-outlined">delete</span>
                    Thùng rác
                </a>
                <a href="them_homestay.php" class="flex items-center gap-2 px-4 py-2 bg-primary text-gray-900 font-bold rounded-lg hover:opacity-90">
                    <span class="material-symbols-outlined">add</span>
                    Thêm Homestay
                </a>
            <?php endif; ?>
          </div>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $message_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined"><?php echo $message_type == 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo $message; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
          <form method="GET" class="flex gap-4">
            <?php if ($is_trash_view): ?>
                <input type="hidden" name="view" value="trash">
            <?php endif; ?>

            <div class="flex-1">
              <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm kiếm theo tên hoặc địa điểm..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800">Tìm kiếm</button>
            <?php if ($search): ?>
                <a href="qly_home.php<?php echo $is_trash_view ? '?view=trash' : ''; ?>" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">Reset</a>
            <?php endif; ?>
          </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">ID</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Homestay</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Quận/Huyện</th> 
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Giá Ngày thường</th> 
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">
                    <?php echo $is_trash_view ? 'Ngày xóa' : 'Rating'; ?>
                </th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Thao tác</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <?php if ($homestays && $homestays->num_rows > 0): ?>
                <?php while($row = $homestays->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 text-sm text-gray-900">#<?php echo $row['homestay_id']; ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-start gap-3">
                      <?php 
                        $image_path = "https://via.placeholder.com/100";
                        if (!empty($row['main_image_path'])) {
                            $image_path = "uploads/" . htmlspecialchars($row['main_image_path']);
                        }
                      ?>
                      <img src="<?php echo $image_path; ?>" class="w-12 h-12 rounded-lg object-cover" onerror="this.src='https://via.placeholder.com/100'">
                      
                      <div class="flex flex-col">
                        <span class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['name']); ?></span>
                        
                        <?php if (!$is_trash_view): // Chỉ hiện trạng thái đặt phòng ở view thường ?>
                            <?php if (!empty($row['next_available'])): ?>
                            <div class="mt-1 flex items-center gap-1 text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full border border-red-200 w-fit">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1">event_busy</span>
                                ĐÃ ĐƯỢC ĐẶT
                            </div>
                            <?php else: ?>
                            <div class="mt-1 flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-200 w-fit">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1">event_available</span>
                                Sẵn sàng
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($row['district']); ?></td>
                  <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?php echo number_format($row['price_weekday'], 0, ',', '.'); ?>đ</td>
                  <td class="px-6 py-4">
                    <?php if ($is_trash_view): ?>
                        <span class="text-sm text-red-500 font-medium">
                            <?php echo date('d/m/Y H:i', strtotime($row['deleted_at'])); ?>
                        </span>
                    <?php else: ?>
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-yellow-500 text-sm" style="font-variation-settings: 'FILL' 1">star</span>
                            <span class="text-sm font-medium"><?php echo $row['rating']; ?></span>
                        </div>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <?php if ($is_trash_view): ?>
                        <a href="?action=restore&id=<?php echo $row['homestay_id']; ?>&view=trash" class="text-green-600 hover:text-green-800 font-bold mr-3" title="Khôi phục">
                            Khôi phục
                        </a>
                        <a href="?action=force_delete&id=<?php echo $row['homestay_id']; ?>&view=trash" onclick="return confirm('CẢNH BÁO: Hành động này sẽ xóa homestay VĨNH VIỄN và không thể khôi phục! Bạn có chắc chắn?')" class="text-red-600 hover:text-red-800 font-medium" title="Xóa vĩnh viễn">
                            Xóa hẳn
                        </a>
                    <?php else: ?>
                        <a href="sua_homestay.php?id=<?php echo $row['homestay_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium mr-3">Sửa</a>
                        <a href="?action=delete&id=<?php echo $row['homestay_id']; ?>" onclick="return confirm('Bạn có chắc muốn chuyển homestay này vào thùng rác?')" class="text-red-600 hover:text-red-800 font-medium">Xóa</a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                      <?php echo $is_trash_view ? 'Thùng rác trống' : 'Không tìm thấy homestay nào'; ?>
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