document.addEventListener("DOMContentLoaded", () => {
    initPreloader();
    initScrollAnimations();
});

function initPreloader() {
    const loaderLine = document.querySelector('.loader-line');
    const preloader = document.getElementById('preloader');

    if (!loaderLine || !preloader) return;

    setTimeout(() => { loaderLine.style.width = "50%"; }, 200);
    setTimeout(() => { loaderLine.style.width = "100%"; }, 600);

    setTimeout(() => {
        anime({
            targets: '#preloader',
            opacity: 0,
            duration: 800,
            easing: 'easeInOutQuad',
            complete: function() { 
                preloader.style.display = 'none'; 
                triggerHeroAnimations();
            }
        });
    }, 1000);
}

function triggerHeroAnimations() {
    anime({
        targets: '.reveal-hero',
        translateY: [50, 0],
        opacity: [0, 1],
        delay: anime.stagger(100),
        easing: 'easeOutExpo',
        duration: 1500
    });
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');

        anime({
            targets: '#mobile-menu',
            translateY: [-20, 0],
            opacity: [0, 1],
            duration: 300,
            easing: 'easeOutQuad'
        });
    } else {
        menu.classList.add('hidden');
    }
}

function initScrollAnimations() {
    const elementsToReveal = document.querySelectorAll('.reveal-scroll');

    if (elementsToReveal.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                anime({
                    targets: entry.target,
                    translateY: [30, 0],
                    opacity: [0, 1],
                    easing: 'easeOutQuad',
                    duration: 800
                });
                observer.unobserve(entry.target);
            }
        });
    }, { 
        threshold: 0.15, 

        rootMargin: "0px 0px -50px 0px" 

    });

    elementsToReveal.forEach(el => observer.observe(el));
}