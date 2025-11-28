<?php
session_start();

// 1. KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "homestays");
$conn->set_charset("utf8mb4");

// 2. XỬ LÝ LỌC THEO QUẬN (Khi bấm vào các nút địa điểm)
$where_clause = "1=1";
$current_district = "Tất cả";

if (isset($_GET['district']) && !empty($_GET['district'])) {
    $dist = $conn->real_escape_string($_GET['district']);
    $where_clause .= " AND district LIKE '%$dist%'";
    $current_district = $dist;
}

// Lấy danh sách cho phần "Gợi ý"
$sql_list = "SELECT * FROM homestays WHERE $where_clause ORDER BY id DESC";
$result_list = $conn->query($sql_list);

// Lấy 3 Homestay ngẫu nhiên để làm mục "Ưu đãi" (Giả lập giảm giá)
$sql_deals = "SELECT * FROM homestays ORDER BY RAND() LIMIT 3";
$result_deals = $conn->query($sql_deals);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Khám phá - HomestayApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { vertical-align: middle; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="trang_chu.php" class="flex items-center gap-2 hover:opacity-80 transition">
                <span class="material-symbols-outlined text-[#13ecc8] text-4xl">other_houses</span>
                <span class="font-extrabold text-2xl tracking-tight text-gray-900">Homestay<span class="text-[#13ecc8]">App</span></span>
            </a>
            
            <nav class="hidden md:flex gap-8 font-medium text-gray-600">
                <a href="trang_chu.php" class="hover:text-[#13ecc8] transition">Trang chủ</a>
                <a href="kham_pha.php" class="text-[#13ecc8] font-bold">Khám phá</a> <a href="blog.php" class="hover:text-[#13ecc8] transition">Blog</a>
                <a href="ho_tro.php" class="hover:text-[#13ecc8] transition">Hỗ trợ</a>
            </nav>

            <div class="flex items-center gap-4">
                <?php if(isset($_SESSION['fullname'])): ?>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium hidden sm:block">Hi, <b><?php echo $_SESSION['fullname']; ?></b></span>
                        <a href="logout.php" class="text-sm text-red-500 font-bold hover:underline">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <a href="dangnhap.php" class="text-sm font-bold text-gray-600 hover:text-[#13ecc8] transition">Đăng nhập</a>
                    <a href="dangky.php" class="bg-[#13ecc8] text-white px-5 py-2.5 rounded-full font-bold text-sm hover:shadow-lg transition-all">Đăng ký ngay</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-yellow-500">explore</span> Điểm đến được yêu thích
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="kham_pha.php?district=Tây Hồ" class="relative h-40 rounded-xl overflow-hidden group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?q=80&w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition"></div>
                    <span class="absolute bottom-3 left-4 text-white font-bold text-lg">Tây Hồ</span>
                </a>
                <a href="kham_pha.php?district=Ba Vì" class="relative h-40 rounded-xl overflow-hidden group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1623129372134-29cb67c427c3?q=80&w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition"></div>
                    <span class="absolute bottom-3 left-4 text-white font-bold text-lg">Ba Vì</span>
                </a>
                <a href="kham_pha.php?district=Sóc Sơn" class="relative h-40 rounded-xl overflow-hidden group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition"></div>
                    <span class="absolute bottom-3 left-4 text-white font-bold text-lg">Sóc Sơn</span>
                </a>
                <a href="kham_pha.php?district=Hoàn Kiếm" class="relative h-40 rounded-xl overflow-hidden group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1555921015-5532091f6026?q=80&w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition"></div>
                    <span class="absolute bottom-3 left-4 text-white font-bold text-lg">Hoàn Kiếm</span>
                </a>
            </div>
        </div>

        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2 text-red-500">
                <span class="material-symbols-outlined">local_fire_department</span> Ưu đãi độc quyền mùa hè
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while($deal = $result_deals->fetch_assoc()): 
                    // Tính giá ảo (cao hơn 30% để làm giá gốc)
                    $fake_original_price = $deal['price_weekday'] * 1.3;
                ?>
                <a href="chi_tiet_home.php?id=<?php echo $deal['id']; ?>" class="block bg-white rounded-xl shadow-md overflow-hidden hover:-translate-y-1 transition border border-red-100">
                    <div class="relative h-48">
                        <img src="uploads/<?php echo $deal['main_image']; ?>" 
                             onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'"
                             class="w-full h-full object-cover">
                        <div class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg animate-pulse">
                            GIẢM 30%
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 truncate"><?php echo $deal['name']; ?></h3>
                        <p class="text-xs text-gray-500 mb-2"><?php echo $deal['district']; ?></p>
                        <div class="flex items-end gap-2">
                            <span class="text-red-500 font-bold text-lg"><?php echo number_format($deal['price_weekday'], 0, ',', '.'); ?>₫</span>
                            <span class="text-gray-400 text-sm line-through decoration-1"><?php echo number_format($fake_original_price, 0, ',', '.'); ?>₫</span>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-bold mb-6">Gợi ý tại Hà Nội</h2>
            
            <div class="flex flex-wrap gap-3 mb-8 overflow-x-auto pb-2 scrollbar-hide">
                <?php 
                $districts = ["Tất cả", "Hoàn Kiếm", "Tây Hồ", "Ba Đình", "Sóc Sơn", "Ba Vì", "Sơn Tây"];
                foreach($districts as $d) {
                    $activeClass = ($current_district == $d || ($d == "Tất cả" && !isset($_GET['district']))) 
                        ? "bg-[#13ecc8] text-white border-[#13ecc8]" 
                        : "bg-white text-gray-600 border-gray-200 hover:bg-gray-50";
                    
                    // Link: Nếu chọn "Tất cả" thì bỏ tham số district
                    $link = ($d == "Tất cả") ? "kham_pha.php" : "kham_pha.php?district=$d";
                    
                    echo "<a href='$link' class='px-5 py-2.5 rounded-full text-sm font-bold border transition shadow-sm whitespace-nowrap $activeClass'>$d</a>";
                }
                ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php 
                if ($result_list->num_rows > 0):
                    while($row = $result_list->fetch_assoc()): 
                ?>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group">
                    <a href="chi_tiet_home.php?id=<?php echo $row['id']; ?>">
                        <div class="relative h-60 overflow-hidden">
                            <img src="uploads/<?php echo $row['main_image']; ?>" 
                                 onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            
                            <div class="absolute bottom-3 left-3 bg-[#13ecc8] text-white text-[10px] font-bold px-2 py-1 rounded">
                                View <?php echo $row['district']; ?>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 truncate mb-1"><?php echo $row['name']; ?></h3>
                            <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                <?php echo $row['address']; ?>
                            </p>
                            <div class="flex justify-between items-center">
                                <p class="text-[#13ecc8] font-bold text-lg">
                                    <?php echo number_format($row['price_weekday'], 0, ',', '.'); ?>₫
                                    <span class="text-gray-400 text-xs font-normal">/đêm</span>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <div class="col-span-full text-center py-10">
                        <span class="material-symbols-outlined text-4xl text-gray-300">search_off</span>
                        <p class="text-gray-500 mt-2">Chưa có homestay nào ở khu vực này.</p>
                        <a href="kham_pha.php" class="text-[#13ecc8] font-bold underline mt-2 block">Xem tất cả</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <footer class="bg-gray-900 text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500">© 2024 HomestayApp. All rights reserved.</p>
        </div>
    </footer>

</body>
</html> 