@extends('layouts.app')

@section('title', 'Beranda Betawi Asix')
@if(session('error'))
    <div id="session-error-alert" class="bg-red-500 text-white p-3 rounded mb-4">
        {{ session('error') }}
    </div>
    <script>
        setTimeout(function() {
            var alert = document.getElementById('session-error-alert');
            if (alert) alert.style.display = 'none';
        }, 4000); // 4000 ms = 4 detik, bisa diubah sesuai kebutuhan
    </script>
@endif
@section('content')
<section id="home" class="relative min-h-[60vh] bg-gradient-to-r py-8 md:py-12 flex items-center overflow-hidden">
    <!-- Background Video -->
    <video
        class="absolute inset-0 w-full h-full object-cover z-0"
        src="{{ asset('video/bg-betawi.mp4') }}"
        autoplay
        loop
        muted
        playsinline
        aria-hidden="true"
    ></video>
    <!-- Overlay agar teks tetap terbaca -->
    <div class="absolute inset-0 bg-black bg-opacity-40 z-10"></div>

<div class="container mx-auto px-4 flex flex-col md:flex-row items-start relative z-20 h-full">
            <!-- ...existing content... -->
            <div class="md:w-1/2 mt-24 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-100 mb-4">Soto & Bir Pletok Autentik Betawi Asix</h1>
                <p class="text-lg text-gray-200 mb-8">Nikmati cita rasa tradisional dengan sentuhan modern yang menggugah selera.</p>
                <div class="flex space-x-4">
                    <a href="#menu" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-full transition duration-300">Lihat Menu</a>
                    <a href="#about" class="border border-amber-500 text-amber-500 hover:bg-amber-50 font-bold py-3 px-6 rounded-full transition duration-300">Tentang Kami</a>
                </div>
            </div>
            <div class="md:w-1/2 flex mb-16 justify-center">
                <img src="{{ asset('image/Logo.png')}}" alt="Soto and Bir Pletok" class="rounded-full shadow-xl w-xl max-w-md">
            </div>
        </div>
    </section>

  @livewire('public-menu')

      <section id="about" class="py-16 bg-gray-50 overflow-x-hidden">
        {{-- <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Tentang Kami</h2>
                <div class="w-20 h-1 bg-amber-500 mx-auto"></div>
            </div> --}}
            
           {{-- <section id="tentang-kami" class="bg-white py-16 px-6 md:px-20 mt-10 rounded-lg shadow-lg"> --}}
    <!-- Judul Utama -->
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold text-gray-800">Tentang UD Betawi Asix</h2>
      <p class="text-gray-600 mt-2">Nusantara dalam Cita Rasa: Soto Ayam Betawi & Bir Pletok Autentik</p>
    </div>

    <!-- 1. Profil Perusahaan -->
    <div class="flex flex-col md:flex-row items-center mb-16">
  <div class="ml-0 lg:translate-x-3 md:ml-24 md:w-lg mb-8 md:mb-0 md:pr-8 sm:-translate-x-5">
  <img src="image/gedung.png" alt="Profil BetawiAsix" class="rounded-full shadow-lg w-full">
</div>
      <div class="md:w-1/2 lg:translate-x-7 sm:-translate-x-5">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Profil Singkat Perusahaan</h3>
        <p class="text-gray-600 mb-4">
          Perusahaan yang bergerak di bidang kuliner dan jasa kreatif, dengan fokus utama pada pelestarian budaya Betawi melalui produk makanan, minuman, dan layanan penunjang usaha. UD Betawi Asix menggabungkan semangat tradisional dengan manajemen modern untuk menjangkau pasar yang lebih luas.
        </p>
        <p class="text-gray-600 mb-4">
          UD Betawi Asix untuk pada tahun 2025 sebagai bagian dari simulasi pembelajaran P5 dengan tema "KEBEKERJAAN" di SMK Negeri 8 Jakarta. Terinspirasi dari dunia kerja nyata, perusahaan ini dirancang dengan struktur dan alur kerja profesional.
        </p>
      </div>
    </div>

    <!-- Produk Unggulan Slider -->
<div class="mb-16">
  <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Produk Unggulan</h3>
  <div id="featured-slider" class="relative max-w-2xl mx-auto">
    <!-- Slides -->
    <div class="overflow-hidden rounded-lg shadow-lg bg-gray-50">
      <div class="slider-track flex transition-transform duration-700 ease-in-out">
        <!-- Slide 1 -->
        <div class="slider-slide min-w-full flex flex-col md:flex-row items-center p-6 opacity-100 transition-opacity duration-700">
          <img src="{{ asset('image/soto.jpeg')}}" alt="Soto Ayam Betawi" class="w-full md:w-1/2 h-48 object-cover rounded-lg shadow-md mb-4 md:mb-0 md:mr-6">
          <div class="md:w-1/2">
            <h4 class="text-xl font-semibold text-amber-600 mb-2">Soto Ayam Betawi Premium</h4>
            <p class="text-gray-600 mb-4">
              Soto kami dibuat dari ayam kampung pilihan, rempah-rempah asli Betawi, dan disajikan dengan emping, kentang, serta irisan seledri. Kuah kuning kental dengan rasa gurih yang otentik, cocok untuk menikmati sarapan atau makan siang.
            </p>
            <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition">Lihat Detail</a>
          </div>
        </div>
        <!-- Slide 2 -->
        <div class="slider-slide min-w-full flex flex-col md:flex-row items-center p-6 opacity-0 transition-opacity duration-700">
          <img src="{{ asset('image/bir pletok.avif')}}" alt="Bir Pletok" class="w-full md:w-1/2 h-48 object-cover rounded-lg shadow-md mb-4 md:mb-0 md:mr-6">
          <div class="md:w-1/2">
            <h4 class="text-xl font-semibold text-amber-600 mb-2">Bir Pletok Original & Varian Rasa</h4>
            <p class="text-gray-600 mb-4">
              Bir Pletok kami terbuat dari jahe merah, kayu secang, serai, dan cengkeh pilihan. Tersedia varian rasa jahe-kayu manis dan jahe-cengkeh. Nikmati hangat atau dingin untuk sensasi menyegarkan dan petualangan rasa tradisional.
            </p>
            <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition">Lihat Detail</a>
          </div>
        </div>
        <!-- Slide 3 -->
        <div class="slider-slide min-w-full flex flex-col md:flex-row items-center p-6 opacity-0 transition-opacity duration-700">
          <img src="{{ asset('image/sotoNasi.jpg')}}" alt="soto + nasi" class="w-full md:w-1/2 h-48 object-cover rounded-lg shadow-md mb-4 md:mb-0 md:mr-6">
          <div class="md:w-1/2">
            <h4 class="text-xl font-semibold text-amber-600 mb-2">Nasi Uduk Betawi Spesial</h4>
            <p class="text-gray-600 mb-4">
              Nasi uduk gurih khas Betawi, disajikan dengan lauk ayam goreng, telur balado, sambal kacang, dan taburan bawang goreng. Cocok untuk sarapan maupun makan malam bersama keluarga.
            </p>
            <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition">Lihat Detail</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Detail Produk Unggulan -->
<div id="featured-detail-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 relative">
    <button id="close-featured-detail" class="absolute top-3 right-3 text-gray-500 hover:text-amber-600 text-2xl" aria-label="Tutup">
      <i class="fas fa-times"></i>
    </button>
    <img id="featured-detail-img" src="" alt="" class="w-full h-56 object-cover rounded-lg mb-4">
    <h3 id="featured-detail-title" class="text-2xl font-bold text-amber-700 mb-2"></h3>
    <p id="featured-detail-desc" class="text-gray-700"></p>
  </div>
</div>
    <!-- Navigasi -->
    <button id="slider-prev" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white bg-opacity-80 hover:bg-amber-500 hover:text-white text-amber-600 rounded-full shadow p-2 transition z-10">
      <i class="fas fa-chevron-left"></i>
    </button>
    <button id="slider-next" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white bg-opacity-80 hover:bg-amber-500 hover:text-white text-amber-600 rounded-full shadow p-2 transition z-10">
      <i class="fas fa-chevron-right"></i>
    </button>
    <!-- Dots -->
    <div class="flex justify-center mt-4 space-x-2">
      <button class="slider-dot w-3 h-3 rounded-full bg-amber-500"></button>
      <button class="slider-dot w-3 h-3 rounded-full bg-gray-300"></button>
      <button class="slider-dot w-3 h-3 rounded-full bg-gray-300"></button>
    </div>
  </div>
</div>

   <!-- 3. Struktur Organisasi -->
<!-- 3. Struktur Organisasi -->
<div class="text-center mb-16">
    <h1 class="text-4xl md:text-5xl font-bold text-black mb-4">Struktur Organisasi</h1>
    <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-amber-600 mx-auto rounded-full"></div>
    <p class="text-amber-500 mt-6 max-w-2xl mx-auto">
        Tim profesional yang berdedikasi untuk membangun perusahaan menjadi yang terbaik di industri kuliner dan teknologi
    </p>
</div>

<!-- Content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
    <!-- Kolom Kiri: Judul + Stack Foto -->
 <!-- Container untuk divisi utama -->
        <div id="org-container" class="relative h-[550px] flex items-center justify-center">
            <!-- Floating Background Elements -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="absolute w-64 h-64 rounded-full bg-gradient-to-r from-amber-400/20 to-amber-600/20 blur-2xl -left-10 -top-10"></div>
                <div class="absolute w-72 h-72 rounded-full bg-gradient-to-r from-blue-400/20 to-blue-600/20 blur-2xl -right-10 -bottom-10"></div>
            </div>
            
            <!-- Stacked Photos -->
            <div id="org-photo-stack" class="relative w-full h-full max-w-md mx-auto mt-10 mb-8 flex flex-row items-center justify-center lg:-translate-x-2 md:-translate-x-7 sm:translate-x-20">
                <!-- Data foto -->
                <div class="stacked-photo absolute w-40 h-52 md:w-48 md:h-64 rounded-xl bg-cover bg-center border-4 border-white shadow-lg transition-all duration-700 ease-in-out hover:scale-110 hover:z-50 cursor-pointer"
                     style="background-image: url('{{ asset('image/marketing.jpeg') }}');">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 text-white rounded-b-xl">
                        <p class="font-bold">Div. Marketing</p>
                        <p class="text-sm">Sarah Johnson</p>
                    </div>
                </div>
                
                <div class="stacked-photo absolute w-40 h-52 md:w-48 md:h-64 rounded-xl bg-cover bg-center border-4 border-white shadow-lg transition-all duration-700 ease-in-out hover:scale-110 hover:z-50 cursor-pointer"
                     style="background-image: url('{{ asset('image/it.jpeg') }}');">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 text-white rounded-b-xl">
                        <p class="font-bold">Div. IT</p>
                        <p class="text-sm">M. Rafly Al-Gybran</p> 
                    </div>
                </div>
                
                <div class="stacked-photo absolute w-40 h-52 md:w-48 md:h-64 rounded-xl bg-cover bg-center border-4 border-white shadow-lg transition-all duration-700 ease-in-out hover:scale-110 hover:z-50 cursor-pointer"
                     style="background-image: url('{{asset('image/mice.jpeg')}}');">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 text-white rounded-b-xl">
                        <p class="font-bold">Div. MICE</p>
                        <p class="text-sm">Jennifer Lopez</p>
                    </div>
                </div>
                
                <div class="stacked-photo absolute w-40 h-52 md:w-48 md:h-64 rounded-xl bg-cover bg-center border-4 border-white shadow-lg transition-all duration-700 ease-in-out hover:scale-110 hover:z-50 cursor-pointer"
                     style="background-image: url('{{asset('image/accounting.jpeg')}}');">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 text-white rounded-b-xl">
                        <p class="font-bold">Div. Accounting</p>
                        <p class="text-sm">Emily Davis</p>
                    </div>
                </div>
                
                <div class="stacked-photo absolute w-40 h-52 md:w-48 md:h-64 rounded-xl bg-cover bg-center border-4 border-white shadow-lg transition-all duration-700 ease-in-out hover:scale-110 hover:z-50 cursor-pointer"
                     style="background-image: url('{{asset('image/administrasi.jpeg')}}');">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 text-white rounded-b-xl">
                        <p class="font-bold">Div. Administrasi</p>
                        <p class="text-sm">Robert Brown</p>
                    </div>
                </div>

                  <div class="stacked-photo absolute w-40 h-52 md:w-48 md:h-64 rounded-xl bg-cover bg-center border-4 border-white shadow-lg transition-all duration-700 ease-in-out hover:scale-110 hover:z-50 cursor-pointer"
                     style="background-image: url('{{asset('image/pengurus.jpeg')}}');">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 text-white rounded-b-xl">
                        <p class="font-bold">Pengurus Inti</p>
                        <p class="text-sm">Abitzar Ahmad Farabi</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="w-full flex justify-center mt-2 -translate-y-9 sm:mt-64">
            <video class="rounded-xl shadow-lg w-full max-w-md mobile-video" controls>
                <source src="{{asset('video/slogan.mp4')}}" type="video/mp4">
                Browser Anda tidak mendukung video.
            </video>
        </div>
    </div>

    <!-- Kolom Kanan: Organization Details -->
    <div class="space-y-8 px-20 sm:mr-0 ml-0">
      <div class="org-card bg-white rounded-2xl p-6 shadow-xl">
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-amber-500 p-[10px] rounded-lg">
            <i class="fas fa-lightbulb text-white text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800">Visi & Misi</h3>
    </div>
    <div class="mb-3">
        <p class="font-semibold text-amber-700 mb-1">Visi:</p>
        <p class="text-gray-700 mb-3">
            Menjadi perusahaan kuliner dan kreatif berbasis budaya lokal yang unggul, profesional, dan berdaya saing tinggi di tingkat nasional
        </p>
        <p class="font-semibold text-amber-700 mb-1">Misi:</p>
        <ul class="list-disc ml-6 text-gray-700 space-y-1">
            <li>Menyediakan produk berkualitas tinggi yang mengangkat kearifan lokal.</li>
            <li>Mengembangkan layanan profesional berbasis kebutuhan pelanggan.</li>
            <li>Berinovasi dalam promosi dan pengembangan usaha berbasis digital.</li>
        </ul>
    </div>
</div>
        <!-- Leadership -->
        <div class="org-card bg-white rounded-2xl p-6 shadow-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-amber-500 p-2 rounded-lg">
                    <i class="fas fa-crown text-white text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Pimpinan & Anggota Inti</h3>
            </div>
            <ul class="space-y-3 ml-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-circle text-amber-500 mt-2 text-xs"></i>
                    <div>
                        <p class="font-semibold">Pimpinan</p>
                        <p class="text-gray-600 text-sm">Mengawasi seluruh operasional perusahaan</p>
                    </div>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-circle text-amber-500 mt-2 text-xs"></i>
                    <div>
                        <p class="font-semibold">Direktur</p>
                        <p class="text-gray-600 text-sm">Menentukan strategi dan arah perusahaan</p>
                    </div>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-circle text-amber-500 mt-2 text-xs"></i>
                    <div>
                        <p class="font-semibold">Sekretaris</p>
                        <p class="text-gray-600 text-sm">Mengelola administrasi dan dokumentasi</p>
                    </div>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-circle text-amber-500 mt-2 text-xs"></i>
                    <div>
                        <p class="font-semibold">Bendahara</p>
                        <p class="text-gray-600 text-sm">Mengelola keuangan dan anggaran</p>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Divisions -->
        <div class="org-card bg-white rounded-2xl p-6 shadow-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-amber-500 p-2 rounded-lg">
                    <i class="fas fa-sitemap text-white text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Divisi Utama</h3>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                    <p class="font-bold text-amber-700">Divisi Marketing</p>
                </div>
                <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                    <p class="font-bold text-amber-700">Divisi IT</p>
                </div>
                <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                    <p class="font-bold text-amber-700">Divisi MICE</p>
                </div>
                <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                    <p class="font-bold text-amber-700">Divisi Administrasi</p>
                </div>
                <div class="bg-amber-50 p-3 rounded-lg border border-amber-200 col-span-2">
                    <p class="font-bold text-amber-700">Divisi Accounting</p>
                </div>
            </div>
        </div>
        <!-- Responsibilities -->
        <div class="org-card bg-white rounded-2xl p-6 shadow-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-amber-500 p-2 rounded-lg">
                    <i class="fas fa-tasks text-white text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Tugas & Tanggung Jawab</h3>
            </div>
            <div class="space-y-4">
                <div class="p-4 bg-gradient-to-r from-amber-50 to-white rounded-xl border-l-4 border-amber-500">
                    <p class="font-bold text-gray-800">Pimpinan & Direktur:</p>
                    <p class="text-gray-600">Menetapkan visi, misi, dan arah strategi perusahaan</p>
                </div>
                <div class="p-4 bg-gradient-to-r from-amber-50 to-white rounded-xl border-l-4 border-amber-500">
                    <p class="font-bold text-gray-800">Sekretaris & Bendahara:</p>
                    <p class="text-gray-600">Mengelola administrasi, pendataan, anggaran, dan laporan keuangan</p>
                </div>
                <div class="p-4 bg-gradient-to-r from-amber-50 to-white rounded-xl border-l-4 border-amber-500">
                    <p class="font-bold text-gray-800">Divisi Marketing:</p>
                    <p class="text-gray-600">Merancang kampanye promosi, mengelola media sosial, dan menjalin relasi dengan mitra</p>
                </div>
                <div class="p-4 bg-gradient-to-r from-amber-50 to-white rounded-xl border-l-4 border-amber-500">
                    <p class="font-bold text-gray-800">Divisi IT:</p>
                    <p class="text-gray-600">Mengembangkan dan memelihara sistem backend (Laravel & Filament Admin) serta integrasi QRIS offline</p>
                </div>
                <div class="p-4 bg-gradient-to-r from-amber-50 to-white rounded-xl border-l-4 border-amber-500">
                    <p class="font-bold text-gray-800">Divisi MICE:</p>
                    <p class="text-gray-600">Menangani event, katering, dan penyelenggaraan pameran kuliner</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Slider Landscape -->
<div class="max-w-5xl mx-auto mb-20 mt-16">
  <h3 class="text-3xl font-bold text-gray-800 mb-8 text-center">Galeri</h3>
  <div class="relative group overflow-visible">
    <!-- ini track full-width, di-center oleh parent -->
    <div id="gallery-slider-track"
         class="relative flex items-center justify-center h-80 w-full">
      
      <!-- slide–slide tetap absolute relative ke track -->
      <div class="gallery-slide absolute transition-all duration-700 ease-in-out">
        <img src="{{ asset('image/galeri1.jpeg') }}"
             alt="Galeri 1"
             class="rounded-2xl shadow-lg w-[500px] h-72 object-cover object-center border-4 border-amber-100">
      </div>
      <div class="gallery-slide absolute transition-all duration-700 ease-in-out">
        <img src="{{ asset('image/galeri2.jpeg') }}"
             alt="Galeri 2"
             class="rounded-2xl shadow-lg w-[500px] h-72 object-cover object-center border-4 border-amber-100">
      </div>
      <div class="gallery-slide absolute transition-all duration-700 ease-in-out">
        <img src="{{ asset('image/galeri3.jpeg') }}"
             alt="Galeri 3"
             class="rounded-2xl shadow-lg w-[500px] h-72 object-cover object-center border-4 border-amber-100">
      </div>
      <div class="gallery-slide absolute transition-all duration-700 ease-in-out">
        <img src="{{ asset('image/galeri4.jpeg') }}"
             alt="Galeri 4"
             class="rounded-2xl shadow-lg w-[500px] h-72 object-cover object-center border-4 border-amber-100">
      </div>
    </div>

    <!-- Navigasi -->
    <button id="gallery-prev"
            class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-amber-500 hover:text-white text-amber-600 rounded-full shadow p-3 transition z-10 opacity-0 group-hover:opacity-100">
      <i class="fas fa-chevron-left"></i>
    </button>
    <button id="gallery-next"
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-amber-500 hover:text-white text-amber-600 rounded-full shadow p-3 transition z-10 opacity-0 group-hover:opacity-100">
      <i class="fas fa-chevron-right"></i>
    </button>

    <!-- Dots -->
    <div class="flex justify-center mt-4 space-x-2">
      <button class="gallery-dot w-3 h-3 rounded-full bg-amber-500"></button>
      <button class="gallery-dot w-3 h-3 rounded-full bg-gray-300"></button>
      <button class="gallery-dot w-3 h-3 rounded-full bg-gray-300"></button>
      <button class="gallery-dot w-3 h-3 rounded-full bg-gray-300"></button>
    </div>
  </div>
</div>


     <div class="text-center contact-info">
            <h3 class="text-3xl font-bold text-gray-800 mb-8 sm:mt-7">Kontak Kami</h3>
            
            <!-- Contact Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto mb-12">
                
                <!-- Alamat -->
                <div class="contact-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-amber-100 p-4 rounded-full mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.14 2 5 5.14 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.86-3.14-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-lg text-gray-800 mb-2">Alamat Kantor</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                                 Jl. Raya Pejaten<br> 
                                 Pasar Minggu<br> 
                                 Jakarta Selatan
                        </p>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="contact-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-amber-100 p-4 rounded-full mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-lg text-gray-800 mb-2">Email</h4>
                        <p class="text-gray-600 text-sm mb-2">Hubungi kami melalui:</p>
                        <a href="mailto:support@betawiasix.id" 
                           class="text-amber-600 hover:text-amber-700 hover:underline font-medium transition-colors">
                            Betawi.a6@gmail.com
                        </a>
                    </div>
                </div>
                
                <!-- Telepon / WA -->
                <div class="contact-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-amber-100 p-4 rounded-full mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6.62 10.79a15.091 15.091 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.21c1.21.48 2.53.74 3.89.74a1 1 0 011 1V20a1 1 0 01-1 1C10.07 21 3 13.93 3 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.36.25 2.68.74 3.89a1 1 0 01-.21 1.11l-2.2 2.2z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-lg text-gray-800 mb-2">Telepon / WhatsApp</h4>
                        <p class="text-gray-600 text-sm mb-2">Layanan 24 jam:</p>
                        <a href="tel:+6281234567890" 
                           class="text-amber-600 hover:text-amber-700 hover:underline font-medium transition-colors">
                            0821-0306-25
                        </a>
                    </div>
                </div>
                
            </div>
            
            <!-- Media Sosial -->
            <!-- Media Sosial -->
<div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 max-w-2xl mx-auto mt-8">
    <h4 class="text-xl font-bold text-gray-800 mb-6 text-center">Ikuti Media Sosial Kami</h4>
    <div class="flex justify-center items-center gap-8 text-center">
        <a href="#" aria-label="Facebook" class="social-icon text-gray-600 hover:text-blue-600 p-3 bg-gray-50 rounded-full hover:bg-blue-50 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22.675 0h-21.35C.596 0 0 .596 0 1.325v21.351C0 23.405.596 24 1.325 24H12.82v-9.294H9.692v-3.622h3.129V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.098 2.797.142v3.24l-1.918.001c-1.504 0-1.795.716-1.795 1.764v2.316h3.59l-.467 3.622h-3.123V24h6.127C23.404 24 24 23.405 24 22.676V1.325C24 .596 23.404 0 22.675 0z" />
            </svg>
        </a>
        <a href="#" aria-label="Twitter" class="social-icon text-gray-600 hover:text-blue-400 p-3 bg-gray-50 rounded-full hover:bg-blue-50 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 4.557a9.93 9.93 0 01-2.828.775 4.932 4.932 0 002.163-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.381 4.482 13.944 13.944 0 01-10.125-5.135 4.822 4.822 0 00-.664 2.475 4.916 4.916 0 002.188 4.096 4.902 4.902 0 01-2.228-.616c-.054 2.281 1.582 4.415 3.949 4.89a4.935 4.935 0 01-2.224.084 4.918 4.918 0 004.59 3.417A9.868 9.868 0 010 21.54a13.944 13.944 0 007.548 2.212c9.057 0 14.009-7.513 14.009-14.009 0-.213-.005-.425-.014-.636A10.012 10.012 0 0024 4.557z" />
            </svg>
        </a>
        <a href="#" aria-label="Instagram" class="social-icon text-gray-600 hover:text-pink-600 p-3 bg-gray-50 rounded-full hover:bg-pink-50 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.849.07 1.366.062 2.633.34 3.608 1.315.975.976 1.252 2.243 1.315 3.608.058 1.265.069 1.645.069 4.849s-.012 3.584-.07 4.849c-.062 1.366-.34 2.633-1.315 3.608-.976.975-2.243 1.252-3.608 1.315-1.265.058-1.645.069-4.849.069s-3.584-.012-4.849-.07c-1.366-.062-2.633-.34-3.608-1.315-.975-.976-1.252-2.243-1.315-3.608C2.174 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.849c.062-1.366.34-2.633 1.315-3.608.976-.975 2.243-1.252 3.608-1.315C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.736 0 8.332.014 7.052.072 5.75.13 4.615.445 3.678 1.382 2.741 2.319 2.426 3.454 2.368 4.756c-.058 1.28-.072 1.684-.072 4.95s.014 3.67.072 4.95c.058 1.302.373 2.437 1.31 3.374.937.937 2.072 1.252 3.374 1.31 1.28.058 1.684.072 4.95.072s3.67-.014 4.95-.072c1.302-.058 2.437-.373 3.374-1.31.937-.937 1.252-2.072 1.31-3.374.058-1.28.072-1.684.072-4.95s-.014-3.67-.072-4.95c-.058-1.302-.373-2.437-1.31-3.374-.937-.937-2.072-1.252-3.374-1.31C15.668.014 15.264 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 11-2.881 0 1.44 1.44 0 012.881 0z" />
            </svg>
        </a>
        <a href="#" aria-label="LinkedIn" class="social-icon text-gray-600 hover:text-blue-700 p-3 bg-gray-50 rounded-full hover:bg-blue-50 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
        </a>
    </div>
    <p class="text-gray-500 text-sm mt-4 leading-relaxed text-center">
        Dapatkan update terbaru tentang layanan dan promo menarik dari kami
    </p>
</div>
  {{-- </section>
            <section> --}}
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center ml-5">
                    <div class="text-amber-500 text-4xl mb-4">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Bersertifikat Halal</h4>
                    <p class="text-gray-600">Semua bahan dan proses pengolahan telah mendapatkan sertifikasi halal dari MUI.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="text-amber-500 text-4xl mb-4">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Bahan Berkualitas</h4>
                    <p class="text-gray-600">Kami hanya menggunakan bahan-bahan segar dan berkualitas tinggi.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md text-center mr-5">
                    <div class="text-amber-500 text-4xl mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Racikan Spesial</h4>
                    <p class="text-gray-600">Resep turun-temurun dengan racikan rempah yang pas menjadikan cita rasa unik.</p>
                </div>
            </div>
        </div>
    </section>

 @include('components.app-modals', [
    'cartItems' => $cartItems,
    'totalPrice' => $totalPrice,
    'hasActivePromo' => $hasActivePromo,
])
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.gallery-slide');
    const dots = document.querySelectorAll('.gallery-dot');
    const prev = document.getElementById('gallery-prev');
    const next = document.getElementById('gallery-next');
    let current = 0;
    const total = slides.length;

    function updateSlider(idx) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active', 'left', 'right');
            if (i === idx) {
                slide.classList.add('active');
                slide.style.left = '50%';
                slide.style.transform = 'translateX(-50%) scale(1.1) translateY(0)';
            } else if (i === (idx - 1 + total) % total) {
                slide.classList.add('left');
                slide.style.left = '25%';
                slide.style.transform = 'translateX(-50%) scale(0.85) translateY(20px)';
            } else if (i === (idx + 1) % total) {
                slide.classList.add('right');
                slide.style.left = '75%';
                slide.style.transform = 'translateX(-50%) scale(0.85) translateY(20px)';
            } else {
                slide.style.left = '50%';
                slide.style.transform = 'translateX(-50%) scale(0.7) translateY(40px)';
            }
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-amber-500', i === idx);
            dot.classList.toggle('bg-gray-300', i !== idx);
        });
    }

    next.addEventListener('click', () => {
        current = (current + 1) % total;
        updateSlider(current);
    });
    prev.addEventListener('click', () => {
        current = (current - 1 + total) % total;
        updateSlider(current);
    });
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            current = i;
            updateSlider(current);
        });
    });

    // Swipe support
    let startX = 0;
    const track = document.getElementById('gallery-slider-track');
    track.addEventListener('touchstart', e => startX = e.touches[0].clientX);
    track.addEventListener('touchend', e => {
        let endX = e.changedTouches[0].clientX;
        if (endX - startX > 50) prev.click();
        if (startX - endX > 50) next.click();
    });

    // Auto-slide
    setInterval(() => {
        current = (current + 1) % total;
        updateSlider(current);
    }, 7000);

    updateSlider(0);
});
</script>