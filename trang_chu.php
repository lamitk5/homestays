<?php
session_start();

// 1. KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "homestays");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 2. XỬ LÝ TÌM KIẾM
$where_clause = "1=1"; 

if (isset($_GET['location']) && !empty($_GET['location'])) {
    $location = $conn->real_escape_string($_GET['location']);
    $where_clause .= " AND (district LIKE '%$location%' OR name LIKE '%$location%' OR address LIKE '%$location%')";
}

if (isset($_GET['guests']) && !empty($_GET['guests'])) {
    $guests = intval($_GET['guests']);
    $where_clause .= " AND max_guests >= $guests";
}

$sql = "SELECT * FROM homestays WHERE $where_clause ORDER BY homestay_id DESC";
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
        /* Hiệu ứng chuyển cảnh chậm và mượt hơn */
        .slide { 
            position: absolute; 
            inset: 0; 
            width: 100%; 
            height: 100%; 
            background-size: cover; 
            background-position: center; 
            transition: opacity 1.5s ease-in-out; /* Tăng thời gian lên 1.5s cho mượt */
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
                <a href="ho_tro.php" class="hover:text-[#13ecc8] transition">Hỗ trợ</a>
            </nav>

            <div class="flex items-center gap-4">
                <?php if(isset($_SESSION['fullname'])): ?>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium hidden sm:block">Hi, <b><?php echo $_SESSION['fullname']; ?></b></span>
                        <?php if($_SESSION['role'] == 'admin'): ?>
                            <a href="dashboard.php" class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold hover:bg-blue-200 transition">Quản trị</a>
                        <?php endif; ?>
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
            <div class="slide opacity-0 z-0" style="background-image: url('https://images.unsplash.com/photo-1613490493576-2f5037657918?q=80&w=1920');"></div>
            <div class="slide opacity-0 z-0" style="background-image: url('https://images.unsplash.com/photo-1510798831971-661eb04b3739?q=80&w=1920');"></div>
        </div>

        <div class="absolute inset-0 bg-black/40 z-20 pointer-events-none"></div>

        <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-50 bg-white/20 hover:bg-white/40 text-white p-3 rounded-full backdrop-blur-sm transition opacity-0 group-hover:opacity-100 cursor-pointer border border-white/30">
            <span class="material-symbols-outlined text-3xl">chevron_left</span>
        </button>
        <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-50 bg-white/20 hover:bg-white/40 text-white p-3 rounded-full backdrop-blur-sm transition opacity-0 group-hover:opacity-100 cursor-pointer border border-white/30">
            <span class="material-symbols-outlined text-3xl">chevron_right</span>
        </button>

        <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center z-30">
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 drop-shadow-xl tracking-tight leading-tight">
                Tìm kiếm chốn dừng chân hoàn hảo
            </h1>
            <p class="text-base md:text-xl text-gray-100 font-medium mb-10 drop-shadow-md max-w-2xl">
                Khám phá hàng ngàn homestay độc đáo tại Hà Nội và vùng lân cận với giá tốt nhất.
            </p>

            <form action="trang_chu.php" method="GET" class="bg-white p-2 md:p-3 rounded-[32px] shadow-2xl max-w-4xl w-full flex flex-col md:flex-row gap-2 items-stretch md:items-center relative z-40">
                <div class="flex-1 px-4 py-2 border-b md:border-b-0 md:border-r border-gray-200 relative group text-left">
                    <label class="block text-[10px] md:text-xs font-bold text-gray-800 ml-7 uppercase tracking-wider mb-1">Địa điểm</label>
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#13ecc8] transition">search</span>
                    <input type="text" name="location" class="w-full pl-7 pr-2 py-1 outline-none text-gray-700 font-bold placeholder-gray-400 bg-transparent text-sm md:text-base" placeholder="Bạn muốn đi đâu? (VD: Tây Hồ)" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                </div>
                <div class="w-full md:w-40 px-4 py-2 border-b md:border-b-0 md:border-r border-gray-200 text-left">
                    <label class="block text-[10px] md:text-xs font-bold text-gray-800 uppercase tracking-wider mb-1">Ngày đến</label>
                    <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Thêm ngày" class="w-full py-1 outline-none text-gray-600 font-medium bg-transparent cursor-pointer text-sm">
                </div>
                <div class="w-full md:w-40 px-4 py-2 border-b md:border-b-0 md:border-r border-gray-200 text-left">
                    <label class="block text-[10px] md:text-xs font-bold text-gray-800 uppercase tracking-wider mb-1">Ngày đi</label>
                    <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Thêm ngày" class="w-full py-1 outline-none text-gray-600 font-medium bg-transparent cursor-pointer text-sm">
                </div>
                <div class="w-full md:w-40 px-4 py-2 relative text-left">
                    <label class="block text-[10px] md:text-xs font-bold text-gray-800 ml-6 uppercase tracking-wider mb-1">Số khách</label>
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">group_add</span>
                    <input type="number" name="guests" min="1" class="w-full pl-6 pr-2 py-1 outline-none text-gray-700 font-bold bg-transparent placeholder-gray-400 text-sm md:text-base" placeholder="Thêm khách" value="<?php echo isset($_GET['guests']) ? htmlspecialchars($_GET['guests']) : ''; ?>">
                </div>
                <button type="submit" class="bg-[#13ecc8] hover:bg-[#10d4b4] text-white rounded-[24px] px-8 py-3 md:py-4 font-bold text-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">search</span> Tìm
                </button>
            </form>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-16 w-full flex-grow">
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                <?php 
                if(isset($_GET['location']) && !empty($_GET['location'])) 
                    echo 'Kết quả tìm kiếm cho "' . htmlspecialchars($_GET['location']) . '"';
                else 
                    echo 'Khám phá điểm đến nổi bật';
                ?>
            </h2>
            
            <div class="flex flex-wrap gap-2">
                <a href="trang_chu.php" class="px-4 py-2 bg-black text-white border border-black rounded-full text-sm font-bold shadow-sm transition">Tất cả</a>
                <a href="trang_chu.php?location=Tây Hồ" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium hover:border-[#13ecc8] hover:text-[#13ecc8] transition">Tây Hồ</a>
                <a href="trang_chu.php?location=Sóc Sơn" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium hover:border-[#13ecc8] hover:text-[#13ecc8] transition">Sóc Sơn</a>
                <a href="trang_chu.php?location=Ba Vì" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium hover:border-[#13ecc8] hover:text-[#13ecc8] transition">Ba Vì</a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php 
            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()): 
            ?>
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group border border-gray-100 flex flex-col h-full overflow-hidden">
                <a href="chi_tiet_home.php?id=<?php echo $row['homestay_id']; ?>" class="block relative h-64 overflow-hidden">
                    <img src="uploads/<?php echo $row['main_image']; ?>" 
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                         alt="<?php echo $row['name']; ?>">
                    
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold shadow-sm flex items-center gap-1 text-gray-800">
                        <span class="material-symbols-outlined text-[16px] text-red-500">location_on</span>
                        <?php echo $row['district']; ?>
                    </div>
                    
                    <div class="absolute top-3 right-3 bg-black/20 p-1.5 rounded-full hover:bg-white/20 transition cursor-pointer text-white hover:text-red-500">
                        <span class="material-symbols-outlined block">favorite</span>
                    </div>
                </a>
                
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-gray-900 line-clamp-2 leading-tight flex-1 mr-2" title="<?php echo $row['name']; ?>">
                            <a href="chi_tiet_home.php?id=<?php echo $row['homestay_id']; ?>" class="hover:text-[#13ecc8] transition">
                                <?php echo $row['name']; ?>
                            </a>
                        </h3>
                        <div class="flex items-center gap-1 text-xs font-bold bg-gray-100 px-2 py-1 rounded whitespace-nowrap">
                            <span class="material-symbols-outlined text-[14px] text-orange-500" style="font-variation-settings: 'FILL' 1;">star</span> 
                            <?php echo isset($row['rating']) ? $row['rating'] : '5.0'; ?> 
                            <span class="text-gray-400 font-normal ml-1">(<?php echo isset($row['num_reviews']) ? $row['num_reviews'] : '18'; ?>)</span>
                        </div>
                    </div>

                    <p class="text-gray-500 text-xs mb-3 line-clamp-1">
                        <?php echo $row['address']; ?>
                    </p>

                    <div class="flex items-center gap-4 text-xs text-gray-500 font-medium mb-4 pt-3 border-t border-dashed border-gray-100 mt-auto">
                        <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">bed</span> <?php echo $row['num_bedrooms']; ?> PN</div>
                        <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">group</span> Max <?php echo $row['max_guests']; ?></div>
                        <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">single_bed</span> <?php echo $row['num_beds']; ?> G</div>
                    </div>

                    <div class="flex justify-between items-end">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Giá từ (T2-T5)</span>
                            <div class="text-[#13ecc8] font-black text-xl">
                                <?php echo number_format($row['price_weekday'], 0, ',', '.'); ?>₫
                            </div>
                        </div>
                        <a href="chi_tiet_home.php?id=<?php echo $row['homestay_id']; ?>" class="bg-gray-900 text-white p-2 rounded-lg hover:bg-[#13ecc8] transition shadow-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
                <div class="col-span-1 md:col-span-2 lg:col-span-3 xl:col-span-4 py-20 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-xl font-bold text-gray-600">Không tìm thấy homestay nào phù hợp.</p>
                    <a href="trang_chu.php" class="inline-block mt-6 px-6 py-2 bg-[#13ecc8] text-white font-bold rounded-full hover:bg-[#10d4b4] transition">Xem tất cả</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#13ecc8] text-3xl">other_houses</span> HomestayApp
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm">Nền tảng đặt phòng homestay uy tín.</p>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-4 text-white">Liên hệ</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li>support@homestayapp.com</li>
                    <li>1900 1234</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-12 pt-8 border-t border-gray-800 text-center text-gray-600 text-sm">
            <p>© 2024 HomestayApp. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const slides = document.querySelectorAll('.slide');
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('z-10', 'opacity-100');
                slide.classList.add('z-0', 'opacity-0');
            });
            slides[index].classList.remove('z-0', 'opacity-0');
            slides[index].classList.add('z-10', 'opacity-100');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
            resetTimer();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
            resetTimer();
        }

        function startTimer() {
            slideInterval = setInterval(nextSlide, 5000);
        }

        function resetTimer() {
            clearInterval(slideInterval);
            startTimer();
        }

        startTimer();
    </script>
</body>
</html>