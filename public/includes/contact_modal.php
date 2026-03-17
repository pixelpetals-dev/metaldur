<div id="contact-modal" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-black/80 transition-opacity opacity-0" id="modal-backdrop" onclick="closeContactModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <div id="modal-panel" class="relative transform overflow-hidden rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <button onclick="closeContactModal()" class="absolute top-4 right-4 text-white/50 hover:text-white transition-colors z-20">
                    <i class="ri-close-circle-line text-3xl"></i>
                </button>

                <div id="modal-success" class="absolute inset-0 bg-[#0a111c]/95 z-30 flex flex-col items-center justify-center text-center p-8 opacity-0 pointer-events-none transition-opacity duration-300">
                    <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mb-4">
                        <i class="ri-check-line text-3xl text-green-500"></i>
                    </div>
                    <h3 class="text-2xl text-white font-bold mb-2 brand-font">REQUEST SENT</h3>
                    <p class="text-gray-400 text-sm">We'll be in touch shortly.</p>
                    <button onclick="closeContactModal()" class="mt-6 text-blue-400 hover:text-white text-sm font-bold">Close Window</button>
                </div>

                <div class="p-8 sm:p-10">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-white brand-font mb-2">GET A QUOTE</h3>
                        <p class="text-gray-300 text-sm font-light">Precision tools at the lowest guaranteed price.</p>
                    </div>

                    <form id="modalForm" onsubmit="handleModalSubmit(event)" class="space-y-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-300 uppercase tracking-widest mb-1">Name</label>
                            <input type="text" name="name" required 
                                   class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-blue-400 focus:bg-black/40 focus:outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-300 uppercase tracking-widest mb-1">Email</label>
                                <input type="email" name="email" required 
                                       class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-blue-400 focus:bg-black/40 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-300 uppercase tracking-widest mb-1">Phone</label>
                                <input type="tel" name="phone" required 
                                       class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-blue-400 focus:bg-black/40 focus:outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-300 uppercase tracking-widest mb-1">Requirement</label>
                            <textarea name="message" rows="3" required placeholder="Tell us what you need..."
                                      class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-blue-400 focus:bg-black/40 focus:outline-none transition-all"></textarea>
                        </div>

                        <div style="position: absolute; left: -5000px;" aria-hidden="true">
                            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                            <input type="hidden" name="form_timestamp" value="<?php echo time(); ?>">
                        </div>

                        <button type="submit" id="modalSubmitBtn" 
                                class="w-full bg-white text-black font-bold py-4 rounded-lg hover:bg-gray-200 transition-all transform hover:scale-[1.02] shadow-xl mt-4">
                            SEND REQUEST
                        </button>

                        <p class="text-center text-xs text-gray-400 mt-4">
                            Or call us directly at <a href="tel:+917021583452" class="text-white hover:underline">+91 70215 83452</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openContactModal() {
    const modal = document.getElementById('contact-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    
    modal.classList.remove('hidden');
    
    requestAnimationFrame(() => {
        backdrop.classList.remove('opacity-0');
        panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    });
}

function closeContactModal() {
    const modal = document.getElementById('contact-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    
    backdrop.classList.add('opacity-0');
    panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('modal-success').style.opacity = '0';
        document.getElementById('modal-success').style.pointerEvents = 'none';
        document.getElementById('modalForm').reset();
    }, 300);
}

function handleModalSubmit(e) {
    e.preventDefault();
    
    const btn = document.getElementById('modalSubmitBtn');
    const originalText = btn.innerHTML;
    const successOverlay = document.getElementById('modal-success');
    
    btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> SENDING...';
    btn.disabled = true;

    const formData = new FormData(e.target);

    fetch('contact_submission.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            successOverlay.style.pointerEvents = 'auto';
            successOverlay.style.opacity = '1';
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
}
</script>