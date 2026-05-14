document.addEventListener('DOMContentLoaded', () => {
    // 1. FAQ Accordion
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const body = item.querySelector('.accordion-body');
            const icon = header.querySelector('i');
            
            // Toggle current item
            const isOpen = body.style.display === 'block';
            
            // Close all items
            document.querySelectorAll('.accordion-body').forEach(b => b.style.display = 'none');
            document.querySelectorAll('.accordion-header i').forEach(i => {
                i.classList.remove('fa-minus');
                i.classList.add('fa-plus');
            });
            
            if (!isOpen) {
                body.style.display = 'block';
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            }
        });
    });

    // 2. Counter Animation & Circle Progress
    const counters = document.querySelectorAll('.counter');
    const circle = document.querySelector('.circle-progress');
    const speed = 400;

    const animateElements = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const targetEl = entry.target;
                
                if (targetEl.classList.contains('counter')) {
                    const targetValue = +targetEl.getAttribute('data-target');
                    let count = 0;
                    const updateCount = () => {
                        const inc = targetValue / speed;
                        if (count < targetValue) {
                            count += inc;
                            targetEl.innerText = Math.ceil(count);
                            setTimeout(updateCount, 1);
                        } else {
                            targetEl.innerText = targetValue;
                        }
                    };
                    updateCount();
                } 
                
                if (targetEl.classList.contains('circle-progress')) {
                    const targetPercent = +targetEl.getAttribute('data-percent');
                    let currentPercent = 0;
                    const duration = 2000;
                    const startTime = performance.now();

                    const animateCircle = (currentTime) => {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        currentPercent = progress * targetPercent;
                        
                        targetEl.style.setProperty('--progress', currentPercent);
                        const textEl = targetEl.querySelector('.percent-text');
                        if (textEl) textEl.innerText = Math.round(currentPercent) + '%';

                        if (progress < 1) {
                            requestAnimationFrame(animateCircle);
                        }
                    };
                    requestAnimationFrame(animateCircle);
                }

                observer.unobserve(targetEl);
            }
        });
    };

    const elementObserver = new IntersectionObserver(animateElements, { threshold: 0.5 });
    counters.forEach(c => elementObserver.observe(c));
    if (circle) elementObserver.observe(circle);

    // 3. Navbar Sticky on Scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (navbar) {
            if (window.scrollY > 50) {
                navbar.style.padding = '10px 0';
            } else {
                navbar.style.padding = '20px 0';
            }
        }
    });

    // 4. Default open first FAQ
    if (accordionHeaders.length > 0) {
        accordionHeaders[0].click();
    }
});
