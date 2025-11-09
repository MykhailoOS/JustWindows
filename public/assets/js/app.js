(function ($) {
  'use strict';

  const JustWindows = {
    init() {
      this.cacheDom();
      this.bindEvents();
      this.initHeroSlider();
      this.initLazy();
      this.initTooltips();
      this.watchMobileSwipe();
      this.initCommentPolling();
    },

    cacheDom() {
      this.$document = $(document);
      this.$window = $(window);
      this.$hero = $('.hero-slider');
      this.$slides = $('.hero-slide');
      this.$dots = $('.hero-dot');
      this.$burger = $('.burger');
      this.$mobileMenu = $('.mobile-menu');
      this.$mobileOverlay = $('.mobile-overlay');
      this.$closeMenu = $('.mobile-menu-close');
      this.slideIndex = 0;
      this.slideTimer = null;
    },

    bindEvents() {
      const self = this;

      this.$burger.on('click', function () {
        $(this).toggleClass('active');
        self.toggleMobileMenu();
      });

      this.$closeMenu.on('click', function () {
        self.closeMobileMenu();
      });

      this.$mobileOverlay.on('click', function () {
        self.closeMobileMenu();
      });

      this.$dots.on('click', function () {
        const index = $(this).data('index');
        self.showSlide(index);
      });

      this.$document.on('click', '.comment-reply-btn', function () {
        const target = $(this).data('target');
        $(target).toggleClass('d-none');
      });
    },

    toggleMobileMenu() {
      this.$mobileMenu.toggleClass('active');
      this.$mobileOverlay.toggleClass('active');
      $('body').toggleClass('mobile-open');
    },

    closeMobileMenu() {
      this.$mobileMenu.removeClass('active');
      this.$mobileOverlay.removeClass('active');
      this.$burger.removeClass('active');
      $('body').removeClass('mobile-open');
    },

    watchMobileSwipe() {
      let touchStartX = 0;
      let touchEndX = 0;

      this.$mobileMenu.on('touchstart', function (e) {
        touchStartX = e.originalEvent.touches[0].clientX;
      });

      this.$mobileMenu.on('touchmove', function (e) {
        touchEndX = e.originalEvent.touches[0].clientX;
      });

      this.$mobileMenu.on('touchend', () => {
        if (touchStartX - touchEndX > 50) {
          this.closeMobileMenu();
        }
      });
    },

    initHeroSlider() {
      if (!this.$hero.length || this.$slides.length <= 1) {
        return;
      }

      this.showSlide(0);
      this.slideTimer = setInterval(() => {
        this.showSlide((this.slideIndex + 1) % this.$slides.length);
      }, 5000);
    },

    showSlide(index) {
      this.slideIndex = index;
      this.$slides.removeClass('active').eq(index).addClass('active');
      this.$dots.removeClass('active').eq(index).addClass('active');
    },

    initLazy() {
      const lazyImages = document.querySelectorAll('[data-lazy]');
      if (!('IntersectionObserver' in window)) {
        lazyImages.forEach((img) => this.loadImage(img));
        return;
      }

      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            this.loadImage(entry.target);
            obs.unobserve(entry.target);
          }
        });
      }, {
        rootMargin: '100px 0px',
      });

      lazyImages.forEach((img) => observer.observe(img));
    },

    loadImage(img) {
      const src = img.getAttribute('data-src');
      if (!src) return;
      img.src = src;
      img.onload = function () {
        img.classList.add('loaded');
      };
    },

    initTooltips() {
      if (!window.bootstrap) {
        return;
      }
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
      });
    },

    initCommentPolling() {
      const container = document.querySelector('[data-comments-poll]');
      if (!container) return;

      const itemId = container.getAttribute('data-item-id');
      let sinceId = parseInt(container.getAttribute('data-last-id'), 10) || 0;

      const poll = () => {
        $.ajax({
          url: '/api/comments',
          method: 'GET',
          data: { item_id: itemId, since_id: sinceId },
          success: (response) => {
            if (!response.comments || !response.comments.length) {
              return;
            }
            response.comments.forEach((comment) => {
              sinceId = Math.max(sinceId, comment.id);
              container.insertAdjacentHTML('beforeend', this.commentTemplate(comment));
            });
          },
          complete: () => {
            setTimeout(poll, 5000);
          },
        });
      };

      setTimeout(poll, 5000);
    },

    commentTemplate(comment) {
      const isAdmin = comment.role === 'admin';
      const badge = isAdmin ? '<span class="card-badge admin">admin</span>' : '';
      return `
        <div class="comment" id="comment-${comment.id}">
          <div class="comment-avatar" style="background-image:url('${comment.avatar_path || '/public/assets/img/avatar-placeholder.svg'}')"></div>
          <div class="comment-content">
            <div class="comment-header">
              <span class="comment-author">${comment.display_name}</span>
              ${badge}
              <span class="comment-time">${comment.created_at}</span>
            </div>
            <div class="comment-body">${comment.body}</div>
          </div>
        </div>
      `;
    },
  };

  $(function () {
    JustWindows.init();
  });
})(jQuery);
