<?php
session_start();

// 1. KIỂM TRA ĐĂNG NHẬP & QUYỀN ADMIN
// Nếu chưa đăng nhập HOẶC không phải là admin thì đá về trang đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

// 2. KẾT NỐI CSDL ĐỂ LẤY SỐ LIỆU THỐNG KÊ THẬT
$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) { die("Kết nối thất bại"); }

// Đếm tổng user
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='customer'")->fetch_assoc()['count'];

// Đếm tổng homestay
$total_homestays = $conn->query("SELECT COUNT(*) as count FROM homestays")->fetch_assoc()['count'];

// (Tạm thời Booking chưa có dữ liệu thì để số 0 hoặc query tương tự)
$total_bookings = 0; 
$total_revenue = 0;

$conn->close();
?>

<!DOCTYPE html>
<html class="light" lang="vi">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bảng điều khiển Admin</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: { "primary": "#13ecc8", "background-light": "#f6f8f8", "background-dark": "#10221f" },
          fontFamily: { "display": ["Plus Jakarta Sans", "sans-serif"] },
        },
      },
    }
</script>
<style>.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 }</style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="relative flex min-h-screen w-full flex-col">
<div class="flex h-full grow">
<aside class="flex w-64 flex-col gap-8 border-r border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-background-dark sticky top-0 h-screen">
    <div class="flex items-center gap-3 px-2">
        <span class="material-symbols-outlined text-primary text-3xl">home_work</span>
        <h1 class="text-xl font-bold text-slate-800 dark:text-white">Homestay Admin</h1>
    </div>
    <div class="flex flex-col gap-4">
        <div class="flex gap-3 items-center">
            <div class="bg-gray-200 rounded-full size-12 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">person</span>
            </div>
            <div class="flex flex-col">
                <h1 class="text-slate-900 dark:text-slate-100 text-base font-medium"><?php echo $_SESSION['fullname']; ?></h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Quản trị viên</p>
            </div>
        </div>
        <nav class="flex flex-col gap-2 mt-4">
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 text-primary font-bold" href="dashboard.php">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="quanlykh.php">
                <span class="material-symbols-outlined">group</span> Khách hàng
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="trang_chu.php">
                <span class="material-symbols-outlined">public</span> Xem trang chủ
            </a>
        </nav>
    </div>
    <div class="mt-auto">
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-red-600" href="dangnhap.php">
            <span class="material-symbols-outlined">logout</span> Đăng xuất
        </a>
    </div>
</aside>

<main class="flex-1 p-8">
    <div class="flex flex-wrap justify-between gap-4 items-center mb-8">
        <div class="flex flex-col gap-1">
            <p class="text-slate-900 dark:text-slate-50 text-3xl font-bold">Tổng quan</p>
            <p class="text-slate-500 dark:text-slate-400 text-base">Chào mừng trở lại, <?php echo $_SESSION['fullname']; ?>!</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200">
            <p class="text-slate-600 font-medium">Khách hàng</p>
            <p class="text-3xl font-bold"><?php echo $total_users; ?></p>
        </div>
        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200">
            <p class="text-slate-600 font-medium">Homestay</p>
            <p class="text-3xl font-bold"><?php echo $total_homestays; ?></p>
        </div>
         <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200">
            <p class="text-slate-600 font-medium">Đơn đặt phòng</p>
            <p class="text-3xl font-bold"><?php echo $total_bookings; ?></p>
        </div>
         <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200">
            <p class="text-slate-600 font-medium">Doanh thu</p>
            <p class="text-3xl font-bold text-primary"><?php echo number_format($total_revenue); ?>đ</p>
        </div>
    </div>
    
    </main>
</div>
</div>
</body>
</html>