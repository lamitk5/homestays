<?php
session_start();

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "homestays");
    
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    // Lấy dữ liệu từ form
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate
    if (empty($username) || empty($fullname) || empty($email) || empty($password)) {
        $message = "Vui lòng điền đầy đủ thông tin bắt buộc!";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email không hợp lệ!";
        $message_type = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Mật khẩu xác nhận không khớp!";
        $message_type = "error";
    } elseif (strlen($password) < 6) {
        $message = "Mật khẩu phải có ít nhất 6 ký tự!";
        $message_type = "error";
    } else {
        // Kiểm tra username
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $message = "Tên đăng nhập đã tồn tại!";
            $message_type = "error";
        } else {
            $stmt->close();
            
            // Kiểm tra email
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $message = "Email đã được đăng ký!";
                $message_type = "error";
            } else {
                $stmt->close();
                
                // Mã hóa mật khẩu
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Thêm user mới
                $stmt = $conn->prepare("INSERT INTO users (username, fullname, email, phone, password, role, status, email_verified) VALUES (?, ?, ?, ?, ?, 'customer', 'active', 0)");
                $stmt->bind_param("sssss", $username, $fullname, $email, $phone, $hashed_password);
                
                if ($stmt->execute()) {
                    $message = "Đăng ký thành công! Đang chuyển đến trang đăng nhập...";
                    $message_type = "success";
                    echo "<script>
                        setTimeout(function() {
                            window.location.href='dangnhap.php';
                        }, 2000);
                    </script>";
                } else {
                    $message = "Lỗi: " . $stmt->error;
                    $message_type = "error";
                }
                $stmt->close();
            }
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html class="light" lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Đăng ký tài khoản - HomestayHub</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ecc8",
                        "background-light": "#f6f8f8",
                        "background-dark": "#10221f",
                        "accent": "#3A5A40",
                        "text-main": "#333333",
                        "error": "#D9534F"
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>

<body class="font-display">
    <div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden p-4 md:p-6" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBdDrkGklSaLcQYhcERwYB1kywXRoyuDEm1EmcCwAU_lOha-ves1XwIYxYChi2uY6HvP5N9PGV6StHVAFxXHkwJAgbGjCJmSwnXXIUfkJIu4EsB1z0Y2yccqmEiUbPWSXmabVeVKqNfFHSk6KwJnBobFcGxJkNjFp7_O0hvYSZUqFU5xbPXAtXvOxwu43cGmL_eBWeKzM2OM9HFRHMY2o-j-1umIiaHVe6SyCBllaMxxItWVWLTXcfW7Idl3-VllcbRWigsOJ5OS9lH'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm"></div>
        <div class="relative z-10 flex w-full max-w-md flex-col items-center rounded-xl bg-background-light dark:bg-background-dark shadow-2xl p-8 md:p-10 border border-gray-200 dark:border-gray-700">
            <div class="mb-6 flex flex-col items-center text-center">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-accent text-4xl">home_pin</span>
                    <span class="text-2xl font-bold text-accent">HomestayHub</span>
                </div>
                <h1 class="text-text-main dark:text-gray-100 text-3xl font-bold tracking-tight">Tạo tài khoản mới</h1>
                <p class="text-text-main/70 dark:text-gray-300 text-base font-normal leading-normal mt-2">Tìm kiếm và đặt ngay những homestay tuyệt vời nhất.</p>
            </div>

            <!-- Thông báo -->
            <?php if ($message): ?>
            <div class="w-full mb-4 p-4 rounded-lg <?php echo $message_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined"><?php echo $message_type == 'success' ? 'check_circle' : 'error'; ?></span>
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($message); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" class="w-full space-y-4">
                <!-- Username -->
                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="username">
                        Tên đăng nhập <span class="text-error">*</span>
                    </label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">account_circle</span>
                        <input 
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-base font-normal leading-normal" 
                            id="username" 
                            name="username"
                            placeholder="Nhập tên đăng nhập" 
                            type="text" 
                            required
                            pattern="[a-zA-Z0-9_]+"
                            title="Chỉ dùng chữ, số và dấu gạch dưới"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                        />
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Chỉ dùng chữ cái, số và dấu gạch dưới (_)</p>
                </div>

                <!-- Full Name -->
                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="fullname">
                        Họ và Tên <span class="text-error">*</span>
                    </label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">person</span>
                        <input 
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-base font-normal leading-normal" 
                            id="fullname" 
                            name="fullname"
                            placeholder="Nhập họ và tên đầy đủ" 
                            type="text" 
                            required
                            value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                        />
                    </div>
                </div>

                <!-- Email -->
                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="email">
                        Email <span class="text-error">*</span>
                    </label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">mail</span>
                        <input 
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-base font-normal leading-normal" 
                            id="email" 
                            name="email"
                            placeholder="example@email.com" 
                            type="email" 
                            required
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        />
                    </div>
                </div>

                <!-- Phone -->
                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="phone">
                        Số điện thoại
                    </label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">phone</span>
                        <input 
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-base font-normal leading-normal" 
                            id="phone" 
                            name="phone"
                            placeholder="0901234567" 
                            type="tel"
                            pattern="[0-9]{10,11}"
                            title="Nhập 10-11 số"
                            value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                        />
                    </div>
                </div>

                <!-- Password -->
                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="password">
                        Mật khẩu <span class="text-error">*</span>
                    </label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">lock</span>
                        <input 
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-10 text-base font-normal leading-normal" 
                            id="password" 
                            name="password"
                            placeholder="Tối thiểu 6 ký tự" 
                            type="password" 
                            required
                            minlength="6"
                        />
                        <button class="absolute right-3 text-gray-400 dark:text-gray-500 hover:text-text-main dark:hover:text-gray-300" type="button" onclick="togglePassword('password', 'toggleIcon1')">
                            <span class="material-symbols-outlined" id="toggleIcon1">visibility_off</span>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="flex w-full flex-col">
                    <label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="confirm_password">
                        Xác nhận Mật khẩu <span class="text-error">*</span>
                    </label>
                    <div class="relative flex w-full items-center">
                        <span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">lock</span>
                        <input 
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-10 text-base font-normal leading-normal" 
                            id="confirm_password" 
                            name="confirm_password"
                            placeholder="Nhập lại mật khẩu" 
                            type="password" 
                            required
                            minlength="6"
                        />
                        <button class="absolute right-3 text-gray-400 dark:text-gray-500 hover:text-text-main dark:hover:text-gray-300" type="button" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                            <span class="material-symbols-outlined" id="toggleIcon2">visibility_off</span>
                        </button>
                    </div>
                </div>

                <div class="pt-4">
                    <button class="flex w-full items-center justify-center rounded-lg bg-accent h-12 px-6 text-base font-bold text-white shadow-sm hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:focus:ring-offset-background-dark transition-all" type="submit">
                        <span class="material-symbols-outlined mr-2">person_add</span>
                        Đăng ký
                    </button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <p class="text-text-main/80 dark:text-gray-400 text-sm">
                    Đã có tài khoản?
                    <a class="font-bold text-accent hover:underline" href="dangnhap.php">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }

        // Validate password match
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