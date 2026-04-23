const animatedSections = document.querySelectorAll('.animate-on-scroll');

if ('IntersectionObserver' in window && animatedSections.length) {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fade-in');
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.15,
    }
  );

  animatedSections.forEach((section) => observer.observe(section));
}

