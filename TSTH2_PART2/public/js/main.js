// Wait for DOM to load
document.addEventListener('DOMContentLoaded', () => {
    
    // --- Sticky Header ---
    const header = document.getElementById('header');
    // if (header) {
    //     window.addEventListener('scroll', () => {
    //         if (window.scrollY > 50) {
    //             header.classList.add('scrolled');
    //         } else {
    //             header.classList.remove('scrolled');
    //         }
    //     });
    // }

    // --- Hero Slider ---
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    let currentSlide = 0;
    let slideInterval;

    const showSlide = (index) => {
        if (!slides.length) return;
        
        // Remove active class from all
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Set new active slide
        slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
        
        currentSlide = index;
    };

    const nextSlide = () => {
        if (!slides.length) return;
        let next = currentSlide + 1;
        if (next >= slides.length) next = 0;
        showSlide(next);
    };

    // Initialize auto slide
    if (slides.length > 1) {
        slideInterval = setInterval(nextSlide, 5000);
        
        // Dot clicks
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                clearInterval(slideInterval);
                showSlide(index);
                slideInterval = setInterval(nextSlide, 5000);
            });
        });
    }

    // --- Scroll Animations (Intersection Observer) ---
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    if (animatedElements.length > 0) {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.15
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Stop observing once visible
                }
            });
        }, observerOptions);

        animatedElements.forEach(el => {
            observer.observe(el);
        });
    }
});
