<?php
// TÊN FILE: index.php (hoặc tên file bạn chọn, nhưng phải là .php)
// Cấu hình kết nối CSDL (Thay đổi các giá trị này cho phù hợp với CSDL của bạn)
$servername = "localhost"; // Tên máy chủ (thường là localhost)
$username = "root"; // Tên người dùng CSDL của bạn
$password = ""; // Mật khẩu CSDL của bạn
$dbname = "khachsan"; // Tên CSDL của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    // Dừng kịch bản và hiển thị lỗi nếu kết nối thất bại.
    // **LƯU Ý:** Trong môi trường sản phẩm (Production), bạn nên log lỗi này
    // thay vì hiển thị trực tiếp cho người dùng vì lý do bảo mật.
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

// Nếu kết nối thành công, bạn có thể thực hiện các truy vấn SQL ở đây.
// Ví dụ: $result = $conn->query("SELECT * FROM homestays LIMIT 4");

// Để giữ nguyên thiết kế và không làm thay đổi HTML, chúng ta sẽ KHÔNG thêm
// bất kỳ mã PHP nào khác ngoài phần kết nối và kiểm tra lỗi ở trên.

// Đóng kết nối (Nên đóng sau khi tất cả các truy vấn đã được thực hiện, thường là ở cuối trang)
$conn->close(); 
// Tuy nhiên, đối với một trang đơn giản như thế này, chúng ta sẽ để nó đóng TỰ ĐỘNG
// khi kịch bản kết thúc, hoặc có thể đóng sau này khi lấy dữ liệu xong.
?>
<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>HomestayApp - Tìm kiếm chốn dừng chân hoàn hảo</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#13ecc8",
              "background-light": "#f6f8f8",
              "background-dark": "#10221f",
            },
            fontFamily: {
              "display": ["Plus Jakarta Sans", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-[#484848] dark:text-gray-300">
<div class="relative flex min-h-screen w-full flex-col">
<header class="sticky top-0 z-50 w-full bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<div class="flex h-16 items-center justify-between">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary text-3xl">other_houses</span>
<h2 class="text-[#0d1b19] dark:text-white text-lg font-bold">HomestayApp</h2>
</div>
<nav class="hidden md:flex items-center gap-9">
<a class="text-sm font-medium hover:text-primary dark:text-gray-300 dark:hover:text-primary" href="#">Trang chủ</a>
<a class="text-sm font-medium hover:text-primary dark:text-gray-300 dark:hover:text-primary" href="#">Khám phá</a>
<a class="text-sm font-medium hover:text-primary dark:text-gray-300 dark:hover:text-primary" href="#">Blog</a>
<a class="text-sm font-medium hover:text-primary dark:text-gray-300 dark:hover:text-primary" href="#">Hỗ trợ</a>
</nav>
<div class="flex items-center gap-2">
<button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 text-[#0d1b19] dark:text-white dark:bg-primary/30 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/40 dark:hover:bg-primary/50">
<span class="truncate">Đăng nhập</span>
</button>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-[#0d1b19] text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90">
<span class="truncate">Đăng ký</span>
</button>
</div>
</div>
</div>
</header>
<main class="flex-grow">
<section class="relative">
<div class="absolute inset-0 bg-cover bg-center" data-alt="A tranquil homestay with a pool surrounded by lush greenery." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBvz--2yM2na3Nke8tvyfZKzBCUcoy-XlGrVSUA4_kkOUFX-GCatOAlaC7Q0EleAfxbESOdijohEN9toKC6A2q1Gqaupn7jKpUKfvHWW52cfbUbfYeBM-uKgreX-r1mIIwG3_kTIS1IYkLCSA2WTNF7pE_pxDAp8jy47my8vioqrqhz8a7-Av3nPDK67RaWnuEeRa32zrGXZ_HouyVHIBFNCwtnQ3_qso5uLG98sCtCHARcS05JwdHetv_0ZUNYv8kossmZDqQBuwo-");'></div>
<div class="absolute inset-0 bg-black/40"></div>
<div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
<div class="text-center">
<h1 class="text-white text-4xl font-black leading-tight tracking-[-0.033em] sm:text-5xl lg:text-6xl">Tìm kiếm chốn dừng chân hoàn hảo</h1>
<p class="mt-4 text-lg text-white/90">Khám phá hàng ngàn homestay độc đáo và tiện nghi tại Việt Nam.</p>
</div>
<div class="mt-10 mx-auto max-w-4xl p-4 bg-background-light/90 dark:bg-background-dark/90 backdrop-blur-sm rounded-xl shadow-lg">
<div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-10 gap-2 items-center">
<div class="relative lg:col-span-3">
<label class="absolute -top-2 left-3 inline-block bg-background-light dark:bg-background-dark px-1 text-xs font-medium text-gray-500 dark:text-gray-400" for="location">Địa điểm</label>
<div class="flex items-center">
<span class="material-symbols-outlined absolute left-3 text-gray-400">search</span>
<input class="w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-transparent focus:ring-primary focus:border-primary dark:text-white" id="location" placeholder="Bạn muốn đi đâu?" type="text"/>
</div>
</div>
<div class="relative lg:col-span-2">
<label class="absolute -top-2 left-3 inline-block bg-background-light dark:bg-background-dark px-1 text-xs font-medium text-gray-500 dark:text-gray-400" for="checkin">Ngày đến</label>
<input class="w-full py-3 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-transparent focus:ring-primary focus:border-primary dark:text-white dark:[color-scheme:dark]" id="checkin" type="date"/>
</div>
<div class="relative lg:col-span-2">
<label class="absolute -top-2 left-3 inline-block bg-background-light dark:bg-background-dark px-1 text-xs font-medium text-gray-500 dark:text-gray-400" for="checkout">Ngày đi</label>
<input class="w-full py-3 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-transparent focus:ring-primary focus:border-primary dark:text-white dark:[color-scheme:dark]" id="checkout" type="date"/>
</div>
<div class="relative lg:col-span-2">
<label class="absolute -top-2 left-3 inline-block bg-background-light dark:bg-background-dark px-1 text-xs font-medium text-gray-500 dark:text-gray-400" for="guests">Số khách</label>
<div class="flex items-center">
<span class="material-symbols-outlined absolute left-3 text-gray-400">group</span>
<input class="w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-transparent focus:ring-primary focus:border-primary dark:text-white" id="guests" min="1" placeholder="2 khách" type="number"/>
</div>
</div>
<div class="lg:col-span-1">
<button class="w-full flex items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-[#0d1b19] text-base font-bold tracking-[0.015em] hover:opacity-90">
<span class="md:hidden lg:inline truncate">Tìm</span>
<span class="hidden md:inline lg:hidden material-symbols-outlined">search</span>
</button>
</div>
</div>
</div>
</div>
</section>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
<section>
<h2 class="text-2xl font-bold leading-tight tracking-tight text-[#0d1b19] dark:text-white">Homestay nổi bật cho bạn</h2>
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="flex flex-col rounded-xl bg-white dark:bg-gray-800 shadow-md overflow-hidden group">
<div class="relative">
<img class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" data-alt="Modern living room of The Chill Garden homestay in Da Lat" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDNXHhG8MXQDBxhOdI1MAglv2ipt9Wil-bgUusV8T9me8HeoADeG1DPcYH46lTZQxBXG9vZk2fKu8R4blENQNdV-BxYsR4zcO6GsjbvkcbUQi-7_jgnN2ELYB5nHvn_aM0Mr6_hn5Zh_KTIU3sCgRgGXFsSTIZ_pMItoXHalczCjWIMTyQoylc4CRoZtfTjiCqKa0fyuwY9-lJg6mmd-neZGJ6vzyMAGWvexOysnxXWE-yXqSME8gNwSMugCJH75NXo1FwL0BDpMNm2"/>
<button class="absolute top-3 right-3 p-2 rounded-full bg-white/70 dark:bg-gray-900/70 text-gray-700 dark:text-gray-300 hover:text-red-500">
<span class="material-symbols-outlined">favorite_border</span>
</button>
</div>
<div class="p-4 flex flex-col flex-1">
<p class="text-lg font-bold text-[#0d1b19] dark:text-white">The Chill Garden</p>
<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Đà Lạt</p>
<div class="mt-2 flex items-center justify-between">
<p class="text-base font-semibold text-[#0d1b19] dark:text-white">1.200.000₫<span class="font-normal text-sm text-gray-500 dark:text-gray-400">/đêm</span></p>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-yellow-500 text-base" style="font-variation-settings: 'FILL' 1">star</span>
<span class="text-sm font-medium">4.9</span>
</div>
</div>
</div>
</div>
<div class="flex flex-col rounded-xl bg-white dark:bg-gray-800 shadow-md overflow-hidden group">
<div class="relative">
<img class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" data-alt="Oceanfront Villa with a stunning sea view in Vung Tau" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnt9TfyI-rmSXq443AGqbb7L3x2d58aOQIoFRQLz7uWNukcrwsRDVg4Sv8cx3CdQ_MVAOghav22DqwsxV4VBk6N6maZJSewa7Tsx66X-TyRR43Zacn7tbnTmuHK8GTMkLYQFZCrYj4z8xQxkuy5Ca545uB-ACmcV5uzu8CdsT2oXIJyfaH8gjZE31OdI5usss4YEeN22_Dp_a7JQZYrpRxoXq2QoKgbtL6oghU7L3nGscxPuVXzCu480jslSp01WcvhNa9hHEgzvz0"/>
<button class="absolute top-3 right-3 p-2 rounded-full bg-white/70 dark:bg-gray-900/70 text-gray-700 dark:text-gray-300 hover:text-red-500">
<span class="material-symbols-outlined">favorite_border</span>
</button>
</div>
<div class="p-4 flex flex-col flex-1">
<p class="text-lg font-bold text-[#0d1b19] dark:text-white">Oceanfront Villa</p>
<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Vũng Tàu</p>
<div class="mt-2 flex items-center justify-between">
<p class="text-base font-semibold text-[#0d1b19] dark:text-white">3.500.000₫<span class="font-normal text-sm text-gray-500 dark:text-gray-400">/đêm</span></p>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-yellow-500 text-base" style="font-variation-settings: 'FILL' 1">star</span>
<span class="text-sm font-medium">4.8</span>
</div>
</div>
</div>
</div>
<div class="flex flex-col rounded-xl bg-white dark:bg-gray-800 shadow-md overflow-hidden group">
<div class="relative">
<img class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" data-alt="Cozy Wooden Attic homestay nestled in the mountains of Ha Giang" src="https://lh3.googleusercontent.com/aida-public/AB6AXuASiwXeG_5xygIZfAwEY3phpuYA8_2NicqPO1zWs22P_3T4Q8DvJy30cEk5G_Zb_I7xbR7yYArdeoZmutugryI3r0me-SV5gI0zrudu8mBBR0KCzrbCTLsDfpXRlbpz2Knuz--ejkUyNhnLZ2fYdTZw_Gzej8IihtUt5xAA-6SXCNcR_fsQok6aQUCRFZWHve4a6xWTUWUU9-IUqEAkJPPp9fJkAG5jScTfhKYbnVEu6DNML4lM6od6h1cIrbAks2XAXUcsgX3_sxra"/>
<button class="absolute top-3 right-3 p-2 rounded-full bg-white/70 dark:bg-gray-900/70 text-gray-700 dark:text-gray-300 hover:text-red-500">
<span class="material-symbols-outlined">favorite_border</span>
</button>
</div>
<div class="p-4 flex flex-col flex-1">
<p class="text-lg font-bold text-[#0d1b19] dark:text-white">Cozy Wooden Attic</p>
<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Hà Giang</p>
<div class="mt-2 flex items-center justify-between">
<p class="text-base font-semibold text-[#0d1b19] dark:text-white">950.000₫<span class="font-normal text-sm text-gray-500 dark:text-gray-400">/đêm</span></p>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-yellow-500 text-base" style="font-variation-settings: 'FILL' 1">star</span>
<span class="text-sm font-medium">4.9</span>
</div>
</div>
</div>
</div>
<div class="flex flex-col rounded-xl bg-white dark:bg-gray-800 shadow-md overflow-hidden group">
<div class="relative">
<img class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" data-alt="Green Field Bungalow in Ninh Binh surrounded by rice paddies" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBccnb5p8SAtIr5Rh8EEowEG7D2b2cOkgsJTesArYvhNuCirESJyZRIX5A_AJxTGlcX_kGCsI6fXXcZ_1iEa6_fNNGYtLYm4gwdGPjUg_RPhMmtGE2aqyffF5-6lPmGMi38ieNmjIQREspHLor7GgyoR68czHF0DtXj_t4rVyK6kb-68FSVi58AyFu71VubjkJWLxMDvGuOXwyE1lA9W77DpcvNwLpUzairVl4kSESnEdsesCcNc5D6PYyWojtxXIGtgc_BxfPEI6-Y"/>
<button class="absolute top-3 right-3 p-2 rounded-full bg-white/70 dark:bg-gray-900/70 text-gray-700 dark:text-gray-300 hover:text-red-500">
<span class="material-symbols-outlined">favorite_border</span>
</button>
</div>
<div class="p-4 flex flex-col flex-1">
<p class="text-lg font-bold text-[#0d1b19] dark:text-white">Green Field Bungalow</p>
<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ninh Bình</p>
<div class="mt-2 flex items-center justify-between">
<p class="text-base font-semibold text-[#0d1b19] dark:text-white">1.500.000₫<span class="font-normal text-sm text-gray-500 dark:text-gray-400">/đêm</span></p>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-yellow-500 text-base" style="font-variation-settings: 'FILL' 1">star</span>
<span class="text-sm font-medium">4.7</span>
</div>
</div>
</div>
</div>
</div>
</section>
<section class="mt-16">
<h2 class="text-2xl font-bold leading-tight tracking-tight text-[#0d1b19] dark:text-white">Khám phá những điểm đến hàng đầu</h2>
<div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-6">
<a class="group relative overflow-hidden rounded-xl" href="#">
<img class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Pine forests in Da Lat" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCgDBNti5mcJ90_I1bXm51qzycMie-odybXrzI-v64ujo9hG3Q0ulDQr8smXtji8cQw__Am_r99-PRfguEwdLIbg4U860adN7Et1gS421C55_rsSR1GQjrW8U6k0hGPOFVpHKkuHCSnenrYG2K_Prbei8SQRCRp5pAKvi9ilbe1USLv4jbWOAK2Pcnkbh8BW92F08hny0ijBib39TMhM88BULXKL5p_c9iz_mm4M_2pjX7UMJcGhGbFxgwY12zFporLq2pnblLK8FkW"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
<p class="absolute bottom-4 left-4 text-xl font-bold text-white">Đà Lạt</p>
</a>
<a class="group relative overflow-hidden rounded-xl" href="#">
<img class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Beautiful beach in Vung Tau" src="https://lh3.googleusercontent.com/aida-public/AB6AXuATZIM-5xY_SaDQUBJvjwgwF0_wF1OwSGh4xCjgh67CvGQqZUszwQe1SG96UJIuXsY6XbaTh__cxOPNxV5Jt4W_yz118YUEqrKchqeQ6yPU7fwf6RNAckRrdfAl2PaBaWQhWkOS0pvb42k-SIWWDMaZ_GP8s9lyt1svtU8H1txNtNcitel6x6j6sAc1ExUQ1lgk7Edr2gTDR7RiD2I2qFMISQqZ8BEdV7el8IahTEL7ZHVK8Ms9QSHzuy_hMMy4a5k_seBdyRvuTEjF"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
<p class="absolute bottom-4 left-4 text-xl font-bold text-white">Vũng Tàu</p>
</a>
<a class="group relative overflow-hidden rounded-xl" href="#">
<img class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Hoan Kiem Lake in Ha Noi" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD3Faghm1uoCO0Z2UCBT9A6SSCR4D96hId5xUbRRtGWaMP01cR3X70g0OcOhKQ04_N1VOsL8aEe6ibBIZTBhsaBS_E3MmUUGFPr5sIwRLbf3ZGNlKkgAlGnYvAqfhuNKxUD9kkoA4de51ovjtHNU6BAQoFNIPGr4Ozrgoo-XdqqbQuoa8ew86OoduXIJmEydQ4-ZoQaTfoaJyHoTQQP8YrY5x58G2mKLHB5xuVx71JkxFaFBdApIW3hkny0C680Tb00S-BI0WwNmSRb"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
<p class="absolute bottom-4 left-4 text-xl font-bold text-white">Hà Nội</p>
</a>
<a class="group relative overflow-hidden rounded-xl" href="#">
<img class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Ancient town of Hoi An at night" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD2BqxGd1P0tBktHt8vEqA3c297t3cPToLzmDQe6oC6CIy2FM8ETt7VLH_7_vfXVnyYhzylh8_y7DgnI4TcsPDCZmUq7UUZYxS1PGDXT-AbLIw-RDARMeYtzrHnpv0vRs-AEwFw1JrmiO9GERa_exvzvlBZnt-Zq1NjdvEWaxOwysRilrRvC08lLPGzo5vIywWcFJyeEJL7Ff9g5ELGyXpH0KGWt2vRNGfBJrk_wORt2qRsUNSp46XZt1NmUV3woSYhKWFzCyAjSyIv"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
<p class="absolute bottom-4 left-4 text-xl font-bold text-white">Hội An</p>
</a>
</div>
</section>
</div>
</main>
<footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
<div class="grid grid-cols-2 md:grid-cols-4 gap-8">
<div>
<h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase">Về chúng tôi</h3>
<ul class="mt-4 space-y-4">
<li><a class="text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="#">Về HomestayApp</a></li>
<li><a class="text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="#">Cơ hội nghề nghiệp</a></li>
<li><a class="text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="#">Blog</a></li>
</ul>
</div>
<div>
<h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase">Hỗ trợ</h3>
<ul class="mt-4 space-y-4">
<li><a class="text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="#">Trung tâm trợ giúp</a></li>
<li><a class="text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="#">Chính sách hủy</a></li>
<li><a class="text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="#">Liên hệ</a></li>
</ul>
</div>
<div>
<h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase">Chính sách</h3>
<ul class="mt-4 space-y-4">
<li><a class="text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="#">Điều khoản sử dụng</a></li>
<li><a class="text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="#">Chính sách bảo mật</a></li>
</ul>
</div>
<div>
<h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase">Theo dõi chúng tôi</h3>
<div class="mt-4 flex space-x-6">
<a class="text-gray-400 hover:text-gray-500" href="#">
<span class="sr-only">Facebook</span>
<svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewbox="0 0 24 24"><path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd"></path></svg>
</a>
<a class="text-gray-400 hover:text-gray-500" href="#">
<span class="sr-only">Instagram</span>
<svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewbox="0 0 24 24"><path clip-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.012-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.345 2.525c.636-.247 1.363-.416 2.427-.465C9.794 2.013 10.148 2 12.315 2zm-1.161 1.542a9.922 9.922 0 00-3.321.366c-.95.236-1.549.486-2.028.675-.724.288-1.28.69-1.848 1.258-.567.567-.97 1.124-1.258 1.848-.19.479-.44 1.078-.675 2.028-.285.96-.345 1.476-.364 3.193-.02 1.717-.015 2.21.005 3.321.02.96.08 1.476.364 2.428.236.95.486 1.548.675 2.028.288.724.69 1.28 1.258 1.848.567.567 1.124.97 1.848 1.258.479.19 1.078.44 2.028.675.96.285 1.476.345 3.193.364 1.717.02 2.21.015 3.321-.005.96-.02 1.476-.08 2.428-.364.95-.236 1.548-.486 2.028-.675.724-.288 1.28-.69 1.848-1.258.567-.567 1.124-.97 1.258-1.848.19-.479.44-1.078.675-2.028.285-.96.345-1.476.364-3.193.02-1.717.015-2.21-.005-3.321-.02-.96-.08-1.476-.364-2.428-.236-.95-.486-1.549-.675-2.028-.288-.724-.69-1.28-1.258-1.848-.567-.567-1.124-.97-1.848-1.258-.479-.19-1.078-.44-2.028-.675-.96-.285-1.476-.345-3.193-.364-1.06-.02-1.554-.02-2.16-.02zm.17 1.564a7.999 7.999 0 100 15.998 7.999 7.999 0 000-15.998zm-4.887 7.999a4.887 4.887 0 119.773 0 4.887 4.887 0 01-9.773 0z" fill-rule="evenodd"></path></svg>
</a>
</div>
</div>
</div>
<div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8 text-center">
<p class="text-base text-gray-400">© 2024 HomestayApp. All rights reserved.</p>
</div>
</div>
</footer>
</div>
</body></html>