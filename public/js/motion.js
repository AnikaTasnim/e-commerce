(() => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (reduceMotion.matches) return;

  const revealTargets = [
    '.promise-strip > div', '.section-heading', '.product-card',
    '.story-image', '.story-copy', '.why-heading', '.why-card',
    '.testimonial-heading', '.testimonial-card', '.review-section',
    '.visit-details', '.visit-card', '.newsletter', '.product-intro > *',
    '.product-information > *', '.billing-heading', '.order-form', '.order-summary',
    '.admin-heading', '.admin-card', '.metric-card', '.dashboard-shortcuts > a',
    '.admin-login-card', '.report-summary > *', '.report-table-card',
    '.admin-order-detail-grid > *', '.shipping-admin-row', '.shipping-add-form',
  ].join(',');

  const targets = [...document.querySelectorAll(revealTargets)];
  if (!targets.length) return;

  targets.forEach((element, index) => {
    element.classList.add('motion-reveal');
    element.style.setProperty('--motion-order', String(index % 5));
  });

  document.documentElement.classList.add('motion-ready');

  if (!('IntersectionObserver' in window)) {
    targets.forEach((element) => element.classList.add('motion-visible'));
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('motion-visible');
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });

  targets.forEach((element) => observer.observe(element));
})();
