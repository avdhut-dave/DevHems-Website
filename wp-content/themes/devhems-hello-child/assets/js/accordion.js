/**
 * Accessible FAQ accordion toggling for .devhems-faq-accordion blocks
 * (used by the fallback single-service.php template and the reusable
 * Elementor FAQ accordion component built with matching markup/classes).
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.devhems-faq-question').forEach(function (button) {
      button.addEventListener('click', function () {
        var expanded = button.getAttribute('aria-expanded') === 'true';
        var panel = document.getElementById(button.getAttribute('aria-controls'));
        button.setAttribute('aria-expanded', String(!expanded));
        if (panel) {
          panel.hidden = expanded;
        }
      });
    });
  });
})();
