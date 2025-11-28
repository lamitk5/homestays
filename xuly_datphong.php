<?php
session_start();

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để đặt phòng!'); window.location.href='dangnhap.php';</script>";
    exit();
}

// 2. Kết nối Database
$conn = new mysqli("localhost", "root", "", "homestays");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $homestay_id = $_POST['homestay_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $price_per_night = $_POST['price_per_night'];

    // 3. Tính toán số ngày và tổng tiền
    $date1 = new DateTime($check_in);
    $date2 = new DateTime($check_out);
    $interval = $date1->diff($date2);
    $days = $interval->days;

    if ($days <= 0) {
        echo "<script>alert('Ngày trả phòng phải sau ngày nhận phòng!'); history.back();</script>";
        exit();
    }

    $total_price = ($days * $price_per_night) + 150000; // Cộng phí vệ sinh 150k

    // 4. Lưu vào Database
    $sql = "INSERT INTO bookings (user_id, homestay_id, check_in, check_out, total_price) 
            VALUES ('$user_id', '$homestay_id', '$check_in', '$check_out', '$total_price')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Đặt phòng thành công! Tổng tiền: ".number_format($total_price)." VNĐ'); window.location.href='trang_chu.php';</script>";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
$conn->close();
?>