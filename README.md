# DevHems Technology — WordPress / Elementor Build

This repository contains the **child theme and custom functionality** for the
DevHems Technology website: `wp-content/themes/devhems-hello-child/`.

It does not contain WordPress core or third-party plugins (those are never
version-controlled) — only the code that is specific to this site: custom
post types, ACF field groups, the mega menu, Contact Form 7 templates,
schema markup, SEO scaffolding and performance tweaks. Everything else
(page layouts, Elementor sections, actual content, plugin configuration,
hosting, SMTP credentials, reCAPTCHA/Turnstile keys) is done visually in
WordPress admin by whoever installs this theme, and is intentionally **not**
hardcoded so the site owner can edit it without touching code.

## What's in this repo vs. what you still configure in wp-admin

| Requirement | Where it lives |
|---|---|
| Services / Case Studies / Careers / Testimonials post types & fields | Code — `inc/post-types-*.php`, `inc/acf-fields.php` |
| Service / Industry / Department taxonomies | Code — `inc/taxonomies.php` |
| Mega menu markup, ARIA, keyboard, mobile accordion | Code — `inc/mega-menu.php`, `inc/class-devhems-mega-menu-walker.php`, `assets/css/mega-menu.css`, `assets/js/mega-menu.js` |
| Contact Form 7 form fields, validation, mail templates | Code (as paste-in templates) — `cf7-forms/*.txt` |
| CF7 honeypot, resume validation, Reply-To safety net, UTM capture, redirect + GA4/GTM events | Code — `inc/cf7-integration.php`, `assets/js/forms.js` |
| Schema.org JSON-LD (Organization, WebSite, Service, Article, JobPosting, BreadcrumbList) | Code — `inc/schema.php`, `inc/breadcrumbs.php` |
| SEO meta fallback + ACF↔Rank Math/Yoast bridge + noindex | Code — `inc/seo-support.php` |
| Performance hardening (lazy-load, dequeues, defer, CWV) | Code — `inc/performance.php` |
| Fallback PHP templates for Service/Case Study/Career/404/HTML Sitemap | Code — `templates/` (used only if Elementor Pro Theme Builder isn't licensed) |
| Actual page layouts (Home, About, Contact, etc.), colors, fonts, spacing | **Elementor** — built visually by the site editor using Site Settings |
| Real service/case-study/career/testimonial content, blog posts | **WP Admin** — entered by the site administrator |
| Plugin installation & activation | **WP Admin → Plugins** |
| CF7 form creation from the templates below | **WP Admin → Contact → Add New** (paste in) |
| SMTP credentials, reCAPTCHA/Turnstile keys, GA4/GTM IDs | **Plugin settings screens** (never hardcode secrets in the theme) |

## Required plugins (install & activate before this theme is usable)

- **Hello Elementor** (parent theme) + **Elementor** (and **Elementor Pro** if licensed)
- **Advanced Custom Fields** (or ACF PRO) — required for `inc/acf-fields.php`
- **Contact Form 7** + **Flamingo** — required for `cf7-forms/*.txt` and `inc/cf7-integration.php`
- **Rank Math SEO** or **Yoast SEO** — `inc/seo-support.php` and `inc/schema.php` detect and defer to whichever is active
- **FluentSMTP** or **WP Mail SMTP** — authenticated outbound mail (do not rely on PHP `mail()`)
- A caching/optimization plugin appropriate to the host (e.g. WP Rocket, LiteSpeed Cache, or the host's built-in page cache) for minification, WebP/AVIF conversion and full-page caching — intentionally not bundled in the theme, per "use minimum plugins"

## Setup order

1. Install WordPress, then install/activate the plugins above.
2. Install this theme: copy `wp-content/themes/devhems-hello-child/` into
   the site's `wp-content/themes/` folder, set **Hello Elementor** as the
   parent (already declared via `Template: hello-elementor` in `style.css`),
   then activate **DevHems Technology Child**.
3. Appearance → Menus: create a menu, assign it to **Primary Mega Menu**,
   and build the structure described in `inc/mega-menu.php`'s doc block
   (Services → category → sub-service, three levels deep) using the
   category names and sub-services from the project brief (Digital
   Marketing, Website Services, AI and Automation).
4. Custom Fields → Field Groups → **Sync available** (if using the JSON
   sync UI) to pick up `acf-json/*.json` once ACF has written them, or let
   `inc/acf-fields.php`'s PHP registration run as-is — both work together.
5. Contact → Add New: create the 7 forms using `cf7-forms/1-*.txt` through
   `cf7-forms/7-*.txt`. Note each form's ID/shortcode.
6. Add this to a code snippet or `wp-config.php`-adjacent mu-plugin (not
   this theme, since it's environment-specific) to wire the Service Enquiry
   and Career Application forms to their real CF7 IDs:
   ```php
   add_filter( 'devhems_service_enquiry_form_id', fn() => 123 );
   add_filter( 'devhems_career_application_form_id', fn() => 124 );
   ```
7. Build the header/footer/pages in Elementor (Pro Theme Builder if
   licensed) per the section lists in the project brief, using the
   `[devhems_mega_menu]`, `[devhems_breadcrumbs]`, `[devhems_service_enquiry_form]`
   and `[devhems_career_application_form]` shortcodes where noted.
8. Configure SMTP, reCAPTCHA/Turnstile, Rank Math/Yoast, and GA4/GTM from
   their own plugin settings screens.
9. Add real content: Services, Case Studies, Careers, Testimonials, Blog
   posts, and the required legal pages (Privacy Policy, Terms & Conditions).
10. Create a Page using the **HTML Sitemap** template
    (`templates/page-html-sitemap.php`) for the required HTML Sitemap page —
    it lists Pages/Services/Case Studies/Careers/Blog automatically.
11. QA per the project brief's Final Testing checklist (forms, emails,
    resume upload, spam protection, thank-you redirect, tracking, mega
    menu, responsive breakpoints, accessibility, SEO, schema, cross-browser).

## Notes on specific decisions

- **Mega menu**: `class-devhems-mega-menu-walker.php` renders a WP menu as
  the dark-panel/white-panel mega menu with full ARIA wiring if Elementor
  Pro isn't licensed. If it is, build the header with Elementor's Nav Menu
  widget instead and reuse `assets/css/mega-menu.css` classes/structure —
  either path shares the same CSS/JS behavior file.
- **CF7 auto-select fields**: CF7 alone can't read "the current service/job
  page." `[devhems_service_enquiry_form]` and
  `[devhems_career_application_form]` (in `inc/template-tags.php`) wrap the
  real CF7 shortcode and inject the current post's title into the
  `interested_service` / `job_position` fields.
- **Schema de-duplication**: `inc/schema.php` and `inc/seo-support.php` both
  check `devhems_seo_plugin_active()` and skip their own Organization/
  WebSite/meta output when Rank Math or Yoast is active, so structured data
  and meta tags are never emitted twice.
- **No secrets in code**: SMTP credentials, reCAPTCHA/Turnstile site keys,
  and analytics IDs belong in their respective plugin settings screens, not
  in this theme — keeps them editable without a deploy and out of version
  control.
