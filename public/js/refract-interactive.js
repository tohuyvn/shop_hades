/**
 * REFRACT GLASSY DASHBOARD V31 - PHONE STORE LIVE VIDEO & SPOTLIGHT ENGINE
 * Liquid Water Ripple, Live Animated Backgrounds & Interactive Spotlight
 * Inspired by Codexr UI "Refract" (Glassy Dashboard v31)
 */
(function() {
  'use strict';

  // 1. Wallpaper & Video Background Library
  const BACKGROUNDS = {
    // Live Video Backgrounds
    'video-phones': {
      type: 'video',
      name: 'Smartphone 3D Futuristic',
      desc: 'Thiết bị & điện thoại 3D chuyển động',
      url: '/video/phone_devices_loop.mp4',
      badge: 'Video Hot'
    },
    'video-particles': {
      type: 'video',
      name: 'Cyber Neon Particles',
      desc: 'Hạt ánh sáng neon chuyển động',
      url: '/video/neon_cyber_particles.mp4',
      badge: 'Video'
    },
    'video-chipset': {
      type: 'video',
      name: 'Chipset & Circuit Board',
      desc: 'Vi mạch & bộ vi xử lý phát sáng',
      url: '/video/tech_chipset_circuit.mp4',
      badge: 'Video'
    },
    // Static Wallpapers
    'titanium': {
      type: 'image',
      name: 'Titanium Neon Ambient',
      desc: 'Dải titanium & ánh sáng ambient',
      url: '/image/phone_glassy_bg.jpg'
    },
    'showroom': {
      type: 'image',
      name: 'Cyber Showroom Stage',
      desc: 'Sân khấu công nghệ cao cấp',
      url: '/image/phone_showroom_bg.jpg'
    },
    'futuristic': {
      type: 'image',
      name: 'Obsidian Flagship',
      desc: 'Phòng trưng bày tương lai',
      url: '/image/phone_futuristic_bg.jpg'
    },
    'minimal': {
      type: 'image',
      name: 'Cyber Gradient Mesh',
      desc: 'Tối giản sâu thẳm huyền bí',
      url: 'radial-gradient(circle at 20% 20%, rgba(30, 41, 59, 0.95), #06080e 70%)'
    }
  };

  let currentKey = 'video-phones';

  function applyBackground(key) {
    const bg = BACKGROUNDS[key] || BACKGROUNDS['video-phones'];
    currentKey = key;

    const body = document.body;
    const videoContainer = document.getElementById('videoBgContainer');
    const videoPlayer = document.getElementById('bgVideoPlayer');
    const videoSource = document.getElementById('bgVideoSource');
    const videoCtrlBtn = document.getElementById('btnVideoToggle');

    if (!body) return;

    if (bg.type === 'video' && videoPlayer && videoSource && videoContainer) {
      body.classList.add('has-video-bg');
      videoContainer.style.display = 'block';

      if (videoSource.getAttribute('src') !== bg.url) {
        videoSource.setAttribute('src', bg.url);
        videoPlayer.load();
      }

      const playPromise = videoPlayer.play();
      if (playPromise !== undefined) {
        playPromise.catch(() => {
          // Autoplay fallback (browser policy)
        });
      }

      if (videoCtrlBtn) {
        videoCtrlBtn.style.display = 'inline-flex';
        videoCtrlBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
        videoCtrlBtn.setAttribute('title', 'Tạm dừng video nền');
      }
    } else {
      // Static Image
      body.classList.remove('has-video-bg');
      if (videoContainer) videoContainer.style.display = 'none';
      if (videoPlayer) videoPlayer.pause();

      if (bg.url.startsWith('radial-gradient') || bg.url.startsWith('linear-gradient')) {
        body.style.setProperty('--current-wallpaper', bg.url);
      } else {
        body.style.setProperty('--current-wallpaper', `url('${bg.url}')`);
      }

      if (videoCtrlBtn) {
        videoCtrlBtn.style.display = 'none';
      }
    }

    localStorage.setItem('hades_glassy_bg_key', key);

    // Update active state in UI
    document.querySelectorAll('.wallpaper-opt-btn').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.bg === key);
    });
  }

  function toggleVideoPlay() {
    const videoPlayer = document.getElementById('bgVideoPlayer');
    const videoCtrlBtn = document.getElementById('btnVideoToggle');
    const body = document.body;
    if (!videoPlayer) return;

    if (videoPlayer.paused) {
      videoPlayer.play();
      body.classList.remove('video-paused');
      if (videoCtrlBtn) {
        videoCtrlBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
        videoCtrlBtn.setAttribute('title', 'Tạm dừng video nền');
      }
    } else {
      videoPlayer.pause();
      body.classList.add('video-paused');
      if (videoCtrlBtn) {
        videoCtrlBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
        videoCtrlBtn.setAttribute('title', 'Phát tiếp video nền');
      }
    }
  }

  function initBackgroundSwitcher() {
    // Default to video-phones if first visit
    const saved = localStorage.getItem('hades_glassy_bg_key') || 'video-phones';
    applyBackground(saved);

    // Bind clicks on option buttons
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.wallpaper-opt-btn');
      if (btn && btn.dataset.bg) {
        e.preventDefault();
        applyBackground(btn.dataset.bg);
      }

      const toggleBtn = e.target.closest('#btnVideoToggle');
      if (toggleBtn) {
        e.preventDefault();
        toggleVideoPlay();
      }
    });

    // Handle initial browser autoplay restriction
    document.addEventListener('click', function startAutoplayOnce() {
      const videoPlayer = document.getElementById('bgVideoPlayer');
      if (videoPlayer && videoPlayer.paused && document.body.classList.contains('has-video-bg') && !document.body.classList.contains('video-paused')) {
        videoPlayer.play().catch(() => {});
      }
      document.removeEventListener('click', startAutoplayOnce);
    }, { once: true });
  }

  // 2. Mouse Spotlight Tracker & 3D Tilt on Glass Cards & Buttons
  function initGlassSpotlight() {
    const selector = '.product-card, .card, .refract-card, .hero-benefit-card, .home-category-card, .home-trust-item, .search-keywords-card, .btn-buy, .category-nav a';
    const elements = document.querySelectorAll(selector);

    elements.forEach(card => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        card.style.setProperty('--mouse-x', `${x}px`);
        card.style.setProperty('--mouse-y', `${y}px`);

        if (card.classList.contains('product-card') || card.classList.contains('hero-benefit-card') || card.classList.contains('home-category-card')) {
          const centerX = rect.width / 2;
          const centerY = rect.height / 2;
          const rotateX = ((y - centerY) / centerY) * -3.5;
          const rotateY = ((x - centerX) / centerX) * 3.5;
          card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-4px)`;
        }
      });

      card.addEventListener('mouseleave', () => {
        if (card.classList.contains('product-card') || card.classList.contains('hero-benefit-card') || card.classList.contains('home-category-card')) {
          card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
        }
      });
    });
  }

  // 3. Multi-Ring Liquid Water Droplet Ripple Effect (Gợn nước lăn tăn phản quang toàn màn hình)
  function initWaterRipple() {
    function createRipple(clientX, clientY) {
      if (typeof clientX !== 'number' || typeof clientY !== 'number') return;

      const rippleBox = document.createElement('div');
      rippleBox.className = 'water-ripple-container';
      rippleBox.style.left = `${clientX}px`;
      rippleBox.style.top = `${clientY}px`;

      // Central droplet impact spark
      const impact = document.createElement('div');
      impact.className = 'water-droplet-impact';
      rippleBox.appendChild(impact);

      // Generate 4 concentric water ripple rings with delay (lăn tăn từng đợt sóng)
      for (let i = 0; i < 4; i++) {
        const wave = document.createElement('div');
        wave.className = `water-wave-ring wave-ring-${i + 1}`;
        wave.style.animationDelay = `${i * 0.12}s`;
        rippleBox.appendChild(wave);
      }

      document.body.appendChild(rippleBox);

      // Clean up after animation finishes
      setTimeout(() => {
        rippleBox.remove();
      }, 1100);
    }

    // Trigger instantly on pointerdown (touch or click)
    document.addEventListener('pointerdown', function(e) {
      createRipple(e.clientX, e.clientY);
    }, { passive: true });
  }

  window.HadesGlass = {
    applyBackground: applyBackground,
    toggleVideoPlay: toggleVideoPlay,
    initGlassSpotlight: initGlassSpotlight
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initBackgroundSwitcher();
      initGlassSpotlight();
      initWaterRipple();
    });
  } else {
    initBackgroundSwitcher();
    initGlassSpotlight();
    initWaterRipple();
  }
})();
