<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Customer Details</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#28A745",
            "background-light": "#F8F9FA",
            "background-dark": "#10221f",
          },
          fontFamily: {
            "display": ["Inter", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
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
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="relative flex min-h-screen w-full">
<!-- SideNavBar -->
<nav class="flex h-screen w-64 flex-col border-r border-[#DEE2E6] bg-white dark:bg-[#182c28] dark:border-gray-700">
<div class="flex h-full flex-col justify-between p-4">
<div class="flex flex-col gap-4">
<div class="flex items-center gap-3">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="A placeholder image for the admin user's avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB1sNUvTW_9FZc5YsnYZsR7Wgpx1AM4Yg7V71SV5oq4cMi6WcPJ5x4ycs99nJHSxKrx7xJ_HfZPAjdxBbiWPrrG8RaNscDYLCeuGSJXM4Vq0fm54ZBRJOyx5LNf2sw-peAc2P0gwoHSjPdNaaL1Cu4HvEI40eGkrJmo2rfcvC3Onkbv4TFaMrsxXU0A3d2-9BVHz_aik8qGqXeiok80lxhQL_sfQnHlFIAuH5XAW1wrwvlHaIwZZvuFfH_6QEZ1Or8cW193uQMl2NIH");'></div>
<div class="flex flex-col">
<h1 class="text-[#343A40] dark:text-white text-base font-medium leading-normal">Admin Name</h1>
<p class="text-[#6c757d] dark:text-gray-400 text-sm font-normal leading-normal">admin@staymanager.com</p>
</div>
</div>
<div class="flex flex-col gap-2 mt-4">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-[#343A40] dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">dashboard</span>
<p class="text-sm font-medium leading-normal">Dashboard</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-[#343A40] dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">calendar_month</span>
<p class="text-sm font-medium leading-normal">Bookings</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 text-primary dark:bg-primary/30 dark:text-primary" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span>
<p class="text-sm font-medium leading-normal">Customers</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-[#343A40] dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">home</span>
<p class="text-sm font-medium leading-normal">Homestays</p>
</a>
</div>
</div>
<div class="flex flex-col gap-1">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-[#343A40] dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">settings</span>
<p class="text-sm font-medium leading-normal">Settings</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-[#343A40] dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">logout</span>
<p class="text-sm font-medium leading-normal">Logout</p>
</a>
</div>
</div>
</nav>
<!-- Main Content -->
<main class="flex-1 overflow-y-auto">
<div class="p-6 md:p-8">
<!-- Breadcrumbs -->
<div class="flex flex-wrap gap-2 mb-6">
<a class="text-[#6c757d] dark:text-gray-400 text-sm font-medium leading-normal" href="#">Trang chủ</a>
<span class="text-[#6c757d] dark:text-gray-400 text-sm font-medium leading-normal">/</span>
<a class="text-[#6c757d] dark:text-gray-400 text-sm font-medium leading-normal" href="#">Khách hàng</a>
<span class="text-[#6c757d] dark:text-gray-400 text-sm font-medium leading-normal">/</span>
<span class="text-[#343A40] dark:text-white text-sm font-medium leading-normal">Nguyễn Văn An</span>
</div>
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
<h1 class="text-[#343A40] dark:text-white text-2xl md:text-3xl font-bold leading-tight tracking-[-0.015em]">Chi tiết khách hàng</h1>
<!-- Action Buttons -->
<div class="flex items-center gap-3 mt-4 lg:mt-0">
<button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-transparent text-[#6c757d] dark:text-gray-400 border border-[#DEE2E6] dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-bold leading-normal tracking-[0.015em]">
<span class="truncate">Hủy bỏ</span>
</button>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
<span class="truncate">Lưu thay đổi</span>
</button>
</div>
</div>
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
<!-- Left Column: Personal Information -->
<div class="xl:col-span-1 flex flex-col gap-6">
<div class="bg-white dark:bg-[#182c28] border border-[#DEE2E6] dark:border-gray-700 rounded-xl p-6">
<div class="flex flex-col items-center">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-32 w-32 mb-4" data-alt="Avatar of Nguyen Van An" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAyYfOdFNWIdiCP-tjb2NeTJ2638JDyfAHYI-hk8mLfIBQr5y7A0FE_g9jC11hwhn7HXjBQbFMQXuMUk84gCE6AoxfABBzn-evY-H3Vw1gG6LmOPbbvt2uOpC6oZq_ro0bTahYHFZGEu0PrpTfVVBzJXfKuVupLvK3NeRTRElFRTqG-FOwai2Fh5_taEstXpjGH7uXr8HKUKJQPn8sj5FpCfDMhmtoqihD-zYEoOLGpveW8aj4xge3IuBQMaRvEBLRwqMeYqwM7_ZzO");'></div>
<p class="text-[#343A40] dark:text-white text-xl font-bold leading-tight">Nguyễn Văn An</p>
<p class="text-[#6c757d] dark:text-gray-400 text-sm font-normal leading-normal">Tham gia ngày 15/01/2023</p>
</div>
</div>
<div class="bg-white dark:bg-[#182c28] border border-[#DEE2E6] dark:border-gray-700 rounded-xl p-6">
<div class="flex items-center justify-between mb-4">
<h2 class="text-[#343A40] dark:text-white text-lg font-bold leading-tight">Thông tin cá nhân</h2>
<button class="flex items-center justify-center size-8 rounded-full hover:bg-primary/10 text-primary">
<span class="material-symbols-outlined text-base">edit</span>
</button>
</div>
<div class="flex flex-col gap-4">
<label class="flex flex-col">
<p class="text-[#343A40] dark:text-gray-300 text-sm font-medium leading-normal pb-2">Họ và Tên</p>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#343A40] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#DEE2E6] dark:border-gray-600 bg-background-light dark:bg-[#10221f] h-11 placeholder:text-[#6c757d] px-3 text-base font-normal leading-normal" value="Nguyễn Văn An"/>
</label>
<label class="flex flex-col">
<p class="text-[#343A40] dark:text-gray-300 text-sm font-medium leading-normal pb-2">Địa chỉ Email</p>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#343A40] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#DEE2E6] dark:border-gray-600 bg-background-light dark:bg-[#10221f] h-11 placeholder:text-[#6c757d] px-3 text-base font-normal leading-normal" value="nguyen.van.an@email.com"/>
</label>
<label class="flex flex-col">
<p class="text-[#343A40] dark:text-gray-300 text-sm font-medium leading-normal pb-2">Số điện thoại</p>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#343A40] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#DEE2E6] dark:border-gray-600 bg-background-light dark:bg-[#10221f] h-11 placeholder:text-[#6c757d] px-3 text-base font-normal leading-normal" value="0901234567"/>
</label>
<label class="flex flex-col">
<p class="text-[#343A40] dark:text-gray-300 text-sm font-medium leading-normal pb-2">Địa chỉ</p>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#343A40] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#DEE2E6] dark:border-gray-600 bg-background-light dark:bg-[#10221f] h-11 placeholder:text-[#6c757d] px-3 text-base font-normal leading-normal" value="123 Đường ABC, Quận 1, TP. HCM"/>
</label>
<label class="flex flex-col">
<p class="text-[#343A40] dark:text-gray-300 text-sm font-medium leading-normal pb-2">Ghi chú</p>
<textarea class="form-textarea flex w-full min-w-0 flex-1 resize-y overflow-hidden rounded-lg text-[#343A40] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#DEE2E6] dark:border-gray-600 bg-background-light dark:bg-[#10221f] min-h-24 placeholder:text-[#6c757d] px-3 py-2 text-base font-normal leading-normal">Khách hàng VIP, ưu tiên check-in sớm nếu có thể.</textarea>
</label>
</div>
</div>
</div>
<!-- Right Column: Booking History -->
<div class="xl:col-span-2">
<div class="bg-white dark:bg-[#182c28] border border-[#DEE2E6] dark:border-gray-700 rounded-xl p-6 h-full">
<h2 class="text-[#343A40] dark:text-white text-lg font-bold leading-tight mb-4">Lịch sử đặt phòng</h2>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="border-b border-[#DEE2E6] dark:border-gray-700">
<tr class="text-sm font-semibold text-[#6c757d] dark:text-gray-400">
<th class="py-3 px-4">Mã đặt phòng</th>
<th class="py-3 px-4">Tên Homestay</th>
<th class="py-3 px-4">Ngày nhận phòng</th>
<th class="py-3 px-4">Ngày trả phòng</th>
<th class="py-3 px-4 text-center">Trạng thái</th>
</tr>
</thead>
<tbody class="divide-y divide-[#DEE2E6] dark:divide-gray-700">
<tr class="text-sm text-[#343A40] dark:text-gray-300">
<td class="py-3 px-4 font-medium">BK-12345</td>
<td class="py-3 px-4">The Cozy Cabin</td>
<td class="py-3 px-4">10/12/2023</td>
<td class="py-3 px-4">12/12/2023</td>
<td class="py-3 px-4 text-center">
<span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-300">Hoàn thành</span>
</td>
</tr>
<tr class="text-sm text-[#343A40] dark:text-gray-300">
<td class="py-3 px-4 font-medium">BK-67890</td>
<td class="py-3 px-4">Seaside Villa</td>
<td class="py-3 px-4">20/08/2023</td>
<td class="py-3 px-4">25/08/2023</td>
<td class="py-3 px-4 text-center">
<span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-300">Hoàn thành</span>
</td>
</tr>
<tr class="text-sm text-[#343A40] dark:text-gray-300">
<td class="py-3 px-4 font-medium">BK-11223</td>
<td class="py-3 px-4">Mountain Retreat</td>
<td class="py-3 px-4">05/05/2023</td>
<td class="py-3 px-4">07/05/2023</td>
<td class="py-3 px-4 text-center">
<span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/50 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-300">Đã hủy</span>
</td>
</tr>
<tr class="text-sm text-[#343A40] dark:text-gray-300">
<td class="py-3 px-4 font-medium">BK-44556</td>
<td class="py-3 px-4">Lakeside Bungalow</td>
<td class="py-3 px-4">28/02/2024</td>
<td class="py-3 px-4">02/03/2024</td>
<td class="py-3 px-4 text-center">
<span class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900/50 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:text-yellow-300">Sắp tới</span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
</body></html>