<?php
session_start();

// 1. KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 2. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: dangnhap.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; // Lấy quyền hạn (admin/customer)
$message = "";
$msg_type = ""; 
$user = null; // Biến chứa thông tin user

// --- XỬ LÝ DỮ LIỆU NGƯỜI DÙNG DỰA TRÊN ROLE ---
if ($role === 'admin') {
    // A. NẾU LÀ ADMIN (Lấy từ qtrivien)
    $stmt = $conn->prepare("SELECT id, fullname, email, username FROM qtrivien WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $data = $res->fetch_assoc();
        // Tạo dữ liệu giả lập cho khớp với giao diện khách hàng
        $user = [
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'phone' => 'Hotline Admin', // Admin không có sđt cá nhân trong bảng này
            'address' => 'Văn phòng quản trị', // Admin không có địa chỉ cá nhân
            'password' => '' // Không cần lấy pass
        ];
    }
} else {
    // B. NẾU LÀ KHÁCH HÀNG (Lấy từ users)
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
}

// Nếu không lấy được user (tránh lỗi null)
if (!$user) {
    echo "Không tìm thấy thông tin tài khoản.";
    exit();
}

// =========================================================
// CÁC CHỨC NĂNG DƯỚI ĐÂY CHỈ DÀNH CHO KHÁCH HÀNG (CUSTOMER)
// Admin sẽ bị bỏ qua để tránh lỗi logic database
// =========================================================

if ($role !== 'admin') {

    // 3. XỬ LÝ HỦY ĐƠN ĐẶT PHÒNG
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_booking_id'])) {
        $booking_id = intval($_POST['cancel_booking_id']);
        
        $check_stmt = $conn->prepare("SELECT check_in, status FROM bookings WHERE id = ? AND user_id = ?");
        $check_stmt->bind_param("ii", $booking_id, $user_id);
        $check_stmt->execute();
        $booking_data = $check_stmt->get_result()->fetch_assoc();
        $check_stmt->close();

        if ($booking_data) {
            $check_in_date = strtotime($booking_data['check_in']);
            $today = time();

            if ($booking_data['status'] !== 'cancelled') {
                if ($check_in_date > $today) {
                    $cancel_stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
                    $cancel_stmt->bind_param("i", $booking_id);
                    if ($cancel_stmt->execute()) {
                        $message = "Đã hủy đơn đặt phòng thành công!";
                        $msg_type = "success";
                    }
                    $cancel_stmt->close();
                } else {
                    $message = "Không thể hủy đơn khi đã quá hạn hoặc sát ngày check-in.";
                    $msg_type = "error";
                }
            } else {
                $message = "Đơn này đã được hủy trước đó.";
                $msg_type = "error";
            }
        } else {
            $message = "Không tìm thấy đơn đặt phòng.";
            $msg_type = "error";
        }
    }

    // 4. XỬ LÝ CẬP NHẬT THÔNG TIN CÁ NHÂN
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
        $fullname = trim($_POST['fullname']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        
        if (empty($fullname) || empty($phone)) {
            $message = "Vui lòng điền đầy đủ Tên và Số điện thoại.";
            $msg_type = "error";
        } else {
            $stmt = $conn->prepare("UPDATE users SET fullname = ?, phone = ?, address = ? WHERE user_id = ?");
            $stmt->bind_param("sssi", $fullname, $phone, $address, $user_id);
            
            if ($stmt->execute()) {
                $message = "Cập nhật thông tin thành công!";
                $msg_type = "success";
                $_SESSION['fullname'] = $fullname; 
                // Cập nhật lại biến user để hiển thị ngay
                $user['fullname'] = $fullname;
                $user['phone'] = $phone;
                $user['address'] = $address;
            } else {
                $message = "Có lỗi xảy ra, vui lòng thử lại.";
                $msg_type = "error";
            }
            $stmt->close();
        }
    }

    // 5. XỬ LÝ ĐỔI MẬT KHẨU
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
        $current_pass = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        // Lấy pass hiện tại trong DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user_data = $res->fetch_assoc();
        $stmt->close();

        if (!password_verify($current_pass, $user_data['password'])) { 
             $message = "Mật khẩu hiện tại không đúng.";
             $msg_type = "error";
        } elseif ($new_pass !== $confirm_pass) {
             $message = "Mật khẩu mới không khớp.";
             $msg_type = "error";
        } else {
            // Hash password mới
            $new_pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_pass_hash, $user_id);
            if ($stmt->execute()) {
                $message = "Đổi mật khẩu thành công!";
                $msg_type = "success";
            }
            $stmt->close();
        }
    }
} else {
    // Nếu Admin post form lên thì báo lỗi (đề phòng)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $message = "Admin không thể thực hiện thao tác này tại đây.";
        $msg_type = "error";
    }
}

// 6. LẤY LỊCH SỬ ĐẶT PHÒNG (Chỉ cho Customer)
$history = null;
if ($role !== 'admin') {
    $sql_history = "
        SELECT b.*, h.name as homestay_name, h.district 
        FROM bookings b 
        JOIN homestays h ON b.homestay_id = h.homestay_id 
        WHERE b.user_id = ? 
        ORDER BY b.created_at DESC
    ";
    $stmt = $conn->prepare($sql_history);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $history = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Hồ sơ <?php echo ($role === 'admin') ? 'Admin' : 'Khách hàng'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { vertical-align: middle; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="trang_chu.php" class="flex items-center gap-2 hover:opacity-80 transition">
                <span class="material-symbols-outlined text-[#13ecc8] text-4xl">other_houses</span>
                <span class="font-extrabold text-2xl tracking-tight text-gray-900">Homestay<span class="text-[#13ecc8]">App</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="trang_chu.php" class="text-sm font-bold text-gray-600 hover:text-[#13ecc8]">Trang chủ</a>
                
                <?php if ($role === 'admin'): ?>
                    <a href="dashboard.php" class="text-sm font-bold text-gray-900 bg-gray-200 px-3 py-1 rounded-full hover:bg-gray-300">Về Dashboard</a>
                <?php endif; ?>

                <a href="logout.php" class="text-sm text-red-500 font-bold hover:underline">Đăng xuất</a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-10 w-full flex-grow">
        
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg flex items-center gap-2 <?php echo $msg_type == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <span class="material-symbols-outlined"><?php echo $msg_type == 'success' ? 'check_circle' : 'error'; ?></span>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
            <div class="mb-6 bg-blue-50 text-blue-700 p-4 rounded-lg border border-blue-200 flex items-center gap-2">
                <span class="material-symbols-outlined">info</span>
                <span>Bạn đang xem giao diện hồ sơ với tư cách <b>Admin</b>. Một số chức năng (sửa hồ sơ, đặt phòng) sẽ bị vô hiệu hóa tại đây.</span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm p-6 text-center border border-gray-100 sticky top-24">
                    <div class="w-24 h-24 rounded-full bg-gray-200 mx-auto mb-4 flex items-center justify-center text-4xl font-bold text-gray-400 border-2 <?php echo $role==='admin' ? 'border-red-500' : 'border-gray-100'; ?>">
                        <?php echo strtoupper(substr($user['fullname'], 0, 1)); ?>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($user['fullname']); ?></h2>
                    <p class="text-gray-500 text-sm mb-4"><?php echo htmlspecialchars($user['email']); ?></p>
                    
                    <?php if ($role === 'admin'): ?>
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-full">ADMINISTRATOR</span>
                    <?php else: ?>
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full">MEMBER</span>
                    <?php endif; ?>

                    <div class="border-t border-gray-100 pt-4 flex flex-col gap-2 text-left mt-4">
                        <button onclick="switchTab('profile')" class="tab-btn active p-3 rounded-lg font-bold flex items-center gap-3 hover:bg-gray-50 transition text-[#13ecc8] bg-gray-50">
                            <span class="material-symbols-outlined">person</span> Hồ sơ cá nhân
                        </button>
                        
                        <?php if ($role !== 'admin'): ?>
                        <button onclick="switchTab('history')" class="tab-btn p-3 rounded-lg font-medium text-gray-600 flex items-center gap-3 hover:bg-gray-50 transition">
                            <span class="material-symbols-outlined">history</span> Lịch sử đặt phòng
                        </button>
                        <button onclick="switchTab('password')" class="tab-btn p-3 rounded-lg font-medium text-gray-600 flex items-center gap-3 hover:bg-gray-50 transition">
                            <span class="material-symbols-outlined">lock</span> Đổi mật khẩu
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                
                <div id="tab-profile" class="tab-content bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#13ecc8]">edit_square</span> Thông tin tài khoản
                    </h3>
                    <form action="" method="POST" class="space-y-6 max-w-xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Họ và tên</label>
                                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#13ecc8] focus:ring-1 focus:ring-[#13ecc8] <?php echo $role === 'admin' ? 'bg-gray-100 cursor-not-allowed' : ''; ?>" required <?php echo $role === 'admin' ? 'readonly' : ''; ?>>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Số điện thoại</label>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#13ecc8] focus:ring-1 focus:ring-[#13ecc8] <?php echo $role === 'admin' ? 'bg-gray-100 cursor-not-allowed' : ''; ?>" required <?php echo $role === 'admin' ? 'readonly' : ''; ?>>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email (Không thể thay đổi)</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Địa chỉ</label>
                            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" placeholder="Nhập địa chỉ của bạn" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#13ecc8] focus:ring-1 focus:ring-[#13ecc8] <?php echo $role === 'admin' ? 'bg-gray-100 cursor-not-allowed' : ''; ?>" <?php echo $role === 'admin' ? 'readonly' : ''; ?>>
                        </div>
                        
                        <?php if ($role !== 'admin'): ?>
                            <button type="submit" name="update_profile" class="bg-[#13ecc8] text-white px-6 py-3 rounded-lg font-bold shadow-md hover:bg-[#10d4b4] transition">Lưu thay đổi</button>
                        <?php else: ?>
                             <p class="text-sm text-red-500 italic">* Admin vui lòng cập nhật thông tin trong trang Dashboard.</p>
                        <?php endif; ?>
                    </form>
                </div>

                <?php if ($role !== 'admin'): ?>
                <div id="tab-history" class="tab-content hidden bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#13ecc8]">receipt_long</span> Lịch sử đặt phòng
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-sm uppercase tracking-wider">
                                    <th class="px-6 py-4 font-bold">Homestay</th>
                                    <th class="px-6 py-4 font-bold">Ngày đi / Ngày về</th>
                                    <th class="px-6 py-4 font-bold">Tổng tiền</th>
                                    <th class="px-6 py-4 font-bold text-center">Trạng thái</th>
                                    <th class="px-6 py-4 font-bold text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if ($history && $history->num_rows > 0): ?>
                                    <?php while($row = $history->fetch_assoc()): 
                                        $status = isset($row['status']) ? $row['status'] : 'confirmed'; 
                                        $check_in_time = strtotime($row['check_in']);
                                        $is_past = $check_in_time < time();
                                        $can_cancel = ($status !== 'cancelled' && !$is_past);
                                    ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900"><?php echo htmlspecialchars($row['homestay_name']); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['district']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <div><span class="font-bold">IN:</span> <?php echo date('d/m/Y', strtotime($row['check_in'])); ?></div>
                                            <div><span class="font-bold">OUT:</span> <?php echo date('d/m/Y', strtotime($row['check_out'])); ?></div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-[#13ecc8]">
                                            <?php echo number_format($row['total_price'], 0, ',', '.'); ?>₫
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <?php if ($status == 'cancelled'): ?>
                                                <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold border border-red-200">Đã hủy</span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-bold border border-green-200">Đã đặt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <?php if ($can_cancel): ?>
                                                <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn đặt phòng này không?');">
                                                    <input type="hidden" name="cancel_booking_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold border border-red-200 px-3 py-1.5 rounded hover:bg-red-50 transition">
                                                        Hủy đơn
                                                    </button>
                                                </form>
                                            <?php elseif($status == 'cancelled'): ?>
                                                <span class="text-gray-400 text-xs italic">Đã hủy</span>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-xs italic">Đã hoàn thành/Quá hạn</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Bạn chưa có đơn đặt phòng nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="tab-password" class="tab-content hidden bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#13ecc8]">lock_reset</span> Đổi mật khẩu
                    </h3>
                    <form action="" method="POST" class="space-y-6 max-w-xl">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#13ecc8] focus:ring-1 focus:ring-[#13ecc8]" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mật khẩu mới</label>
                            <input type="password" name="new_password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#13ecc8] focus:ring-1 focus:ring-[#13ecc8]" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                            <input type="password" name="confirm_password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#13ecc8] focus:ring-1 focus:ring-[#13ecc8]" required>
                        </div>
                        <button type="submit" name="change_password" class="bg-gray-900 text-white px-6 py-3 rounded-lg font-bold shadow-md hover:bg-black transition">Cập nhật mật khẩu</button>
                    </form>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-auto text-center text-sm text-gray-400">
        <p>© 2024 HomestayApp. All rights reserved.</p>
    </footer>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-[#13ecc8]', 'bg-gray-50', 'font-bold', 'active');
                btn.classList.add('text-gray-600', 'font-medium');
            });
            
            const activeBtn = document.querySelector(`button[onclick="switchTab('${tabName}')"]`);
            if(activeBtn) {
                activeBtn.classList.add('text-[#13ecc8]', 'bg-gray-50', 'font-bold', 'active');
                activeBtn.classList.remove('text-gray-600', 'font-medium');
            }
        }
    </script>
</body>
</html>