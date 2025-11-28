<?php
session_start();
$conn = new mysqli("localhost", "root", "", "homestays");
$conn->set_charset("utf8mb4");

// Lấy ID từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM posts WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    echo "<h1 style='text-align:center; margin-top:50px;'>Bài viết không tồn tại!</h1>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <title><?php echo $post['title']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-white text-gray-800">

    <header class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="blog.php" class="flex items-center gap-2 hover:text-[#13ecc8] transition">
                <span class="material-symbols-outlined">arrow_back</span> 
                <span class="font-bold">Quay lại Blog</span>
            </a>
            <h2 class="font-bold text-xl text-[#13ecc8]">HomestayApp Blog</h2>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-12">
        <span class="text-[#13ecc8] font-bold uppercase text-sm tracking-wider"><?php echo $post['category']; ?></span>
        <h1 class="text-3xl md:text-5xl font-black text-gray-900 mt-2 mb-6 leading-tight"><?php echo $post['title']; ?></h1>
        
        <div class="flex items-center gap-3 text-gray-500 text-sm mb-8 border-b pb-8">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600">A</div>
            <div>
                <p class="font-bold text-gray-900"><?php echo $post['author']; ?></p>
                <p>Đăng ngày <?php echo date("d/m/Y", strtotime($post['created_at'])); ?></p>
            </div>
        </div>

        <img src="<?php echo $post['image_url']; ?>" class="w-full h-auto rounded-xl shadow-lg mb-10">

        <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed text-justify">
            <?php echo nl2br($post['content']); ?>
        </div>
    </main>

    <footer class="bg-gray-100 py-8 text-center mt-12 text-gray-500">
        © 2024 HomestayApp. All rights reserved.
    </footer>
</body>
</html>