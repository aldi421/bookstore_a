/* LENTERA / BOOKS & STORIES — accessible vanilla-JS interactions */
document.addEventListener('DOMContentLoaded', () => {
  document.documentElement.classList.add('has-js');
  const mainNav = document.querySelector('.navbar');
  const navButton = document.querySelector('.mobile-nav-toggle');
  const navMenu = document.querySelector('.navbar .nav-menu');
  function closeNav(){if(!navButton||!navMenu)return;navMenu.classList.remove('is-open');navButton.setAttribute('aria-expanded','false');navButton.setAttribute('aria-label','Buka navigasi');}
  if(navButton&&navMenu){
    navButton.addEventListener('click',()=>{const opened=navMenu.classList.toggle('is-open');navButton.setAttribute('aria-expanded',String(opened));navButton.setAttribute('aria-label',opened?'Tutup navigasi':'Buka navigasi');});
    navMenu.querySelectorAll('a').forEach(a=>a.addEventListener('click',closeNav));
  }
  const adminButton=document.querySelector('.admin-menu-toggle');
  const adminOverlay=document.querySelector('.sidebar-overlay');
  const closeAdmin=()=>{document.body.classList.remove('admin-nav-open');if(adminButton)adminButton.setAttribute('aria-expanded','false');};
  if(adminButton){adminButton.addEventListener('click',()=>{const opened=document.body.classList.toggle('admin-nav-open');adminButton.setAttribute('aria-expanded',String(opened));});}
  if(adminOverlay)adminOverlay.addEventListener('click',closeAdmin);
  document.addEventListener('keydown',event=>{if(event.key==='Escape'){closeNav();closeAdmin();}});
  window.addEventListener('resize',()=>{if(window.innerWidth>768){closeNav();closeAdmin();}},{passive:true});
  if(mainNav){const update=()=>mainNav.classList.toggle('is-scrolled',window.scrollY>12);window.addEventListener('scroll',update,{passive:true});update();}
  const reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const targets=[...document.querySelectorAll('.book-card,.category-card,.user-card,.dashboard-box,.member-banner,.detail-card,.about-card,.message-card,.order-card,.form-admin,.admin-quicklinks-grid>a')];
  if(!reduced && 'IntersectionObserver' in window){
    const observer=new IntersectionObserver(entries=>{for(const entry of entries){if(entry.isIntersecting){entry.target.classList.add('rb-visible');observer.unobserve(entry.target);}}},{threshold:.06,rootMargin:'0px 0px 60px 0px'});
    targets.forEach((node,i)=>{node.classList.add('rb-reveal');node.style.animationDelay=`${Math.min(i*36,210)}ms`;observer.observe(node);});
  }
});
