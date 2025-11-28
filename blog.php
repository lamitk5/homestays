<?php
session_start();
$conn = new mysqli("localhost", "root", "", "homestays");
$conn->set_charset("utf8mb4");

// 1. Lấy bài viết mới nhất (Hero)
$sql_hero = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 1";
$result_hero = $conn->query($sql_hero);
$hero_post = $result_hero->fetch_assoc();

// 2. Lấy danh sách bài viết (Bỏ bài đầu tiên)
$sql_list = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 6 OFFSET 1";
$result_list = $conn->query($sql_list);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Blog Du Lịch - HomestayApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
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
                <a href="kham_pha.php" class="hover:text-[#13ecc8] transition">Khám phá</a>
                <a href="blog.php" class="text-[#13ecc8] font-bold">Blog</a>
                <a href="ho_tro.php" class="hover:text-[#13ecc8] transition">Hỗ trợ</a>
            </nav>
            <div class="flex items-center gap-4">
                <?php if(isset($_SESSION['fullname'])): ?>
                    <span class="text-sm font-medium">Hi, <b><?php echo $_SESSION['fullname']; ?></b></span>
                <?php else: ?>
                    <a href="dangnhap.php" class="text-sm font-bold">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-black text-gray-900 mb-4">Góc chia sẻ & Kinh nghiệm</h1>
            <p class="text-lg text-gray-500">Khám phá những điểm đến thú vị và bí kíp du lịch.</p>
        </div>

        <?php if($hero_post): ?>
        <div class="relative h-[450px] rounded-2xl overflow-hidden mb-16 group shadow-2xl">
            <img src="<?php echo $hero_post['image_url']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-8 md:p-12 z-10">
                <span class="bg-[#13ecc8] text-black text-xs font-bold px-3 py-1 rounded uppercase mb-4 inline-block"><?php echo $hero_post['category']; ?></span>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
                    <a href="chi_tiet_blog.php?id=<?php echo $hero_post['id']; ?>" class="hover:underline"><?php echo $hero_post['title']; ?></a>
                </h2>
                <p class="text-gray-300 text-lg mb-6 line-clamp-2 max-w-3xl"><?php echo $hero_post['description']; ?></p>
            </div>
            <a href="chi_tiet_blog.php?id=<?php echo $hero_post['id']; ?>" class="absolute inset-0 z-20"></a>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <?php while($post = $result_list->fetch_assoc()): ?>
            <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition group flex flex-col h-full border border-gray-100 relative">
                
                <div class="relative h-56 overflow-hidden">
                    <img src="<?php echo $post['image_url']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-blue-600 uppercase z-10"><?php echo $post['category']; ?></div>
                    <a href="chi_tiet_blog.php?id=<?php echo $post['id']; ?>" class="absolute inset-0 z-20"></a>
                </div>

                <div class="p-6 flex flex-col flex-1 relative">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#13ecc8] transition">
                        <a href="chi_tiet_blog.php?id=<?php echo $post['id']; ?>" class="relative z-20"><?php echo $post['title']; ?></a>
                    </h3>
                    <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-1"><?php echo $post['description']; ?></p>
                    
                    <a href="chi_tiet_blog.php?id=<?php echo $post['id']; ?>" class="inline-flex items-center text-[#13ecc8] font-bold hover:underline relative z-20">
                        Đọc tiếp <span class="material-symbols-outlined text-lg ml-1">arrow_forward</span>
                    </a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-8 text-center mt-12">
        <p>© 2024 HomestayApp. All rights reserved.</p>
    </footer>
</body>
</html>