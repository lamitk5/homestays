<?php
// XỬ LÝ ĐĂNG KÝ ADMIN
$message = "";
$message_type = "error"; // success hoặc error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "homestays");
    
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    // Lấy dữ liệu từ form
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate dữ liệu
    if (empty($username) || empty($fullname) || empty($email) || empty($password)) {
        $message = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($password !== $confirm_password) {
        $message = "Mật khẩu xác nhận không khớp!";
    } elseif (strlen($password) < 6) {
        $message = "Mật khẩu phải có ít nhất 6 ký tự!";
    } else {
        // Kiểm tra username đã tồn tại chưa
        $stmt = $conn->prepare("SELECT id FROM qtrivien WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $message = "Tên đăng nhập này đã tồn tại!";
        } else {
            $stmt->close();
            
            // Kiểm tra email đã tồn tại chưa
            $stmt = $conn->prepare("SELECT id FROM qtrivien WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $message = "Email này đã được đăng ký!";
            } else {
                $stmt->close();
                
                // Mã hóa mật khẩu
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Thêm admin mới
                $stmt = $conn->prepare("INSERT INTO qtrivien (username, fullname, email, password, role) VALUES (?, ?, ?, ?, 'admin')");
                $stmt->bind_param("ssss", $username, $fullname, $email, $hashed_password);
                
                if ($stmt->execute()) {
                    $message = "Đăng ký Admin thành công! Đang chuyển đến trang đăng nhập...";
                    $message_type = "success";
                    echo "<script>
                        setTimeout(function() {
                            window.location.href='dangnhapqtv.php';
                        }, 2000);
                    </script>";
                } else {
                    $message = "Lỗi: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký Quản Trị Viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-green-900 to-gray-900 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-3">
                <span class="material-symbols-outlined text-green-600 text-4xl">admin_panel_settings</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">Đăng ký Admin</h2>
            <p class="text-gray-500 mt-2">Tạo tài khoản quản trị viên mới</p>
        </div>
        
        <!-- Thông báo -->
        <?php if($message): ?>
            <div class="<?php echo $message_type === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400'; ?> border-l-4 p-4 rounded mb-6 flex items-start">
                <span class="material-symbols-outlined mr-2">
                    <?php echo $message_type === 'success' ? 'check_circle' : 'error'; ?>
                </span>
                <span class="text-sm"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="space-y-4">
            <!-- Username -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">person</span>
                        Tên đăng nhập
                    </span>
                </label>
                <input 
                    type="text" 
                    name="username" 
                    required 
                    minlength="3"
                    pattern="[a-zA-Z0-9_]+"
                    title="Chỉ được dùng chữ, số và dấu gạch dưới"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    placeholder="Nhập tên đăng nhập (vd: admin123)"
                >
                <p class="text-xs text-gray-500 mt-1">Chỉ dùng chữ, số và dấu gạch dưới (_)</p>
            </div>

            <!-- Fullname -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">badge</span>
                        Họ và Tên
                    </span>
                </label>
                <input 
                    type="text" 
                    name="fullname" 
                    required 
                    value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    placeholder="Nhập họ và tên đầy đủ"
                >
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">mail</span>
                        Email
                    </span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    required 
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    placeholder="admin@example.com"
                >
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">lock</span>
                        Mật khẩu
                    </span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        required 
                        minlength="6"
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                        placeholder="Tối thiểu 6 ký tự"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('password', 'toggleIcon1')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                        <span class="material-symbols-outlined" id="toggleIcon1">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">lock_reset</span>
                        Xác nhận mật khẩu
                    </span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="confirm_password" 
                        id="confirm_password"
                        required 
                        minlength="6"
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                        placeholder="Nhập lại mật khẩu"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('confirm_password', 'toggleIcon2')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                        <span class="material-symbols-outlined" id="toggleIcon2">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white font-bold py-3 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
            >
                <span class="material-symbols-outlined">person_add</span>
                Tạo tài khoản Admin
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center space-y-2">
            <p class="text-sm text-gray-600">
                Đã có tài khoản? 
                <a href="dangnhapqtv.php" class="text-green-600 font-semibold hover:underline">Đăng nhập ngay</a>
            </p>
            <p class="text-xs text-gray-400">
                © 2024 Homestay Pro. All Rights Reserved.
            </p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        // Validate password match on submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
            }
        });
    </script>
</body>
</html>