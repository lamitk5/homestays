<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: dangnhapqtv.php");
    exit();
}

$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';
$admin_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$message = "";
$message_type = "";

// Xử lý cập nhật thông tin cá nhân
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    
    // Cập nhật trong session
    $_SESSION['fullname'] = $fullname;
    $_SESSION['email'] = $email;
    
    $message = "Cập nhật thông tin thành công!";
    $message_type = "success";
    $admin_name = $fullname;
    $admin_email = $email;
}

// Xử lý đổi mật khẩu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Kiểm tra mật khẩu mới khớp nhau
    if ($new_password !== $confirm_password) {
        $message = "Mật khẩu mới không khớp!";
        $message_type = "error";
    } else if (strlen($new_password) < 6) {
        $message = "Mật khẩu phải có ít nhất 6 ký tự!";
        $message_type = "error";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $message = "Đổi mật khẩu thành công!";
        $message_type = "success";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cài đặt - Homestay Pro</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .tab-active { border-bottom: 3px solid #13ecc8; color: #111; }
  </style>
  <script>
    tailwind.config = { theme: { extend: { colors: { primary: "#13ecc8" } } } }
  </script>
</head>
<body class="bg-gray-50">
  <div class="flex h-screen">
    <?php include 'sidebar.php'; ?>
    
    <main class="flex-1 overflow-y-auto p-8">
      <div class="max-w-5xl mx-auto">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Cài đặt</h1>
          <p class="text-gray-500 mt-1">Quản lý thông tin tài khoản và hệ thống</p>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $message_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined"><?php echo $message_type == 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo $message; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="bg-white rounded-xl shadow-sm mb-6">
          <div class="flex border-b px-6">
            <button onclick="showTab('profile')" id="tab-profile" class="px-6 py-4 font-semibold text-gray-500 hover:text-gray-900 tab-active">
              Thông tin cá nhân
            </button>
            <button onclick="showTab('security')" id="tab-security" class="px-6 py-4 font-semibold text-gray-500 hover:text-gray-900">
              Bảo mật
            </button>
            <button onclick="showTab('system')" id="tab-system" class="px-6 py-4 font-semibold text-gray-500 hover:text-gray-900">
              Hệ thống
            </button>
          </div>
        </div>

        <!-- Tab Thông tin cá nhân -->
        <div id="content-profile" class="tab-content">
          <div class="bg-white rounded-xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Thông tin cá nhân</h2>
            
            <form method="POST" class="space-y-6">
              <div class="flex items-center gap-6 mb-8">
                <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center text-4xl font-bold text-gray-900">
                  <?php echo strtoupper(substr($admin_name, 0, 1)); ?>
                </div>
                <div>
                  <button type="button" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-gray-700">
                    Đổi ảnh đại diện
                  </button>
                  <p class="text-sm text-gray-500 mt-2">JPG, PNG tối đa 2MB</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Họ và tên</label>
                  <input type="text" name="fullname" value="<?php echo htmlspecialchars($admin_name); ?>" required 
                         class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
                
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                  <input type="email" name="email" value="<?php echo htmlspecialchars($admin_email); ?>" required 
                         class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Số điện thoại</label>
                <input type="tel" name="phone" value="" placeholder="0912 345 678" 
                       class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Vai trò</label>
                <input type="text" value="Quản trị viên (Admin)" disabled 
                       class="w-full border rounded-lg p-3 bg-gray-50 text-gray-500">
              </div>

              <div class="flex gap-4 pt-4">
                <button type="submit" name="update_profile" class="px-6 py-3 bg-primary hover:opacity-90 text-gray-900 font-bold rounded-lg">
                  Lưu thay đổi
                </button>
                <button type="button" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg">
                  Hủy bỏ
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Tab Bảo mật -->
        <div id="content-security" class="tab-content hidden">
          <div class="bg-white rounded-xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Đổi mật khẩu</h2>
            
            <form method="POST" class="space-y-6 max-w-xl">
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Mật khẩu hiện tại</label>
                <input type="password" name="current_password" required 
                       class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Mật khẩu mới</label>
                <input type="password" name="new_password" required 
                       class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                <p class="text-sm text-gray-500 mt-1">Tối thiểu 6 ký tự</p>
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                <input type="password" name="confirm_password" required 
                       class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
              </div>

              <div class="flex gap-4 pt-4">
                <button type="submit" name="change_password" class="px-6 py-3 bg-primary hover:opacity-90 text-gray-900 font-bold rounded-lg">
                  Đổi mật khẩu
                </button>
              </div>
            </form>

            <div class="border-t mt-8 pt-8">
              <h3 class="font-bold text-gray-900 mb-4">Bảo mật nâng cao</h3>
              <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border rounded-lg">
                  <div>
                    <p class="font-semibold text-gray-900">Xác thực 2 bước</p>
                    <p class="text-sm text-gray-500">Thêm lớp bảo mật cho tài khoản</p>
                  </div>
                  <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                    Bật
                  </button>
                </div>

                <div class="flex items-center justify-between p-4 border rounded-lg">
                  <div>
                    <p class="font-semibold text-gray-900">Lịch sử đăng nhập</p>
                    <p class="text-sm text-gray-500">Xem các thiết bị đã đăng nhập</p>
                  </div>
                  <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                    Xem
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab Hệ thống -->
        <div id="content-system" class="tab-content hidden">
          <div class="bg-white rounded-xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Cài đặt hệ thống</h2>
            
            <div class="space-y-6">
              <div class="flex items-center justify-between p-4 border rounded-lg">
                <div>
                  <p class="font-semibold text-gray-900">Chế độ bảo trì</p>
                  <p class="text-sm text-gray-500">Tạm khóa website cho khách hàng</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
              </div>

              <div class="flex items-center justify-between p-4 border rounded-lg">
                <div>
                  <p class="font-semibold text-gray-900">Email thông báo</p>
                  <p class="text-sm text-gray-500">Nhận email khi có đặt phòng mới</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
              </div>

              <div class="flex items-center justify-between p-4 border rounded-lg">
                <div>
                  <p class="font-semibold text-gray-900">Tự động backup</p>
                  <p class="text-sm text-gray-500">Sao lưu dữ liệu hàng ngày</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
              </div>

              <div class="border-t pt-6 mt-6">
                <h3 class="font-bold text-gray-900 mb-4">Dữ liệu & Lưu trữ</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                    <span class="material-symbols-outlined text-blue-500 mb-2">backup</span>
                    <p class="font-semibold text-gray-900">Sao lưu dữ liệu</p>
                    <p class="text-sm text-gray-500">Tạo bản backup thủ công</p>
                  </button>

                  <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                    <span class="material-symbols-outlined text-green-500 mb-2">cloud_upload</span>
                    <p class="font-semibold text-gray-900">Khôi phục dữ liệu</p>
                    <p class="text-sm text-gray-500">Import từ file backup</p>
                  </button>

                  <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                    <span class="material-symbols-outlined text-orange-500 mb-2">delete_sweep</span>
                    <p class="font-semibold text-gray-900">Xóa dữ liệu cũ</p>
                    <p class="text-sm text-gray-500">Dọn dẹp logs và cache</p>
                  </button>

                  <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                    <span class="material-symbols-outlined text-purple-500 mb-2">download</span>
                    <p class="font-semibold text-gray-900">Export dữ liệu</p>
                    <p class="text-sm text-gray-500">Tải xuống Excel/CSV</p>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    function showTab(tabName) {
      // Ẩn tất cả tab content
      document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
      
      // Xóa active class khỏi tất cả tabs
      document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.remove('tab-active'));
      
      // Hiển thị tab được chọn
      document.getElementById('content-' + tabName).classList.remove('hidden');
      document.getElementById('tab-' + tabName).classList.add('tab-active');
    }
  </script>

</body>
</html>