<?php
session_start();

// 1. KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "homestays");
$conn->set_charset("utf8mb4"); // Hiển thị tiếng Việt

// 2. LẤY ID TỪ URL
$homestay_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM homestays WHERE homestay_id = $homestay_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $homestay = $result->fetch_assoc();
} else {
    echo "<h1>Không tìm thấy Homestay này!</h1>";
    exit();
}

// 3. XỬ LÝ ẢNH (LOGIC MỚI)
// Lấy tên file ảnh chính (ví dụ: ulesa_lemon.jpg)
$main_img_path = "uploads/" . $homestay['main_image'];
$base_name = pathinfo($homestay['main_image'], PATHINFO_FILENAME); // Lấy "ulesa_lemon"

// Tạo danh sách ảnh để hiển thị
$gallery = [];

// Nếu ảnh chính tồn tại trong thư mục uploads thì lấy, không thì lấy ảnh mạng
if (file_exists($main_img_path) && !empty($homestay['main_image'])) {
    $gallery[] = $main_img_path;
    
    // Tự động tìm thêm ảnh phụ (ulesa_lemon_1.jpg, ulesa_lemon_2.jpg...)
    for ($i = 1; $i <= 4; $i++) {
        $extra_img = "uploads/" . $base_name . "_" . $i . ".jpg";
        if (file_exists($extra_img)) {
            $gallery[] = $extra_img;
        }
    }
} else {
    // Nếu chưa có ảnh trong máy, dùng ảnh mẫu trên mạng
    $gallery[] = "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?q=80&w=1920";
}

// Nếu ít ảnh quá (dưới 5), chèn thêm ảnh mẫu cho đẹp giao diện
while (count($gallery) < 5) {
    $gallery[] = "https://images.unsplash.com/photo-1584622050111-993a426fbf0a?q=80&w=800";
}
?>

<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $homestay['name']; ?> - HomestayApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; } 
    </style>
</head>
<body class="bg-white text-gray-800">
    
    <header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 h-16 flex items-center justify-between">
            <a href="trang_chu.php" class="flex items-center gap-2 hover:opacity-80">
                <span class="material-symbols-outlined text-[#13ecc8] text-3xl">other_houses</span>
                <h2 class="text-xl font-bold">HomestayApp</h2>
            </a>
            
            <div class="hidden md:flex items-center bg-gray-100 rounded-full px-4 py-2 border border-gray-200 text-sm font-medium">
                <span class="px-2">Bất cứ đâu</span> | <span class="px-2">Tuần bất kỳ</span> | <span class="px-2 text-gray-500">Thêm khách</span>
                <div class="bg-[#13ecc8] rounded-full p-1 ml-2 text-white"><span class="material-symbols-outlined text-sm block">search</span></div>
            </div>

            <div class="flex items-center gap-3">
                <?php if(isset($_SESSION['fullname'])): ?>
                    <span class="font-bold text-sm">Chào, <?php echo $_SESSION['fullname']; ?></span>
                <?php else: ?>
                    <a href="dangnhap.php" class="text-sm font-bold hover:bg-gray-100 px-4 py-2 rounded-full">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 mb-2">
                <?php echo $homestay['name']; ?>
            </h1>
            <div class="flex flex-wrap items-center justify-between gap-4 text-sm">
                <div class="flex items-center gap-2 underline font-medium">
                    <span class="material-symbols-outlined text-sm filled text-yellow-500">star</span>
                    <span>5.0 · 18 đánh giá</span> · 
                    <span class="text-gray-600"><?php echo $homestay['address']; ?>, <?php echo $homestay['district']; ?></span>
                </div>
                <div class="flex gap-4">
                     <button class="flex items-center gap-1 hover:bg-gray-100 px-2 py-1 rounded-lg"><span class="material-symbols-outlined text-lg">share</span> Chia sẻ</button>
                     <button class="flex items-center gap-1 hover:bg-gray-100 px-2 py-1 rounded-lg"><span class="material-symbols-outlined text-lg">favorite</span> Lưu</button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-2 h-[300px] md:h-[450px] rounded-xl overflow-hidden mb-12">
            <div class="col-span-2 row-span-2 relative group cursor-pointer">
                <img src="<?php echo $gallery[0]; ?>" class="w-full h-full object-cover hover:brightness-95 transition">
            </div>
            
            <div class="hidden md:block relative cursor-pointer"><img src="<?php echo $gallery[1]; ?>" class="w-full h-full object-cover hover:brightness-95 transition"></div>
            <div class="hidden md:block relative cursor-pointer"><img src="<?php echo $gallery[2]; ?>" class="w-full h-full object-cover hover:brightness-95 transition"></div>
            <div class="hidden md:block relative cursor-pointer"><img src="<?php echo $gallery[3]; ?>" class="w-full h-full object-cover hover:brightness-95 transition"></div>
            <div class="hidden md:block relative cursor-pointer">
                <img src="<?php echo $gallery[4]; ?>" class="w-full h-full object-cover hover:brightness-95 transition">
                <button class="absolute bottom-4 right-4 bg-white text-black text-xs font-bold px-3 py-1.5 rounded-lg shadow-md border border-gray-300 hover:bg-gray-50">
                    Xem tất cả ảnh
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 relative">
            
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center pb-6 border-b border-gray-200">
                    <div>
                        <h2 class="text-xl font-bold">Toàn bộ căn tại <?php echo $homestay['district']; ?></h2>
                        <p class="text-gray-600 text-sm mt-1">
                            <?php echo $homestay['max_guests']; ?> khách · <?php echo $homestay['num_bedrooms']; ?> phòng ngủ · <?php echo $homestay['num_beds']; ?> giường
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-gray-200 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="py-8 border-b border-gray-200 space-y-6">
                    <div class="flex gap-4">
                        <span class="material-symbols-outlined text-2xl text-gray-600">workspace_premium</span>
                        <div><h3 class="font-bold">Chủ nhà siêu cấp</h3><p class="text-gray-500 text-sm">Chủ nhà có kinh nghiệm dày dặn được đánh giá cao.</p></div>
                    </div>
                    <div class="flex gap-4">
                        <span class="material-symbols-outlined text-2xl text-gray-600">location_on</span>
                        <div><h3 class="font-bold">Vị trí tuyệt vời</h3><p class="text-gray-500 text-sm">90% khách gần đây đánh giá 5 sao cho vị trí này.</p></div>
                    </div>
                </div>

                <div class="py-8 border-b border-gray-200">
                    <h3 class="text-xl font-bold mb-4">Về chỗ ở này</h3>
                    <div class="text-gray-700 leading-relaxed space-y-4 text-justify">
    <?php 
        // Hàm nl2br giúp chuyển các dấu xuống dòng trong dữ liệu thành thẻ <br> của web
        echo nl2br($homestay['description']); 
    ?>
</div>
                </div>

                <div class="py-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold mb-6">Nơi này có những gì</h2>
                    <div class="grid grid-cols-2 gap-4 text-gray-700">
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">wifi</span> Wifi tốc độ cao</div>
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">ac_unit</span> Điều hòa nhiệt độ</div>
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">kitchen</span> Bếp đầy đủ tiện nghi</div>
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">pool</span> Bể bơi (nếu có)</div>
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">tv</span> Smart TV</div>
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">local_parking</span> Chỗ đỗ xe miễn phí</div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 relative">
                <div class="sticky top-24">
                    <div class="border border-gray-200 rounded-xl p-6 shadow-xl bg-white">
                        <div class="flex justify-between items-end mb-6">
                            <div>
                                <span class="text-2xl font-bold text-gray-900">
                                    <?php echo number_format($homestay['price_weekday'], 0, ',', '.'); ?>₫
                                </span>
                                <span class="text-gray-500 text-sm"> / đêm</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-bold">
                                <span class="material-symbols-outlined text-sm text-yellow-500 filled">star</span> 5.0
                            </div>
                        </div>

                        <form action="xuly_datphong.php" method="POST">
                            <input type="hidden" name="homestay_id" value="<?php echo $homestay['id']; ?>">
                            <input type="hidden" name="price_per_night" value="<?php echo $homestay['price_weekday']; ?>">
                            
                            <div class="border border-gray-400 rounded-lg overflow-hidden mb-4">
                                <div class="flex border-b border-gray-400">
                                    <div class="w-1/2 p-3 border-r border-gray-400">
                                        <label class="block text-[10px] font-bold uppercase text-gray-800">Nhận phòng</label>
                                        <input type="date" name="check_in" required class="w-full p-0 border-none text-sm text-gray-600 focus:ring-0 cursor-pointer">
                                    </div>
                                    <div class="w-1/2 p-3">
                                        <label class="block text-[10px] font-bold uppercase text-gray-800">Trả phòng</label>
                                        <input type="date" name="check_out" required class="w-full p-0 border-none text-sm text-gray-600 focus:ring-0 cursor-pointer">
                                    </div>
                                </div>
                                <div class="p-3">
                                    <label class="block text-[10px] font-bold uppercase text-gray-800">Khách</label>
                                    <select name="guests" class="w-full p-0 border-none text-sm text-gray-600 focus:ring-0 bg-transparent">
                                        <option value="1">1 khách</option>
                                        <option value="2">2 khách</option>
                                        <option value="4">4 khách</option>
                                        <option value="<?php echo $homestay['max_guests']; ?>">Tối đa (<?php echo $homestay['max_guests']; ?> người)</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-[#E51D54] hover:bg-[#d41b4e] text-white font-bold py-3.5 rounded-lg mb-4 transition-colors text-lg">
                                Đặt phòng ngay
                            </button>
                        </form>

                        <p class="text-center text-sm text-gray-500 mb-4">Bạn vẫn chưa bị trừ tiền</p>
                        
                       <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
    <h3 class="font-bold text-gray-800 mb-3 border-b pb-2">Chi tiết giá phòng</h3>
    
    <div class="flex justify-between text-sm mb-2">
        <span class="text-gray-600">Thứ 2 - Thứ 5</span>
        <span class="font-bold text-gray-900"><?php echo number_format($homestay['price_weekday'], 0, ',', '.'); ?>₫</span>
    </div>
    
    <div class="flex justify-between text-sm mb-2">
        <span class="text-gray-600">Thứ 6 - Chủ Nhật</span>
        <span class="font-bold text-[#E51D54]"><?php echo number_format($homestay['price_weekend'], 0, ',', '.'); ?>₫</span>
    </div>

    <div class="flex justify-between text-sm">
        <span class="text-gray-600">Phí khách tăng thêm</span>
        <span class="font-medium text-gray-900">
            <?php echo ($homestay['price_extra_guest'] > 0) ? number_format($homestay['price_extra_guest'], 0, ',', '.').'₫' : 'Miễn phí'; ?>
        </span>
    </div>
    
    <p class="text-xs text-gray-400 mt-2 italic">* Giá có thể thay đổi theo ngày lễ</p>
</div>
                        <hr class="my-4 border-gray-200">
                        <div class="flex justify-between font-bold text-lg text-gray-900">
                            <span>Tổng tiền (ước tính)</span>
                            <span><?php echo number_format($homestay['price_weekday'], 0, ',', '.'); ?>₫</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-100 border-t border-gray-200 mt-12 py-8 text-center">
        <p class="text-gray-500">© 2024 HomestayApp. All rights reserved.</p>
    </footer>
</body>
</html>