<div class="flex flex-col h-full bg-gray-900 text-white w-full relative">
    
    <div class="h-20 flex items-center justify-center border-b border-gray-800 relative">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center group-hover:bg-[#ffc800] transition duration-300">
                <img src="{{ asset('landing-page/assets/img/img1.png') }}" alt="Logo" class="h-6 w-auto">
            </div>
            <span class="font-bold text-xl tracking-wider font-heading group-hover:text-[#ffc800] transition duration-300">
                ON<span class="text-[#ffc800] group-hover:text-white transition duration-300">MAI</span>
            </span>
        </a>

        <button onclick="toggleSidebar()" class="absolute right-4 text-gray-400 hover:text-white lg:hidden focus:outline-none transition transform hover:rotate-90">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
        
        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">Menu Utama</div>

        <a href="{{ route('home') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('home') ? 'bg-[#ffc800] text-gray-900 shadow-[0_0_15px_rgba(255,200,0,0.4)] font-bold' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}">
            <i class="fas fa-home text-lg {{ request()->routeIs('home') ? 'text-gray-900' : 'group-hover:text-[#ffc800]' }}"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('dashboard') ? 'bg-[#ffc800] text-gray-900 shadow-[0_0_15px_rgba(255,200,0,0.4)] font-bold' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}">
            <i class="fas fa-chart-pie text-lg {{ request()->routeIs('dashboard') ? 'text-gray-900' : 'group-hover:text-[#ffc800]' }}"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('mandiri.materi') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('mandiri.*') ? 'bg-[#ffc800] text-gray-900 shadow-[0_0_15px_rgba(255,200,0,0.4)] font-bold' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}">
            <i class="fas fa-book-open text-lg {{ request()->routeIs('mandiri.*') ? 'text-gray-900' : 'group-hover:text-[#ffc800]' }}"></i>
            <span>Belajar Mandiri</span>
        </a>

        <div class="border-t border-gray-800 my-4"></div>
        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">Manajemen</div>

        <a href="{{ route('ujian.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('ujian.*') ? 'bg-[#ffc800] text-gray-900 shadow-[0_0_15px_rgba(255,200,0,0.4)] font-bold' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}">
            <i class="fas fa-chalkboard-teacher text-lg {{ request()->routeIs('ujian.*') ? 'text-gray-900' : 'group-hover:text-[#ffc800]' }}"></i>
            <span>Manajemen Ujian</span>
        </a>
        <a href="{{ route('utbk.soal') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('utbk.*') ? 'bg-[#ffc800] text-gray-900 shadow-[0_0_15px_rgba(255,200,0,0.4)] font-bold' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}">
                <i class="fas fa-chalkboard-teacher text-lg {{ request()->routeIs('utbk.*') ? 'text-gray-900' : 'group-hover:text-[#ffc800]' }}"></i>
                <span>Manajemen UTBK</span>
            </a>

        <a href="{{ route('akun-guru') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('akun-guru') ? 'bg-[#ffc800] text-gray-900 shadow-[0_0_15px_rgba(255,200,0,0.4)] font-bold' : 'text-gray-400 hover:bg-gray-800 hover:text-[#ffc800]' }}">
            <i class="fas fa-user-plus text-lg {{ request()->routeIs('akun-guru') ? 'text-gray-900' : 'group-hover:text-[#ffc800]' }}"></i>
            <span>Buat Akun Guru</span>
        </a>

    </div>

    <div class="p-4 border-t border-gray-800">
        @if(Auth::check())
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-red-600/10 text-red-500 hover:bg-red-600 hover:text-white transition-all duration-300 font-bold group">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i>
                <span>Sign Out</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        @endif
    </div>
</div>