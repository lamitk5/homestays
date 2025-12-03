<?php
session_start();

// 1. KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 🎯 LƯU Ý: Đã xóa đoạn code tự động chuyển hướng Admin tại đây để Admin có thể xem trang chủ.

$user_fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : '';

// 2. XỬ LÝ TÌM KIẾM VÀ LOGIC CHECK LỊCH
$where_clause = "1=1"; 

// Lọc địa điểm
if (isset($_GET['location']) && !empty($_GET['location'])) {
    $location = $conn->real_escape_string($_GET['location']);
    $where_clause .= " AND (h.district LIKE '%$location%' OR h.name LIKE '%$location%' OR h.address LIKE '%$location%')";
}

// Lọc số khách
if (isset($_GET['guests']) && !empty($_GET['guests'])) {
    $guests = intval($_GET['guests']);
    $where_clause .= " AND h.max_guests >= $guests";
}

// 🎯 LOGIC KIỂM TRA TRẠNG THÁI "ĐÃ ĐẶT" (BOOKED)
// Mặc định coi là chưa đặt (0)
$is_booked_column = "0 AS is_booked"; 
$searching_dates = false; // Biến cờ để biết khách có đang tìm theo ngày không

if (isset($_GET['date_in']) && !empty($_GET['date_in']) && isset($_GET['date_out']) && !empty($_GET['date_out'])) {
    $searching_dates = true;
    $d_in = $conn->real_escape_string($_GET['date_in']);
    $d_out = $conn->real_escape_string($_GET['date_out']);
    
    // Subquery kiểm tra trùng lịch:
    // Nếu tồn tại booking nào có thời gian giao thoa với thời gian khách tìm -> Trả về 1 (True)
    $is_booked_column = "
        (SELECT COUNT(*) FROM bookings b 
         WHERE b.homestay_id = h.homestay_id 
         AND b.status = 'confirmed'       -- Chỉ tính đơn đã xác nhận
         AND b.deleted_at IS NULL         -- Bỏ qua đơn đã xóa
         AND ('$d_in' < b.check_out AND '$d_out' > b.check_in) -- Logic giao thoa ngày
        ) > 0 AS is_booked
    ";
}

// SQL QUERY CHÍNH
$sql = "
    SELECT 
        h.*, 
        i.image_path AS main_image_path,
        $is_booked_column
    FROM homestays h
    LEFT JOIN images i ON h.homestay_id = i.homestay_id AND i.is_primary = 1
    WHERE $where_clause 
    ORDER BY is_booked ASC, h.homestay_id DESC -- Ưu tiên hiện phòng trống trước
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Trang chủ - HomestayApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { vertical-align: middle; }
        .slide { 
            position: absolute; inset: 0; width: 100%; height: 100%; 
            background-size: cover; background-position: center; 
            transition: opacity 1.5s ease-in-out; 
        }
        /* Style cho nhãn đã đặt */
        .booked-overlay {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(2px);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
    
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="trang_chu.php" class="flex items-center gap-2 hover:opacity-80 transition">
                <span class="material-symbols-outlined text-[#13ecc8] text-4xl">other_houses</span>
                <span class="font-extrabold text-2xl tracking-tight text-gray-900">Homestay<span class="text-[#13ecc8]">App</span></span>
            </a>
            
            <nav class="hidden md:flex gap-8 font-medium text-gray-600">
                <a href="trang_chu.php" class="text-[#13ecc8] font-bold">Trang chủ</a>
                <a href="kham_pha.php" class="hover:text-[#13ecc8] transition">Khám phá</a>
                <a href="blog.php" class="hover:text-[#13ecc8] transition">Blog</a>
            </nav>

            <div class="flex items-center gap-4">
                <?php if(isset($_SESSION['fullname'])): ?>
                    <div class="flex items-center gap-3">
                        
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="dashboard.php" class="hidden md:flex items-center gap-1 bg-gray-900 text-white px-3 py-1.5 rounded-full text-xs font-bold hover:bg-gray-700 transition shadow-md border border-gray-700">
                                <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
                                Quản trị
                            </a>
                        <?php endif; ?>
                        <a href="chitiet_kh.php" class="text-sm font-medium hidden sm:block hover:text-[#13ecc8] transition">
                             Hi, <b><?php echo htmlspecialchars($user_fullname); ?></b>
                        </a>
                        <a href="logout.php" class="text-sm text-red-500 font-bold hover:underline">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <a href="dangnhap.php" class="text-sm font-bold text-gray-600 hover:text-[#13ecc8] transition">Đăng nhập</a>
                    <a href="dangky.php" class="bg-[#13ecc8] text-white px-5 py-2.5 rounded-full font-bold text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all">Đăng ký ngay</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="relative h-[600px] w-full overflow-hidden group bg-gray-900">
        <div id="slider-container" class="absolute inset-0 w-full h-full">
            <div class="slide opacity-100 z-10" style="background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1920');"></div>
            <div class="slide opacity-0 z-0" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1920');"></div>
            <div class="slide opacity-0 z-0" style="background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=1920');"></div>
        </div>

        <div class="absolute inset-0 bg-black/40 z-20 pointer-events-none"></div>

        <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center z-30">
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 drop-shadow-xl tracking-tight leading-tight">
                Tìm kiếm chốn dừng chân hoàn hảo
            </h1>
            
            <form action="trang_chu.php" method="GET" class="bg-white p-2 md:p-3 rounded-[32px] shadow-2xl max-w-4xl w-full flex flex-col md:flex-row gap-2 items-stretch md:items-center relative z-40">
                <div class="flex-1 px-4 py-2 border-b md:border-b-0 md:border-r border-gray-200 relative group text-left">
                    <label class="block text-[10px] md:text-xs font-bold text-gray-800 ml-7 uppercase tracking-wider mb-1">Địa điểm</label>
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#13ecc8] transition">search</span>
                    <input type="text" name="location" class="w-full pl-7 pr-2 py-1 outline-none text-gray-700 font-bold placeholder-gray-400 bg-transparent text-sm md:text-base" placeholder="Bạn muốn đi đâu?" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                </div>

                <div class="w-full md:w-40 px-4 py-2 border-b md:border-b-0 md:border-r border-gray-200 text-left">
                    <label class="block text-[10px] md:text-xs font-bold text-gray-800 uppercase tracking-wider mb-1">Ngày đến</label>
                    <input type="text" id="checkin" name="date_in" placeholder="Thêm ngày" value="<?php echo isset($_GET['date_in']) ? htmlspecialchars($_GET['date_in']) : ''; ?>" class="w-full py-1 outline-none text-gray-600 font-medium bg-transparent cursor-pointer text-sm">
                </div>

                <div class="w-full md:w-40 px-4 py-2 border-b md:border-b-0 md:border-r border-gray-200 text-left">
                    <label class="block text-[10px] md:text-xs font-bold text-gray-800 uppercase tracking-wider mb-1">Ngày đi</label>
                    <input type="text" id="checkout" name="date_out" placeholder="Thêm ngày" value="<?php echo isset($_GET['date_out']) ? htmlspecialchars($_GET['date_out']) : ''; ?>" class="w-full py-1 outline-none text-gray-600 font-medium bg-transparent cursor-pointer text-sm">
                </div>

                <div class="w-full md:w-40 px-4 py-2 relative text-left">
                    <label class="block text-[10px] md:text-xs font-bold text-gray-800 ml-6 uppercase tracking-wider mb-1">Số khách</label>
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">group_add</span>
                    <input type="number" name="guests" min="1" class="w-full pl-6 pr-2 py-1 outline-none text-gray-700 font-bold bg-transparent placeholder-gray-400 text-sm md:text-base" placeholder="Khách" value="<?php echo isset($_GET['guests']) ? htmlspecialchars($_GET['guests']) : ''; ?>">
                </div>

                <button type="submit" class="bg-[#13ecc8] hover:bg-[#10d4b4] text-white rounded-[24px] px-8 py-3 md:py-4 font-bold text-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </form>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-16 w-full flex-grow">
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                <?php 
                if($searching_dates) 
                    echo 'Kết quả từ ' . date('d/m', strtotime($_GET['date_in'])) . ' đến ' . date('d/m', strtotime($_GET['date_out']));
                else if(isset($_GET['location']) && !empty($_GET['location'])) 
                    echo 'Kết quả cho "' . htmlspecialchars($_GET['location']) . '"';
                else 
                    echo 'Khám phá điểm đến nổi bật';
                ?>
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php 
            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()): 
                    $image_src = "https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800";
                    if (!empty($row['main_image_path'])) {
                        $image_src = "uploads/" . htmlspecialchars($row['main_image_path']);
                    }
                    
                    // Kiểm tra trạng thái booked
                    $is_booked = $row['is_booked'] == 1;
            ?>
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group border border-gray-100 flex flex-col h-full overflow-hidden relative">
                
                <a href="chi_tiet_home.php?id=<?php echo $row['homestay_id']; ?>" class="block relative h-64 overflow-hidden">
                    
                    <img src="<?php echo $image_src; ?>" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 <?php echo $is_booked ? 'grayscale-[50%]' : ''; ?>"
                         alt="<?php echo $row['name']; ?>">
                    
                    <?php if ($is_booked): ?>
                        <div class="absolute inset-0 bg-black/50 z-10 flex items-center justify-center">
                            <div class="bg-red-600 text-white font-bold px-4 py-2 rounded-lg transform -rotate-6 border-2 border-white shadow-2xl flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">event_busy</span>
                                ĐÃ KÍN LỊCH
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold shadow-sm flex items-center gap-1 text-gray-800 z-20">
                        <span class="material-symbols-outlined text-[16px] text-red-500">location_on</span>
                        <?php echo $row['district']; ?>
                    </div>
                </a>
                
                <div class="p-5 flex flex-col flex-1 <?php echo $is_booked ? 'bg-gray-50' : ''; ?>">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-gray-900 line-clamp-2 leading-tight flex-1 mr-2">
                            <a href="chi_tiet_home.php?id=<?php echo $row['homestay_id']; ?>" class="hover:text-[#13ecc8] transition">
                                <?php echo $row['name']; ?>
                            </a>
                        </h3>
                        <div class="flex items-center gap-1 text-xs font-bold bg-gray-100 px-2 py-1 rounded">
                            <span class="material-symbols-outlined text-[14px] text-orange-500" style="font-variation-settings: 'FILL' 1;">star</span> 
                            <?php echo isset($row['rating']) ? $row['rating'] : '5.0'; ?> 
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-xs text-gray-500 font-medium mb-4 pt-3 border-t border-dashed border-gray-100 mt-auto">
                        <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">bed</span> <?php echo $row['num_bedrooms']; ?> PN</div>
                        <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">group</span> Max <?php echo $row['max_guests']; ?></div>
                    </div>

                    <div class="flex justify-between items-end">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Giá từ</span>
                            <div class="<?php echo $is_booked ? 'text-gray-400 decoration-gray-400' : 'text-[#13ecc8]'; ?> font-black text-xl">
                                <?php echo number_format($row['price_weekday'], 0, ',', '.'); ?>₫
                            </div>
                        </div>
                        
                        <?php if ($is_booked): ?>
                            <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-bold text-sm cursor-not-allowed flex items-center gap-1">
                                Đã đặt
                            </button>
                        <?php else: ?>
                            <a href="chi_tiet_home.php?id=<?php echo $row['homestay_id']; ?>" class="bg-gray-900 text-white p-2 rounded-lg hover:bg-[#13ecc8] transition shadow-lg">
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
                <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-xl font-bold text-gray-600">Không tìm thấy homestay nào phù hợp.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-4 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#13ecc8] text-3xl">other_houses</span> HomestayApp
            </h2>
            <p class="text-gray-400 text-sm">© 2024 HomestayApp. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // SLIDER
        const slides = document.querySelectorAll('.slide');
        let currentSlide = 0;
        function showSlide(index) {
            slides.forEach((slide) => {
                slide.classList.remove('z-10', 'opacity-100');
                slide.classList.add('z-0', 'opacity-0');
            });
            slides[index].classList.remove('z-0', 'opacity-0');
            slides[index].classList.add('z-10', 'opacity-100');
        }
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        setInterval(nextSlide, 5000);

        // DATE LOGIC
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');

        function getTodayDate() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }

        checkinInput.addEventListener('focus', function() {
            this.type = 'date';
            this.min = getTodayDate();
        });

        checkinInput.addEventListener('change', function() {
            const checkinDate = this.value;
            if (checkoutInput.value && checkoutInput.value <= checkinDate) {
                checkoutInput.value = '';
            }
            checkoutInput.min = checkinDate;
        });

        checkoutInput.addEventListener('focus', function() {
            this.type = 'date';
            if (checkinInput.value) {
                this.min = checkinInput.value;
            } else {
                this.min = getTodayDate();
            }
        });

        checkinInput.addEventListener('blur', function() { if (!this.value) this.type = 'text'; });
        checkoutInput.addEventListener('blur', function() { if (!this.value) this.type = 'text'; });
    </script>
</body>
</html>