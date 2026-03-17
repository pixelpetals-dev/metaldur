<?php include 'includes/header.php'; ?>

<div id="preloader">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-white brand-font tracking-widest mb-4">METALDUR</h1>
        <div class="w-64 h-[1px] bg-gray-800 mx-auto overflow-hidden">
            <div class="loader-line h-full w-0"></div>
        </div>
    </div>
</div>

<main class="relative z-10">
    
<!-- HERO SECTION WITH 3JS ANIMATION -->

    <!---
    <section id="home" class="relative min-h-[90vh] flex items-center pt-24 overflow-hidden bg-gradient-to-br from-[#050a10] to-[#0a1520]">
        
        <div class="max-w-7xl mx-auto px-4 w-full h-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center h-full">
                
                <div class="order-2 lg:order-1 pb-16 lg:pb-0">
                    <div class="mb-6 inline-block px-4 py-1 border border-blue-500/30 rounded-full bg-blue-500/10 backdrop-blur-sm reveal-hero">
                        <span class="text-blue-400 text-xs font-bold tracking-[0.2em] uppercase">Powered by Carbiforce</span>
                    </div>
                    
                    <h1 class="text-6xl md:text-8xl font-bold tracking-tighter mb-8 leading-none brand-font text-white">
                        <span class="block reveal-hero">PRECISION.</span>
                        <span class="block reveal-hero text-gray-500">POWER.</span>
                        <span class="block reveal-hero text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-white">PERFORMANCE.</span>
                    </h1>

                    <p class="text-xl text-gray-300 mb-10 max-w-lg leading-relaxed reveal-hero font-light border-l-2 border-blue-500 pl-6 glass-panel p-6 rounded-r-xl border-t-0 border-b-0 border-r-0">
                        Lowest price guaranteed in India without compromise in quality.
                    </p>

                    <div class="flex flex-wrap gap-6 reveal-hero">
                        <a href="#products" class="bg-white text-black px-8 py-4 rounded-full font-bold text-lg hover:scale-105 transition-transform shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                            Explore Tools
                        </a>
                        <a href="downloads.php" class="px-8 py-4 rounded-full border border-gray-600 font-bold text-lg hover:bg-white/10 text-white backdrop-blur-sm transition-all">
                            Download Catalog
                        </a>
                    </div>
                </div>

                <div class="order-1 lg:order-2 h-[50vh] lg:h-[80vh] w-full relative flex items-center justify-center cursor-grab" id="tool-canvas">
                    </div>

            </div>
        </div>
    </section> -->

<!-- HERO SECTION WITH BG IMAGE -->

    <section id="home" class="relative min-h-[90vh] flex items-center justify-center pt-20 pb-20 overflow-hidden">
        
        <div class="absolute inset-0 z-0">
            <img src="assets/images/metaldur_collage.webp" 
                 class="w-full h-full object-cover scale-105 opacity-100" 
                 alt="Metaldur Manufacturing">
            
            <div class="absolute inset-0 bg-[#050a10]/80"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#050a10] via-transparent to-[#050a10]/80"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
            
            <div class="mt-10 mb-8 inline-block px-6 py-2 border border-blue-500/30 rounded-full bg-blue-900/20 backdrop-blur-md reveal-hero">
                <span class="text-blue-400 text-xs md:text-sm font-bold tracking-[0.3em] uppercase">Powered by Carbiforce</span>
            </div>
            
            <h1 class="text-6xl md:text-8xl lg:text-9xl font-bold tracking-tighter mb-8 leading-[0.9] brand-font text-white">
                <span class="block reveal-hero">PRECISION.</span>
                <span class="block reveal-hero text-gray-600">POWER.</span>
                <span class="block reveal-hero text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-white">PERFORMANCE.</span>
            </h1>

            <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-2xl mx-auto leading-relaxed reveal-hero font-light border-l-4 border-blue-500 pl-6 glass-panel p-6 rounded-r-2xl border-t-0 border-b-0 border-r-0 text-left md:text-center md:border-l-0 md:border-t-4 md:rounded-b-2xl md:rounded-tr-none md:pt-6">
                Lowest price guaranteed in India without compromise in quality.
            </p>

            <div class="flex flex-col sm:flex-row gap-6 justify-center reveal-hero">
                <a href="#products" class="bg-blue-600 text-white px-10 py-5 rounded-full font-bold text-lg hover:bg-blue-700 hover:scale-105 transition-all shadow-[0_0_30px_rgba(37,99,235,0.4)] uppercase tracking-wide">
                    Explore Tools
                </a>
                <a href="downloads.php" class="px-10 py-5 rounded-full border border-white/20 font-bold text-lg hover:bg-white hover:text-black hover:border-white transition-all backdrop-blur-sm uppercase tracking-wide flex items-center justify-center gap-2">
                    <span>Download Catalog</span>
                    <i class="ri-download-line"></i>
                </a>
            </div>

        </div>
    </section>
    
    
<!-- ABOUT SECTION WITH COLLAGE IMAGE -->
    <!---
    <section id="about" class="py-24 relative bg-black/30 backdrop-blur-sm border-t border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                
                <div class="lg:col-span-5 h-full">
                    <div class="sticky top-32">
                        <h2 class="text-5xl md:text-6xl font-bold mb-6 leading-none text-white brand-font">
                            WHY <br><span class="text-blue-500">METALDUR?</span>
                        </h2>
                        <div class="h-1 w-20 bg-blue-600 mb-8 shadow-[0_0_15px_#2563eb]"></div>
                        
                        <div class="relative overflow-hidden rounded-2xl border border-gray-700/50 shadow-2xl aspect-[4/5] group">
                             <img src="../assets/images/metaldur_collage.webp" 
                                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000 opacity-90" 
                                  alt="Precision Manufacturing">
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-8">
                    
                    <div class="reveal-scroll glass-panel p-8 rounded-2xl">
                        <p class="text-lg text-gray-200 leading-relaxed font-light">
                            Metaldur is powered by <strong class="text-blue-400">Carbiforce Pvt. Ltd.</strong> and has been introduced with a singular vision - to create a brand that is truly price-centric while maintaining the highest standards of quality.
                        </p>
                    </div>

                    <div class="reveal-scroll glass-panel p-8 rounded-2xl">
                        <p class="text-lg text-gray-200 leading-relaxed font-light">
                            In today's CNC industry, customers demand not only precision and reliability but also cost-efficiency, and Metaldur is the answer that demand.
                        </p>
                    </div>

                    <div class="reveal-scroll glass-panel p-8 rounded-2xl">
                        <p class="text-lg text-gray-200 leading-relaxed font-light">
                            With the strong foundation of Carbiforce's expertise and commitment to innovation, Metaldur promises to deliver carbide tooling solutions at the most competitive prices in India, supported by the assurance of uncompromised quality.
                        </p>
                    </div>

                    <div class="reveal-scroll glass-panel p-8 rounded-2xl">
                        <p class="text-lg text-gray-200 leading-relaxed font-light">
                            Metaldur stands as a brand that redefines value, ensuring that customers receive world-class products at the lowest possible rates.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section> --->

<!-- ABOUT SECTION WITH 3JS ANIMATION -->

    <section id="about" class="py-24 relative bg-black/30 backdrop-blur-sm border-t border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                
                <div class="lg:col-span-5 h-full">
                    <div class="sticky top-32">
                        <h2 class="text-5xl md:text-6xl font-bold mb-6 leading-none text-white brand-font">
                            WHY <br><span class="text-blue-500">METALDUR?</span>
                        </h2>
                        <div class="h-1 w-20 bg-blue-600 mb-8 shadow-[0_0_15px_#2563eb]"></div>
                        
                        <div id="tool-canvas" class="relative overflow-hidden rounded-2xl border border-gray-700/50 shadow-2xl aspect-[4/5] group cursor-grab bg-gradient-to-br from-gray-900/40 to-black/60">
                            </div>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-8">
                    
                    <div class="reveal-scroll glass-panel p-8 rounded-2xl">
                        <p class="text-lg text-gray-200 leading-relaxed font-light">
                            Metaldur is powered by <strong class="text-blue-400">Carbiforce Pvt. Ltd.</strong> and has been introduced with a singular vision - to create a brand that is truly price-centric while maintaining the highest standards of quality.
                        </p>
                    </div>

                    <div class="reveal-scroll glass-panel p-8 rounded-2xl">
                        <p class="text-lg text-gray-200 leading-relaxed font-light">
                            In today's CNC industry, customers demand not only precision and reliability but also cost-efficiency, and Metaldur is the answer that demand.
                        </p>
                    </div>

                    <div class="reveal-scroll glass-panel p-8 rounded-2xl">
                        <p class="text-lg text-gray-200 leading-relaxed font-light">
                            With the strong foundation of Carbiforce's expertise and commitment to innovation, Metaldur promises to deliver carbide tooling solutions at the most competitive prices in India, supported by the assurance of uncompromised quality.
                        </p>
                    </div>

                    <div class="reveal-scroll glass-panel p-8 rounded-2xl">
                        <p class="text-lg text-gray-200 leading-relaxed font-light">
                            Metaldur stands as a brand that redefines value, ensuring that customers receive world-class products at the lowest possible rates.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <section id="products" class="py-32 relative bg-[#050a10] overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMCAwaDQwdjQwSDB6IiBmaWxsPSJub25lIi8+PHBhdGggZD0iTTAgNDBoNDBNNDAgMGg0MCIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEiIG9wYWNpdHk9IjAuMDUiLz48L3N2Zz4=')] opacity-30 z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-24 reveal-scroll">
                <span class="text-blue-500 font-bold tracking-[0.2em] uppercase text-sm">Product Range</span>
                <h2 class="text-5xl md:text-6xl font-bold mt-3 text-white brand-font tracking-tight">ENGINEERED FOR EXCELLENCE</h2>
            </div>

            <div class="flex flex-wrap justify-center gap-x-8 gap-y-20">
                
                <a href="inserts.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block">
                    <div class="h-40 flex items-center justify-center mb-6 transition-transform duration-500 group-hover:-translate-y-2">
                        <img src="assets/images/metaldur-01.webp" alt="Carbide Inserts" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    </div>
                    <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors brand-font tracking-wider">INSERTS</h3>
                </a>

                <a href="tool_holders.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block" style="transition-delay: 50ms">
                    <div class="h-40 flex items-center justify-center mb-6 transition-transform duration-500 group-hover:-translate-y-2">
                        <img src="assets/images/metaldur-02.webp" alt="Tool Holders" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    </div>
                    <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors brand-font tracking-wider">TOOL HOLDERS</h3>
                </a>

                <a href="boring_bars.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block" style="transition-delay: 100ms">
                    <div class="h-40 flex items-center justify-center mb-6 transition-transform duration-500 group-hover:-translate-y-2">
                        <img src="assets/images/metaldur-03.webp" alt="Boring Bars" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    </div>
                    <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors brand-font tracking-wider">BORING BARS</h3>
                </a>

                <a href="endmills.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block" style="transition-delay: 150ms">
                    <div class="h-40 flex items-center justify-center mb-6 transition-transform duration-500 group-hover:-translate-y-2">
                        <img src="assets/images/metaldur-04.webp" alt="Solid Carbide Endmills" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    </div>
                    <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors brand-font tracking-wider">ENDMILLS</h3>
                </a>

                <a href="indexable_tools.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block" style="transition-delay: 200ms">
                    <div class="h-40 flex items-center justify-center mb-6 transition-transform duration-500 group-hover:-translate-y-2">
                        <img src="assets/images/metaldur-05.webp" alt="Indexable Milling Tools" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    </div>
                    <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors brand-font tracking-wider">INDEXABLE TOOLS</h3>
                </a>

                <a href="drills.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block" style="transition-delay: 250ms">
                    <div class="h-40 flex items-center justify-center mb-6 transition-transform duration-500 group-hover:-translate-y-2">
                        <img src="assets/images/metaldur-06.webp" alt="CNC Drills" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    </div>
                    <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors brand-font tracking-wider">CNC DRILLS</h3>
                </a>

                <a href="collets.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block" style="transition-delay: 300ms">
                    <div class="h-40 flex items-center justify-center mb-6 transition-transform duration-500 group-hover:-translate-y-2">
                        <img src="assets/images/metaldur-07.webp" alt="Collets & Accessories" class="h-full w-auto object-contain opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    </div>
                    <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors brand-font tracking-wider">COLLETS</h3>
                </a>

            </div>
        </div>
    </section>

    <section class="py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-900/5 z-0"></div>
        
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-5xl md:text-7xl font-bold mb-8 text-white brand-font leading-tight">
                LOWEST PRICE. <br>GUARANTEED.
            </h2>
            <p class="text-gray-400 mb-10 text-xl font-light">
                Join the revolution in cost-efficient precision machining.
            </p>
            
            <button onclick="openContactModal()" class="bg-blue-600 text-white px-12 py-5 rounded-full font-bold text-lg hover:bg-blue-700 transition-all hover:scale-105 shadow-[0_0_30px_rgba(59,130,246,0.5)]">
                Get a Quote Today
            </button>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>