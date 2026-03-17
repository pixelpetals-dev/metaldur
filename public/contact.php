<?php include 'includes/header.php'; ?>

<style>
    /* Dark Mode Map Filter - Adjusted for Blue Theme */
    .map-container iframe {
        filter: grayscale(100%) invert(90%) hue-rotate(180deg) contrast(90%);
        -webkit-filter: grayscale(100%) invert(90%) hue-rotate(180deg) contrast(90%);
    }
</style>

<div id="preloader">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-white brand-font tracking-widest mb-4">CONTACT</h1>
        <div class="w-64 h-[1px] bg-white/20 mx-auto overflow-hidden">
            <div class="loader-line h-full w-0"></div>
        </div>
    </div>
</div>

<main class="relative z-10 bg-[#114470]">

    <section class="relative h-[50vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="assets/images/metaldur_collage.webp" 
                 class="w-full h-full object-cover opacity-60 mix-blend-overlay" 
                 alt="Contact Metaldur">
        </div>

        <div class="relative z-10 text-center max-w-4xl px-4">
            <span class="text-[#e0eaef] font-bold tracking-[0.3em] uppercase text-sm mb-4 block reveal-hero">Get in Touch</span>
            <h1 class="text-6xl md:text-7xl font-bold text-white brand-font mb-6 reveal-hero drop-shadow-lg">
                START THE <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-white">CONVERSATION.</span>
            </h1>
        </div>
    </section>

    <section class="py-20 relative bg-[#114470]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                
                <div class="space-y-8 reveal-scroll">
                    <div>
                        <h2 class="text-3xl font-bold text-white brand-font mb-6 border-l-4 border-blue-400 pl-4">REACH US</h2>
                        <p class="text-blue-100 font-light mb-8">
                            Ready to optimize your machining costs? Our team is here to provide quotes, technical support, and catalog requests.
                        </p>
                        
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-[#1a6096]/40 border border-white/10 flex items-center justify-center flex-shrink-0">
                                    <i class="ri-phone-line text-blue-300 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200 uppercase tracking-widest font-bold">Call Us</p>
                                    <a href="tel:+917021583452" class="text-xl text-white hover:text-blue-300 transition-colors">
                                        +91 70215 83452
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-[#1a6096]/40 border border-white/10 flex items-center justify-center flex-shrink-0">
                                    <i class="ri-mail-send-line text-blue-300 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200 uppercase tracking-widest font-bold">Email Us</p>
                                    <a href="mailto:sales@carbiforce.com" class="text-xl text-white hover:text-blue-300 transition-colors">
                                        sales@carbiforce.com
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-[#1a6096]/40 border border-white/10 flex items-center justify-center flex-shrink-0">
                                    <i class="ri-building-4-line text-blue-300 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200 uppercase tracking-widest font-bold">Powered By</p>
                                    <p class="text-xl text-white">Carbiforce Pvt. Ltd.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="map-container rounded-2xl overflow-hidden h-64 border border-white/20 shadow-2xl relative">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3773.3710410935955!2d72.82708007582795!3d18.95921405562734!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7cf6be0d0ab1f%3A0x5d53d790d1e83fd8!2sCarbiforce%20Pvt%20Ltd!5e0!3m2!1sen!2sin!4v1766225219478!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="reveal-scroll" style="transition-delay: 100ms">
                    <div class="glass-panel p-8 md:p-10 rounded-3xl relative overflow-hidden bg-[#1a6096]/20 border-white/10 shadow-xl">
                        
                        <div id="form-success" class="absolute inset-0 bg-[#114470] z-20 flex flex-col items-center justify-center text-center p-8 opacity-0 pointer-events-none transition-opacity duration-500">
                            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mb-6">
                                <i class="ri-check-line text-4xl text-green-500"></i>
                            </div>
                            <h3 class="text-3xl text-white font-bold mb-2 brand-font">MESSAGE SENT</h3>
                            <p class="text-blue-200">Thank you. Our sales team will get back to you shortly.</p>
                            <button onclick="resetForm()" class="mt-8 text-white hover:text-blue-300 transition-colors border-b border-white hover:border-blue-300 pb-1">Send another message</button>
                        </div>

                        <h3 class="text-2xl text-white brand-font mb-8 border-l-4 border-blue-400 pl-4">SEND A MESSAGE</h3>
                        
                        <form id="contactForm" class="space-y-6">
                            
                            <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                                <input type="hidden" name="form_timestamp" value="<?php echo time(); ?>">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-blue-200 uppercase tracking-widest">Name</label>
                                    <input type="text" name="name" required 
                                           class="w-full bg-[#0d3354]/60 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-blue-400 focus:outline-none focus:bg-[#0d3354] transition-all placeholder-blue-300/30" placeholder="Your Name">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-blue-200 uppercase tracking-widest">Phone</label>
                                    <input type="tel" name="phone" required 
                                           class="w-full bg-[#0d3354]/60 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-blue-400 focus:outline-none focus:bg-[#0d3354] transition-all placeholder-blue-300/30" placeholder="Mobile Number">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-blue-200 uppercase tracking-widest">Email</label>
                                <input type="email" name="email" required 
                                       class="w-full bg-[#0d3354]/60 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-blue-400 focus:outline-none focus:bg-[#0d3354] transition-all placeholder-blue-300/30" placeholder="email@address.com">
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-blue-200 uppercase tracking-widest">Message</label>
                                <textarea name="message" rows="4" required 
                                          class="w-full bg-[#0d3354]/60 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-blue-400 focus:outline-none focus:bg-[#0d3354] transition-all placeholder-blue-300/30" placeholder="How can we help you?"></textarea>
                            </div>

                            <button type="submit" id="submitBtn" 
                                    class="w-full bg-blue-600 text-white font-bold py-4 rounded-lg hover:bg-blue-700 transition-all hover:scale-[1.02] shadow-lg flex justify-center items-center gap-2 uppercase tracking-wide">
                                <span>SEND MESSAGE</span>
                                <i class="ri-send-plane-fill"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('submitBtn');
    const originalText = btn.innerHTML;
    const successOverlay = document.getElementById('form-success');
    
    // Loading State
    btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-xl"></i> SENDING...';
    btn.disabled = true;

    const formData = new FormData(this);

    fetch('contact_submission.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Show Success Overlay
            successOverlay.style.pointerEvents = 'auto';
            successOverlay.style.opacity = '1';
            this.reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong. Please try again.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});

function resetForm() {
    const successOverlay = document.getElementById('form-success');
    successOverlay.style.opacity = '0';
    successOverlay.style.pointerEvents = 'none';
}
</script>

<?php include 'includes/footer.php'; ?>