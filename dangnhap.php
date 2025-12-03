<?php
session_start();
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Kết nối CSDL
    $conn = new mysqli("localhost", "root", "", "homestays");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

    $login_input = trim($_POST['login_input']);
    $password_input = $_POST['password'];

    // 2. KIỂM TRA ADMIN (Bảng qtrivien)
    // Lưu ý: Password trong DB phải được mã hóa bằng password_hash() thì mới dùng password_verify() được.
    $sql_admin = "SELECT id, fullname, role, password FROM qtrivien WHERE username = ? OR email = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("ss", $login_input, $login_input);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows > 0) {
        $admin = $result_admin->fetch_assoc();
        
        // Kiểm tra mật khẩu
        if (password_verify($password_input, $admin['password'])) {
            // Thiết lập Session cho Admin
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['fullname'] = $admin['fullname'];
            $_SESSION['role'] = 'admin'; 
            $_SESSION['logged_in'] = true;
            
            // 🎯 SỬA ĐỔI: Chuyển Admin về trang chủ để xem giao diện trước
            // (Tại trang chủ sẽ có nút "Quản trị" để vào Dashboard sau)
            header("Location: trang_chu.php"); 
            exit();
        } else {
            $error_message = "Mật khẩu Admin không chính xác!";
        }
    } else {
        // 3. KIỂM TRA KHÁCH HÀNG (Bảng users)
        // Nếu không phải Admin thì tìm trong bảng Users
        $sql_user = "SELECT user_id, fullname, role, password FROM users WHERE email = ? OR phone = ? OR username = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("sss", $login_input, $login_input, $login_input);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_user->num_rows > 0) {
            $user = $result_user->fetch_assoc();
            
            if (password_verify($password_input, $user['password'])) {
                // Thiết lập Session cho Khách
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role']; // customer hoặc vip
                $_SESSION['logged_in'] = true;
                
                header("Location: trang_chu.php");
                exit();
            } else {
                $error_message = "Mật khẩu không chính xác!";
            }
        } else {
            $error_message = "Tài khoản không tồn tại!";
        }
        $stmt_user->close();
    }
    
    $stmt_admin->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Đăng nhập Homestay</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        #login-bg { transition: background-image 1s ease-in-out; }
    </style>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#13ecc8", "primary-hover": "#10d4b4", "background-light": "#f6f8f8" }, // Đã chỉnh màu xanh ngọc cho đồng bộ
                    fontFamily: { "display": ["Plus Jakarta Sans", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="font-display">
    <div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light group/design-root overflow-x-hidden">
        <main class="flex min-h-screen w-full items-stretch justify-center">
            <div class="flex w-full max-w-7xl flex-1">
                
                <div class="flex flex-1 flex-col justify-center px-4 py-10 sm:px-10 lg:px-16">
                    <div class="flex max-w-md flex-col items-center text-center lg:items-start lg:text-left">
                        <a class="mb-8 flex items-center gap-2 text-2xl font-bold text-[#0d1b19] hover:opacity-80 transition" href="trang_chu.php">
                            <span class="material-symbols-outlined text-primary text-4xl">other_houses</span>
                            <span>Homestay<span class="text-primary">App</span></span>
                        </a>
                        <div class="w-full">
                            <h1 class="text-[#0d1b19] text-4xl font-black mb-2">Chào mừng trở lại!</h1>
                            <p class="text-gray-500 text-base font-normal">Đăng nhập để tiếp tục hành trình của bạn.</p>
                            
                            <?php if(!empty($error_message)): ?>
                                <div class="mt-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm font-bold border border-red-200 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">error</span>
                                    <?php echo htmlspecialchars($error_message); ?>
                                </div>
                            <?php endif; ?>

                            <form class="mt-8 flex flex-col gap-4" action="dangnhap.php" method="POST">
                                <label class="flex flex-col min-w-40 flex-1 text-left">
                                    <p class="text-[#0d1b19] text-sm font-bold pb-2 uppercase tracking-wide">Tài khoản</p>
                                    <input name="login_input" type="text" required class="form-input flex w-full rounded-xl border border-gray-200 h-14 px-4 bg-white focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition" placeholder="Email, SĐT hoặc Username" />
                                </label>
                                
                                <label class="flex flex-col min-w-40 flex-1 text-left">
                                    <div class="flex items-center justify-between pb-2">
                                        <p class="text-[#0d1b19] text-sm font-bold uppercase tracking-wide">Mật khẩu</p>
                                        <a class="text-sm font-bold text-primary hover:underline" href="#">Quên mật khẩu?</a>
                                    </div>
                                    <div class="flex w-full flex-1 items-stretch rounded-xl relative">
                                        <input id="loginPassword" name="password" type="password" required class="form-input flex w-full rounded-xl rounded-r-none border border-gray-200 h-14 px-4 bg-white focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none border-r-0 transition" placeholder="Nhập mật khẩu" />
                                        
                                        <button type="button" onclick="toggleLoginPassword()" class="flex border border-gray-200 bg-white items-center justify-center px-4 rounded-r-xl border-l-0 hover:bg-gray-50 cursor-pointer transition">
                                            <span id="loginEyeIcon" class="material-symbols-outlined select-none text-gray-500">visibility_off</span>
                                        </button>
                                    </div>
                                </label>

                                <button type="submit" class="flex h-14 w-full items-center justify-center rounded-full bg-gray-900 text-white hover:bg-primary hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 text-base font-bold mt-4">
                                    Đăng nhập
                                </button>
                            </form>
                            
                            <div class="mt-8 text-center text-sm text-gray-500 font-medium">
                                <span>Chưa có tài khoản?</span>
                                <a class="font-bold text-primary hover:underline" href="dangky.php">Đăng ký ngay</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="login-bg" class="relative hidden w-1/2 flex-1 lg:block bg-cover bg-center transition-all duration-1000 rounded-l-[40px] m-4 overflow-hidden shadow-2xl" style="background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1920');">
                    <div class="absolute inset-0 bg-black/20"></div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleLoginPassword() {
            const passwordInput = document.getElementById('loginPassword');
            const eyeIcon = document.getElementById('loginEyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerText = 'visibility';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerText = 'visibility_off';
            }
        }

        const loginImages = [
            "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1920",
            "https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1920",
            "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=1920"
        ];
        let loginIndex = 0;
        const loginBg = document.getElementById('login-bg');

        function changeLoginBackground() {
            if (!loginBg) return;
            loginIndex = (loginIndex + 1) % loginImages.length;
            loginBg.style.backgroundImage = `url('${loginImages[loginIndex]}')`;
        }
        setInterval(changeLoginBackground, 5000);
    </script>
</body>
</html>