<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Đăng nhập Homestay</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#3A86FF",
            "background-light": "#f6f8f8",
            "background-dark": "#10221f",
          },
          fontFamily: {
            "display": ["Plus Jakarta Sans", "sans-serif"]
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
<body class="font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<main class="flex min-h-screen w-full items-stretch justify-center">
<div class="flex w-full max-w-7xl flex-1">
<div class="flex flex-1 flex-col justify-center px-4 py-10 sm:px-10 lg:px-16">
<div class="flex max-w-md flex-col items-center text-center lg:items-start lg:text-left">
<a class="mb-8 flex items-center gap-3 text-2xl font-bold text-[#0d1b19] dark:text-white" href="#">
<span class="material-symbols-outlined text-primary text-4xl"> other_houses </span>
<span>HomestayDeluxe</span>
</a>
<div class="w-full">
<div class="flex flex-col gap-3 text-left">
<p class="text-[#0d1b19] dark:text-gray-200 text-4xl font-black leading-tight tracking-[-0.033em]">Chào mừng trở lại!</p>
<p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Đăng nhập để tiếp tục hành trình của bạn.</p>
</div>
<div class="mt-8 flex flex-col gap-4">
<label class="flex flex-col min-w-40 flex-1 text-left">
<p class="text-[#0d1b19] dark:text-gray-200 text-base font-medium leading-normal pb-2">Email hoặc Tên người dùng</p>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0d1b19] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#cfe7e3] dark:border-gray-700 bg-background-light dark:bg-background-dark focus:border-primary h-14 placeholder:text-gray-500 dark:placeholder:text-gray-400 p-[15px] text-base font-normal leading-normal transition-all" placeholder="Nhập email hoặc tên người dùng của bạn" value=""/>
</label>
<label class="flex flex-col min-w-40 flex-1 text-left">
<div class="flex items-center justify-between pb-2">
<p class="text-[#0d1b19] dark:text-gray-200 text-base font-medium leading-normal">Mật khẩu</p>
<a class="text-sm font-medium text-primary hover:underline" href="#">Quên mật khẩu?</a>
</div>
<div class="flex w-full flex-1 items-stretch rounded-lg">
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0d1b19] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#cfe7e3] dark:border-gray-700 bg-background-light dark:bg-background-dark focus:border-primary h-14 placeholder:text-gray-500 dark:placeholder:text-gray-400 p-[15px] rounded-r-none border-r-0 pr-2 text-base font-normal leading-normal transition-all" placeholder="Nhập mật khẩu của bạn" type="password" value=""/>
<button class="text-gray-500 dark:text-gray-400 flex border border-[#cfe7e3] dark:border-gray-700 bg-background-light dark:bg-background-dark items-center justify-center pr-[15px] rounded-r-lg border-l-0 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
<span class="material-symbols-outlined" data-icon="Eye" data-size="24px" data-weight="regular">visibility</span>
</button>
</div>
</label>
<button class="flex h-14 w-full items-center justify-center rounded-lg bg-primary px-6 text-base font-bold text-white shadow-sm transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 dark:focus:ring-offset-background-dark">
                    Đăng nhập
                  </button>
</div>
<div class="relative my-8 flex items-center">
<div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
<span class="mx-4 flex-shrink text-sm text-gray-500 dark:text-gray-400">Hoặc đăng nhập bằng</span>
<div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
</div>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
<button class="flex h-12 w-full items-center justify-center gap-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-background-light dark:bg-background-dark px-4 py-2 text-sm font-medium text-[#0d1b19] dark:text-white shadow-sm transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 dark:focus:ring-offset-background-dark">
<svg class="h-5 w-5" fill="none" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M22.5777 12.2577C22.5777 11.4577 22.5177 10.6677 22.3877 9.89766H12.2178V14.3377H18.1578C17.8678 15.8977 16.9978 17.2377 15.6578 18.0977V20.8177H19.5078C21.4978 18.9877 22.5777 15.9077 22.5777 12.2577Z" fill="#4285F4"></path>
<path d="M12.2178 23.0001C15.2578 23.0001 17.7878 22.0101 19.5078 20.8101L15.6578 18.0901C14.6578 18.7601 13.5278 19.1601 12.2178 19.1601C9.64779 19.1601 7.49779 17.4301 6.64779 15.0901H2.70779V17.9001C4.48779 21.0901 8.08779 23.0001 12.2178 23.0001Z" fill="#34A853"></path>
<path d="M6.64779 15.09C6.42779 14.43 6.29779 13.73 6.29779 13C6.29779 12.27 6.42779 11.57 6.64779 10.91V8.1H2.70779C1.94779 9.56 1.42779 11.22 1.42779 13C1.42779 14.78 1.94779 16.44 2.70779 17.9L6.64779 15.09Z" fill="#FBBC05"></path>
<path d="M12.2178 6.84C13.6278 6.81 14.9378 7.32 15.9878 8.31L19.5778 4.88C17.7178 3.23 15.1778 2 12.2178 2C8.08779 2 4.48779 3.91 2.70779 7.1L6.64779 9.91C7.49779 7.57 9.64779 5.84 12.2178 5.84V6.84Z" fill="#EA4335"></path>
</svg>
<span>Google</span>
</button>
<button class="flex h-12 w-full items-center justify-center gap-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-background-light dark:bg-background-dark px-4 py-2 text-sm font-medium text-[#0d1b19] dark:text-white shadow-sm transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 dark:focus:ring-offset-background-dark">
<svg class="h-5 w-5" fill="none" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M13.397 20.997V12.801H16.162L16.573 9.592H13.397V7.548C13.397 6.551 13.675 5.874 15.046 5.874H16.697V3.125C16.443 3.09 15.358 3 14.118 3C11.532 3 9.778 4.633 9.778 7.235V9.592H6.987V12.801H9.778V20.997H13.397Z" fill="#1877F2"></path>
</svg>
<span>Facebook</span>
</button>
</div>
<div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
<span>Chưa có tài khoản?</span>
<a class="font-bold text-primary hover:underline" href="#">Đăng ký ngay</a>
</div>
</div>
</div>
</div>
<div class="relative hidden w-1/2 flex-1 lg:block">
<div class="absolute inset-0 h-full w-full bg-center bg-no-repeat bg-cover" data-alt="A tranquil and modern homestay living room with large windows overlooking a green forest" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBQxZB6A_PjynTbiNq6Z8CvJZIZLYEsO3DnAv08kX-AA-7iMH1FI1iSBqGC0q_0wgZx5rwKEs7aB10tRK0kIZUyzQCcr0XIyvGJKYwiuqp8_8HeiK-DB3IahWPwpV--9EOVZRpNQajLqO6vuy4YvmI-pcXuxSxyPkembgf4X_1vR8FDgIj_Uk1QVpRiZpNa_VUHAVGrVR82gyuLH6Oarq6iYjqWssDZXezbt8PmV1DVeTwqu8O3DWFaea_xF1pvAOXf2Ur9ToOMJEWi');"></div>
</div>
</div>
</main>
</div>
</div>
</body></html>