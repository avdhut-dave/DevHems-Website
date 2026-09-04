/**
 * Toggles the "Get Free Consultation" modal. Any element with the class
 * devhems-open-modal opens it — including the [devhems_consultation_trigger]
 * shortcode button and, if used, Elementor buttons with that CSS class
 * added via the Advanced > CSS Classes field.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('devhems-consultation-modal');
    if (!modal) {
      return;
    }

    function open() {
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      var firstField = modal.querySelector('input, textarea, select');
      if (firstField) {
        firstField.focus();
      }
    }

    function close() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
    }

    document.addEventListener('click', function (e) {
      if (e.target.closest('.devhems-open-modal')) {
        e.preventDefault();
        open();
      }
      if (e.target === modal || e.target.closest('.devhems-modal-close')) {
        close();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) {
        close();
      }
    });
  });
})();
