<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ONMAI</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="icon" type="image/png" href="{{ asset('landing-page/assets/img/img1.png') }}" />

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700,800" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />

    

    <style>
        body { font-family: 'Roboto Slab', serif; }
        .font-heading { font-family: 'Montserrat', sans-serif; }
        
        /* Animasi Kilatan Tombol */
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }

        /* Animasi Masuk (Fade In Up) untuk Kartu */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        /* Animasi Background Blobs (Bergerak Halus) */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        .animate-blob {
            animation: blob 7s infinite;
        }
        
        /* Delay animasi untuk blob kedua agar gerakan tidak barengan */
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen relative selection:bg-[#ffc800] selection:text-black">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 left-0 w-96 h-96 bg-[#ffc800]/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2 animate-blob animation-delay-2000"></div>
    </div>

    <div class="relative w-full max-w-md p-4 py-10 z-10 animate-fade-in-up">
        
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-t-4 border-[#ffc800]">
            
            <div class="p-8 pb-4 text-center">
                <div class="inline-flex items-center gap-2 mb-6 group select-none hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('landing-page/assets/img/img1.png') }}" class="h-12 w-auto" alt="Logo" />
                    <span class="text-2xl font-extrabold font-heading tracking-wide text-gray-800">ON<span class="text-[#ffc800]">MAI</span></span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 font-heading">Selamat Datang Kembali!</h3>
                <p class="text-sm text-gray-500 mt-2">Silakan login untuk mengakses akun Anda.</p>
            </div>

            <div class="p-8 pt-0">
                
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg text-sm flex items-center shadow-sm animate-pulse" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('loginProses') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1 ml-1">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-[#ffc800]">
                                <i class="fas fa-envelope text-gray-400 group-focus-within:text-[#ffc800]"></i>
                            </div>
                            <input type="email" id="email" name="email" required autofocus placeholder="nama@email.com" 
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-[#ffc800] focus:ring-2 focus:ring-[#ffc800]/20 transition duration-200 outline-none placeholder-gray-400 text-gray-800 bg-gray-50 focus:bg-white" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1 ml-1">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 group-focus-within:text-[#ffc800]"></i>
                            </div>
                            <input type="password" id="password" name="password" required placeholder="••••••••" 
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-[#ffc800] focus:ring-2 focus:ring-[#ffc800]/20 transition duration-200 outline-none placeholder-gray-400 text-gray-800 bg-gray-50 focus:bg-white" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-[#ffc800] focus:ring-[#ffc800] border-gray-300 rounded cursor-pointer transition">
                            <label for="remember" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none hover:text-gray-900 transition">Remember me</label>
                        </div>
                    </div>

                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-white bg-[#ffc800] hover:bg-yellow-500 font-bold shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-[#ffc800]/40 overflow-hidden">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-[150%] skew-x-12 transition-transform duration-1000 group-hover:translate-x-[150%] ease-in-out"></div>
                        <span class="relative flex items-center gap-2 tracking-widest text-sm">
                            LOGIN <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-600 space-y-4">
                    <div>
                        
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100">
                        <a href="{{ url('/') }}" class="inline-flex items-center text-gray-400 hover:text-[#ffc800] transition duration-300 text-xs font-bold uppercase tracking-wide gap-2 group">
                            <i class="fas fa-arrow-left transition-transform duration-300 group-hover:-translate-x-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-8 text-gray-500 text-xs opacity-60">
            &copy; 2025 ONMAI. All rights reserved.
        </div>
    </div>
</body>
</html>