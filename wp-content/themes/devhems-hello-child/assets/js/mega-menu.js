/**
 * Accessible mega menu behaviour:
 * - opens on hover (desktop) and keyboard focus
 * - closes on outside click and Escape
 * - toggles aria-expanded
 * - mobile hamburger + nested accordion, touch-friendly
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', init);

  function init() {
    var nav = document.querySelector('.devhems-primary-nav');
    if (!nav) {
      return;
    }

    var menu = nav.querySelector('.devhems-menu');
    var items = nav.querySelectorAll('.devhems-menu-item.has-mega-menu');
    var isDesktop = function () {
      return window.matchMedia('(min-width: 1025px)').matches;
    };
    var closeTimers = new WeakMap();

    items.forEach(function (item) {
      var link = item.querySelector(':scope > .devhems-menu-link');
      var panel = item.querySelector(':scope > .devhems-mega-panel');
      if (!link || !panel) {
        return;
      }

      function openPanel() {
        closeAllExcept(item);
        item.classList.add('is-open');
        link.setAttribute('aria-expanded', 'true');
      }

      function closePanel() {
        item.classList.remove('is-open');
        link.setAttribute('aria-expanded', 'false');
      }

      item.addEventListener('mouseenter', function () {
        if (!isDesktop()) return;
        clearTimeout(closeTimers.get(item));
        openPanel();
      });

      item.addEventListener('mouseleave', function () {
        if (!isDesktop()) return;
        var t = setTimeout(closePanel, 150);
        closeTimers.set(item, t);
      });

      link.addEventListener('focus', function () {
        if (isDesktop()) {
          openPanel();
        }
      });

      link.addEventListener('click', function (e) {
        if (!isDesktop()) {
          e.preventDefault();
          var willOpen = !item.classList.contains('is-open');
          closeAllExcept(willOpen ? item : null);
          if (willOpen) {
            openPanel();
          } else {
            closePanel();
          }
        }
      });

      // Keep panel open while focus is anywhere inside it (keyboard nav).
      panel.addEventListener('focusin', openPanel);
      item.addEventListener('focusout', function (e) {
        if (!item.contains(e.relatedTarget)) {
          closePanel();
        }
      });
    });

    function closeAllExcept(exceptItem) {
      items.forEach(function (item) {
        if (item !== exceptItem) {
          item.classList.remove('is-open');
          var link = item.querySelector(':scope > .devhems-menu-link');
          if (link) link.setAttribute('aria-expanded', 'false');
        }
      });
    }

    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target)) {
        closeAllExcept(null);
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeAllExcept(null);
        var mobileToggle = document.querySelector('.devhems-mobile-toggle');
        if (menu && menu.classList.contains('is-open')) {
          menu.classList.remove('is-open');
          if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'false');
          if (mobileToggle) mobileToggle.focus();
        }
      }
    });

    // Mobile hamburger toggle.
    var toggle = document.querySelector('.devhems-mobile-toggle');
    if (toggle && menu) {
      toggle.addEventListener('click', function () {
        var isOpen = menu.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        document.body.classList.toggle('devhems-menu-open', isOpen);
      });
    }

    // Prevent stale open states when resizing across the breakpoint.
    window.addEventListener('resize', function () {
      if (isDesktop()) {
        if (menu) menu.classList.remove('is-open');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('devhems-menu-open');
      } else {
        closeAllExcept(null);
      }
    });
  }
})();
