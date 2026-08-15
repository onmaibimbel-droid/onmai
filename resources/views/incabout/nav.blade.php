<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-20">
        <a class="flex items-center gap-3 text-xl font-bold font-heading tracking-wider text-white group" href="#page-top">
            <img src="{{ asset('landing-page/assets/img/img1.png') }}" class="h-10 w-auto transition transform group-hover:rotate-12" alt="Logo" />
            <span>ON<span class="text-brand">MAI</span></span>
        </a>

        <div class="hidden lg:flex items-center space-x-8 text-sm font-semibold tracking-wide text-gray-300 font-heading">
            <a class="hover:text-[#ffc800] transition duration-300 relative group" href="{{ route('home') }}">
                Beranda <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#ffc800] transition-all group-hover:w-full"></span>
            </a>
            <a class="hover:text-[#ffc800] transition duration-300 relative group" href="{{ route('detail.fasilitas') }}">
                Fasilitas <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#ffc800] transition-all group-hover:w-full"></span>
            </a>
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
                    <a href="#team" class="block px-4 py-3 hover:bg-gray-50 hover:text-brand transition border-b border-gray-100">Sukses Bersama</a>
                    <a href="#contact" class="block px-4 py-3 hover:bg-gray-50 hover:text-brand transition">Kontak</a>
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
    </ul>
</div>