<?php
session_start();
// Chỉ admin mới được xem
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { die("Truy cập bị từ chối!"); }

$conn = new mysqli("localhost", "root", "", "homestays");
if (!isset($_GET['id'])) { die("Không tìm thấy khách hàng!"); }

$user_id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (!$user) { die("Khách hàng không tồn tại"); }
?>

<!DOCTYPE html>
<html class="light" lang="vi">
<head>
<meta charset="utf-8"/>
<title>Chi tiết: <?php echo $user['fullname']; ?></title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<style>.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 }</style>
</head>
<body class="bg-gray-50">
<div class="flex min-h-screen">
    <main class="flex-1 p-8">
        <div class="flex items-center justify-between mb-6">
            <a href="quanlykh.php" class="flex items-center text-gray-500 hover:text-blue-600">
                <span class="material-symbols-outlined">arrow_back</span> Quay lại danh sách
            </a>
            <h1 class="text-2xl font-bold">Hồ sơ khách hàng</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex flex-col items-center mb-6">
                    <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-4xl text-gray-500 mb-4">
                        <?php echo strtoupper(substr($user['fullname'], 0, 1)); ?>
                    </div>
                    <h2 class="text-xl font-bold"><?php echo $user['fullname']; ?></h2>
                    <p class="text-gray-500">Khách hàng</p>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-bold text-gray-700">Email</label>
                        <input type="text" value="<?php echo $user['email']; ?>" class="w-full mt-1 p-2 border rounded bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700">Số điện thoại</label>
                        <input type="text" value="<?php echo $user['phone'] ? $user['phone'] : 'Chưa cập nhật'; ?>" class="w-full mt-1 p-2 border rounded bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700">Ngày tham gia</label>
                        <input type="text" value="<?php echo $user['created_at']; ?>" class="w-full mt-1 p-2 border rounded bg-gray-50" readonly>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-lg mb-4">Lịch sử đặt phòng</h3>
                <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg">
                    Chức năng hiển thị lịch sử đặt phòng đang được xây dựng...
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>