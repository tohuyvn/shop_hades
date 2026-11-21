/**
 * REFRACT GLASSY DASHBOARD V31 - WATER DROPLET RIPPLE ENGINE
 * Liquid Water Ripple Effect on Click (Đa tầng sóng gợn nước lăn tăn phản quang)
 */
(function() {
  'use strict';

  // 1. Mouse Spotlight Tracker on Glass Cards & Buttons
  function initGlassSpotlight() {
    const glassElements = document.querySelectorAll('.refract-card, .refract-sidebar, .card, .btn-refract-action, .category-nav a, .nav-pill-link');

    glassElements.forEach(card => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        card.style.setProperty('--mouse-x', `${x}px`);
        card.style.setProperty('--mouse-y', `${y}px`);

        if (card.classList.contains('refract-card') || card.classList.contains('card')) {
          const centerX = rect.width / 2;
          const centerY = rect.height / 2;
          const rotateX = ((y - centerY) / centerY) * -3.5;
          const rotateY = ((x - centerX) / centerX) * 3.5;
          card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-4px)`;
        }
      });

      card.addEventListener('mouseleave', () => {
        if (card.classList.contains('refract-card') || card.classList.contains('card')) {
          card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
        }
      });
    });
  }

  // 2. Multi-Ring Liquid Water Droplet Ripple Effect (Gợn nước lăn tăn phản quang)
  function initWaterRipple() {
    document.addEventListener('click', function(e) {
      // Find the clickable glass element or fallback to card/body
      const target = e.target.closest('.refract-card, .btn, .btn-refract-action, .category-nav a, .sidebar-nav-item, .nav-pill-link, .card, a, button');
      const container = target || document.body;
      const rect = container.getBoundingClientRect();

      // Compute click position relative to target
      const clickX = e.clientX - rect.left;
      const clickY = e.clientY - rect.top;

      // Create a ripple wrapper container
      const rippleBox = document.createElement('div');
      rippleBox.className = 'water-ripple-container';
      rippleBox.style.left = `${clickX}px`;
      rippleBox.style.top = `${clickY}px`;

      // Generate 3 concentric water ripple rings with delay (lăn tăn từng đợt)
      for (let i = 0; i < 3; i++) {
        const wave = document.createElement('div');
        wave.className = `water-wave-ring wave-ring-${i + 1}`;
        wave.style.animationDelay = `${i * 0.12}s`;
        rippleBox.appendChild(wave);
      }

      container.appendChild(rippleBox);

      // Clean up after animation finishes
      setTimeout(() => {
        rippleBox.remove();
      }, 950);
    });
  }

  // Initialize
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initGlassSpotlight();
      initWaterRipple();
    });
  } else {
    initGlassSpotlight();
    initWaterRipple();
  }
})();
