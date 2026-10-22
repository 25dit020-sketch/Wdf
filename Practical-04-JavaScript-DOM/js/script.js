// Practical 4: JavaScript DOM Manipulation & Interactivity (Beginner Style)

document.addEventListener('DOMContentLoaded', function () {

  // ==========================================
  // 1. Light / Dark Theme Switcher with localStorage
  // ==========================================
  const themeBtn = document.getElementById('theme-btn');
  
  // Check if user previously saved a theme in localStorage
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
    themeBtn.textContent = '☀️ Light Mode';
  } else {
    themeBtn.textContent = '🌙 Dark Mode';
  }

  // Toggle theme on button click
  themeBtn.addEventListener('click', function () {
    document.body.classList.toggle('dark-mode');

    if (document.body.classList.contains('dark-mode')) {
      localStorage.setItem('theme', 'dark');
      themeBtn.textContent = '☀️ Light Mode';
    } else {
      localStorage.setItem('theme', 'light');
      themeBtn.textContent = '🌙 Dark Mode';
    }
  });


  // ==========================================
  // 2. Mobile Hamburger Menu Toggle
  // ==========================================
  const hamburgerBtn = document.getElementById('hamburger-btn');
  const navMenu = document.getElementById('nav-menu');

  hamburgerBtn.addEventListener('click', function () {
    navMenu.classList.toggle('show');
  });


  // ==========================================
  // 3. Dismissible Notification Banner
  // ==========================================
  const closeBannerBtn = document.getElementById('close-banner-btn');
  const banner = document.getElementById('notification-banner');

  if (closeBannerBtn && banner) {
    closeBannerBtn.addEventListener('click', function () {
      banner.style.display = 'none'; // hides banner
    });
  }


  // ==========================================
  // 4. Content / Announcements Slider (Next & Prev)
  // ==========================================
  const slides = document.querySelectorAll('.slide');
  const prevBtn = document.getElementById('prev-btn');
  const nextBtn = document.getElementById('next-btn');
  let currentSlide = 0;

  function showSlide(index) {
    // Hide all slides
    slides.forEach(function (slide) {
      slide.classList.remove('active');
    });

    // Wrap around index
    if (index >= slides.length) {
      currentSlide = 0;
    } else if (index < 0) {
      currentSlide = slides.length - 1;
    } else {
      currentSlide = index;
    }

    // Show current slide
    slides[currentSlide].classList.add('active');
  }

  prevBtn.addEventListener('click', function () {
    showSlide(currentSlide - 1);
  });

  nextBtn.addEventListener('click', function () {
    showSlide(currentSlide + 1);
  });


  // ==========================================
  // 5. Collapsible FAQ Accordion
  // ==========================================
  const accordionButtons = document.querySelectorAll('.accordion-header');

  accordionButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const content = button.nextElementSibling;
      const isOpen = content.classList.contains('show');

      // Close all other accordion sections
      document.querySelectorAll('.accordion-content').forEach(function (item) {
        item.classList.remove('show');
      });

      // Toggle clicked section
      if (!isOpen) {
        content.classList.add('show');
      }
    });
  });


  // ==========================================
  // 6. Modal Popup for Event Details
  // ==========================================
  const openModalBtn = document.getElementById('open-modal-btn');
  const closeModalBtn = document.getElementById('close-modal-btn');
  const modal = document.getElementById('event-modal');

  if (openModalBtn && modal) {
    openModalBtn.addEventListener('click', function () {
      modal.style.display = 'flex'; // show modal
    });

    closeModalBtn.addEventListener('click', function () {
      modal.style.display = 'none'; // hide modal
    });

    // Close when clicking on dark background outside the modal box
    window.addEventListener('click', function (event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  }

});
