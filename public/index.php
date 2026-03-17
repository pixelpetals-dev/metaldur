<?php include 'includes/header.php'; ?>

<style>
    .reveal-up {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 1s ease-out, transform 1s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }

    .reveal-up.active {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div id="preloader">
    <div class="text-center px-4">
        <img src="assets/images/metaldur-white.png" alt="Metaldur" class="h-18 md:h-20 mx-auto mb-6 object-contain">
        
        <h1 class="text-3xl md:text-5xl font-bold text-white brand-font tracking-wider mb-3 leading-tight">
            LOWEST PRICE IN INDIA.<br>GUARANTEED!
        </h1>
        
        <p class="text-lg md:text-xl text-blue-200 font-light tracking-wide mb-8">
            Without compromise in quality.
        </p>
        
        <div class="w-64 h-[1px] bg-white/20 mx-auto overflow-hidden rounded-full">
            <div class="loader-line h-full w-0 bg-white"></div>
        </div>
    </div>
</div>

<main class="relative z-10">
    
    <section id="home" class="relative min-h-screen flex items-center justify-center pt-24 pb-12 overflow-hidden bg-[#114470]">
        
        <div class="absolute inset-x-0 top-24 bottom-0 z-0">
             <img src="assets/images/metaldur_collage.webp" 
                 class="w-full h-full object-cover object-top opacity-90" 
                 alt="Metaldur Manufacturing">
        </div>

        <div class="relative z-10 w-full flex-grow flex flex-col justify-center items-center px-4 pb-8">
            <div class="text-center w-full max-w-sm md:max-w-4xl mx-auto flex flex-col items-center">
                
                <div class="reveal-up mb-5 md:mb-6 inline-block px-3 py-1 md:px-4 md:py-1 border border-white/20 rounded-full bg-black/20 backdrop-blur-md" style="transition-delay: 500ms;">
                    <span class="text-blue-100 text-[10px] md:text-xs font-bold tracking-[0.2em] uppercase">Powered by Carbiforce</span>
                </div>
                
                <p class="reveal-up text-lg md:text-xl text-white mb-6 md:mb-10 max-w-xs md:max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md" style="transition-delay: 600ms;">
                    Lowest price guaranteed in India without compromise in quality.
                </p>

                <h1 class="text-5xl md:text-8xl font-bold tracking-tighter leading-none brand-font text-white drop-shadow-xl flex flex-col items-center">
                    <span class="reveal-up block" style="transition-delay: 1800ms;">PRECISION.</span>
                    <span class="reveal-up block" style="transition-delay: 2200ms;">POWER.</span>
                    <span class="reveal-up block" style="transition-delay: 2600ms;">PERFORMANCE.</span>
                </h1>
                
            </div>
        </div>
    </section>
    <!---
    <section id="home" class="relative min-h-screen flex flex-col justify-start pt-32 md:pt-40 overflow-hidden bg-[#114470]">
        
        <div class="relative z-10 w-full max-w-5xl mx-auto px-4 text-center">
            
            <h1 class="text-6xl md:text-[7rem] lg:text-[8rem] font-bold tracking-tighter leading-none brand-font text-white drop-shadow-xl uppercase mb-6 reveal-hero">
                PRECISION.<br>POWER.<br>PERFORMANCE.
            </h1>

            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-2xl mx-auto font-light leading-relaxed reveal-hero" style="transition-delay: 100ms;">
                Lowest price guaranteed in India without compromise in quality.
            </p>

            <div class="mb-10 inline-block px-5 py-1.5 border border-blue-300/30 rounded-full bg-[#0a2744] reveal-hero" style="transition-delay: 200ms;">
                <span class="text-blue-200 text-xs md:text-sm font-bold tracking-[0.2em] uppercase">Powered by Carbiforce</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 reveal-hero" style="transition-delay: 300ms;">
                <a href="#products" class="bg-white text-[#114470] px-10 py-4 rounded-full font-bold text-lg hover:bg-blue-50 transition-all shadow-xl hover:shadow-2xl uppercase tracking-wide">
                    Explore Tools
                </a>
                <a href="downloads.php" class="px-10 py-4 rounded-full border-2 border-white text-white font-bold text-lg hover:bg-white/10 transition-all uppercase tracking-wide flex items-center justify-center gap-2">
                    <span>Catalog</span>
                    <i class="ri-download-line"></i>
                </a>
            </div>
        </div>

        <div class="absolute inset-x-0 bottom-0 h-[75vh] z-0">
             <img src="assets/images/metaldur_collage.webp" 
                 class="w-full h-full object-cover object-top opacity-100" 
                 alt="Metaldur Manufacturing">
             
             <div class="absolute top-0 left-0 right-0 h-32 bg-gradient-to-b from-[#114470] to-transparent"></div>
        </div>

    </section>
    -->

    <section id="about" class="py-24 bg-[#114470] border-t border-white/5 relative">
        <div class="absolute inset-0 bg-gradient-to-b from-[#114470] to-[#0d3354] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-5 sticky top-32">
                    <h2 class="text-5xl md:text-6xl font-bold mb-6 leading-none text-white brand-font reveal-scroll">
                        WHY <br><span class="text-blue-300">METALDUR?</span>
                    </h2>
                    <div class="h-1 w-20 bg-white mb-8 shadow-[0_0_15px_rgba(255,255,255,0.5)] reveal-scroll"></div>
                    
                    <div id="tool-canvas" class="relative overflow-hidden rounded-2xl border border-white/10 shadow-2xl aspect-[4/5] group cursor-grab bg-gradient-to-br from-[#1a6096] to-[#0f3d66] reveal-scroll">
                        </div>
                </div>

                <div class="lg:col-span-7 space-y-6 pt-8 lg:pt-0">
                    <div class="reveal-scroll glass-panel p-8 rounded-2xl bg-[#1a6096]/30 border-white/10">
                        <p class="text-lg text-white font-light">
                            Metaldur is powered by <strong class="text-blue-400">Carbiforce Pvt. Ltd.</strong> and has been introduced with a singular vision - to create a brand that is truly price-centric while maintaining the highest standards of quality.
                        </p>
                    </div>
                    <div class="reveal-scroll glass-panel p-8 rounded-2xl bg-[#1a6096]/30 border-white/10">
                        <p class="text-lg text-white font-light">
                            In today's CNC industry, customers demand not only precision and reliability but also cost-efficiency, and Metaldur is the answer that demand.
                        </p>
                    </div>
                    <div class="reveal-scroll glass-panel p-8 rounded-2xl bg-[#1a6096]/30 border-white/10">
                        <p class="text-lg text-white font-light">
                            With the strong foundation of Carbiforce's expertise and commitment to innovation, Metaldur promises to deliver carbide tooling solutions at the most competitive prices in India, supported by the assurance of uncompromised quality.
                        </p>
                    </div>
                    <div class="reveal-scroll glass-panel p-8 rounded-2xl bg-[#1a6096]/30 border-white/10">
                        <p class="text-lg text-white font-light">
                            Metaldur stands as a brand that redefines value, ensuring that customers receive world-class products at the lowest possible rates.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="products" class="py-24 bg-[#e0eaef] relative overflow-hidden border-t border-gray-200">
        
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMCAwaDQwdjQwSDB6IiBmaWxsPSJub25lIi8+PHBhdGggZD0iTTAgNDBoNDBNNDAgMGg0MCIgc3Ryb2tlPSIjMTE0NDcwIiBzdHJva2Utd2lkdGg9IjEiIG9wYWNpdHk9IjAuMDUiLz48L3N2Zz4=')] opacity-40 z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="text-center mb-16 reveal-scroll">
                <span class="text-[#3b82f6] font-bold tracking-[0.2em] uppercase text-sm">Product Range</span>
                <h2 class="text-5xl md:text-6xl font-bold mt-3 text-[#114470] brand-font">ENGINEERED FOR EXCELLENCE</h2>
            </div>

            <div class="flex flex-wrap justify-center gap-8 md:gap-x-8 md:gap-y-16">
                
                <a href="inserts.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block">
                    <div class="h-32 w-32 md:h-40 md:w-40 mx-auto flex items-center justify-center mb-4 rounded-full bg-[#114470] shadow-lg transition-transform duration-500 group-hover:-translate-y-2 group-hover:bg-[#0d3354] group-hover:shadow-2xl">
                        <img src="assets/images/metaldur-01.webp" alt="Inserts" class="h-3/4 w-3/4 object-contain group-hover:scale-110 transition-all">
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-[#114470] group-hover:text-[#3b82f6] transition-colors brand-font">INSERTS</h3>
                </a>

                <a href="tool_holders.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block">
                    <div class="h-32 w-32 md:h-40 md:w-40 mx-auto flex items-center justify-center mb-4 rounded-full bg-[#114470] shadow-lg transition-transform duration-500 group-hover:-translate-y-2 group-hover:bg-[#0d3354] group-hover:shadow-2xl">
                        <img src="assets/images/metaldur-02.webp" alt="Holders" class="h-3/4 w-3/4 object-contain group-hover:scale-110 transition-all">
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-[#114470] group-hover:text-[#3b82f6] transition-colors brand-font">HOLDERS</h3>
                </a>

                <a href="boring_bars.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block">
                    <div class="h-32 w-32 md:h-40 md:w-40 mx-auto flex items-center justify-center mb-4 rounded-full bg-[#114470] shadow-lg transition-transform duration-500 group-hover:-translate-y-2 group-hover:bg-[#0d3354] group-hover:shadow-2xl">
                        <img src="assets/images/metaldur-03.webp" alt="Boring Bars" class="h-3/4 w-3/4 object-contain group-hover:scale-110 transition-all">
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-[#114470] group-hover:text-[#3b82f6] transition-colors brand-font">BORING BARS</h3>
                </a>

                <a href="endmills.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block">
                    <div class="h-32 w-32 md:h-40 md:w-40 mx-auto flex items-center justify-center mb-4 rounded-full bg-[#114470] shadow-lg transition-transform duration-500 group-hover:-translate-y-2 group-hover:bg-[#0d3354] group-hover:shadow-2xl">
                        <img src="assets/images/metaldur-04.webp" alt="Endmills" class="h-3/4 w-3/4 object-contain group-hover:scale-110 transition-all">
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-[#114470] group-hover:text-[#3b82f6] transition-colors brand-font">ENDMILLS</h3>
                </a>

                <a href="indexable_tools.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block">
                    <div class="h-32 w-32 md:h-40 md:w-40 mx-auto flex items-center justify-center mb-4 rounded-full bg-[#114470] shadow-lg transition-transform duration-500 group-hover:-translate-y-2 group-hover:bg-[#0d3354] group-hover:shadow-2xl">
                        <img src="assets/images/metaldur-05.webp" alt="Indexable" class="h-3/4 w-3/4 object-contain group-hover:scale-110 transition-all">
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-[#114470] group-hover:text-[#3b82f6] transition-colors brand-font">INDEXABLE TOOLS</h3>
                </a>

                <a href="drills.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block">
                    <div class="h-32 w-32 md:h-40 md:w-40 mx-auto flex items-center justify-center mb-4 rounded-full bg-[#114470] shadow-lg transition-transform duration-500 group-hover:-translate-y-2 group-hover:bg-[#0d3354] group-hover:shadow-2xl">
                        <img src="assets/images/metaldur-06.webp" alt="Drills" class="h-3/4 w-3/4 object-contain group-hover:scale-110 transition-all">
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-[#114470] group-hover:text-[#3b82f6] transition-colors brand-font">DRILLS</h3>
                </a>

                <a href="collets.php" class="w-[42%] md:w-[21%] group text-center reveal-scroll block">
                    <div class="h-32 w-32 md:h-40 md:w-40 mx-auto flex items-center justify-center mb-4 rounded-full bg-[#114470] shadow-lg transition-transform duration-500 group-hover:-translate-y-2 group-hover:bg-[#0d3354] group-hover:shadow-2xl">
                        <img src="assets/images/metaldur-07.webp" alt="Collets" class="h-3/4 w-3/4 object-contain group-hover:scale-110 transition-all">
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-[#114470] group-hover:text-[#3b82f6] transition-colors brand-font">COLLETS & ACCESSORIES</h3>
                </a>

            </div>
        </div>
    </section>

    <section class="py-32 bg-[#114470] relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-900/10 z-0"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-5xl md:text-7xl font-bold mb-8 text-white brand-font leading-tight reveal-scroll">
                LOWEST PRICE. <br>GUARANTEED.
            </h2>
            <p class="text-blue-100 mb-10 text-xl font-light reveal-scroll">
                Join the revolution in cost-efficient precision machining.
            </p>
            <button onclick="openContactModal()" class="bg-white text-[#114470] px-12 py-5 rounded-full font-bold text-lg hover:bg-blue-50 transition-all hover:scale-105 shadow-[0_0_30px_rgba(255,255,255,0.3)] reveal-scroll uppercase tracking-wide">
                Get a Quote Today
            </button>
        </div>
    </section>

</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const elements = document.querySelectorAll('.reveal-up');
        setTimeout(() => {
            elements.forEach(el => el.classList.add('active'));
        }, 1800);
    });
</script>
<?php include 'includes/footer.php'; ?>