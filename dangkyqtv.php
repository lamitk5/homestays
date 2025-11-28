<?php
// XỬ LÝ ĐĂNG KÝ ADMIN
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "homestays");
    
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Lưu ý: Nên mã hóa password nếu làm thực tế
    
    // Kiểm tra email
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $message = "Email này đã tồn tại!";
    } else {
        // QUAN TRỌNG: role = 'admin'
        $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$fullname', '$email', '$password', 'admin')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Đăng ký Admin thành công! Hãy đăng nhập.'); window.location.href='dangnhap.php';</script>";
        } else {
            $message = "Lỗi: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký Quản Trị Viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Đăng ký Admin</h2>
        
        <?php if($message): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Họ và Tên</label>
                <input type="text" name="fullname" required class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                <input type="password" name="password" required class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-green-500">
            </div>
            <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 rounded hover:bg-green-700 transition">
                Tạo tài khoản Admin
            </button>
        </form>
        <p class="mt-4 text-center text-sm">
            <a href="dangnhap.php" class="text-blue-600 hover:underline">Quay lại Đăng nhập</a>
        </p>
    </div>
</body>
</html>