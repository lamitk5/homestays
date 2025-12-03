<?php
session_start();

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: dangnhap.php?error=login_required");
    exit();
}

// 2. Kiểm tra phương thức POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: trang_chu.php");
    exit();
}

// 3. Kết nối Database
$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 4. Lấy dữ liệu từ form
$user_id = $_SESSION['user_id'];
$homestay_id = intval($_POST['homestay_id']);
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$guests = intval($_POST['guests']);
$price_weekday = intval($_POST['price_weekday']);
$price_weekend = intval($_POST['price_weekend']);

// 5. Validate dữ liệu
if (empty($check_in) || empty($check_out)) {
    echo "<script>alert('Vui lòng chọn ngày nhận và trả phòng!'); history.back();</script>";
    exit();
}

// 6. Kiểm tra ngày hợp lệ
$date_in = new DateTime($check_in);
$date_out = new DateTime($check_out);
$today = new DateTime();
$today->setTime(0, 0, 0);

if ($date_in < $today) {
    echo "<script>alert('Ngày nhận phòng không thể là ngày trong quá khứ!'); history.back();</script>";
    exit();
}

if ($date_out <= $date_in) {
    echo "<script>alert('Ngày trả phòng phải sau ngày nhận phòng!'); history.back();</script>";
    exit();
}

// 7. Tính số ngày và tổng tiền
$interval = $date_in->diff($date_out);
$total_days = $interval->days;

if ($total_days <= 0) {
    echo "<script>alert('Số ngày không hợp lệ!'); history.back();</script>";
    exit();
}

// 8. Tính số ngày cuối tuần và ngày thường
$weekday_count = 0;
$weekend_count = 0;

for ($i = 0; $i < $total_days; $i++) {
    $current_date = clone $date_in;
    $current_date->modify("+$i day");
    $day_of_week = $current_date->format('w'); // 0 = Chủ Nhật, 1 = Thứ 2, ...
    
    // Cuối tuần: Thứ 6, 7, CN (5, 6, 0)
    if ($day_of_week == 0 || $day_of_week == 5 || $day_of_week == 6) {
        $weekend_count++;
    } else {
        $weekday_count++;
    }
}

// 9. Tính tổng tiền
$base_price = ($weekday_count * $price_weekday) + ($weekend_count * $price_weekend);
$service_fee = 150000; // Phí dịch vụ
$total_price = $base_price + $service_fee;

// 10. Kiểm tra homestay có tồn tại không
$check_homestay = $conn->query("SELECT homestay_id FROM homestays WHERE homestay_id = $homestay_id AND deleted_at IS NULL");
if ($check_homestay->num_rows == 0) {
    echo "<script>alert('Homestay không tồn tại!'); window.location.href='trang_chu.php';</script>";
    exit();
}

// 11. Kiểm tra xem phòng đã được đặt trong khoảng thời gian này chưa
$check_booking = $conn->prepare("
    SELECT id FROM bookings 
    WHERE homestay_id = ? 
    AND deleted_at IS NULL
    AND (
        (check_in <= ? AND check_out > ?) OR
        (check_in < ? AND check_out >= ?) OR
        (check_in >= ? AND check_out <= ?)
    )
");
$check_booking->bind_param("issssss", $homestay_id, $check_in, $check_in, $check_out, $check_out, $check_in, $check_out);
$check_booking->execute();
$result_check = $check_booking->get_result();

if ($result_check->num_rows > 0) {
    echo "<script>
        alert('Rất tiếc! Homestay này đã được đặt trong khoảng thời gian bạn chọn. Vui lòng chọn ngày khác.');
        history.back();
    </script>";
    exit();
}
$check_booking->close();

// 12. Lưu vào Database
$stmt = $conn->prepare("
    INSERT INTO bookings 
    (user_id, homestay_id, check_in, check_out, total_price, guests_count, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("iissdi", $user_id, $homestay_id, $check_in, $check_out, $total_price, $guests);

if ($stmt->execute()) {
    $booking_id = $stmt->insert_id;
    
    // Lấy thông tin homestay để hiển thị
    $homestay_info = $conn->query("SELECT name FROM homestays WHERE homestay_id = $homestay_id")->fetch_assoc();
    $homestay_name = $homestay_info['name'];
    
    echo "<script>
        alert('🎉 Đặt phòng thành công!\\n\\n📍 " . addslashes($homestay_name) . "\\n📅 " . date('d/m/Y', strtotime($check_in)) . " → " . date('d/m/Y', strtotime($check_out)) . "\\n🛏️ " . $total_days . " đêm\\n💰 Tổng: " . number_format($total_price, 0, ',', '.') . "₫\\n\\n✅ Mã đặt phòng: #" . $booking_id . "');
        window.location.href='trang_chu.php';
    </script>";
} else {
    echo "<script>
        alert('❌ Có lỗi xảy ra: " . addslashes($conn->error) . "');
        history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>