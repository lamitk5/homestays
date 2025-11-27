<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Đăng ký tài khoản Quản trị viên</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
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
<body class="font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="flex flex-1 justify-center items-center py-10 px-4 md:px-10">
<div class="flex w-full max-w-5xl bg-white dark:bg-slate-900 rounded-xl shadow-lg overflow-hidden">
<div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
<div class="flex flex-col items-center mb-8">
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined text-4xl text-emerald-500">home_work</span>
<h1 class="text-2xl font-bold text-slate-800 dark:text-white">HomestayHub</h1>
</div>
</div>
<div class="flex flex-col gap-1 mb-6">
<p class="text-slate-900 dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">Tạo tài khoản Quản trị viên</p>
<p class="text-slate-500 dark:text-slate-400 text-base font-normal leading-normal">Bắt đầu quản lý homestay của bạn một cách hiệu quả.</p>
</div>
<form class="flex flex-col gap-4">
<label class="flex flex-col w-full">
<p class="text-slate-800 dark:text-slate-200 text-sm font-medium leading-normal pb-2">Họ và Tên</p>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal" placeholder="Nhập họ và tên của bạn" value=""/>
</label>
<label class="flex flex-col w-full">
<p class="text-slate-800 dark:text-slate-200 text-sm font-medium leading-normal pb-2">Email</p>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 text-base font-normal leading-normal" placeholder="Nhập địa chỉ email" type="email" value=""/>
</label>
<label class="flex flex-col w-full">
<p class="text-slate-800 dark:text-slate-200 text-sm font-medium leading-normal pb-2">Mật khẩu</p>
<div class="flex w-full flex-1 items-stretch rounded-lg">
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-l-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 border-r-0 pr-2 text-base font-normal leading-normal" placeholder="Tối thiểu 8 ký tự" type="password" value=""/>
<button class="text-slate-500 dark:text-slate-400 flex border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 items-center justify-center px-3 rounded-r-lg border-l-0 hover:bg-slate-50 dark:hover:bg-slate-800" type="button">
<span class="material-symbols-outlined text-xl">visibility</span>
</button>
</div>
<p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">Tối thiểu 8 ký tự, bao gồm chữ hoa, chữ thường và số.</p>
</label>
<label class="flex flex-col w-full">
<p class="text-slate-800 dark:text-slate-200 text-sm font-medium leading-normal pb-2">Xác nhận Mật khẩu</p>
<div class="flex w-full flex-1 items-stretch rounded-lg">
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-l-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-4 border-r-0 pr-2 text-base font-normal leading-normal" placeholder="Nhập lại mật khẩu của bạn" type="password" value=""/>
<button class="text-slate-500 dark:text-slate-400 flex border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 items-center justify-center px-3 rounded-r-lg border-l-0 hover:bg-slate-50 dark:hover:bg-slate-800" type="button">
<span class="material-symbols-outlined text-xl">visibility_off</span>
</button>
</div>
</label>
<button class="flex items-center justify-center font-bold text-base w-full rounded-lg h-12 px-6 mt-4 bg-primary text-black hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-colors">Đăng ký</button>
</form>
<div class="text-center mt-6">
<p class="text-sm text-slate-500 dark:text-slate-400">
                                Đã có tài khoản? <a class="font-medium text-primary hover:text-primary/80" href="#">Đăng nhập ngay</a>
</p>
</div>
</div>
<div class="hidden md:block md:w-1/2 relative">
<div class="absolute inset-0 bg-gradient-to-br from-black/30 to-black/60"></div>
<div class="w-full h-full bg-center bg-no-repeat bg-cover" data-alt="A cozy and modern homestay living room with a beautiful view" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB6Yh_5jvNzlqKjOU4vuvttCxXwvzZwhwNWr5P94e2-99U2LbtpiEYMAilN4IK93cmUmd_nlRcFkAwVPxyv0ydVZJe5xQSlwPVw9OY4CUUlGMV0Yz0EoxvI8ISr58iCMOYS39O1ZDpC1w1Ga_0bmu5zAh_MxhzHMDnS5ycJKtfCKx7hoXkbeMhxGCxQyQP8r3ktG-CcEBtrvRsVtwksSismo5OeE_N3YzRIRYlvZ-hng4cnqrDuCR4T4qrwoCE33022iTBQfOr4uf51");'></div>
</div>
</div>
</div>
</div>
</div>
</body></html>