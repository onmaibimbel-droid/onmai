@extends('about')

@section('content')

    <section class="pb-20 bg-gray-50 relative overflow-hidden" id="portfolio" style="padding-top: 180px;">
        
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-10">
    
            <i class="fas fa-book absolute text-8xl md:text-9xl text-gray-400" 
               style="top: 40px; left: 20px; transform: rotate(-15deg);"></i>
        
            <i class="fas fa-graduation-cap absolute text-8xl md:text-9xl text-gray-400" 
               style="bottom: 40px; right: 20px; transform: rotate(15deg);"></i>
        
        </div>

        <div class="container mx-auto px-4 md:px-12 relative z-10">
            <div class="text-center max-w-4xl mx-auto" data-aos="fade-down">
                <h3 class="text-base md:text-lg text-gray-500 font-serif italic mb-2">TENTANG ONMAI</h3>
                
                <h2 class="text-2xl md:text-4xl font-extrabold uppercase text-gray-800 font-heading leading-tight py-4 md:py-8">
                    Bimbel ONMAI merupakan lembaga pendidikan dalam bentuk bimbingan belajar bersama dan privat
                </h2>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-white" id="about-desc">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8 lg:gap-12" data-aos="fade-up">
                
                <div class="w-full lg:w-5/12">
                    <h2 class="text-2xl md:text-4xl font-extrabold text-gray-900 font-heading leading-tight text-center lg:text-left">
                        Pusat Bimbingan Belajar dan Privat BENGKULU
                    </h2>
                </div>

                <div class="w-full lg:w-6/12">
                   <p class="text-gray-500 text-base md:text-lg text-justify lg:text-left leading-relaxed">
                        Bimbel ONMAI merupakan lembaga pendidikan dalam bentuk bimbingan belajar yang telah
                        hadir di Bengkulu dan telah mendidik ratusan hingga ribuan siswa 
                        sebagai sarana dan fasilitas dalam menunjang akademik mereka. Dengan fokus 
                        pada pengembangan potensi siswa secara mendalam, kami berkomitmen untuk memberikan 
                        fasilitas dan pelayanan akademik yang terbaik melalui penerapan metode pendekatan 
                        pengajaran yang sesuai dengan kebutuhan siswa dan bersesuaian dengan kurikulum sekolah 
                        untuk mendukung mereka dalam mencapai prestasi tertinggi.
                    </p>
                        <!--p class="text-gray-500 text-base md:text-lg text-justify lg:text-left leading-relaxed">
                        Rekam jejak prestasi dedikasi kami dibuktikan melalui pencapaian 
                        siswa-siswi bimbingan kami yang secara konsisten menempati posisi unggul di 
                        tingkat provinsi: <a href="" id="toggleReadMore"
                        class="inline-block mt-2 text-blue-600 hover:text-blue-800 font-medium">
                        Baca selanjutnya
                        </a-->
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-gray-50" id="team">
        <div class="container mx-auto px-4 md:px-8">

            <div class="text-center mb-12 md:mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-extrabold uppercase mb-2 text-gray-800 font-heading">
                    Sukses Bersama ONMAI
                </h2>
                <h3 class="text-base md:text-lg text-gray-500 font-serif italic">
                    Bukti nyata keberhasilan siswa kami
                </h3>
            </div> 

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-10 max-w-6xl mx-auto">
                <div class="bg-white p-6 rounded-2xl shadow-lg text-center group hover:-translate-y-3 hover:shadow-2xl transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="100">
                    <div class="w-32 h-32 md:w-40 md:h-40 mx-auto mb-6 rounded-full border-4 border-gray-100 p-1 group-hover:border-brand group-hover:scale-105 transition-all duration-300">
                        <img class="w-full h-full rounded-full object-cover" src="{{ asset('landing-page/assets/img/team/1.png') }}" alt="Mayang" />
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold text-gray-800 mb-1 font-heading">MAYANG</h4>
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-4">SMAN 05 Kota Bengkulu</p>
                    <div class="bg-brand text-white text-xs font-bold px-4 py-2 rounded-full inline-block shadow-md bg-blue-600">
                        LULUS HUKUM UNIB
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg text-center group hover:-translate-y-3 hover:shadow-2xl transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="200">
                    <div class="w-32 h-32 md:w-40 md:h-40 mx-auto mb-6 rounded-full border-4 border-gray-100 p-1 group-hover:border-brand group-hover:scale-105 transition-all duration-300">
                            <img class="w-full h-full rounded-full object-cover" src="{{ asset('landing-page/assets/img/team/2.png') }}" alt="Masyah" />
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold text-gray-800 mb-1 font-heading">MASYAH</h4>
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-4">SMAN 05 Kota Bengkulu</p>
                    <div class="bg-brand text-white text-xs font-bold px-4 py-2 rounded-full inline-block shadow-md bg-blue-600">
                        LULUS MANAJEMEN UNIB
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg text-center group hover:-translate-y-3 hover:shadow-2xl transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="300">
                    <div class="w-32 h-32 md:w-40 md:h-40 mx-auto mb-6 rounded-full border-4 border-gray-100 p-1 group-hover:border-brand group-hover:scale-105 transition-all duration-300">
                            <img class="w-full h-full rounded-full object-cover" src="{{ asset('landing-page/assets/img/team/4.png') }}" alt="Aisyah" />
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold text-gray-800 mb-1 font-heading">AISYAH</h4>
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-4">SMAN Kehutanan Pekanbaru</p>
                    <div class="bg-brand text-white text-xs font-bold px-4 py-2 rounded-full inline-block shadow-md bg-blue-600">
                        LULUS KEHUTANAN UNIB
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg text-center group hover:-translate-y-3 hover:shadow-2xl transition-all duration-300 lg:col-start-2"
                    data-aos="fade-up" data-aos-delay="400">
                    <div class="w-32 h-32 md:w-40 md:h-40 mx-auto mb-6 rounded-full border-4 border-gray-100 p-1 group-hover:border-brand group-hover:scale-105 transition-all duration-300">
                            <img class="w-full h-full rounded-full object-cover" src="{{ asset('landing-page/assets/img/team/3.png') }}" alt="Rehan" />
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold text-gray-800 mb-1 font-heading">REHAN</h4>
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-4">SMAN 02 Kota Bengkulu</p>
                    <div class="bg-brand text-white text-xs font-bold px-4 py-2 rounded-full inline-block shadow-md bg-blue-600">
                        LULUS KEDOKTERAN UNIB
                    </div>
                </div>
            </div>

            <div class="text-center mt-16 md:mt-20" data-aos="zoom-in">
                <p class="text-xl md:text-3xl font-heading text-gray-700 px-4">
                    Jadilah Bagian dari Generasi 
                    <span class="text-brand text-blue-600 font-extrabold underline decoration-4 underline-offset-4">
                        ONMAI
                    </span> Selanjutnya...
                </p>
            </div>
         </div>
    </section>
@endsection