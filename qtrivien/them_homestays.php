<?php
session_start();

// 1. Kiểm tra quyền Admin (Nếu chưa đăng nhập thì đuổi về trang đăng nhập)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Tạm thời comment dòng này để bạn test cho dễ, sau này uncomment lại nhé
    // header("Location: dangnhap.php"); exit(); 
}

$message = "";

// 2. Xử lý khi bấm nút "Thêm mới"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "homestays");
    $conn->set_charset("utf8mb4");

    // Lấy dữ liệu từ form
    $name = $conn->real_escape_string($_POST['name']);
    $district = $conn->real_escape_string($_POST['district']);
    $address = $conn->real_escape_string($_POST['address']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // Xử lý giá và số liệu (bỏ dấu chấm/phẩy nếu copy từ Word vào)
    $price_weekday = (int)str_replace(['.', ','], '', $_POST['price_weekday']);
    $price_weekend = (int)str_replace(['.', ','], '', $_POST['price_weekend']);
    $price_extra = (int)str_replace(['.', ','], '', $_POST['price_extra_guest']);
    
    $max_guests = (int)$_POST['max_guests'];
    $num_bedrooms = (int)$_POST['num_bedrooms'];
    $num_beds = (int)$_POST['num_beds'];

    // Xử lý Upload ảnh
    $target_dir = "uploads/";
    $main_image = "";
    
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        // Đặt tên file mới để tránh trùng (thêm thời gian vào đầu)
        $new_filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $main_image = $new_filename;
        } else {
            $message = "Lỗi: Không tải được ảnh lên.";
        }
    }

    // Lưu vào Database
    if ($main_image) {
        $sql = "INSERT INTO homestays (name, district, address, description, price_weekday, price_weekend, price_extra_guest, max_guests, num_bedrooms, num_beds, main_image) 
                VALUES ('$name', '$district', '$address', '$description', '$price_weekday', '$price_weekend', '$price_extra', '$max_guests', '$num_bedrooms', '$num_beds', '$main_image')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "✅ Thêm homestay thành công!";
        } else {
            $message = "❌ Lỗi Database: " . $conn->error;
        }
    } else {
        $message = "❌ Vui lòng chọn ảnh đại diện!";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Homestay Mới</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet"/>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gray-900 text-white p-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Thêm Homestay Mới</h1>
            <a href="dashboard.php" class="text-sm hover:underline">Quay lại Dashboard</a>
        </div>

        <?php if($message): ?>
            <div class="p-4 mb-4 text-center font-bold <?php echo strpos($message, '✅') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tên Homestay</label>
                    <input type="text" name="name" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 outline-none" placeholder="VD: Villa Rừng Thông...">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Khu vực (Quận/Huyện)</label>
                    <select name="district" class="w-full border rounded-lg p-3">
                        <option>Tây Hồ</option>
                        <option>Hoàn Kiếm</option>
                        <option>Ba Đình</option>
                        <option>Sóc Sơn</option>
                        <option>Ba Vì</option>
                        <option>Sơn Tây</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Địa chỉ chi tiết</label>
                <input type="text" name="address" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 outline-none" placeholder="VD: Thôn Lâm Trường, Xã Minh Phú...">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Giá Ngày Thường (VNĐ)</label>
                    <input type="number" name="price_weekday" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Giá Cuối Tuần (VNĐ)</label>
                    <input type="number" name="price_weekend" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Phụ phí thêm khách</label>
                    <input type="number" name="price_extra_guest" value="0" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 bg-gray-50 p-4 rounded-lg">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số khách tối đa</label>
                    <input type="number" name="max_guests" value="2" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số phòng ngủ</label>
                    <input type="number" name="num_bedrooms" value="1" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số giường</label>
                    <input type="number" name="num_beds" value="1" class="w-full border rounded p-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả chi tiết (Copy từ Word)</label>
                <textarea name="description" rows="10" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 outline-none" placeholder="Dán nội dung giới thiệu, tiện ích vào đây..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Ảnh đại diện</label>
                <input type="file" name="image" required class="w-full border p-2 bg-white rounded cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">* Ảnh sẽ tự động được lưu vào thư mục uploads</p>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg shadow-lg text-lg transition transform hover:scale-[1.01]">
                Lưu Homestay
            </button>
        </form>
    </div>

</body>
</html>