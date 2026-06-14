// 1. Navbar scroll effect
window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.custom-navbar');
    if (navbar) {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    }
}, { passive: true });

// 2. Counter animation:
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            const el = entry.target;
            const rawTarget = el.dataset.target || el.innerText;
            const target = parseInt(rawTarget, 10);
            
            if (isNaN(target)) {
                observer.unobserve(el);
                return;
            }
            
            let count = 0;
            const speed = target / 50; 
            const update = () => {
                if (count < target && document.contains(el)) {
                    count += Math.ceil(speed);
                    el.textContent = count > target ? target : count;
                    requestAnimationFrame(update);
                }
            };
            update();
            observer.unobserve(el); 
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.counter').forEach(n => observer.observe(n));

