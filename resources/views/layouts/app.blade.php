{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Betawi Asix')</title>
        <link rel="icon" type="image/png" href="{{ asset('image/Logo.png') }}">
    {{-- Tailwind, FontAwesome, App CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    @vite('resources/css/app.css')
    @livewireStyles
     <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-reverse': 'floatReverse 7s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0) rotate(-2deg)' },
                            '50%': { transform: 'translateY(-15px) rotate(2deg)' },
                        },
                        floatReverse: {
                            '0%, 100%': { transform: 'translateY(0) rotate(2deg)' },
                            '50%': { transform: 'translateY(-15px) rotate(-2deg)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom CSS (sama seperti yang Anda punya) */
           body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            min-height: 100vh;
        }

        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .nav-item::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #f59e0b;
            transition: width .3s;
        }
        .nav-item:hover::after {
            width: 100%;
        }
       .active-nav {
    /* font-weight: bold; */
    color: #d97706 !important; /* amber-600 */
    border-bottom: 2px solid #f59e0b;
}
        .cart-item-enter {
            opacity: 0;
            transform: translateX(-20px);
        }
        .cart-item-enter-active {
            opacity: 1;
            transform: translateX(0);
            transition: all 300ms ease-in;
        }
        .cart-item-exit {
            opacity: 1;
        }
        .cart-item-exit-active {
            opacity: 0;
            transform: translateX(20px);
            transition: all 300ms ease-in;
        }

        .org-card {
            transition: all 0.3s ease;
            transform: translateY(0);
        }
        
        .org-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }
        
         .stacked-photo {
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
                        z-index 0.2s, 
                        box-shadow 0.3s;
            will-change: transform, z-index;
        }
        
        .stacked-photo:hover {
            transform: translateX(-50%) scale(1.18) rotate(0deg) !important;
            z-index: 50 !important;
            box-shadow: 0 35px 60px -10px rgba(0,0,0,0.7);
        }
        
       @media (max-width: 767px) {
    #org-container {
        height: auto !important;
        min-height: 500px;
        padding: 20px 0;
    }
    #org-photo-stack {
        flex-direction: row !important;
        gap: 12px !important;
        margin-top: 0;
        justify-content: center !important;
        align-items: center !important;
    }
    .stacked-photo {
        position: static !important;
        transform: none !important;
        width: 96px !important;   /* w-24 */
        height: 128px !important; /* h-32 */
        margin: 0 !important;
        box-shadow: 0 15px 30px -10px rgba(0,0,0,0.3);
    }
    .mobile-video {
        margin-top: 30px !important;
        max-width: 90% !important;
    }
}

.gallery-slide {
    opacity: 0.5;
    z-index: 1;
    transform: scale(0.7) translateY(40px);
    filter: blur(2px);
    pointer-events: none;
}
.gallery-slide.active {
    opacity: 1;
    z-index: 10;
    transform: scale(1.1) translateY(0);
    filter: none;
    pointer-events: auto;
    box-shadow: 0 8px 32px 0 rgba(0,0,0,0.25);
}
.gallery-slide.left, .gallery-slide.right {
    opacity: 0.8;
    z-index: 5;
    transform: scale(0.85) translateY(20px);
    filter: blur(1px);
}
.spinner {
  display: inline-block;
  width: 1em;
  height: 1em;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
  vertical-align: middle;
  margin-right: 0.5em; /* spasi antara spinner dan teks */
}

/* definisi animasi */
@keyframes spin {
  to { transform: rotate(360deg); }
}

/* class bantu ketika loading: sembunyikan teks span biasa */
.loading .btn-text { visibility: hidden; }
.loading .spinner { visibility: visible; }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    {{-- Navbar --}}
    <x-app-navbar />

    {{-- Bagian konten halaman akan di‐inject di sini --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
   <footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Brand -->
           
		  <h3 class="text-2xl font-bold text-amber-400 text-center mx-auto mt-6 lg:mt-14">
    Betawi Asix
  </h3>

            <!-- Menu -->
            <div>
                <h4 class="text-lg font-semibold mb-3 text-amber-300">Menu</h4>
                <ul class="space-y-2">
                    <li><a href="#menu" class="text-gray-300 hover:text-amber-300 transition">Soto Betawi</a></li>
                    <li><a href="#menu" class="text-gray-300 hover:text-amber-300 transition">Soto + Nasi</a></li>
                    <li><a href="#menu" class="text-gray-300 hover:text-amber-300 transition">Bir Pletok</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="text-lg font-semibold mb-3 text-amber-300">Kontak</h4>
                <ul class="space-y-2 text-gray-300">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-amber-300 mt-1"></i>
                        <span>Jl. Raya Pejaten Pasar Minggu Jakarta Selatan 12510</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-phone text-amber-300"></i>
                        <span>0821-0306-25</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-envelope text-amber-300"></i>
                        <span>Betawi.a6@gmail.com</span>
                    </li>
                </ul>
            </div>

            <!-- Jam Buka & Sosmed -->
            <div>
                <h4 class="text-lg font-semibold mb-3 text-amber-300">Jam Buka</h4>
                <ul class="space-y-2 text-gray-300 mb-4">
                    <li class="flex justify-between">
                        <span>Kamis-Jumat</span>
                        <span>07:00 - 13.00</span>
                    </li>
                </ul>
                <div class="flex justify-center space-x-5 mt-2">
                                        <a href="https://www.tiktok.com/@betawi.anam" target="_blank"  class="text-gray-300 hover:text-amber-300 text-2xl transition"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.instagram.com/betawiasix" target="_blank"  class="text-gray-300 hover:text-amber-300 text-2xl transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-10 pt-6 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} Betawi Asix. All rights reserved.</p>
        </div>
    </div>
</footer>

    {{-- Script.js, Livewire --}}
    {{-- <script src="{{ asset('js/script.js') }}"></script> --}}
    @vite('resources/js/app.js')
    @livewireScripts
    <div id="toast-notif" class="fixed top-6 right-6 z-[9999] hidden px-6 py-4 rounded-lg shadow-lg bg-amber-500 text-white font-semibold text-lg transition-all duration-500"></div>
</body>
</html>
