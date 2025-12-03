<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: dangnhap.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$message = "";
$homestay = null;

// Lấy ID homestay từ URL
$homestay_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($homestay_id <= 0) {
    header("Location: qly_home.php");
    exit();
}

// XỬ LÝ XÓA ẢNH PHỤ
if (isset($_GET['delete_image'])) {
    $image_id = intval($_GET['delete_image']);
    
    // Lấy thông tin ảnh
    $stmt = $conn->prepare("SELECT image_path FROM images WHERE image_id = ? AND homestay_id = ?");
    $stmt->bind_param("ii", $image_id, $homestay_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $image = $result->fetch_assoc();
    $stmt->close();
    
    if ($image) {
        // Xóa file ảnh vật lý
        if (file_exists("uploads/" . $image['image_path'])) {
            unlink("uploads/" . $image['image_path']);
        }
        
        // Xóa record trong database
        $stmt = $conn->prepare("UPDATE images SET deleted_at = NOW() WHERE image_id = ?");
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: sua_homestay.php?id=$homestay_id&msg=deleted");
        exit();
    }
}

// Lấy thông tin homestay hiện tại
$stmt = $conn->prepare("SELECT * FROM homestays WHERE homestay_id = ? AND deleted_at IS NULL");
$stmt->bind_param("i", $homestay_id);
$stmt->execute();
$result = $stmt->get_result();
$homestay = $result->fetch_assoc();
$stmt->close();

if (!$homestay) {
    header("Location: qly_home.php");
    exit();
}

// Lấy danh sách ảnh từ bảng images
$existing_images = $conn->query("SELECT * FROM images WHERE homestay_id = $homestay_id AND deleted_at IS NULL ORDER BY is_primary DESC, image_id ASC");

// Xử lý khi submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $district = $conn->real_escape_string($_POST['district']);
    $address = $conn->real_escape_string($_POST['address']);
    $description = $conn->real_escape_string($_POST['description']);
    
    $price_weekday = (int)str_replace(['.', ','], '', $_POST['price_weekday']);
    $price_weekend = (int)str_replace(['.', ','], '', $_POST['price_weekend']);
    $price_extra = (int)str_replace(['.', ','], '', $_POST['price_extra_guest']);
    
    $max_guests = (int)$_POST['max_guests'];
    $num_bedrooms = (int)$_POST['num_bedrooms'];
    $num_beds = (int)$_POST['num_beds'];
    // Cập nhật thông tin homestay
    $sql = "UPDATE homestays SET 
            name = '$name',
            district = '$district',
            address = '$address',
            description = '$description',
            price_weekday = $price_weekday,
            price_weekend = $price_weekend,
            price_extra_guest = $price_extra,
            max_guests = $max_guests,
            num_bedrooms = $num_bedrooms,
            num_beds = $num_beds
            WHERE homestay_id = $homestay_id";
    
    if ($conn->query($sql) === TRUE) {
        // Upload ảnh vào bảng images
        if (isset($_FILES["images"]) && !empty($_FILES["images"]["name"][0])) {
            $target_dir = "uploads/";
            $upload_count = 0;
            $total_files = count($_FILES["images"]["name"]);
            
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES["images"]["error"][$i] == 0) {
                    $image_filename = time() . "_" . $i . "_" . basename($_FILES["images"]["name"][$i]);
                    $image_path = $target_dir . $image_filename;
                    
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $image_path)) {
                        // Mặc định is_primary = 0.
                        $sql_image = "INSERT INTO images (homestay_id, image_path, is_primary) 
                                     VALUES ($homestay_id, '$image_filename', 0)";
                        
                        if ($conn->query($sql_image)) {
                            $upload_count++;
                        }
                    }
                }
            }
            
            $message = "✅ Cập nhật homestay thành công! Đã thêm $upload_count ảnh mới.";
        } else {
            $message = "✅ Cập nhật homestay thành công!";
        }
        
        // Refresh lại dữ liệu
        $stmt = $conn->prepare("SELECT * FROM homestays WHERE homestay_id = ?");
        $stmt->bind_param("i", $homestay_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $homestay = $result->fetch_assoc();
        $stmt->close();
        
        $existing_images = $conn->query("SELECT * FROM images WHERE homestay_id = $homestay_id AND deleted_at IS NULL ORDER BY is_primary DESC, image_id ASC");
    } else {
        $message = "❌ Lỗi Database: " . $conn->error;
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $message = "✅ Đã xóa ảnh thành công!";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Homestay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        .image-item {
            position: relative;
            padding-bottom: 100%;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e5e7eb;
        }
        .image-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 38, 38, 0.9);
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            z-index: 10;
        }
        .delete-btn:hover {
            background: rgba(185, 28, 28, 1);
        }
        .primary-badge {
            position: absolute;
            top: 5px;
            left: 5px;
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gray-900 text-white p-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Sửa Homestay #<?php echo $homestay['homestay_id']; ?></h1>
            <a href="qly_home.php" class="text-sm hover:underline">← Quay lại</a>
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
                    <input type="text" name="name" value="<?php echo htmlspecialchars($homestay['name']); ?>" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Khu vực (Quận/Huyện)</label>
                    <select name="district" class="w-full border rounded-lg p-3">
                        <option <?php echo $homestay['district'] == 'Tây Hồ' ? 'selected' : ''; ?>>Tây Hồ</option>
                        <option <?php echo $homestay['district'] == 'Hoàn Kiếm' ? 'selected' : ''; ?>>Hoàn Kiếm</option>
                        <option <?php echo $homestay['district'] == 'Ba Đình' ? 'selected' : ''; ?>>Ba Đình</option>
                        <option <?php echo $homestay['district'] == 'Sóc Sơn' ? 'selected' : ''; ?>>Sóc Sơn</option>
                        <option <?php echo $homestay['district'] == 'Ba Vì' ? 'selected' : ''; ?>>Ba Vì</option>
                        <option <?php echo $homestay['district'] == 'Sơn Tây' ? 'selected' : ''; ?>>Sơn Tây</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Địa chỉ chi tiết</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($homestay['address']); ?>" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Giá Ngày Thường (VNĐ)</label>
                    <input type="number" name="price_weekday" value="<?php echo $homestay['price_weekday']; ?>" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Giá Cuối Tuần (VNĐ)</label>
                    <input type="number" name="price_weekend" value="<?php echo $homestay['price_weekend']; ?>" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Phụ phí thêm khách</label>
                    <input type="number" name="price_extra_guest" value="<?php echo $homestay['price_extra_guest']; ?>" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 bg-gray-50 p-4 rounded-lg">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số khách tối đa</label>
                    <input type="number" name="max_guests" value="<?php echo $homestay['max_guests']; ?>" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số phòng ngủ</label>
                    <input type="number" name="num_bedrooms" value="<?php echo $homestay['num_bedrooms']; ?>" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số giường</label>
                    <input type="number" name="num_beds" value="<?php echo $homestay['num_beds']; ?>" class="w-full border rounded p-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả chi tiết</label>
                <textarea name="description" rows="10" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none"><?php echo htmlspecialchars($homestay['description']); ?></textarea>
            </div>

            <div class="border-t pt-6">
                <label class="block text-sm font-bold text-gray-700 mb-3">📸 Thư viện ảnh (<?php echo $existing_images->num_rows; ?> ảnh)</label>
                
                <?php if($existing_images && $existing_images->num_rows > 0): ?>
                <div class="image-grid mb-4">
                    <?php while($img = $existing_images->fetch_assoc()): ?>
                    <div class="image-item">
                        <?php if($img['is_primary']): ?>
                        <span class="primary-badge">PRIMARY</span>
                        <?php endif; ?>
                        <img src="uploads/<?php echo htmlspecialchars($img['image_path']); ?>" alt="Image">
                        
                        <a href="?id=<?php echo $homestay_id; ?>&delete_image=<?php echo $img['image_id']; ?>" 
                           onclick="return confirm('Xóa ảnh này?')" 
                           class="delete-btn">×</a>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <p class="text-gray-500 text-sm mb-4">Chưa có ảnh nào.</p>
                <?php endif; ?>
            </div>

            <div class="border-t pt-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">➕ Thêm ảnh mới</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full border p-2 bg-white rounded cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">* Chọn nhiều ảnh cùng lúc bằng Ctrl/Cmd + Click</p>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg shadow-lg text-lg transition transform hover:scale-[1.01]">
                    💾 Lưu thay đổi
                </button>
                <a href="qly_home.php" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-4 rounded-lg text-center text-lg transition">
                    ❌ Hủy bỏ
                </a>
            </div>
        </form>
    </div>

</body>
</html>
<?php $conn->close(); ?>