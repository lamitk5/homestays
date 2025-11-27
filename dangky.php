<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Đăng ký tài khoản</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
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
                        "accent": "#3A5A40",
                        "text-main": "#333333",
                        "error": "#D9534F"
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
<body class="font-display">
<div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden p-4 md:p-6" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBdDrkGklSaLcQYhcERwYB1kywXRoyuDEm1EmcCwAU_lOha-ves1XwIYxYChi2uY6HvP5N9PGV6StHVAFxXHkwJAgbGjCJmSwnXXIUfkJIu4EsB1z0Y2yccqmEiUbPWSXmabVeVKqNfFHSk6KwJnBobFcGxJkNjFp7_O0hvYSZUqFU5xbPXAtXvOxwu43cGmL_eBWeKzM2OM9HFRHMY2o-j-1umIiaHVe6SyCBllaMxxItWVWLTXcfW7Idl3-VllcbRWigsOJ5OS9lH'); background-size: cover; background-position: center;">
<div class="absolute inset-0 bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm"></div>
<div class="relative z-10 flex w-full max-w-md flex-col items-center rounded-xl bg-background-light dark:bg-background-dark shadow-2xl p-8 md:p-10 border border-gray-200 dark:border-gray-700">
<div class="mb-6 flex flex-col items-center text-center">
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined text-accent text-4xl">home_pin</span>
<span class="text-2xl font-bold text-accent">HomestayHub</span>
</div>
<h1 class="text-text-main dark:text-gray-100 text-3xl font-bold tracking-tight">Tạo tài khoản mới</h1>
<p class="text-text-main/70 dark:text-gray-300 text-base font-normal leading-normal mt-2">Tìm kiếm và đặt ngay những homestay tuyệt vời nhất.</p>
</div>
<form class="w-full space-y-4">
<div class="flex w-full flex-col">
<label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="fullName">Họ và Tên</label>
<div class="relative flex w-full items-center">
<span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">person</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-base font-normal leading-normal" id="fullName" placeholder="Nhập họ và tên của bạn" type="text" value=""/>
</div>
</div>
<div class="flex w-full flex-col">
<label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="email">Email</label>
<div class="relative flex w-full items-center">
<span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">mail</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-base font-normal leading-normal" id="email" placeholder="Nhập email" type="email" value=""/>
</div>
<!-- Example Error Message -->
<!-- <p class="text-error text-sm mt-1">Email không hợp lệ.</p> -->
</div>
<div class="flex w-full flex-col">
<label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="password">Mật khẩu</label>
<div class="relative flex w-full items-center">
<span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">lock</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-10 text-base font-normal leading-normal" id="password" placeholder="Nhập mật khẩu" type="password" value=""/>
<button class="absolute right-3 text-gray-400 dark:text-gray-500 hover:text-text-main dark:hover:text-gray-300" type="button">
<span class="material-symbols-outlined" data-icon="Eye" data-size="24px" data-weight="regular">visibility_off</span>
</button>
</div>
</div>
<div class="flex w-full flex-col">
<label class="text-text-main dark:text-gray-200 text-sm font-medium leading-normal pb-2" for="confirmPassword">Xác nhận Mật khẩu</label>
<div class="relative flex w-full items-center">
<span class="material-symbols-outlined absolute left-3 text-gray-400 dark:text-gray-500">lock</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-main dark:text-gray-100 focus:outline-0 focus:ring-2 focus:ring-accent/50 border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-gray-800 focus:border-accent h-12 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-base font-normal leading-normal" id="confirmPassword" placeholder="Xác nhận lại mật khẩu" type="password" value=""/>
</div>
<!-- Example Error Message -->
<!-- <p class="text-error text-sm mt-1">Mật khẩu không khớp.</p> -->
</div>
<div class="pt-4">
<button class="flex w-full items-center justify-center rounded-lg bg-accent h-12 px-6 text-base font-bold text-white shadow-sm hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:focus:ring-offset-background-dark" type="submit">
                        Đăng ký
                    </button>
</div>
</form>
<div class="mt-6 text-center">
<p class="text-text-main/80 dark:text-gray-400 text-sm">
                    Đã có tài khoản?
                    <a class="font-bold text-accent hover:underline" href="#">Đăng nhập ngay</a>
</p>
</div>
</div>
</div>
</body></html>