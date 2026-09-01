/**
 * Contact Form 7 UX + tracking helpers, shared by all seven forms.
 * - populates hidden source_url / utm_* fields from the current URL
 * - shows a disabled/loading state on submit
 * - moves inline error messages next to their field for accessible reading
 * - auto-select is already handled per-template via the "current post
 *   title" default value on the "interested_service" / "job_position"
 *   fields set from the CF7 shortcode attribute inserted by the theme
 *   (see single-service.php / single-career.php), this script is the
 *   fallback for forms embedded via widget/shortcode without that context.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    populateTrackingFields();
    document.querySelectorAll('.wpcf7-form').forEach(wireForm);
  });

  function populateTrackingFields() {
    var params = new URLSearchParams(window.location.search);
    var map = {
      source_url: window.location.href,
      utm_source: params.get('utm_source') || '',
      utm_medium: params.get('utm_medium') || '',
      utm_campaign: params.get('utm_campaign') || '',
      utm_term: params.get('utm_term') || '',
      utm_content: params.get('utm_content') || ''
    };

    Object.keys(map).forEach(function (name) {
      document.querySelectorAll('input[name="' + name + '"]').forEach(function (el) {
        if (!el.value) {
          el.value = map[name];
        }
      });
    });
  }

  function wireForm(form) {
    var submitBtn = form.querySelector('input[type="submit"], button[type="submit"]');

    form.addEventListener('wpcf7beforesubmit', function () {
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('is-loading');
        submitBtn.dataset.originalText = submitBtn.dataset.originalText || submitBtn.value || submitBtn.textContent;
      }
    });

    form.addEventListener('wpcf7submit', resetButton);
    form.addEventListener('wpcf7mailsent', resetButton);
    form.addEventListener('wpcf7mailfailed', resetButton);
    form.addEventListener('wpcf7invalid', resetButton);
    form.addEventListener('wpcf7spam', resetButton);

    function resetButton() {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('is-loading');
      }
    }
  }
})();
