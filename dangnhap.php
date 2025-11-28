<?php
session_start();
// --- PHẦN 1: XỬ LÝ PHP (ĐĂNG NHẬP) ---
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Kết nối CSDL
    $conn = new mysqli("localhost", "root", "", "homestays");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // 2. Lấy dữ liệu
    $email = $_POST['email'];
    $password_input = $_POST['password'];

    // 3. Truy vấn kiểm tra
    // Chống SQL Injection
    $email = $conn->real_escape_string($email);
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // So sánh mật khẩu (Lưu ý: Nếu dùng password_hash ở đăng ký thì ở đây phải dùng password_verify)
        if ($password_input === $row['password']) {
            // Đăng nhập thành công -> Lưu Session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];

            // Chuyển hướng
            if ($row['role'] == 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: trang_chu.php");
            }
            exit();
        } else {
            $error_message = "Mật khẩu không chính xác!";
        }
    } else {
        $error_message = "Email này chưa được đăng ký!";
    }
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
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        #login-bg { transition: background-image 1s ease-in-out; }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#3A86FF", "background-light": "#f6f8f8", "background-dark": "#10221f" },
                    fontFamily: { "display": ["Plus Jakarta Sans", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="font-display">
    <div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
        <main class="flex min-h-screen w-full items-stretch justify-center">
            <div class="flex w-full max-w-7xl flex-1">
                
                <div class="flex flex-1 flex-col justify-center px-4 py-10 sm:px-10 lg:px-16">
                    <div class="flex max-w-md flex-col items-center text-center lg:items-start lg:text-left">
                        <a class="mb-8 flex items-center gap-3 text-2xl font-bold text-[#0d1b19] dark:text-white hover:opacity-80" href="trang_chu.php">
                            <span class="material-symbols-outlined text-primary text-4xl"> other_houses </span>
                            <span>HomestayDeluxe</span>
                        </a>
                        <div class="w-full">
                            <h1 class="text-[#0d1b19] dark:text-gray-200 text-4xl font-black mb-2">Chào mừng trở lại!</h1>
                            <p class="text-gray-500 dark:text-gray-400 text-base font-normal">Đăng nhập để tiếp tục hành trình của bạn.</p>
                            
                            <?php if(!empty($error_message)): ?>
                                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm font-bold border border-red-200">
                                    ⚠️ <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>

                            <form class="mt-8 flex flex-col gap-4" action="dangnhap.php" method="POST">
                                <label class="flex flex-col min-w-40 flex-1 text-left">
                                    <p class="text-[#0d1b19] dark:text-gray-200 text-base font-medium pb-2">Email</p>
                                    <input name="email" type="email" required class="form-input flex w-full rounded-lg border border-[#cfe7e3] h-14 px-4 bg-background-light focus:ring-2 focus:ring-primary/50" placeholder="admin@homestay.com" />
                                </label>
                                
                                <label class="flex flex-col min-w-40 flex-1 text-left">
                                    <div class="flex items-center justify-between pb-2">
                                        <p class="text-[#0d1b19] dark:text-gray-200 text-base font-medium">Mật khẩu</p>
                                        <a class="text-sm font-medium text-primary hover:underline" href="#">Quên mật khẩu?</a>
                                    </div>
                                    <div class="flex w-full flex-1 items-stretch rounded-lg relative">
                                        <input id="loginPassword" name="password" type="password" required class="form-input flex w-full rounded-lg rounded-r-none border border-[#cfe7e3] h-14 px-4 bg-background-light focus:ring-2 focus:ring-primary/50 border-r-0" placeholder="Nhập mật khẩu" />
                                        
                                        <button type="button" onclick="toggleLoginPassword()" class="flex border border-[#cfe7e3] bg-background-light items-center justify-center px-4 rounded-r-lg border-l-0 hover:bg-gray-100 cursor-pointer">
                                            <span id="loginEyeIcon" class="material-symbols-outlined select-none">visibility_off</span>
                                        </button>
                                    </div>
                                </label>

                                <button type="submit" class="flex h-14 w-full items-center justify-center rounded-lg bg-primary px-6 text-base font-bold text-white hover:bg-primary/90 transition-all shadow-lg hover:shadow-xl">
                                    Đăng nhập
                                </button>
                            </form>

                            <div class="relative my-8 flex items-center">
                                <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                                <span class="mx-4 flex-shrink text-sm text-gray-500">Hoặc đăng nhập bằng</span>
                                <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <button class="flex h-12 items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white hover:bg-gray-50"><img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google"> Google</button>
                                <button class="flex h-12 items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white hover:bg-gray-50"><img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5" alt="Facebook"> Facebook</button>
                            </div>

                            <div class="mt-8 text-center text-sm text-gray-500">
                                <span>Chưa có tài khoản?</span>
                                <a class="font-bold text-primary hover:underline" href="dangky.php">Đăng ký ngay</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="login-bg" class="relative hidden w-1/2 flex-1 lg:block bg-cover bg-center transition-all duration-1000" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBQxZB6A_PjynTbiNq6Z8CvJZIZLYEsO3DnAv08kX-AA-7iMH1FI1iSBqGC0q_0wgZx5rwKEs7aB10tRK0kIZUyzQCcr0XIyvGJKYwiuqp8_8HeiK-DB3IahWPwpV--9EOVZRpNQajLqO6vuy4YvmI-pcXuxSxyPkembgf4X_1vR8FDgIj_Uk1QVpRiZpNa_VUHAVGrVR82gyuLH6Oarq6iYjqWssDZXezbt8PmV1DVeTwqu8O3DWFaea_xF1pvAOXf2Ur9ToOMJEWi');"></div>
            </div>
        </main>
    </div>

    <script>
        // 1. Chức năng Hiện/Ẩn mật khẩu
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

        // 2. Chức năng Slide ảnh nền
        const loginImages = [
            "https://lh3.googleusercontent.com/aida-public/AB6AXuBQxZB6A_PjynTbiNq6Z8CvJZIZLYEsO3DnAv08kX-AA-7iMH1FI1iSBqGC0q_0wgZx5rwKEs7aB10tRK0kIZUyzQCcr0XIyvGJKYwiuqp8_8HeiK-DB3IahWPwpV--9EOVZRpNQajLqO6vuy4YvmI-pcXuxSxyPkembgf4X_1vR8FDgIj_Uk1QVpRiZpNa_VUHAVGrVR82gyuLH6Oarq6iYjqWssDZXezbt8PmV1DVeTwqu8O3DWFaea_xF1pvAOXf2Ur9ToOMJEWi",
            "https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1920",
            "https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=1920",
            "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=1920"
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