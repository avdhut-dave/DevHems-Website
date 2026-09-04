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
| Home, About Us, Contact Us, Privacy Policy, Terms & Conditions, Thank You page layouts | Code — importable Elementor JSON templates in `elementor-templates/` |
| Services Listing, Service Detail, Portfolio/Case Studies Listing, Case Study Detail, Careers Listing, Career Detail, Blog Listing, Blog Detail layouts | Code — `templates/archive/*.php`, `templates/single-*.php`, `index.php`, `single.php` (Elementor Pro Theme Builder equivalent documented in `elementor-templates/THEME-BUILDER-GUIDE.md`) |
| Fallback PHP templates for 404/HTML Sitemap | Code — `templates/` (used only if Elementor Pro Theme Builder isn't licensed) |
| Colors, fonts, spacing, button styling | **Elementor → Site Settings** — set once, referenced by every page |
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
   this theme, since it's environment-specific) to wire the Service Enquiry,
   Career Application and Popup Enquiry forms to their real CF7 IDs:
   ```php
   add_filter( 'devhems_service_enquiry_form_id', fn() => 123 );
   add_filter( 'devhems_career_application_form_id', fn() => 124 );
   add_filter( 'devhems_consultation_form_id', fn() => 125 ); // Popup Enquiry Form, cf7-forms/7-*.txt
   ```
7. Build the header/footer in Elementor (Pro Theme Builder if licensed),
   using the `[devhems_mega_menu]` shortcode for the navigation if not
   using Elementor Pro's own Nav Menu widget, and
   `[devhems_consultation_trigger label="Get Free Consultation"]` for the
   header CTA button — it opens a real modal (built in
   `inc/consultation-modal.php`, no Elementor Pro Popup Builder required)
   containing the Popup Enquiry Form, and never navigates away from the page.
7a. Import the static-content layouts: create a Page in WP Admin for each of
    **Home, About Us, Contact Us, Privacy Policy, Terms and Conditions, and
    Thank You**, edit it with Elementor, then **Templates (folder icon) →
    Import Templates** and select the matching file from
    `elementor-templates/` (`home.json`, `about-us.json`, `contact-us.json`,
    `privacy-policy.json`, `terms-conditions.json`, `thank-you.json`), then
    insert the imported template into the page. Replace the placeholder CF7
    shortcode IDs (`PROJECT_ENQUIRY_FORM_ID`, `GENERAL_CONTACT_FORM_ID`,
    `FOOTER_ENQUIRY_FORM_ID`) with the real form IDs from step 5, swap
    placeholder images/logos/copy for real content, and set the Thank You
    page's slug to `/thank-you/` (the CF7 redirect script in
    `inc/cf7-integration.php` targets that URL). **Privacy Policy and Terms
    and Conditions ship with placeholder legal text — have a lawyer review
    and finalize both before launch.**
7b. Services Listing, Service Detail, Portfolio Listing, Case Study Detail,
    Careers Listing, Career Detail, Blog Listing and Blog Detail render
    automatically from the PHP templates in `templates/`, `index.php` and
    `single.php` the moment content exists (no page needs to be created for
    them — they're CPT/post archive and singular templates). If Elementor
    Pro is licensed, rebuild them natively in the Theme Builder instead by
    following `elementor-templates/THEME-BUILDER-GUIDE.md` — Elementor's own
    Theme Builder template then takes priority automatically, no theme code
    changes needed.
8. Configure SMTP, reCAPTCHA/Turnstile, Rank Math/Yoast, and GA4/GTM from
   their own plugin settings screens.
8a. Settings → Reading: set "Your homepage displays" to **A static page**,
    choose the Home page for Homepage and create/choose a "Blog" page for
    Posts page (this is what makes `index.php` render at `/blog/` as the
    Blog Listing rather than at the site root).
9. Add real content: Services, Case Studies, Careers, Testimonials, Blog
   posts. (Portfolio/Careers listing pages need no separate WP Page — see 7b.)
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
- **Elementor JSON vs. PHP fallback templates**: Home/About/Contact are
  static content, so they're shipped as real, importable Elementor JSON
  (works in free Elementor). Services Listing/Service Detail/Blog
  Listing/Blog Detail need to loop over posts or bind to a different post's
  ACF fields on every page load — that requires Elementor Pro's Theme
  Builder, which can't be authored as JSON and verified without a live site
  to import it into. Those four ship as working PHP templates instead, with
  a widget-by-widget Theme Builder rebuild guide for when Pro is available
  (`elementor-templates/THEME-BUILDER-GUIDE.md`). All PHP templates share
  the same `template-parts/page-banner.php` (dark gradient + breadcrumb + H1)
  and `template-parts/bottom-cta-banner.php` (the one reusable "Ready to
  grow?" component) so the JSON pages and PHP templates read as one system.
- **Brand palette**: extracted from the DevHems Technology logo and set as
  CSS custom properties in `style.css` (`--dh-color-primary` #1E56E0 blue,
  `--dh-color-secondary` #0A1B3D navy, `--dh-color-accent` #3E8EFF sky,
  `--dh-color-cta` #FF9F1C amber reserved for primary CTA buttons) and
  Manrope/Inter fonts, enqueued from Google Fonts in `inc/enqueue.php`.
  Mirror the same 5 colors and 2 fonts into **Elementor → Site Settings →
  Global Colors/Fonts** so Elementor-built sections (Home/About/Contact and
  anything built in the Theme Builder) match the PHP-templated pages exactly.
- **CPT UI plugin**: not needed and shouldn't be installed — Services, Case
  Studies, Careers and Testimonials are already registered as Custom Post
  Types in code (`inc/post-types-*.php`), which is more durable than a
  plugin-based registration (survives theme/plugin changes, lives in
  version control). Installing CPT UI risks a duplicate/conflicting
  registration of the same post type slugs.
- **"Get Free Consultation" modal**: built without Elementor Pro's Popup
  Builder. `inc/consultation-modal.php` renders the modal markup once in
  `wp_footer`; `assets/js/modal.js` toggles it open for any element with the
  class `devhems-open-modal` (the `[devhems_consultation_trigger]` shortcode
  button already carries it — use that shortcode for the header CTA and any
  other "Start Your Project"/"Get Free Consultation" button instead of a
  plain link).
- **Case study tabs & testimonials**: the Home page JSON uses Elementor's
  native **Tabs** and **Testimonial** widgets — both are free, not
  Pro-only — for the metric-driven case-study tabs and client quotes.
