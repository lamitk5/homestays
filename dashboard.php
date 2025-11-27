<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bảng điều khiển - Quản lý Homestay</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
    .material-symbols-outlined {
      font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 24
    }
  </style>
<script id="tailwind-config">
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
            "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
          },
          borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
        },
      },
    }
  </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="relative flex min-h-screen w-full flex-col">
<div class="flex h-full grow">
<!-- SideNavBar -->
<aside class="flex w-64 flex-col gap-8 border-r border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-background-dark sticky top-0 h-screen">
<div class="flex items-center gap-3 px-2">
<span class="material-symbols-outlined text-primary text-3xl">home_work</span>
<h1 class="text-xl font-bold text-slate-800 dark:text-white">Homestay</h1>
</div>
<div class="flex flex-col gap-4">
<div class="flex gap-3 items-center">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-12" data-alt="Admin avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAR6Rawwdc4fkEXSHx8jitRZVDGFDXJuBPgMV4G1vdWpjHKk0CLGVq5TUvg3TS_r41FsZan4TuqHXhNK9uu9LsepxhQWDqN-PovvAX7P7UvaJqUS7QwEye9xW457950mZ8MpKWPiPTZEOJX2LC23QvDCcEsxp8MD0A-wP68pRcIOV9YdjqaV4qjlChidP9FortRiKt8sL3T4hQyzTN2P68kEK4l9BRvOoaBIETJNsYA73tcL3HoE6yS8-N59BSFlKSvf0EQOqe3-JjP");'></div>
<div class="flex flex-col">
<h1 class="text-slate-900 dark:text-slate-100 text-base font-medium leading-normal">Admin Name</h1>
<p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal">Administrator</p>
</div>
</div>
<nav class="flex flex-col gap-2 mt-4">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 text-primary font-bold" href="#">
<span class="material-symbols-outlined">dashboard</span>
<p class="text-sm leading-normal">Dashboard</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="#">
<span class="material-symbols-outlined">book_online</span>
<p class="text-sm font-medium leading-normal">Quản lý Đặt phòng</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="#">
<span class="material-symbols-outlined">villa</span>
<p class="text-sm font-medium leading-normal">Quản lý Homestay</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="#">
<span class="material-symbols-outlined">group</span>
<p class="text-sm font-medium leading-normal">Khách hàng</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="#">
<span class="material-symbols-outlined">assessment</span>
<p class="text-sm font-medium leading-normal">Báo cáo</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="#">
<span class="material-symbols-outlined">settings</span>
<p class="text-sm font-medium leading-normal">Cài đặt</p>
</a>
</nav>
</div>
<div class="mt-auto">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300" href="#">
<span class="material-symbols-outlined">logout</span>
<p class="text-sm font-medium leading-normal">Đăng xuất</p>
</a>
</div>
</aside>
<!-- Main Content -->
<main class="flex-1 p-8">
<!-- PageHeading -->
<div class="flex flex-wrap justify-between gap-4 items-center mb-8">
<div class="flex flex-col gap-1">
<p class="text-slate-900 dark:text-slate-50 text-3xl font-bold leading-tight tracking-tight">Bảng điều khiển</p>
<p class="text-slate-500 dark:text-slate-400 text-base font-normal leading-normal">Tổng quan hệ thống homestay của bạn.</p>
</div>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-slate-50 dark:hover:bg-slate-700">
<span class="material-symbols-outlined text-lg">calendar_month</span>
<span class="truncate">30 ngày qua</span>
<span class="material-symbols-outlined text-lg">expand_more</span>
</button>
</div>
<!-- Stats -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200 dark:border-slate-800">
<p class="text-slate-600 dark:text-slate-400 text-base font-medium leading-normal">Tổng doanh thu</p>
<p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">150.000.000đ</p>
<p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-base">trending_up</span>+15%</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200 dark:border-slate-800">
<p class="text-slate-600 dark:text-slate-400 text-base font-medium leading-normal">Tổng số đặt phòng</p>
<p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">120</p>
<p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-base">trending_up</span>+5%</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200 dark:border-slate-800">
<p class="text-slate-600 dark:text-slate-400 text-base font-medium leading-normal">Homestay hoạt động</p>
<p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">15</p>
<p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-base">trending_up</span>+2%</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-slate-200 dark:border-slate-800">
<p class="text-slate-600 dark:text-slate-400 text-base font-medium leading-normal">Khách hàng mới</p>
<p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">30</p>
<p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-base">trending_up</span>+10%</p>
</div>
</div>
<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
<div class="lg:col-span-2 flex flex-col gap-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark p-6">
<p class="text-slate-900 dark:text-white text-base font-medium leading-normal">Doanh thu theo thời gian</p>
<p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight truncate">150.000.000đ</p>
<div class="flex gap-2">
<p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal">30 ngày qua</p>
<p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-base">trending_up</span>+15%</p>
</div>
<div class="flex min-h-[240px] flex-1 flex-col gap-8 pt-4">
<svg fill="none" height="100%" preserveaspectratio="none" viewbox="0 0 475 150" width="100%" xmlns="http://www.w3.org/2000/svg">
<path d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25V149H0V109Z" fill="url(#paint0_linear_chart)"></path>
<path d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25" stroke="#13ecc8" stroke-linecap="round" stroke-width="3"></path>
<defs>
<lineargradient gradientunits="userSpaceOnUse" id="paint0_linear_chart" x1="236" x2="236" y1="1" y2="149">
<stop stop-color="#13ecc8" stop-opacity="0.3"></stop>
<stop offset="1" stop-color="#13ecc8" stop-opacity="0"></stop>
</lineargradient>
</defs>
</svg>
<div class="flex justify-around">
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal tracking-wide">Tuần 1</p>
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal tracking-wide">Tuần 2</p>
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal tracking-wide">Tuần 3</p>
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal tracking-wide">Tuần 4</p>
</div>
</div>
</div>
<div class="flex flex-col gap-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark p-6">
<p class="text-slate-900 dark:text-white text-base font-medium leading-normal">Tỷ lệ lấp đầy</p>
<p class="text-slate-900 dark:text-white tracking-tight text-3xl font-bold leading-tight truncate">75%</p>
<div class="flex gap-2">
<p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal">Tháng này</p>
<p class="text-green-500 text-sm font-medium leading-normal flex items-center gap-1"><span class="material-symbols-outlined text-base">trending_up</span>+8%</p>
</div>
<div class="grid min-h-[240px] grid-flow-col gap-6 grid-rows-[1fr_auto] items-end justify-items-center px-3 pt-4">
<div class="bg-primary/20 w-full rounded-t-lg" style="height: 90%;"></div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal">A</p>
<div class="bg-primary/20 w-full rounded-t-lg" style="height: 60%;"></div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal">B</p>
<div class="bg-primary/20 w-full rounded-t-lg" style="height: 75%;"></div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal">C</p>
<div class="bg-primary/20 w-full rounded-t-lg" style="height: 80%;"></div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal">D</p>
<div class="bg-primary/20 w-full rounded-t-lg" style="height: 50%;"></div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-bold leading-normal">E</p>
</div>
</div>
</div>
<!-- Recent Activities and Calendar -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- SectionHeader + Recent Activity -->
<div class="lg:col-span-2 flex flex-col rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark">
<h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight p-6 border-b border-slate-200 dark:border-slate-800">Hoạt động gần đây</h2>
<div class="p-6 space-y-4">
<div class="flex items-center gap-4">
<div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
<span class="material-symbols-outlined text-blue-500 dark:text-blue-400">add_shopping_cart</span>
</div>
<div>
<p class="text-sm text-slate-800 dark:text-slate-200"><strong>Khách A</strong> vừa đặt phòng tại <span class="font-semibold text-primary">Homestay D</span></p>
<p class="text-xs text-slate-500 dark:text-slate-400">2 phút trước</p>
</div>
</div>
<div class="flex items-center gap-4">
<div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
<span class="material-symbols-outlined text-green-500 dark:text-green-400">login</span>
</div>
<div>
<p class="text-sm text-slate-800 dark:text-slate-200"><strong>Khách B</strong> vừa check-in tại <span class="font-semibold text-primary">Homestay A</span></p>
<p class="text-xs text-slate-500 dark:text-slate-400">1 giờ trước</p>
</div>
</div>
<div class="flex items-center gap-4">
<div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
<span class="material-symbols-outlined text-yellow-500 dark:text-yellow-400">payments</span>
</div>
<div>
<p class="text-sm text-slate-800 dark:text-slate-200">Thanh toán mới trị giá <span class="font-semibold">2.500.000đ</span> từ <strong>Khách C</strong></p>
<p class="text-xs text-slate-500 dark:text-slate-400">3 giờ trước</p>
</div>
</div>
<div class="flex items-center gap-4">
<div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
<span class="material-symbols-outlined text-red-500 dark:text-red-400">logout</span>
</div>
<div>
<p class="text-sm text-slate-800 dark:text-slate-200"><strong>Khách E</strong> vừa check-out khỏi <span class="font-semibold text-primary">Homestay B</span></p>
<p class="text-xs text-slate-500 dark:text-slate-400">5 giờ trước</p>
</div>
</div>
</div>
</div>
<!-- Calendar -->
<div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark p-6">
<div class="flex items-center justify-between mb-4">
<h2 class="text-slate-900 dark:text-white text-lg font-bold">Lịch tổng quan</h2>
<div class="flex gap-2">
<button class="p-1 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400"><span class="material-symbols-outlined text-xl">chevron_left</span></button>
<button class="p-1 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400"><span class="material-symbols-outlined text-xl">chevron_right</span></button>
</div>
</div>
<p class="text-center font-semibold text-slate-800 dark:text-slate-200 mb-4">Tháng 8, 2024</p>
<div class="grid grid-cols-7 gap-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">
<span>CN</span><span>T2</span><span>T3</span><span>T4</span><span>T5</span><span>T6</span><span>T7</span>
</div>
<div class="grid grid-cols-7 gap-y-2 text-center text-sm mt-2">
<span class="text-slate-400 dark:text-slate-600">28</span><span class="text-slate-400 dark:text-slate-600">29</span><span class="text-slate-400 dark:text-slate-600">30</span><span class="text-slate-400 dark:text-slate-600">31</span><span>1</span><span>2</span><span>3</span>
<span>4</span><span>5</span><span class="relative"><span class="z-10 relative">6</span><span class="absolute inset-0 bg-primary/20 rounded-full z-0"></span></span><span>7</span><span>8</span><span>9</span><span>10</span>
<span>11</span><span>12</span><span>13</span><span>14</span><span class="relative"><span class="z-10 relative text-white">15</span><span class="absolute inset-0 bg-primary rounded-full z-0"></span></span><span>16</span><span>17</span>
<span>18</span><span>19</span><span>20</span><span>21</span><span>22</span><span>23</span><span>24</span>
<span>25</span><span>26</span><span>27</span><span>28</span><span>29</span><span>30</span><span>31</span>
</div>
</div>
</div>
</main>
</div>
</div>
</body></html>