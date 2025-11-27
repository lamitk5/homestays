<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý Khách hàng - Homestay Admin</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..200" rel="stylesheet"/>
<style>
    .material-symbols-outlined {
      font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 24
    }
  </style>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#13ecc8",
            "background-light": "#f6f8f8",
            "background-dark": "#10221f",
            "text-light": "#0d1b19",
            "text-dark": "#e0f2f0",
            "subtext-light": "#4c9a8d",
            "subtext-dark": "#a3d9d1",
            "surface-light": "#e7f3f1",
            "surface-dark": "#1a3a36",
            "border-light": "#cfe7e3",
            "border-dark": "#2a5c54",
            "danger": "#ef4444",
            "warning": "#f59e0b",
            "info": "#3b82f6"
          },
          fontFamily: {
            "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
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
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="relative flex min-h-screen w-full">
<!-- SideNavBar -->
<aside class="sticky top-0 h-screen w-64 flex-shrink-0 bg-background-light dark:bg-background-dark p-4 border-r border-border-light dark:border-border-dark">
<div class="flex h-full flex-col justify-between">
<div class="flex flex-col gap-8">
<div class="flex items-center gap-3 px-3">
<span class="material-symbols-outlined text-primary text-4xl">home</span>
<h1 class="text-xl font-bold text-text-light dark:text-text-dark">Homestay</h1>
</div>
<div class="flex flex-col gap-4">
<div class="flex flex-col gap-2">
<a class="flex items-center gap-3 px-3 py-2 text-text-light dark:text-text-dark hover:bg-surface-light dark:hover:bg-surface-dark rounded-lg" href="#">
<span class="material-symbols-outlined">dashboard</span>
<p class="text-sm font-medium">Dashboard</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-text-light dark:text-text-dark hover:bg-surface-light dark:hover:bg-surface-dark rounded-lg" href="#">
<span class="material-symbols-outlined">calendar_month</span>
<p class="text-sm font-medium">Bookings</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 text-primary" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">groups</span>
<p class="text-sm font-bold">Customers</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-text-light dark:text-text-dark hover:bg-surface-light dark:hover:bg-surface-dark rounded-lg" href="#">
<span class="material-symbols-outlined">bar_chart</span>
<p class="text-sm font-medium">Reports</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 text-text-light dark:text-text-dark hover:bg-surface-light dark:hover:bg-surface-dark rounded-lg" href="#">
<span class="material-symbols-outlined">settings</span>
<p class="text-sm font-medium">Settings</p>
</a>
</div>
</div>
</div>
<div class="flex flex-col gap-4">
<div class="flex gap-3 items-center">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Abstract gradient profile picture" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCut5nPSDR467unqC8JnzaNF0ofxL88zvzCKkxHR_Qb61S39TkxWiAFD62wJO8mB_IVDNP-crYxXzYQfPg35JkaBjy4gmAmHJB6PTQvex3Asmqk7PqVVT4RBWD3IYRO1cr8kR6dKiMU3_k1M8DWwYArWmuykVvWMYDTRH-jqJTNRBA2uo9LMG00RqQDNmfBhOyN0vbB7ewHu9ugrhSlp-AD9BpZjVjm-eo8Uo0O4ZY69XFMG2qMPd0ICedz7zMJhmLg5hSG_J2e_dJD");'></div>
<div class="flex flex-col">
<h1 class="text-text-light dark:text-text-dark text-base font-medium leading-normal">Admin Name</h1>
<p class="text-subtext-light dark:text-subtext-dark text-sm font-normal leading-normal">admin@homestay.com</p>
</div>
</div>
<div class="flex flex-col gap-1 border-t border-border-light dark:border-border-dark pt-4">
<a class="flex items-center gap-3 px-3 py-2 text-text-light dark:text-text-dark hover:bg-surface-light dark:hover:bg-surface-dark rounded-lg" href="#">
<span class="material-symbols-outlined">logout</span>
<p class="text-sm font-medium">Log Out</p>
</a>
</div>
</div>
</div>
</aside>
<!-- Main Content -->
<main class="flex-1 p-8">
<div class="flex flex-col max-w-7xl mx-auto gap-6">
<!-- PageHeading -->
<div class="flex flex-wrap justify-between gap-4 items-center">
<div class="flex flex-col gap-1">
<p class="text-text-light dark:text-text-dark text-3xl font-bold leading-tight">Quản lý Khách hàng</p>
<p class="text-subtext-light dark:text-subtext-dark text-base font-normal">Xem, tìm kiếm, và quản lý tất cả thông tin khách hàng.</p>
</div>
<button class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-background-dark text-sm font-bold">
<span class="material-symbols-outlined">add</span>
<span class="truncate">Thêm Khách hàng mới</span>
</button>
</div>
<!-- Search and Filter Area -->
<div class="flex flex-col gap-4">
<!-- SearchBar -->
<div class="px-0 py-1">
<label class="flex flex-col min-w-40 h-12 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full">
<div class="text-subtext-light dark:text-subtext-dark flex border-none bg-surface-light dark:bg-surface-dark items-center justify-center pl-4 rounded-l-lg border-r-0">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-surface-light dark:bg-surface-dark h-full placeholder:text-subtext-light dark:placeholder:text-subtext-dark px-4 rounded-l-none border-l-0 pl-2 text-base font-normal" placeholder="Tìm kiếm theo tên, email, hoặc số điện thoại..." value=""/>
</div>
</label>
</div>
<!-- Chips -->
<div class="flex gap-3 p-1 overflow-x-auto">
<button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-surface-light dark:bg-surface-dark pl-4 pr-2 hover:bg-primary/20 dark:hover:bg-primary/30">
<p class="text-text-light dark:text-text-dark text-sm font-medium">Loại khách hàng</p>
<span class="material-symbols-outlined text-subtext-light dark:text-subtext-dark">arrow_drop_down</span>
</button>
<button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-surface-light dark:bg-surface-dark pl-4 pr-2 hover:bg-primary/20 dark:hover:bg-primary/30">
<p class="text-text-light dark:text-text-dark text-sm font-medium">Trạng thái</p>
<span class="material-symbols-outlined text-subtext-light dark:text-subtext-dark">arrow_drop_down</span>
</button>
<button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-surface-light dark:bg-surface-dark pl-4 pr-2 hover:bg-primary/20 dark:hover:bg-primary/30">
<p class="text-text-light dark:text-text-dark text-sm font-medium">Lịch sử đặt phòng</p>
<span class="material-symbols-outlined text-subtext-light dark:text-subtext-dark">arrow_drop_down</span>
</button>
<button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg pl-3 pr-3 text-subtext-light dark:text-subtext-dark hover:text-danger">
<span class="material-symbols-outlined">filter_alt_off</span>
<p class="text-sm font-medium">Xóa bộ lọc</p>
</button>
</div>
</div>
<!-- Table -->
<div class="px-0 py-3 @container">
<div class="flex overflow-hidden rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-surface-dark/50">
<table class="w-full text-left">
<thead class="bg-surface-light dark:bg-surface-dark">
<tr>
<th class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-medium">Tên Khách hàng</th>
<th class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-medium">Email</th>
<th class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-medium">Số điện thoại</th>
<th class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-medium text-center">Tổng số đặt phòng</th>
<th class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-medium">Ngày tham gia</th>
<th class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-medium text-center">Hành động</th>
</tr>
</thead>
<tbody class="divide-y divide-border-light dark:divide-border-dark">
<tr class="hover:bg-surface-light dark:hover:bg-surface-dark">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Nguyễn Văn An</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">nguyenvanan@email.com</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">0901234567</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal text-center">5</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">2023-10-26</td>
<td class="px-4 py-3 text-center">
<div class="flex items-center justify-center gap-2">
<button class="p-2 rounded-full hover:bg-primary/20 text-info"><span class="material-symbols-outlined text-base">edit</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-warning"><span class="material-symbols-outlined text-base">history</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-danger"><span class="material-symbols-outlined text-base">delete</span></button>
</div>
</td>
</tr>
<tr class="hover:bg-surface-light dark:hover:bg-surface-dark">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Trần Thị Bích</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">tranthibich@email.com</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">0912345678</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal text-center">2</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">2023-09-15</td>
<td class="px-4 py-3 text-center">
<div class="flex items-center justify-center gap-2">
<button class="p-2 rounded-full hover:bg-primary/20 text-info"><span class="material-symbols-outlined text-base">edit</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-warning"><span class="material-symbols-outlined text-base">history</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-danger"><span class="material-symbols-outlined text-base">delete</span></button>
</div>
</td>
</tr>
<tr class="hover:bg-surface-light dark:hover:bg-surface-dark">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Lê Văn Cường</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">levancuong@email.com</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">0987654321</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal text-center">8</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">2023-08-01</td>
<td class="px-4 py-3 text-center">
<div class="flex items-center justify-center gap-2">
<button class="p-2 rounded-full hover:bg-primary/20 text-info"><span class="material-symbols-outlined text-base">edit</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-warning"><span class="material-symbols-outlined text-base">history</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-danger"><span class="material-symbols-outlined text-base">delete</span></button>
</div>
</td>
</tr>
<tr class="hover:bg-surface-light dark:hover:bg-surface-dark">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Phạm Thị Diệu</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">phamthidieu@email.com</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">0934567890</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal text-center">1</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">2023-11-05</td>
<td class="px-4 py-3 text-center">
<div class="flex items-center justify-center gap-2">
<button class="p-2 rounded-full hover:bg-primary/20 text-info"><span class="material-symbols-outlined text-base">edit</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-warning"><span class="material-symbols-outlined text-base">history</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-danger"><span class="material-symbols-outlined text-base">delete</span></button>
</div>
</td>
</tr>
<tr class="hover:bg-surface-light dark:hover:bg-surface-dark">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Hoàng Văn Em</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">hoangvanem@email.com</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">0978123456</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal text-center">12</td>
<td class="px-4 py-3 text-subtext-light dark:text-subtext-dark text-sm font-normal">2023-01-20</td>
<td class="px-4 py-3 text-center">
<div class="flex items-center justify-center gap-2">
<button class="p-2 rounded-full hover:bg-primary/20 text-info"><span class="material-symbols-outlined text-base">edit</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-warning"><span class="material-symbols-outlined text-base">history</span></button>
<button class="p-2 rounded-full hover:bg-primary/20 text-danger"><span class="material-symbols-outlined text-base">delete</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Pagination -->
<nav class="flex items-center justify-between pt-4">
<div class="text-sm text-subtext-light dark:text-subtext-dark">
                Hiển thị <span class="font-medium text-text-light dark:text-text-dark">1</span> đến <span class="font-medium text-text-light dark:text-text-dark">5</span> trên <span class="font-medium text-text-light dark:text-text-dark">50</span> kết quả
            </div>
<div class="flex items-center gap-2">
<button class="inline-flex items-center justify-center h-9 w-9 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-subtext-light dark:text-subtext-dark hover:bg-surface-light dark:hover:bg-surface-dark">
<span class="material-symbols-outlined text-lg">chevron_left</span>
</button>
<button class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-primary text-background-dark text-sm font-bold">1</button>
<button class="inline-flex items-center justify-center h-9 w-9 rounded-lg text-text-light dark:text-text-dark hover:bg-surface-light dark:hover:bg-surface-dark text-sm">2</button>
<button class="inline-flex items-center justify-center h-9 w-9 rounded-lg text-text-light dark:text-text-dark hover:bg-surface-light dark:hover:bg-surface-dark text-sm">3</button>
<span class="text-subtext-light dark:text-subtext-dark">...</span>
<button class="inline-flex items-center justify-center h-9 w-9 rounded-lg text-text-light dark:text-text-dark hover:bg-surface-light dark:hover:bg-surface-dark text-sm">10</button>
<button class="inline-flex items-center justify-center h-9 w-9 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-subtext-light dark:text-subtext-dark hover:bg-surface-light dark:hover:bg-surface-dark">
<span class="material-symbols-outlined text-lg">chevron_right</span>
</button>
</div>
</nav>
</div>
</main>
</div>
</body></html>