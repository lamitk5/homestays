<?php
session_start();

// Khởi tạo biến
$error_message = "";
$login_success = false;

// Xử lý form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Cấu hình kết nối CSDL
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "homestays";

  // Tạo kết nối
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Kiểm tra kết nối
  if ($conn->connect_error) {
    $error_message = "Kết nối CSDL thất bại: " . $conn->connect_error;
  } else {
    // Thiết lập charset UTF-8
    $conn->set_charset("utf8mb4");
    
    $input_username = trim($_POST['username']);
    $input_password = $_POST['password'];
    
    // Kiểm tra input không rỗng
    if (!empty($input_username) && !empty($input_password)) {
      // Chuẩn bị câu truy vấn (sử dụng prepared statement để tránh SQL Injection)
      $stmt = $conn->prepare("SELECT id, username, email, password, fullname, role FROM qtrivien WHERE username = ? OR email = ?");
      
      if ($stmt === false) {
        $error_message = "Lỗi database: " . $conn->error . ". Vui lòng kiểm tra bảng 'qtrivien' đã tồn tại!";
      } else {
        $stmt->bind_param("ss", $input_username, $input_username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
          $user = $result->fetch_assoc();
          
          // Xác thực mật khẩu
          if (password_verify($input_password, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            $stmt->close();
            $conn->close();
            
            // Chuyển hướng đến trang admin
            header("Location: dashboard.php");
            exit();
          } else {
            $error_message = "Mật khẩu không chính xác. Vui lòng thử lại.";
          }
        } else {
          $error_message = "Tên đăng nhập hoặc email không tồn tại.";
        }
        
        $stmt->close();
      }
    } else {
      $error_message = "Vui lòng nhập đầy đủ thông tin.";
    }
    
    $conn->close();
  }
}
?>
<!DOCTYPE html>
<html class="light" lang="vi">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Đăng nhập Quản trị Homestay</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#2E7D32",
            "background-light": "#F8F9FA",
            "background-dark": "#121212",
            "text-light": "#333333",
            "text-dark": "#E0E0E0",
            "error": "#D32F2F"
          },
          fontFamily: {
            "display": ["Inter", "sans-serif"]
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
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
  </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark">
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
      <main class="flex h-full min-h-screen w-full flex-1">
        <div class="flex flex-col md:flex-row w-full">
          <!-- Left Column: Image -->
          <div class="hidden md:flex w-full md:w-3/5">
            <div class="w-full bg-center bg-no-repeat bg-cover aspect-auto" data-alt="A bright and cozy living room of a modern homestay with comfortable furniture and large windows." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAGo8wmtS8bA0vYc2LpdYu2stxj5pGgAUcjPU7m1KdxPkI6lHasNoHxzVcXN-BJpURfEDuc3e6TLa8-nDoa2GPLgPCt9G_3wjyjKyYinb2CgfCjNF0Ht09YfnxJnFdTWu3ccA1YRrWZkdSpDELWfN9SeVe83VxpCNJiuHgGO1GUmwvBaCYA5-THNMkfQmsECHrIJ5TtMStEP_QTG80-FAQQ872ZC82dBLG4MNhjGN0AWdYfmOEUP2I6DEYnw5a1qy3Ftsn7UaJJz7AN");'></div>
          </div>
          <!-- Right Column: Login Form -->
          <div class="w-full md:w-2/5 bg-background-light dark:bg-background-dark flex flex-col justify-center items-center p-8 lg:p-12">
            <div class="flex flex-col max-w-sm w-full">
              <!-- Logo and Welcome Text -->
              <div class="flex flex-col items-center text-center mb-8">
                <span class="material-symbols-outlined text-primary text-5xl mb-2">home_pin</span>
                <h1 class="text-3xl font-bold text-text-light dark:text-text-dark tracking-tight">Chào mừng trở lại!</h1>
                <p class="text-base text-gray-500 dark:text-gray-400 mt-2">Đăng nhập vào hệ thống quản lý homestay của bạn.</p>
              </div>
              
              <!-- Thông báo lỗi -->
              <?php if (!empty($error_message)): ?>
              <div class="mb-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-error mr-2">error</span>
                  <p class="text-error text-sm font-medium"><?php echo htmlspecialchars($error_message); ?></p>
                </div>
              </div>
              <?php endif; ?>
              
              <!-- Login Form -->
              <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="w-full flex flex-col gap-5">
                <div class="flex flex-col">
                  <label class="flex flex-col min-w-40 flex-1">
                    <p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal pb-2">Email hoặc Tên đăng nhập</p>
                    <div class="relative flex w-full items-center">
                      <span class="material-symbols-outlined absolute left-3 text-gray-400">person</span>
                      <input 
                        name="username" 
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-primary h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 py-2 text-base font-normal leading-normal" 
                        placeholder="Nhập email hoặc tên đăng nhập" 
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                        required 
                      />
                    </div>
                  </label>
                </div>
                <div class="flex flex-col">
                  <label class="flex flex-col min-w-40 flex-1">
                    <p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal pb-2">Mật khẩu</p>
                    <div class="relative flex w-full items-center">
                      <span class="material-symbols-outlined absolute left-3 text-gray-400">lock</span>
                      <input 
                        name="password" 
                        id="password"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-primary h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-10 py-2 text-base font-normal leading-normal" 
                        placeholder="Nhập mật khẩu của bạn" 
                        type="password" 
                        required 
                      />
                      <button class="absolute right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" type="button" onclick="togglePassword()">
                        <span class="material-symbols-outlined" id="toggleIcon">visibility</span>
                      </button>
                    </div>
                  </label>
                </div>
                <button class="flex items-center justify-center w-full bg-primary text-white font-bold h-12 rounded-lg text-base hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300" type="submit">
                  Đăng nhập
                </button>
                <div class="text-center space-y-2">
                  <a class="text-sm font-medium text-primary hover:underline block" href="forgot_password.php">Quên mật khẩu?</a>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Chưa có tài khoản? 
                    <a href="dangkyqtv.php" class="font-semibold text-primary hover:underline">Đăng ký ngay</a>
                  </p>
                </div>
              </form>
              <!-- Footer -->
              <div class="mt-12 text-center">
                <p class="text-xs text-gray-400 dark:text-gray-500">© 2024 Homestay Pro. All Rights Reserved.</p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = 'visibility_off';
      } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = 'visibility';
      }
    }
  </script>
</body>

</html>