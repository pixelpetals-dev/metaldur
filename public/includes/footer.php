<footer class="bg-[#020406] pt-24 pb-12 border-t border-gray-800/50 relative z-10 overflow-hidden">
        
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-900/10 rounded-full blur-[128px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                
                <div class="space-y-6">
                    <a href="index.php" class="block">
                        <img src="../assets/images/metaldur-white.png" style="max-width:150px;">
                    </a>
                    <p class="text-gray-500 font-light text-sm leading-relaxed">
                        Powered by Carbiforce Pvt. Ltd.<br>
                        Redefining value in the CNC industry with precision tools engineered for performance and price.
                    </p>
                    
                    <div class="flex items-center gap-4">
                        <a href="https://www.linkedin.com/company/carbiforce" class="w-10 h-10 rounded-full bg-gray-800/50 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all">
                            <i class="ri-linkedin-fill"></i>
                        </a>
                        <a href="https://www.instagram.com/metaldur_solutions/" class="w-10 h-10 rounded-full bg-gray-800/50 flex items-center justify-center text-gray-400 hover:bg-pink-600 hover:text-white transition-all">
                            <i class="ri-instagram-line"></i>
                        </a>
                        <a href="https://www.facebook.com/carbiforce/" class="w-10 h-10 rounded-full bg-gray-800/50 flex items-center justify-center text-gray-400 hover:bg-blue-500 hover:text-white transition-all">
                            <i class="ri-facebook-fill"></i>
                        </a>
                        <a href="https://www.youtube.com/@Carbiforce/videos" class="w-10 h-10 rounded-full bg-gray-800/50 flex items-center justify-center text-gray-400 hover:bg-red-600 hover:text-white transition-all">
                            <i class="ri-youtube-fill"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Our Products</h4>
                    <ul class="space-y-3 text-gray-400 font-light text-sm">
                        <li><a href="inserts.php" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ri-arrow-right-s-line text-blue-900"></i> Carbide Inserts</a></li>
                        <li><a href="tool_holders.php" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ri-arrow-right-s-line text-blue-900"></i> Turning Holders</a></li>
                        <li><a href="boring_bars.php" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ri-arrow-right-s-line text-blue-900"></i> Boring Bars</a></li>
                        <li><a href="endmills.php" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ri-arrow-right-s-line text-blue-900"></i> End Mills</a></li>
                        <li><a href="indexable_tools.php" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ri-arrow-right-s-line text-blue-900"></i> Indexable Milling</a></li>
                        <li><a href="drills.php" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ri-arrow-right-s-line text-blue-900"></i> CNC Drills</a></li>
                        <li><a href="collets.php" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ri-arrow-right-s-line text-blue-900"></i> Collets &amp; Accessories</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Support</h4>
                    <ul class="space-y-3 text-gray-400 font-light text-sm">
                        <li><a href="index.php#about" class="hover:text-blue-400 transition-colors">About Us</a></li>
                        <li><a href="contact.php" class="hover:text-blue-400 transition-colors">Contact Support</a></li>
                        <li><a href="downloads.php" class="hover:text-blue-400 transition-colors">Catalog Downloads</a></li>
                        <li><a href="privacy.php" class="hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="terms.php" class="hover:text-blue-400 transition-colors">Terms & Conditions</a></li>
                    </ul>
                    
                    <div class="mt-6 pt-6 border-t border-gray-800/50">
                        <a href="mailto:sales@carbiforce.com" class="flex items-center gap-3 text-gray-400 hover:text-white transition-colors group">
                            <i class="ri-mail-send-line text-blue-500 group-hover:scale-110 transition-transform"></i>
                            <span>sales@carbiforce.com</span>
                        </a>
                        <a href="tel:+917021583452" class="flex items-center gap-3 text-gray-400 hover:text-white transition-colors mt-3 group">
                            <i class="ri-phone-line text-blue-500 group-hover:scale-110 transition-transform"></i>
                            <span>+91 70215 83452</span>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Stay Updated</h4>
                    <p class="text-gray-500 text-sm mb-4 font-light">Subscribe to get the latest product updates and price lists.</p>
                    
                    <form id="newsletterForm" class="space-y-3" onsubmit="handleSubscribe(event)">
                        <div class="relative">
                            <input type="email" name="email" required placeholder="Enter your email" 
                                   class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:border-blue-500 focus:outline-none focus:bg-gray-900 transition-all placeholder-gray-600">
                            
                            <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                <input type="text" name="website_url" tabindex="-1" autocomplete="off" placeholder="Do not fill this out">
                                <input type="hidden" name="form_timestamp" value="<?php echo time(); ?>">
                            </div>
                        </div>
                        
                        <button type="submit" id="subBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg text-sm transition-colors uppercase tracking-wide">
                            Subscribe
                        </button>
                    </form>
                    
                    <script>
                    function handleSubscribe(e) {
                        e.preventDefault();
                        const btn = document.getElementById('subBtn');
                        const originalText = btn.innerText;
                        
                        btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Subscribing...';
                        btn.disabled = true;
                    
                        const formData = new FormData(e.target);
                    
                        fetch('subscribe.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.status === 'success') {
                                alert('Thanks for subscribing!');
                                e.target.reset();
                            } else {
                                alert(data.message || 'Subscription failed.');
                            }
                        })
                        .catch(err => alert('Error connecting to server.'))
                        .finally(() => {
                            btn.innerText = originalText;
                            btn.disabled = false;
                        });
                    }
                    </script>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-600 text-xs text-center md:text-left">
                    &copy; <?php echo date("Y"); ?> Metaldur Cutting Tools. All rights reserved. 
                    <span class="hidden md:inline mx-2 text-gray-800">|</span> 
                    <span class="block md:inline mt-1 md:mt-0">Powered by Carbiforce Pvt. Ltd.</span>
                </p>
                <div class="flex items-center gap-6">
                    <p class="text-gray-600 text-xs font-bold tracking-widest uppercase">Precision. Power. Performance.</p>
                </div>
            </div>
        </div>
    </footer>

    </div> <?php 
        $mainJsVer = file_exists('assets/js/main.js') ? filemtime('assets/js/main.js') : '1.0';
        $holderJsVer = file_exists('assets/js/holder.js') ? filemtime('assets/js/holder.js') : '1.0';
    ?>
    
    <?php include 'includes/contact_modal.php'; ?>
    <script src="assets/js/main.js?v=<?php echo $mainJsVer; ?>"></script>

    <?php if (basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
    <script type="module" src="assets/js/holder.js?v=<?php echo $holderJsVer; ?>"></script>
    <?php endif; ?>
</body>
</html>