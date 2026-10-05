(function () {
  'use strict';

  var body = document.body;
  var nav = document.querySelector('.navbar');
  var toggle = document.querySelector('.nav-toggle');

  if (document.querySelector('.sidebar')) body.classList.add('admin-page');
  if (document.querySelector('.login-container') || document.querySelector('.register-container')) body.classList.add('auth-page');

  function buildTransition() {
    if (document.querySelector('.page-transition')) return document.querySelector('.page-transition');
    var wrap = document.createElement('div');
    wrap.className = 'page-transition';
    wrap.innerHTML = '<div class="page-transition-inner"><div class="page-transition-mark">L</div><div class="page-transition-title">LENTERA</div><div class="page-transition-sub">BOOKS &amp; STORIES</div></div>';
    document.body.appendChild(wrap);
    requestAnimationFrame(function () {
      requestAnimationFrame(function () { wrap.classList.add('is-loaded'); });
    });
    setTimeout(function(){ if (wrap.parentNode) wrap.parentNode.removeChild(wrap); }, 1100);
    return wrap;
  }
  buildTransition();

  document.querySelectorAll('a[href]').forEach(function(link){
    link.addEventListener('click', function(e){
      var href = link.getAttribute('href') || '';
      if (!href || href[0] === '#' || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
      if (link.target === '_blank' || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      var url;
      try { url = new URL(href, window.location.href); } catch(err) { return; }
      if (url.origin !== window.location.origin) return;
      if (url.href === window.location.href) return;
      e.preventDefault();
      var overlay = document.createElement('div');
      overlay.className = 'page-transition is-active';
      overlay.innerHTML = '<div class="page-transition-inner"><div class="page-transition-mark">L</div><div class="page-transition-title">LENTERA</div><div class="page-transition-sub">opening the next chapter</div></div>';
      document.body.appendChild(overlay);
      setTimeout(function(){ window.location.href = url.href; }, 280);
    });
  });

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
    '.book-card, .category-card, .user-card, .dashboard-box, .about-card, .message-card, .order-card, .detail-card, .catalog-toolbar, .page-masthead, .hero-inner, .hero-visual, .table-box, .table-card, .cart-wrapper, .checkout-wrapper, .login-card, .register-card, .auth-editorial-panel, .content h1, .content-user h1, .user-container h1'
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
      el.style.transitionDelay = Math.min(index % 5, 4) * 65 + 'ms';
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
      if (cover) cover.style.transform = 'perspective(900px) rotateY(' + (x * 3.2) + 'deg) rotateX(' + (-y * 2.6) + 'deg) translateY(-3px)';
    });
    card.addEventListener('mouseleave', function () {
      var cover = card.querySelector('.cover-wrap');
      if (cover) cover.style.transform = '';
    });
  });
})();
