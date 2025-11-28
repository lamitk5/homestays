<?php
// --- PHẦN 1: XỬ LÝ PHP (ĐĂNG KÝ) ---
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Kết nối CSDL
    $conn = new mysqli("localhost", "root", "", "homestays");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // 2. Lấy dữ liệu từ form
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 3. Kiểm tra cơ bản
    if ($password !== $confirm_password) {
        $error_message = "Mật khẩu xác nhận không khớp!";
    } else {
        // Kiểm tra xem email đã tồn tại chưa
        $check_sql = "SELECT * FROM users WHERE email = '$email'";
        if ($conn->query($check_sql)->num_rows > 0) {
            $error_message = "Email này đã được đăng ký!";
        } else {
            // Thêm người dùng mới (Lưu ý: Thực tế nên mã hóa mật khẩu bằng password_hash)
            $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$fullname', '$email', '$password', 'customer')";
            
            if ($conn->query($sql) === TRUE) {
                $success_message = "Đăng ký thành công! Đang chuyển hướng...";
                // Tự động chuyển qua trang đăng nhập sau 2 giây
                echo "<meta http-equiv='refresh' content='2;url=dangnhap.php'>";
            } else {
                $error_message = "Lỗi hệ thống: " . $conn->error;
            }
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Đăng ký tài khoản</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        #dynamic-bg { transition: background-image 1s ease-in-out; }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#13ecc8", "background-light": "#f6f8f8", "background-dark": "#10221f", "accent": "#3A5A40", "text-main": "#333333", "error": "#D9534F" },
                    fontFamily: { "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="font-display">
    <div id="dynamic-bg" class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark overflow-x-hidden p-4 md:p-6" 
         style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBdDrkGklSaLcQYhcERwYB1kywXRoyuDEm1EmcCwAU_lOha-ves1XwIYxYChi2uY6HvP5N9PGV6StHVAFxXHkwJAgbGjCJmSwnXXIUfkJIu4EsB1z0Y2yccqmEiUbPWSXmabVeVKqNfFHSk6KwJnBobFcGxJkNjFp7_O0hvYSZUqFU5xbPXAtXvOxwu43cGmL_eBWeKzM2OM9HFRHMY2o-j-1umIiaHVe6SyCBllaMxxItWVWLTXcfW7Idl3-VllcbRWigsOJ5OS9lH'); background-size: cover; background-position: center;">
        
        <div class="absolute inset-0 bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm"></div>
        <div class="relative z-10 flex w-full max-w-md flex-col items-center rounded-xl bg-background-light dark:bg-background-dark shadow-2xl p-8 md:p-10 border border-gray-200 dark:border-gray-700">
            
            <div class="mb-6 flex flex-col items-center text-center">
                <a href="trang_chu.php" class="flex items-center gap-2 mb-4 hover:opacity-80 transition-opacity">
                    <span class="material-symbols-outlined text-accent text-4xl">home_pin</span>
                    <span class="text-2xl font-bold text-accent">HomestayHub</span>
                </a>
                <h1 class="text-text-main dark:text-gray-100 text-3xl font-bold tracking-tight">Tạo tài khoản mới</h1>
                <p class="text-text-main/70 dark:text-gray-300 text-base font-normal leading-normal mt-2">Tìm kiếm và đặt ngay những homestay tuyệt vời nhất.</p>
            </div>

            <?php if ($error_message): ?>
                <div class="w-full mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm text-center font-bold border border-red-200">
                    ⚠️ <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div class="w-full mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm text-center font-bold border border-green-200">
                    ✅ <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form class="w-full space-y-4" action="dangky.php" method="POST">
                
                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium pb-2">Họ và Tên</label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400">person</span>
                        <input name="fullname" type="text" required class="form-input flex w-full rounded-lg border border-gray-300 h-12 pl-10 pr-4 focus:ring-2 focus:ring-accent/50" placeholder="Nhập họ và tên" />
                    </div>
                </div>

                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium pb-2">Email</label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400">mail</span>
                        <input name="email" type="email" required class="form-input flex w-full rounded-lg border border-gray-300 h-12 pl-10 pr-4 focus:ring-2 focus:ring-accent/50" placeholder="Nhập email" />
                    </div>
                </div>

                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium pb-2">Mật khẩu</label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400">lock</span>
                        
                        <input id="passwordInput" name="password" type="password" required class="form-input flex w-full rounded-lg border border-gray-300 h-12 pl-10 pr-10 focus:ring-2 focus:ring-accent/50" placeholder="Nhập mật khẩu" />
                        
                        <button type="button" onclick="togglePassword()" class="absolute right-3 text-gray-400 hover:text-accent cursor-pointer z-10">
                            <span id="eyeIcon" class="material-symbols-outlined select-none">visibility_off</span>
                        </button>
                    </div>
                </div>

                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium pb-2">Xác nhận Mật khẩu</label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400">lock</span>
                        <input name="confirm_password" type="password" required class="form-input flex w-full rounded-lg border border-gray-300 h-12 pl-10 pr-4 focus:ring-2 focus:ring-accent/50" placeholder="Nhập lại mật khẩu" />
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-accent h-12 px-6 text-base font-bold text-white hover:bg-accent/90 transition-all">
                        Đăng ký
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-text-main/80 dark:text-gray-400 text-sm">
                    Đã có tài khoản? <a class="font-bold text-accent hover:underline" href="dangnhap.php">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // 1. Chức năng Hiện/Ẩn mật khẩu
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text'; // Hiện mật khẩu
                eyeIcon.innerText = 'visibility'; // Đổi icon thành mắt mở
            } else {
                passwordInput.type = 'password'; // Ẩn mật khẩu
                eyeIcon.innerText = 'visibility_off'; // Đổi icon thành mắt gạch chéo
            }
        }

        // 2. Chức năng Slide ảnh nền
        const images = [
            "https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1920", 
            "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=1920", 
            "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=1920", 
            "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=1920"
        ];
        
        let currentIndex = 0;
        const bgElement = document.getElementById('dynamic-bg');

        function changeBackground() {
            if (!bgElement) return;
            currentIndex = (currentIndex + 1) % images.length;
            bgElement.style.backgroundImage = `url('${images[currentIndex]}')`;
        }
        setInterval(changeBackground, 5000);
    </script>
</body>
</html>