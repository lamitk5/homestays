<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Hỗ trợ - HomestayApp</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#13ecc8", // Màu xanh ngọc chủ đạo
              "background-light": "#f6f8f8",
              "background-dark": "#10221f",
            },
            fontFamily: {
              "display": ["Plus Jakarta Sans", "sans-serif"]
            },
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        /* Hiệu ứng xoay mũi tên */
        .rotate-180 { transform: rotate(180deg); }
        /* Hiệu ứng xổ xuống */
        .faq-content { transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out; max-height: 0; opacity: 0; overflow: hidden; }
        .faq-content.open { max-height: 500px; opacity: 1; }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-[#484848] dark:text-gray-300">
    <div class="flex min-h-screen w-full flex-col">
        
        <header class="sticky top-0 z-50 w-full bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm shadow-sm border-b border-gray-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <a href="trang_chu.php" class="flex items-center gap-4 hover:opacity-80 transition-opacity">
                        <span class="material-symbols-outlined text-primary text-3xl">other_houses</span>
                        <h2 class="text-[#0d1b19] dark:text-white text-lg font-bold">HomestayApp</h2>
                    </a>
                    
                    <nav class="hidden md:flex items-center gap-9">
                        <a class="text-sm font-medium hover:text-primary dark:text-gray-300 transition-colors" href="trang_chu.php">Trang chủ</a>
                        <a class="text-sm font-medium hover:text-primary dark:text-gray-300 transition-colors" href="kham_pha.php">Khám phá</a>
                        <a class="text-sm font-medium hover:text-primary dark:text-gray-300 transition-colors" href="blog.php">Blog</a>
                        <a class="text-sm font-bold text-primary transition-colors" href="ho_tro.php">Hỗ trợ</a>
                    </nav>

                    <div class="flex items-center gap-3">
                        <a href="dangnhap.php" class="flex min-w-[100px] items-center justify-center rounded-lg h-10 px-4 bg-primary/10 text-primary font-bold hover:bg-primary/20 transition-colors">Đăng nhập</a>
                        <a href="dangky.php" class="flex min-w-[100px] items-center justify-center rounded-lg h-10 px-4 bg-primary text-[#0d1b19] font-bold hover:bg-primary/90 transition-colors">Đăng ký</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                
                <div class="text-center mb-16">
                    <h1 class="text-3xl md:text-4xl font-black text-[#0d1b19] dark:text-white mb-4">Chúng tôi có thể giúp gì cho bạn?</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">Tìm câu trả lời nhanh chóng hoặc liên hệ với nhóm hỗ trợ của chúng tôi.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <div class="lg:col-span-2 space-y-6">
                        <h2 class="text-2xl font-bold text-[#0d1b19] dark:text-white mb-6">Câu hỏi thường gặp</h2>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" onclick="toggleFaq(1)">
                                <span class="font-bold text-[#0d1b19] dark:text-white">Làm cách nào để đặt một homestay?</span>
                                <span id="icon-1" class="material-symbols-outlined text-gray-400 transition-transform duration-300">expand_more</span>
                            </button>
                            <div id="content-1" class="faq-content bg-gray-50 dark:bg-gray-700/30">
                                <div class="px-6 pb-4 pt-2 text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                                    Bạn chỉ cần tìm kiếm địa điểm, chọn ngày đi và về, sau đó chọn homestay ưng ý. Nhấn nút "Đặt phòng", điền thông tin cá nhân và tiến hành thanh toán để hoàn tất.
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" onclick="toggleFaq(2)">
                                <span class="font-bold text-[#0d1b19] dark:text-white">Chính sách hủy đặt phòng là gì?</span>
                                <span id="icon-2" class="material-symbols-outlined text-gray-400 transition-transform duration-300">expand_more</span>
                            </button>
                            <div id="content-2" class="faq-content bg-gray-50 dark:bg-gray-700/30">
                                <div class="px-6 pb-4 pt-2 text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                                    Mỗi homestay có chính sách hủy riêng (Linh hoạt, Trung bình, hoặc Nghiêm ngặt). Bạn có thể xem chi tiết trong phần mô tả của từng phòng trước khi đặt. Thông thường, hủy trước 24h sẽ được hoàn tiền 100%.
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" onclick="toggleFaq(3)">
                                <span class="font-bold text-[#0d1b19] dark:text-white">Tôi có thể liên hệ với chủ nhà bằng cách nào?</span>
                                <span id="icon-3" class="material-symbols-outlined text-gray-400 transition-transform duration-300">expand_more</span>
                            </button>
                            <div id="content-3" class="faq-content bg-gray-50 dark:bg-gray-700/30">
                                <div class="px-6 pb-4 pt-2 text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                                    Sau khi đặt phòng thành công, bạn sẽ nhận được số điện thoại và email của chủ nhà. Ngoài ra, bạn có thể chat trực tiếp với chủ nhà thông qua tính năng "Tin nhắn" trên website của chúng tôi.
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" onclick="toggleFaq(4)">
                                <span class="font-bold text-[#0d1b19] dark:text-white">Làm thế nào để thanh toán an toàn?</span>
                                <span id="icon-4" class="material-symbols-outlined text-gray-400 transition-transform duration-300">expand_more</span>
                            </button>
                            <div id="content-4" class="faq-content bg-gray-50 dark:bg-gray-700/30">
                                <div class="px-6 pb-4 pt-2 text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                                    HomestayApp hỗ trợ thanh toán qua thẻ tín dụng, ví điện tử (Momo, ZaloPay) và chuyển khoản ngân hàng. Tất cả giao dịch đều được bảo mật tuyệt đối.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700 sticky top-24">
                            <h2 class="text-xl font-bold text-[#0d1b19] dark:text-white mb-2">Không tìm thấy câu trả lời?</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Hãy gửi cho chúng tôi một tin nhắn.</p>
                            
                            <form class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Họ và tên</label>
                                    <input type="text" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary text-sm p-3" placeholder="Tên của bạn">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                    <input type="email" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary text-sm p-3" placeholder="you@example.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tin nhắn của bạn</label>
                                    <textarea rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary text-sm p-3" placeholder="Viết tin nhắn của bạn ở đây..."></textarea>
                                </div>
                                <button type="button" class="w-full bg-primary hover:bg-primary/90 text-[#0d1b19] font-bold py-3 rounded-lg transition-colors shadow-md">
                                    Gửi tin nhắn
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 text-center">
                <p class="text-gray-400">© 2024 HomestayApp. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        function toggleFaq(id) {
            const content = document.getElementById(`content-${id}`);
            const icon = document.getElementById(`icon-${id}`);
            
            // Nếu đang mở thì đóng lại
            if (content.classList.contains('open')) {
                content.classList.remove('open');
                icon.classList.remove('rotate-180'); // Xoay mũi tên về vị trí cũ
            } else {
                // Đóng tất cả các câu hỏi khác trước khi mở câu này (nếu muốn chỉ mở 1 cái 1 lúc)
                // document.querySelectorAll('.faq-content').forEach(el => el.classList.remove('open'));
                // document.querySelectorAll('.material-symbols-outlined').forEach(el => el.classList.remove('rotate-180'));

                content.classList.add('open');
                icon.classList.add('rotate-180'); // Xoay mũi tên lên
            }
        }
    </script>
</body>
</html>