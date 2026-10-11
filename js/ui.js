document.addEventListener('DOMContentLoaded', () => {
  // Active admin navigation based on current file.
  const current = location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.sidebar .menu a').forEach(a => {
    const href = (a.getAttribute('href') || '').split('?')[0];
    if (href === current) a.classList.add('is-active');
  });

  // Gentle reveal animation. No dependency and respects reduced motion in CSS.
  const targets = document.querySelectorAll('.dashboard-box, .book-card, .category-card, .user-card, .about-card, .message-card, .order-card, .form-admin, .content table, .detail-card');
  targets.forEach((el, i) => {
    el.classList.add('rb-reveal');
    el.style.animationDelay = `${Math.min(i * 45, 240)}ms`;
  });
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('rb-visible');
          io.unobserve(entry.target);
        }
      });
    }, {threshold: .08});
    targets.forEach(el => io.observe(el));
  } else {
    targets.forEach(el => el.classList.add('rb-visible'));
  }
});
