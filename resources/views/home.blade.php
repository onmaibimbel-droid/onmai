<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Pusat Bimbingan Belajar ONMAI" />
    <meta name="author" content="ONMAI" />
    <title>@yield('title', 'ONMAI - Pusat Bimbingan Belajar')</title>
    
    

    <link rel="icon" type="image/png" href="{{ asset('landing-page/assets/img/img1.png') }}" />
    
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800,900" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700" rel="stylesheet" type="text/css" />

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  
    <style>
        body { font-family: 'Roboto Slab', serif; overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Montserrat', sans-serif; }
        
        /* Warna Brand */
        :root {
            --brand-color: #ffc800;
            --brand-dark: #d9aa00;
        }
        
        .bg-brand { background-color: var(--brand-color); }
        .text-brand { color: var(--brand-color); }
        .border-brand { border-color: var(--brand-color); }
        .hover-bg-brand:hover { background-color: var(--brand-dark); }
        .hover-text-brand:hover { color: var(--brand-color); }
        
        /* Preloader Styles */
        #preloader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: #f4f5f8; /* Gray-900 */
            z-index: 9999;
            display: flex; justify-content: center; align-items: center;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
        .loader-content { text-align: center; }
        .loader-logo { width: 80px; animation: pulse 1.5s infinite; }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Card Styles Enhanced */
        .card-ref {
            border: 1px solid #f3f4f6;
            border-radius: 16px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: white;
        }
        .card-ref:hover {
            border-color: var(--brand-color);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px -10px rgba(255, 200, 0, 0.15);
        }

        /* Hamburger Menu */
        .hamburger span {
            display: block; width: 25px; height: 3px; background-color: white;
            margin-bottom: 5px; position: relative; border-radius: 3px;
            transition: transform 0.4s, opacity 0.4s;
        }
        .hamburger.active span:nth-child(1) { transform: translateY(8px) rotate(45deg); }
        .hamburger.active span:nth-child(2) { opacity: 0; }
        .hamburger.active span:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

        /* Navigation & Dropdown */
        .dropdown-menu { opacity: 0; visibility: hidden; transform: translateY(10px); transition: all 0.3s ease-in-out; }
        .group:hover .dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        #mobile-menu { max-height: 0; opacity: 0; overflow: hidden; transition: max-height 0.4s ease, opacity 0.4s ease; }
        #mobile-menu.open { max-height: 500px; opacity: 1; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body id="page-top" class="bg-white text-gray-700 antialiased selection:bg-[#ffc800] selection:text-black">

    <div id="preloader">
        <div class="loader-content">
            <img src="{{ asset('landing-page/assets/img/img1.png') }}" class="loader-logo mb-4" alt="Loading...">
            <h2 class="text-white font-heading font-bold text-xl tracking-widest">ON<span class="text-brand">MAI</span></h2>
        </div>
    </div>

    <nav class="fixed w-full z-50 top-0 bg-gray-900/95 backdrop-blur-md shadow-2xl border-b border-gray-800" id="mainNav">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a class="flex items-center gap-3 text-xl font-bold font-heading tracking-wider text-white group" href="#page-top">
                    <img src="{{ asset('landing-page/assets/img/img1.png') }}" class="h-10 w-auto transition transform group-hover:rotate-12" alt="Logo" />
                    <span>ON<span class="text-brand">MAI</span></span>
                </a>
    
                <div class="hidden lg:flex items-center space-x-8 text-sm font-semibold tracking-wide text-gray-300 font-heading">
                    <a class="hover:text-[#ffc800] transition duration-300 relative group" href="#page-top">
                        Beranda <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#ffc800] transition-all group-hover:w-full"></span>
                    </a>
                    <!--a class="hover:text-[#ffc800] transition duration-300 relative group" href="#services">
                        Fasilitas <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#ffc800] transition-all group-hover:w-full"></span>
                    </a-->
                    <a class="hover:text-[#ffc800] transition duration-300 relative group" href="{{ route('program') }}">
                        Program <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#ffc800] transition-all group-hover:w-full"></span>
                    </a>
                    <a class="hover:text-[#ffc800] transition duration-300 relative group" href="{{ route('about.about') }}">
                        Tentang <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#ffc800] transition-all group-hover:w-full"></span>
                    </a>
                    
                    <div class="relative group h-full flex items-center">
                        <button class="flex items-center hover:text-[#ffc800] transition focus:outline-none py-6">
                            Lainnya <i class="fas fa-caret-down ml-1 text-xs transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="dropdown-menu absolute right-0 top-16 w-56 bg-white rounded-lg shadow-2xl py-2 border-t-4 border-brand text-gray-800 z-50">
                            <a href="#team" class="block px-4 py-3 hover:bg-gray-50 hover:text-brand transition border-b border-gray-100">Testimoni</a>
                            <a href="https://wa.me/6283142064406" class="block px-4 py-3 hover:bg-gray-50 hover:text-brand transition">Kontak</a>
                            <a href="https://forms.gle/QKz23j2vDqR7748w6" class="block px-4 py-3 hover:bg-gray-50 hover:text-brand transition border-t border-gray-100">Formulir Pendaftaran</a>
                        </div>
                    </div>
    
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-[#ffc800] text-gray-900 font-bold rounded-full hover:bg-white hover:text-[#ffc800] transition shadow-lg transform hover:-translate-y-1 hover:shadow-brand/50">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-[#ffc800] text-gray-900 font-bold rounded-full hover:bg-white hover:text-[#ffc800] transition shadow-lg transform hover:-translate-y-1 hover:shadow-brand/50">
                            Login
                        </a>
                    @endauth
                </div>
    
                <div class="lg:hidden flex items-center">
                    <button id="mobile-menu-btn" class="hamburger focus:outline-none p-2">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>
        </div>
    
        <div id="mobile-menu" class="lg:hidden bg-gray-900 border-t border-gray-800">
            <ul class="px-6 py-6 space-y-4 font-bold text-white uppercase text-sm font-heading">
                 <li><a href="{{ route('home') }}" class="block hover:text-brand">Beranda</a></li>
                <li><a href="{{ route('detail.fasilitas') }}" class="block hover:text-brand">Fasilitas</a></li>
                <li><a href="{{ route('program') }}" class="block hover:text-brand">Program</a></li>
                <li><a href="{{ route('about.about') }}" class="block hover:text-brand">Tentang</a></li>
                <li><a href="https://wa.me/6283142064406" target="_blank" class="block hover:text-brand">Kontak</a></li>
                 @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-[#ffc800] text-gray-900 font-bold rounded-full hover:bg-white hover:text-[#ffc800] transition shadow-lg transform hover:-translate-y-1 hover:shadow-brand/50">
                            Dashboard
                        </a>
                @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-[#ffc800] text-gray-900 font-bold rounded-full hover:bg-white hover:text-[#ffc800] transition shadow-lg transform hover:-translate-y-1 hover:shadow-brand/50">
                            Login
                        </a>
                @endauth
            </ul>
        </div>
    </nav>

    <header class="relative bg-gray-900 pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden min-h-screen flex items-center">
        <div class="absolute inset-0 z-0 opacity-20">
             <img src="/landing-page/assets/img/img8.png" alt="" class="w-full h-full object-cover grayscale scale-105 animate-pulse-slow" />
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col-reverse lg:flex-row items-center gap-12">
                
                <div class="w-full lg:w-1/2 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-block px-4 py-1 mb-6 border border-brand/50 rounded-full bg-brand/10 backdrop-blur-sm">
                        <span class="text-brand font-bold uppercase text-xs tracking-widest">Sarana Untuk Berprestasi</span>
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-white font-heading leading-tight mb-6">
                        Selamat Datang <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#ffc800] via-yellow-300 to-[#ffc800] animate-gradient">ONMAI</span>
                    </h1>
                    <p class="text-gray-300 text-lg lg:text-xl leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0 font-light">
                        Pusat bimbingan belajar terlengkap. Kami menawarkan kurikulum terbaik untuk SD, SMP, hingga persiapan masuk PTN dengan pengajar profesional.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('program')}}" class="px-8 py-4 bg-[#ffc800] text-black font-bold rounded-lg shadow-[0_0_20px_rgba(255,200,0,0.4)] hover:shadow-[0_0_30px_rgba(255,200,0,0.6)] hover:bg-white transition duration-300 transform hover:-translate-y-1">
                            Program Belajar
                        </a>
                        <a href="{{ route('about.about') }}" class="px-8 py-4 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-black transition duration-300 transform hover:-translate-y-1">
                            Tentang Kami
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-1/2" data-aos="fade-left" data-aos-duration="1200">
                    <div class="relative">

                        <!-- Blob background -->
                        <div class="absolute -top-12 -right-12 w-64 h-64 bg-[#ffc800] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                        <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>

                        <!-- Stacked images -->
                        <div class="relative z-10 flex flex-col gap-8">

                            <!-- Top image -->
                            <img src="{{ asset('landing-page/assets/img/img9.jpeg') }}"
                                alt="Siswa Belajar Atas"
                                class="rounded-2xl shadow-2xl border-4 border-gray-800 w-full object-cover
                                        aspect-[21/9]
                                        transform rotate-2
                                        hover:rotate-0 hover:scale-105
                                        transition duration-500">

                            <!-- Bottom image -->
                            <img src="{{ asset('landing-page/assets/img/img10.jpeg') }}"
                                alt="Siswa Belajar Bawah"
                                class="rounded-2xl shadow-2xl border-4 border-gray-800 w-full object-cover
                                        aspect-[21/9]
                                        transform -rotate-2 -translate-x-6
                                        hover:rotate-0 hover:translate-x-0 hover:scale-105
                                        transition duration-500">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <section class="py-24 bg-white" id="why-us">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 font-heading mb-4">Mengapa Memilih Kami?</h2>
                <div class="w-24 h-1.5 bg-[#ffc800] mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-ref p-8 flex flex-col items-start h-full bg-white group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#ffc800] transition-colors duration-300 shadow-sm group-hover:shadow-lg group-hover:rotate-3">
                        <i class="fas fa-couch text-3xl text-gray-700 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-heading group-hover:text-[#ffc800] transition-colors">Fasilitas yang nyaman</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        Full AC, Free WiFi kecepatan tinggi, dan Full Furnished. Suasana belajar diset senyaman mungkin agar siswa betah.
                    </p>
                    <a href="{{ route('detail.fasilitas') }}" class="mt-auto text-[#ffc800] font-bold text-sm hover:underline flex items-center gap-1 group/link">
                        Lihat Detail <i class="fas fa-arrow-right transition-transform group-hover/link:translate-x-1"></i>
                    </a>
                </div>

                <div class="card-ref p-8 flex flex-col items-start h-full bg-white group border-t-4 border-t-[#ffc800]" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#ffc800] transition-colors duration-300 shadow-sm group-hover:shadow-lg group-hover:rotate-3">
                        <i class="fas fa-book-reader text-3xl text-gray-700 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-heading group-hover:text-[#ffc800] transition-colors">Instruktur Berkualitas</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        Didukung oleh pengajar profesional dan berpengalaman dalam mendidik siswa, serta materi ajar yang komprehensif.
                    </p>
                    <a href="{{ route('detail.pengajar') }}" class="mt-auto text-[#ffc800] font-bold text-sm hover:underline flex items-center gap-1 group/link">
                        Lihat Detail <i class="fas fa-arrow-right transition-transform group-hover/link:translate-x-1"></i>
                    </a>
                </div>

                <div class="card-ref p-8 flex flex-col items-start h-full bg-white group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#ffc800] transition-colors duration-300 shadow-sm group-hover:shadow-lg group-hover:rotate-3">
                        <i class="fas fa-laugh-beam text-3xl text-gray-700 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-heading group-hover:text-[#ffc800] transition-colors">Pembelajaran Seru</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        Menerapkan metode interaktif dalam proses kegiatan belajar mengajar, sehingga materi mudah diterima anak.
                    </p>
                    <a href="{{ route('detail.pembelajaran') }}" class="mt-auto text-[#ffc800] font-bold text-sm hover:underline flex items-center gap-1 group/link">
                        Lihat Detail <i class="fas fa-arrow-right transition-transform group-hover/link:translate-x-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-24 bg-gray-900 text-white overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none z-10">
            <svg class="relative block w-full h-12 md:h-20" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path>
            </svg>
        </div>

        <div class="container mx-auto px-4 relative z-10 py-12">
            <div class="text-center max-w-4xl mx-auto mb-16" data-aos="fade-down">
                <h2 class="text-3xl md:text-4xl font-extrabold font-heading mb-6 tracking-tight">Pengajar & Bahan Ajar Berkualitas</h2>
                <p class="text-gray-400 text-base md:text-lg leading-relaxed">
                    ONMAI didukung oleh pengajar profesional dari universitas ternama yang memiliki pengalaman dalam mendidik siswa sejak 2013, serta materi ajar komprehensif yang disesuaikan dengan kurikulum terbaru.
                </p>
            </div>

            <div id="stats-section" class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-gray-800" data-aos="zoom-in" data-aos-duration="1000">
                <div class="p-6 flex flex-col items-center group">
                    <span class="block text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-400 font-heading mb-2 group-hover:scale-110 transition-transform">
                        <span class="counter" data-target="100">0</span>+
                    </span>
                    <span class="text-xs md:text-sm font-bold tracking-widest text-[#ffc800] uppercase mt-auto">Siswa</span>
                </div>
                
                <div class="p-6 flex flex-col items-center group">
                    <span class="block text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-400 font-heading mb-2 group-hover:scale-110 transition-transform">
                        <span class="counter" data-target="10">0</span>+
                    </span>
                    <span class="text-xs md:text-sm font-bold tracking-widest text-[#ffc800] uppercase mt-auto">Pengajar</span>
                </div>
            
                <div class="p-6 flex flex-col items-center group">
                    <span class="block text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-400 font-heading mb-2 group-hover:scale-110 transition-transform">
                        <span class="counter" data-target="100">0</span>+
                    </span>
                    <span class="text-xs md:text-sm font-bold tracking-widest text-[#ffc800] uppercase mt-auto">Rating Bintang 5</span>
                </div>
            
                <div class="p-6 flex flex-col items-center group">
                    <span class="block text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-400 font-heading mb-2 group-hover:scale-110 transition-transform">
                        <span class="counter" data-target="4">0</span>
                    </span>
                    <span class="text-xs md:text-sm font-bold tracking-widest text-[#ffc800] uppercase mt-auto">Program Khusus</span>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none rotate-180 z-10">
            <svg class="relative block w-full h-12 md:h-20" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path>
            </svg>
        </div>
    </section>

    <!--section class="py-24 bg-white" id="services">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <h2 class="text-4xl font-extrabold uppercase mb-4 text-gray-800 font-heading">Fasilitas <span class="text-brand">Premium</span></h2>
                <p class="text-lg text-gray-500 font-light leading-relaxed">
                    Di ONMAI, kami menciptakan suasana bimbel yang <span class="text-brand font-bold">cozy</span> dan nyaman, agar belajar tidak lagi membosankan.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-brand/30">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-brand/10 rounded-bl-full rounded-tr-2xl transition-all duration-300 group-hover:bg-brand/20"></div>
                    
                    <div class="relative z-10 flex justify-center mb-6">
                        <div class="w-56 h-56 rounded-xl bg-gray-50 flex items-center justify-center border-4 border-white shadow-md group-hover:scale-105 transition duration-300 overflow-hidden">
                             <img src="" class="w-full h-full object-cover" alt="Ruang Belajar">
                        </div>
                    </div>
                    <div class="text-center relative z-10">
                        <h4 class="text-2xl font-bold mb-3 font-heading text-gray-800 group-hover:text-brand transition">Ruang Belajar</h4>
                        <p class="text-gray-600 leading-relaxed">Full AC, Free WiFi kecepatan tinggi, dan Full Furnished untuk kenyamanan maksimal.</p>
                    </div>
                </div>
    
                <div class="group relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-brand/30"> 
                    <div class="absolute top-0 right-0 w-24 h-24 bg-brand/10 rounded-bl-full rounded-tr-2xl transition-all duration-300 group-hover:bg-brand/20"></div>
                    
                    <div class="relative z-10 flex justify-center mb-6">
                        <div class="w-56 h-56 rounded-xl bg-gray-50 flex items-center justify-center border-4 border-white shadow-md group-hover:scale-105 transition duration-300 overflow-hidden">
                             <img src="" class="w-full h-full object-cover" alt="Persiapan Ujian">
                        </div>
                    </div>
                    <div class="text-center relative z-10">
                        <h4 class="text-2xl font-bold mb-3 font-heading text-gray-800 group-hover:text-brand transition">Persiapan Ujian</h4>
                        <p class="text-gray-600 leading-relaxed">Program intensif TKA, UTBK, UTS, UAS, hingga Ulangan Harian.</p>
                    </div>
                </div>
    
                <div class="group relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-brand/30">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-brand/10 rounded-bl-full rounded-tr-2xl transition-all duration-300 group-hover:bg-brand/20"></div>
                    
                    <div class="relative z-10 flex justify-center mb-6">
                        <div class="w-56 h-56 rounded-xl bg-gray-50 flex items-center justify-center border-4 border-white shadow-md group-hover:scale-105 transition duration-300 overflow-hidden">
                             <img src="" class="w-full h-full object-cover" alt="Ruang Depan">
                        </div>
                    </div>
                    <div class="text-center relative z-10">
                        <h4 class="text-2xl font-bold mb-3 font-heading text-gray-800 group-hover:text-brand transition">Ruang Depan</h4>
                        <p class="text-gray-600 leading-relaxed">Lobby tunggu yang nyaman dengan sofa empuk dan area kantin mini.</p>
                    </div>
                </div>
            </div>
        </div>
    </section--->

    <!--section class="py-24 bg-gray-50 relative" id="portfolio">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-5">
             <i class="fas fa-book absolute -top-10 -left-10 text-9xl"></i>
             <i class="fas fa-graduation-cap absolute bottom-10 right-10 text-9xl"></i>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16" data-aos="fade-down">
                <h2 class="text-4xl font-extrabold uppercase mb-4 text-gray-800 font-heading">Program Belajar</h2>
                <h3 class="text-lg text-gray-500 font-serif italic">Pilihan paket bimbingan berkualitas sesuai jenjang pendidikan</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col group border-t-4 border-brand" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative h-56 bg-gray-200 overflow-hidden h-full">
                        <img class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700" src="{{ asset ('landing-page/assets/img/portfolio/1.png') }}" alt="SD" />
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
                        <div class="absolute top-0 right-0 bg-brand text-white text-sm font-bold px-4 py-2 rounded-bl-xl shadow-md z-10">
                            KELAS 1-6
                        </div>
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <h4 class="text-2xl font-bold mb-4 font-heading text-center text-gray-800 group-hover:text-brand transition">Sekolah Dasar (SD)</h4>
                        <div class="space-y-3 text-sm text-gray-600 flex-grow">
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> Matematika & IPA</div>
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> B. Inggris & B. Indonesia</div>
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> PR Tuntas</div>
                            <div class="flex items-center font-bold text-gray-800 bg-brand/10 p-2 rounded mt-2"><i class="fas fa-gift text-brand mr-3"></i> Tambahan Belajar Gratis</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col group border-t-4 border-brand" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative h-56 bg-gray-200 overflow-hidden h-full">
                        <img class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700" src="{{ asset ('landing-page/assets/img/portfolio/2.png') }}" alt="SMP" />
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
                        <div class="absolute top-0 right-0 bg-brand text-white text-sm font-bold px-4 py-2 rounded-bl-xl shadow-md z-10">
                            KELAS 7-9
                        </div>
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <h4 class="text-2xl font-bold mb-4 font-heading text-center text-gray-800 group-hover:text-brand transition">Sekolah Menengah (SMP)</h4>
                        <div class="space-y-3 text-sm text-gray-600 flex-grow">
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> MTK, Fisika, Biologi</div>
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> B. Inggris, B. Indo, IPS</div>
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> PR Tuntas & Tambahan Gratis</div>
                            <div class="flex items-center font-bold text-gray-800 bg-brand/10 p-2 rounded mt-2"><i class="fas fa-star text-brand mr-3"></i> Kelas 9: Persiapan TKA</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col group border-t-4 border-brand" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative h-56 bg-gray-200 overflow-hidden h-full">
                        <img class="w-full h-full object-cover object-top transform group-hover:scale-105 transition duration-700 ease-in-out" src="{{ asset ('landing-page/assets/img/portfolio/3.png') }}" alt="SMA" />
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
                        <div class="absolute top-0 right-0 bg-brand text-white text-sm font-bold px-4 py-2 rounded-bl-xl shadow-md z-10">
                            KELAS 10-11
                        </div>
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <h4 class="text-2xl font-bold mb-4 font-heading text-center text-gray-800 group-hover:text-brand transition">SMA Reguler</h4>
                        <div class="space-y-3 text-sm text-gray-600 flex-grow">
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> MTK, Fisika, Bio, Ekonomi</div>
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> B. Inggris, B. Indo, IPS</div>
                            <div class="flex items-center"><i class="fas fa-check-circle text-brand mr-3"></i> PR Tuntas & Tambahan Gratis</div>
                            <div class="flex items-center font-bold text-gray-800 bg-brand/10 p-2 rounded mt-2"><i class="fas fa-comments text-brand mr-3"></i> Konsultasi Orang Tua</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col group border-t-4 border-brand lg:col-span-3 lg:w-2/3 lg:mx-auto" data-aos="zoom-in-up" data-aos-delay="400">
                    <div class="flex flex-col md:flex-row h-full relative">
                        <div class="md:w-1/2 relative h-64 md:h-auto bg-gray-200 overflow-hidden">
                             <img class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700" src="{{ asset ('landing-page/assets/img/portfolio/4.png') }}" alt="SMA 12" />
                             <div class="absolute inset-0 bg-gradient-to-r from-black/0 to-black/10"></div>
                        </div>
                        <div class="p-8 md:w-1/2 flex flex-col justify-center relative"> 
                            <div class="absolute top-0 left-0 bg-brand text-white text-sm font-bold px-4 py-2 rounded-br-xl shadow-md">
                                KELAS 12 PEJUANG PTN
                            </div>
                        
                            <h4 class="text-2xl font-bold mb-2 font-heading text-gray-800 mt-6 md:mt-0 group-hover:text-brand transition">Program Akhir SMA & Alumni</h4>
                            <p class="text-gray-500 mb-6 text-sm">Fokus total menembus PTN impian.</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600 mb-6">
                                <div>
                                    <h6 class="font-bold text-brand mb-1">Mata Pelajaran:</h6>
                                    <ul class="space-y-1">
                                        <li><i class="fas fa-check text-xs mr-1 text-gray-400"></i> MTK, Fisika, Biologi, Kimia</li>
                                        <li><i class="fas fa-check text-xs mr-1 text-gray-400"></i> Ekonomi, B.Inggris, B.Indo</li>
                                    </ul>
                                </div>
                                <div>
                                    <h6 class="font-bold text-brand mb-1">Persiapan UTBK:</h6>
                                    <ul class="space-y-1">
                                        <li><i class="fas fa-check text-xs mr-1 text-gray-400"></i> TPS & Literasi</li>
                                        <li><i class="fas fa-check text-xs mr-1 text-gray-400"></i> Penalaran Matematika</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 border-l-4 border-l-brand">
                                <span class="font-bold text-gray-800 text-sm block mb-2">Program Khusus Tersedia:</span>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-white rounded-full text-xs font-bold border shadow-sm text-gray-600">KEDINASAN</span>
                                    <span class="px-3 py-1 bg-white rounded-full text-xs font-bold border shadow-sm text-gray-600">KEDOKTERAN (Intensif)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section--->

    <section class="py-24 bg-white" id="team">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 font-heading mb-4">Testimoni Siswa</h2>
                <p class="text-gray-500">Apa kata mereka yang sudah sukses bersama ONMAI</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="border border-gray-100 bg-white rounded-3xl p-8 relative hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute -top-4 -right-4 w-12 h-12 bg-[#ffc800] rounded-full flex items-center justify-center text-white text-xl shadow-lg rotate-12 group-hover:rotate-0 transition">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <p class="text-gray-600 mb-8 italic leading-relaxed">"Belajar di ONMAI seru banget, gurunya asik dan cara ngajarnya mudah dimengerti. Alhamdulillah saya dapat juara disekolah."</p>
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                        <img class="w-14 h-14 rounded-full object-cover border-2 border-[#ffc800] p-1" src="{{ asset('landing-page/assets/img/about/6.jpeg') }}" alt="Rehan">
                        <div>
                            <h5 class="font-bold text-gray-900 text-base">DAFA</h5>
                            <p class="text-xs font-bold text-[#ffc800] uppercase tracking-wide">SMA N 05 Kota Bengkulu</p>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-100 bg-white rounded-3xl p-8 relative hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute -top-4 -right-4 w-12 h-12 bg-[#ffc800] rounded-full flex items-center justify-center text-white text-xl shadow-lg rotate-12 group-hover:rotate-0 transition">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <p class="text-gray-600 mb-8 italic leading-relaxed">"Suasana belajarnya nyaman, fasilitas lengkap. Sangat membantu persiapan saya dalam ujian semester."</p>
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                        <img class="w-14 h-14 rounded-full object-cover border-2 border-[#ffc800] p-1" src="{{ asset('landing-page/assets/img/about/9.jpeg') }}" alt="Mayang">
                        <div>
                            <h5 class="font-bold text-gray-900 text-base">NADHILA</h5>
                            <p class="text-xs font-bold text-[#ffc800] uppercase tracking-wide">SMA N 05 Kota Bengkulu</p>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-100 bg-white rounded-3xl p-8 relative hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute -top-4 -right-4 w-12 h-12 bg-[#ffc800] rounded-full flex items-center justify-center text-white text-xl shadow-lg rotate-12 group-hover:rotate-0 transition">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <p class="text-gray-600 mb-8 italic leading-relaxed">"Recommended banget buat yang mau ngejar PTN. Tutornya care dan selalu siap bantu konsultasi."</p>
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                        <img class="w-14 h-14 rounded-full object-cover border-2 border-[#ffc800] p-1" src="{{ asset('landing-page/assets/img/about/7.jpeg') }}" alt="Masyah">
                        <div>
                            <h5 class="font-bold text-gray-900 text-base">KAYLA</h5>
                            <p class="text-xs font-bold text-[#ffc800] uppercase tracking-wide">SMA N 05 Kota Bengkulu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-50 border-t border-gray-200 pt-20 pb-10 text-sm text-gray-600">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="space-y-6">
                    <div class="flex items-center gap-2 text-3xl font-bold text-gray-900 font-heading">
                        <img src="{{ asset('landing-page/assets/img/img1.png') }}" class="h-10 w-auto" alt="Logo" />
                        <span>ON<span class="text-brand">MAI</span></span>
                    </div>
                    <p class="text-gray-500 leading-relaxed text-base">
                        Partner terbaikmu meraih prestasi akademik dan menembus PTN impian.
                    </p>
                    <div class="flex space-x-4 pt-2">
                        <a href="#" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#1877F2] hover:border-[#1877F2] transition duration-300"><i class="fab fa-facebook-f text-lg"></i></a>
                        <a href="https://instagram.com/bimbel_onmai" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#E4405F] hover:border-[#E4405F] transition duration-300"><i class="fab fa-instagram text-lg"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#1DA1F2] hover:border-[#1DA1F2] transition duration-300"><i class="fab fa-twitter text-lg"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-6 text-lg">Program Kami</h4>
                    <ul class="space-y-4 text-base">
                        <li><a  class="hover:text-[#ffc800] hover:translate-x-1 inline-block transition">Sekolah Dasar (SD)</a></li>
                        <li><a  class="hover:text-[#ffc800] hover:translate-x-1 inline-block transition">Sekolah Menengah (SMP)</a></li>
                        <li><a  class="hover:text-[#ffc800] hover:translate-x-1 inline-block transition">SMA Reguler</a></li>
                        <li><a  class="hover:text-[#ffc800] hover:translate-x-1 inline-block transition">Persiapan UTBK & PTN</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-6 text-lg">Jelajahi</h4>
                    <ul class="space-y-4 text-base">
                        <li><a href="{{ route('about.about') }}" class="hover:text-[#ffc800] hover:translate-x-1 inline-block transition">Tentang Kami</a></li>
                        <li><a href="{{ route('detail.fasilitas') }}" class="hover:text-[#ffc800] hover:translate-x-1 inline-block transition">Fasilitas</a></li>
                        <li><a  class="hover:text-[#ffc800] hover:translate-x-1 inline-block transition">Testimoni</a></li>
                        <li><a href="https://forms.gle/QKz23j2vDqR7748w6" class="hover:text-[#ffc800] hover:translate-x-1 inline-block transition" target="_blank">Formulir Pendaftaran</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-6 text-lg">Hubungi Kami</h4>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-[#ffc800] mt-1"></i>
                            <span class="text-base">Jl. Mayjend Sutoyo No. 25 Tanah Patah, Kota Bengkulu, 55293</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-[#ffc800]"></i>
                            <span class="text-base font-medium">onmaibimbel@gmail.com</span>
                        </li>
                        <li class="flex items-center gap-3">
                             <i class="fab fa-whatsapp text-[#ffc800] text-lg"></i>
                            <span class="text-base font-medium">083142064406</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm">&copy; 2025 ONMAI Learning Center. All rights reserved.</p>
                <a href="https://wa.me/6283142064406" target="_blank" class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white w-14 h-14 rounded-full shadow-2xl hover:scale-110 transition duration-300 flex items-center justify-center group">
                    <i class="fab fa-whatsapp text-3xl group-hover:rotate-12 transition"></i>
                </a>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50,
            });
    
            const preloader = document.getElementById('preloader');
            window.addEventListener('load', () => {
                setTimeout(() => {
                    preloader.style.opacity = '0';
                    preloader.style.visibility = 'hidden';
                }, 800);
            });
    
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
    
            if (btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('open');
                    btn.classList.toggle('active');
                });
    
                menu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        menu.classList.remove('open');
                        btn.classList.remove('active');
                    });
                });
            }
    
            const counters = document.querySelectorAll('.counter');
            const speed = 200;
    
            const animate = (counter) => {
                const animateText = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;
    
                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        requestAnimationFrame(animateText);
                    } else {
                        counter.innerText = target;
                    }
                }
                animateText();
            }
    
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        animate(counter);
                        observer.unobserve(counter);
                    }
                });
            }, {
                threshold: 0.5
            });
    
            counters.forEach(counter => {
                observer.observe(counter);
            });
        });
    </script>
</body>
</html>