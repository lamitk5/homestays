<?php
session_start();

// 1. KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "homestays");
$conn->set_charset("utf8mb4");

// 2. LẤY ID TỪ URL
$homestay_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin homestay
$sql = "SELECT * FROM homestays WHERE homestay_id = $homestay_id AND deleted_at IS NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $homestay = $result->fetch_assoc();
} else {
    echo "<h1>Không tìm thấy Homestay này!</h1>";
    exit();
}

// 3. XỬ LÝ ẢNH - LẤY TỪ DATABASE
$gallery = [];

// Lấy ảnh từ bảng images
$sql_img = "SELECT image_path FROM images WHERE homestay_id = $homestay_id AND deleted_at IS NULL ORDER BY is_primary DESC, image_id ASC";
$result_img = $conn->query($sql_img);

if ($result_img->num_rows > 0) {
    while($row = $result_img->fetch_assoc()) {
        // Kiểm tra file có tồn tại không
        $image_path = "uploads/" . $row['image_path'];
        if (file_exists($image_path)) {
            $gallery[] = $image_path;
        }
    }
}

// Nếu không có ảnh trong bảng images, thử lấy ảnh chính từ bảng homestays
if (empty($gallery) && !empty($homestay['main_image'])) {
    $main_image_path = "uploads/" . $homestay['main_image'];
    if (file_exists($main_image_path)) {
        $gallery[] = $main_image_path;
    }
}

// Nếu vẫn không có ảnh nào, dùng ảnh placeholder
if (empty($gallery)) {
    $gallery[] = "https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800";
}

$today = date('Y-m-d'); 
$tomorrow = date('Y-m-d', strtotime('+1 day')); 
?>

<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo htmlspecialchars($homestay['name']); ?> - HomestayApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { vertical-align: middle; }
        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background-color: #f3f4f6;
        }
        .gallery-img:hover {
            filter: brightness(0.95);
            transition: all 0.3s;
        }
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
                    <span class="font-bold text-sm">Chào, <?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
                <?php else: ?>
                    <a href="dangnhap.php" class="text-sm font-bold hover:bg-gray-100 px-4 py-2 rounded-full">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 mb-2">
                <?php echo htmlspecialchars($homestay['name']); ?>
            </h1>
            <div class="flex flex-wrap items-center justify-between gap-4 text-sm">
                <div class="flex items-center gap-2 underline font-medium">
                    <span class="material-symbols-outlined text-sm filled text-yellow-500">star</span>
                    <span>5.0 · 18 đánh giá</span> · 
                    <span class="text-gray-600"><?php echo htmlspecialchars($homestay['address']); ?>, <?php echo htmlspecialchars($homestay['district']); ?></span>
                </div>
                <div class="flex gap-4">
                     <button class="flex items-center gap-1 hover:bg-gray-100 px-2 py-1 rounded-lg"><span class="material-symbols-outlined text-lg">share</span> Chia sẻ</button>
                     <button class="flex items-center gap-1 hover:bg-gray-100 px-2 py-1 rounded-lg"><span class="material-symbols-outlined text-lg">favorite</span> Lưu</button>
                </div>
            </div>
        </div>

        <?php 
            $count = count($gallery);
            if ($count > 0): 
        ?>
            <div class="h-[300px] md:h-[450px] rounded-xl overflow-hidden mb-12">
                
                <?php if ($count == 1): ?>
                    <img src="<?php echo htmlspecialchars($gallery[0]); ?>" 
                         alt="<?php echo htmlspecialchars($homestay['name']); ?>"
                         class="gallery-img cursor-pointer"
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'">

                <?php elseif ($count == 2): ?>
                    <div class="grid grid-cols-2 gap-2 h-full">
                        <img src="<?php echo htmlspecialchars($gallery[0]); ?>" 
                             alt="Image 1"
                             class="gallery-img cursor-pointer"
                             onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'">
                        <img src="<?php echo htmlspecialchars($gallery[1]); ?>" 
                             alt="Image 2"
                             class="gallery-img cursor-pointer"
                             onerror="this.src='https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=800'">
                    </div>

                <?php elseif ($count == 3): ?>
                    <div class="grid grid-cols-3 gap-2 h-full">
                        <div class="col-span-2 h-full">
                            <img src="<?php echo htmlspecialchars($gallery[0]); ?>" 
                                 alt="Image 1"
                                 class="gallery-img cursor-pointer"
                                 onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'">
                        </div>
                        <div class="grid grid-rows-2 gap-2 h-full">
                            <img src="<?php echo htmlspecialchars($gallery[1]); ?>" 
                                 alt="Image 2"
                                 class="gallery-img cursor-pointer"
                                 onerror="this.src='https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=800'">
                            <img src="<?php echo htmlspecialchars($gallery[2]); ?>" 
                                 alt="Image 3"
                                 class="gallery-img cursor-pointer"
                                 onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=800'">
                        </div>
                    </div>

                <?php elseif ($count == 4): ?>
                    <div class="grid grid-cols-2 gap-2 h-full">
                        <div class="h-full">
                            <img src="<?php echo htmlspecialchars($gallery[0]); ?>" 
                                 alt="Image 1"
                                 class="gallery-img cursor-pointer"
                                 onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'">
                        </div>
                        <div class="grid grid-rows-3 gap-2 h-full">
                            <img src="<?php echo htmlspecialchars($gallery[1]); ?>" 
                                 alt="Image 2"
                                 class="gallery-img cursor-pointer"
                                 onerror="this.src='https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=800'">
                            <img src="<?php echo htmlspecialchars($gallery[2]); ?>" 
                                 alt="Image 3"
                                 class="gallery-img cursor-pointer"
                                 onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=800'">
                            <img src="<?php echo htmlspecialchars($gallery[3]); ?>" 
                                 alt="Image 4"
                                 class="gallery-img cursor-pointer"
                                 onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=800'">
                        </div>
                    </div>

                <?php else: ?>
                    <div class="grid grid-cols-4 grid-rows-2 gap-2 h-full">
                        <div class="col-span-2 row-span-2 relative group cursor-pointer">
                            <img src="<?php echo htmlspecialchars($gallery[0]); ?>" 
                                 alt="Image 1"
                                 class="gallery-img"
                                 onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'">
                        </div>
                        <div class="relative cursor-pointer">
                            <img src="<?php echo htmlspecialchars($gallery[1]); ?>" 
                                 alt="Image 2"
                                 class="gallery-img"
                                 onerror="this.src='https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=800'">
                        </div>
                        <div class="relative cursor-pointer">
                            <img src="<?php echo htmlspecialchars($gallery[2]); ?>" 
                                 alt="Image 3"
                                 class="gallery-img"
                                 onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=800'">
                        </div>
                        <div class="relative cursor-pointer">
                            <img src="<?php echo htmlspecialchars($gallery[3]); ?>" 
                                 alt="Image 4"
                                 class="gallery-img"
                                 onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=800'">
                        </div>
                        <div class="relative cursor-pointer">
                            <img src="<?php echo htmlspecialchars($gallery[4]); ?>" 
                                 alt="Image 5"
                                 class="gallery-img"
                                 onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=800'">
                            <button class="absolute bottom-4 right-4 bg-white text-black text-xs font-bold px-3 py-1.5 rounded-lg shadow-md border border-gray-300 hover:bg-gray-50">
                                Xem tất cả <?php echo $count; ?> ảnh
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 relative">
            
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center pb-6 border-b border-gray-200">
                    <div>
                        <h2 class="text-xl font-bold">Toàn bộ căn tại <?php echo htmlspecialchars($homestay['district']); ?></h2>
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
                        <?php echo nl2br(htmlspecialchars($homestay['description'])); ?>
                    </div>
                </div>

                <div class="py-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold mb-6">Nơi này có những gì</h2>
                    <div class="grid grid-cols-2 gap-4 text-gray-700">
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">wifi</span> Wifi tốc độ cao</div>
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">ac_unit</span> Điều hòa nhiệt độ</div>
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">kitchen</span> Bếp đầy đủ tiện nghi</div>
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined">pool</span> Bể bơi</div>
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

                        <form action="xuly_datphong.php" method="POST" id="bookingForm">
                            <input type="hidden" name="homestay_id" value="<?php echo $homestay['homestay_id']; ?>">
                            <input type="hidden" name="price_weekday" value="<?php echo $homestay['price_weekday']; ?>">
                            <input type="hidden" name="price_weekend" value="<?php echo $homestay['price_weekend']; ?>">
                            
                            <div class="border border-gray-400 rounded-lg overflow-hidden mb-4">
                                <div class="flex border-b border-gray-400">
                                    <div class="w-1/2 p-3 border-r border-gray-400">
                                        <label class="block text-[10px] font-bold uppercase text-gray-800">Nhận phòng</label>
                                        <input type="date" name="check_in" id="check_in" 
                                               required 
                                               min="<?php echo $today; ?>"
                                               class="w-full p-0 border-none text-sm text-gray-600 focus:ring-0 cursor-pointer">
                                    </div>
                                    <div class="w-1/2 p-3">
                                        <label class="block text-[10px] font-bold uppercase text-gray-800">Trả phòng</label>
                                        <input type="date" name="check_out" id="check_out" 
                                               required 
                                               min="<?php echo $tomorrow; ?>"
                                               class="w-full p-0 border-none text-sm text-gray-600 focus:ring-0 cursor-pointer">
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

    <script>
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');

        checkIn.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            if (!isNaN(checkInDate.getTime())) {
                const nextDay = new Date(checkInDate);
                nextDay.setDate(checkInDate.getDate() + 1); 
                const nextDayString = nextDay.toISOString().split('T')[0];
                checkOut.min = nextDayString;

                if (checkOut.value && new Date(checkOut.value) <= checkInDate) {
                    checkOut.value = nextDayString;
                }
            }
        });
    </script>
</body>
</html>