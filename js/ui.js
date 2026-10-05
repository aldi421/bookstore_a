(function () {
  'use strict';

  var nav = document.querySelector('.navbar');
  var toggle = document.querySelector('.nav-toggle');

  function syncNav() {
    if (!nav) return;
    nav.classList.toggle('is-scrolled', window.scrollY > 18);
  }

  syncNav();
  window.addEventListener('scroll', syncNav, { passive: true });

  if (nav && toggle) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('nav-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.textContent = open ? '×' : '☰';
    });

    nav.querySelectorAll('.nav-menu a').forEach(function (link) {
      link.addEventListener('click', function () {
        nav.classList.remove('nav-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.textContent = '☰';
      });
    });
  }

  var revealTargets = document.querySelectorAll(
    '.book-card, .category-card, .user-card, .dashboard-box, .about-card, .message-card, .order-card, .detail-card, .catalog-toolbar'
  );

  revealTargets.forEach(function (el) {
    el.classList.add('reveal-on-scroll');
  });

  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -25px 0px' });

    revealTargets.forEach(function (el, index) {
      el.style.transitionDelay = Math.min(index % 4, 3) * 70 + 'ms';
      observer.observe(el);
    });
  } else {
    revealTargets.forEach(function (el) { el.classList.add('is-visible'); });
  }

  document.querySelectorAll('.book-card').forEach(function (card) {
    card.addEventListener('mousemove', function (event) {
      if (window.matchMedia('(hover: none)').matches) return;
      var rect = card.getBoundingClientRect();
      var x = (event.clientX - rect.left) / rect.width - 0.5;
      var y = (event.clientY - rect.top) / rect.height - 0.5;
      var cover = card.querySelector('.cover-wrap');
      if (cover) cover.style.transform = 'perspective(900px) rotateY(' + (x * 2.2) + 'deg) rotateX(' + (-y * 1.8) + 'deg)';
    });
    card.addEventListener('mouseleave', function () {
      var cover = card.querySelector('.cover-wrap');
      if (cover) cover.style.transform = '';
    });
  });
})();
