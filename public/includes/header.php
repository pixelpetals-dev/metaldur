<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metaldur | Precision. Power. Performance.</title>
    
    <meta name="description" content="Metaldur - Powered by Carbiforce. Lowest price guaranteed cutting tools in India without compromise in quality.">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style.css?v=<?= filemtime('assets/css/style.css') ?>">
</head>
<body class="antialiased text-white bg-[#114470]">
    
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-24">
                
                <div class="flex-shrink-0 cursor-pointer z-50">
                    <a href="index.php">
                        <img src="assets/images/metaldur-white.png" alt="Metaldur Logo" style="max-width:100px;">
                    </a>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="index.php" class="px-3 py-2 rounded-md text-sm font-bold tracking-wide uppercase text-white hover:opacity-80 transition-opacity">Home</a>
                        
                        <div class="relative group h-24 flex items-center">
                            <button class="px-3 py-2 rounded-md text-sm font-bold tracking-wide uppercase flex items-center gap-1 text-white hover:opacity-80 transition-opacity">
                                Products <i class="ri-arrow-down-s-line"></i>
                            </button>
                            
                            <div class="absolute top-20 left-1/2 transform -translate-x-1/2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 ease-out translate-y-4 group-hover:translate-y-0 pt-2">
                                <div class="rounded-xl overflow-hidden border border-white/10 shadow-2xl p-2 bg-[#114470]">
                                    <div class="flex flex-col space-y-1">
                                        <a href="inserts.php" class="px-4 py-3 rounded-lg hover:bg-white/10 text-white transition-colors text-sm font-medium">Inserts</a>
                                        <a href="tool_holders.php" class="px-4 py-3 rounded-lg hover:bg-white/10 text-white transition-colors text-sm font-medium">Tool Holders</a>
                                        <a href="boring_bars.php" class="px-4 py-3 rounded-lg hover:bg-white/10 text-white transition-colors text-sm font-medium">Boring Bars</a>
                                        <a href="endmills.php" class="px-4 py-3 rounded-lg hover:bg-white/10 text-white transition-colors text-sm font-medium">End Mills</a>
                                        <a href="indexable_tools.php" class="px-4 py-3 rounded-lg hover:bg-white/10 text-white transition-colors text-sm font-medium">Indexable Tools</a>
                                        <a href="drills.php" class="px-4 py-3 rounded-lg hover:bg-white/10 text-white transition-colors text-sm font-medium">CNC Drills</a>
                                        <a href="collets.php" class="px-4 py-3 rounded-lg hover:bg-white/10 text-white transition-colors text-sm font-medium">Collets & Accessories</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="index.php#about" class="px-3 py-2 rounded-md text-sm font-bold tracking-wide uppercase text-white hover:opacity-80 transition-opacity">About</a>
                        <a href="price-list.php" class="px-3 py-2 rounded-md text-sm font-bold tracking-wide uppercase text-white hover:opacity-80 transition-opacity">Price List</a>
                        
                        <a href="contact.php" class="bg-blue-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-blue-700 transition-all shadow-[0_0_15px_rgba(37,99,235,0.5)] text-sm tracking-wide uppercase">
                            Contact Us
                        </a>
                    </div>
                </div>

                <div class="-mr-2 flex md:hidden z-50">
                    <button type="button" onclick="toggleMobileMenu()" class="text-white p-2 focus:outline-none hover:opacity-80">
                        <i class="ri-menu-4-line text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-[#114470] border-b border-white/10 absolute w-full left-0 top-24 shadow-2xl">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="index.php" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-white/5 border-b border-white/10">Home</a>
                
                <div class="px-3 py-3 border-b border-white/10">
                    <span class="text-blue-300 uppercase text-xs font-bold tracking-widest mb-3 block">Product Range</span>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="inserts.php" class="text-sm text-white hover:text-blue-200">Inserts</a>
                        <a href="tool_holders.php" class="text-sm text-white hover:text-blue-200">Tool Holders</a>
                        <a href="boring_bars.php" class="text-sm text-white hover:text-blue-200">Boring Bars</a>
                        <a href="endmills.php" class="text-sm text-white hover:text-blue-200">End Mills</a>
                        <a href="indexable_tools.php" class="text-sm text-white hover:text-blue-200">Indexable Tools</a>
                        <a href="drills.php" class="text-sm text-white hover:text-blue-200">CNC Drills</a>
                        <a href="collets.php" class="text-sm text-white hover:text-blue-200">Collets</a>
                    </div>
                </div>

                <a href="index.php#about" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-white/5 border-b border-white/10">About</a>
                <a href="price-list.php" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-white/5 border-b border-white/10">Price List</a>
                <a href="contact.php" class="block px-3 py-3 rounded-md text-base font-bold text-white hover:bg-white/5">Contact Us</a>
            </div>
        </div>
    </nav>