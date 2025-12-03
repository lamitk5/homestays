<?php
// FILE KIỂM TRA LỖI DASHBOARD
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Dashboard Database Connection</h1>";

$conn = new mysqli("localhost", "root", "", "homestays");

if ($conn->connect_error) {
    die("❌ Kết nối thất bại: " . $conn->connect_error);
}

echo "✅ Kết nối database thành công!<br><br>";

// Test 1: Kiểm tra bảng bookings
echo "<h2>Test 1: Bảng bookings</h2>";
$test1 = $conn->query("SELECT COUNT(*) as count FROM bookings");
if ($test1) {
    $count = $test1->fetch_assoc()['count'];
    echo "✅ Tổng đơn: $count<br>";
} else {
    echo "❌ Lỗi: " . $conn->error . "<br>";
}

// Test 2: Kiểm tra cột created_at
echo "<h2>Test 2: Cột created_at</h2>";
$test2 = $conn->query("SELECT * FROM bookings LIMIT 1");
if ($test2 && $test2->num_rows > 0) {
    $row = $test2->fetch_assoc();
    echo "✅ Cột created_at: " . (isset($row['created_at']) ? $row['created_at'] : 'KHÔNG TỒN TẠI') . "<br>";
    echo "Các cột có: " . implode(', ', array_keys($row)) . "<br>";
} else {
    echo "⚠️ Không có dữ liệu hoặc lỗi: " . $conn->error . "<br>";
}

// Test 3: Kiểm tra JOIN
echo "<h2>Test 3: JOIN với homestays và users</h2>";
$test3 = $conn->query("
    SELECT b.id, h.name as homestay_name, u.fullname as customer_name
    FROM bookings b
    LEFT JOIN homestays h ON b.homestay_id = h.homestay_id
    LEFT JOIN users u ON b.user_id = u.user_id
    LIMIT 1
");
if ($test3) {
    if ($test3->num_rows > 0) {
        $row = $test3->fetch_assoc();
        echo "✅ JOIN thành công:<br>";
        echo "- Booking ID: " . $row['id'] . "<br>";
        echo "- Homestay: " . ($row['homestay_name'] ?? 'NULL') . "<br>";
        echo "- Customer: " . ($row['customer_name'] ?? 'NULL') . "<br>";
    } else {
        echo "⚠️ Không có dữ liệu trong bookings<br>";
    }
} else {
    echo "❌ Lỗi JOIN: " . $conn->error . "<br>";
}

// Test 4: Kiểm tra SUM total_price
echo "<h2>Test 4: Tổng doanh thu</h2>";
$test4 = $conn->query("SELECT COALESCE(SUM(total_price), 0) as total FROM bookings");
if ($test4) {
    $total = $test4->fetch_assoc()['total'];
    echo "✅ Tổng doanh thu: " . number_format($total, 0, ',', '.') . "₫<br>";
} else {
    echo "❌ Lỗi: " . $conn->error . "<br>";
}

// Test 5: Kiểm tra DATE_FORMAT
echo "<h2>Test 5: GROUP BY tháng</h2>";
$test5 = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
    FROM bookings
    GROUP BY month
    ORDER BY month DESC
    LIMIT 3
");
if ($test5) {
    echo "✅ Kết quả:<br>";
    while ($row = $test5->fetch_assoc()) {
        echo "- Tháng " . $row['month'] . ": " . $row['count'] . " đơn<br>";
    }
} else {
    echo "❌ Lỗi: " . $conn->error . "<br>";
}

// Test 6: Kiểm tra Chart.js có load không
echo "<h2>Test 6: Chart.js</h2>";
echo '<canvas id="testChart" width="400" height="200"></canvas>';
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
echo '<script>
const ctx = document.getElementById("testChart");
if (ctx) {
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["Test"],
            datasets: [{
                label: "Test Data",
                data: [100],
                backgroundColor: "rgba(19, 236, 200, 0.8)"
            }]
        }
    });
    console.log("✅ Chart.js loaded successfully");
} else {
    console.error("❌ Canvas not found");
}
</script>';

$conn->close();
?>

<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
    background: #f5f5f5;
}
h1 { color: #333; }
h2 { color: #666; margin-top: 20px; padding: 10px; background: #fff; border-left: 4px solid #13ecc8; }
</style>