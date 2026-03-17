<?php include 'includes/header.php'; ?>

<div id="preloader">
    <div class="text-center">
        <h1 class="text-5xl md:text-6xl font-bold text-white brand-font tracking-widest mb-4">DOWNLOADS</h1>
        <div class="w-64 h-[1px] bg-gray-800 mx-auto overflow-hidden">
            <div class="loader-line h-full w-0"></div>
        </div>
    </div>
</div>

<main class="relative z-10 bg-[#050a10]">

    <section class="relative h-[50vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1581093458791-9f3c3900df4b?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover opacity-20 grayscale" 
                 alt="Technical Resources">
            <div class="absolute inset-0 bg-gradient-to-b from-[#050a10]/80 via-transparent to-[#050a10]"></div>
        </div>

        <div class="relative z-10 text-center max-w-4xl px-4">
            <span class="text-blue-500 font-bold tracking-[0.3em] uppercase text-sm mb-4 block reveal-hero">Technical Resources</span>
            <h1 class="text-6xl md:text-7xl font-bold text-white brand-font mb-6 reveal-hero">
                CATALOGS & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-white">DATA</span>
            </h1>
        </div>
    </section>

    <section class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div class="glass-panel p-8 rounded-2xl reveal-scroll group hover:border-blue-500 transition-all duration-300">
                    <div class="flex items-start justify-between mb-8">
                        <div class="w-16 h-16 bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                            <i class="ri-booklet-line text-3xl text-white"></i>
                        </div>
                        <span class="px-3 py-1 border border-blue-500/30 rounded-full text-blue-400 text-xs font-bold uppercase">Latest</span>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-white brand-font mb-2">Metaldur Main Catalog</h3>
                    <p class="text-gray-400 text-sm mb-8 line-clamp-3">
                        Full product range including inserts, tool holders, boring bars, and end mills. Includes technical specifications and speed charts.
                    </p>
                    
                    <a href="assets/downloads/Metaldur_Main_catalog.pdf" download class="inline-flex items-center gap-2 text-white font-bold hover:text-blue-400 transition-colors">
                        <span>DOWNLOAD PDF</span>
                        <i class="ri-download-line"></i>
                    </a>
                </div>

                <div class="glass-panel p-8 rounded-2xl reveal-scroll group hover:border-blue-500 transition-all duration-300" style="transition-delay: 100ms">
                    <div class="flex items-start justify-between mb-8">
                        <div class="w-16 h-16 bg-gray-800 rounded-xl flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                            <i class="ri-file-list-3-line text-3xl text-white"></i>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-white brand-font mb-2">Grade Selection Guide</h3>
                    <p class="text-gray-400 text-sm mb-8">
                        Quick reference for selecting the right carbide grade for steel, cast iron, and aluminum applications.
                    </p>
                    
                    <span class="inline-flex items-center gap-2 text-gray-600 cursor-not-allowed">
                        <span>COMING SOON</span>
                    </span>
                </div>

            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>