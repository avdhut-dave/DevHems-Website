# Building the dynamic templates in Elementor Pro Theme Builder

`home.json`, `about-us.json` and `contact-us.json` in this folder are
ready-to-import Elementor **Page** templates (Templates → Saved Templates →
Import Templates) — they work with the free version of Elementor since
their content is static/editable, not looped from a query.

**Services Listing, Service Detail, Blog Listing and Blog Detail are
different**: they need to loop over posts (Services Listing/Blog Listing)
or pull a different post's data on every page (Service Detail/Blog Detail).
That requires Elementor Pro's Theme Builder + the Posts/Loop Grid widgets
and ACF dynamic tags. Hand-authoring that JSON without a live site to test
the import against risks shipping a template that silently fails to bind —
so instead, this repo ships:

- A fully working **non-Pro fallback** for all four (PHP templates already
  wired into the theme — see the table below). These render correctly with
  only free Elementor/ACF/CF7 active, using the same CSS design tokens and
  component classes as the three Elementor JSON pages above.
- This guide, so that once Elementor Pro is licensed, the same four pages
  can be rebuilt natively in the Theme Builder, widget by widget.

| Page | Non-Pro fallback (works today) | Elementor Pro Theme Builder location |
|---|---|---|
| Services Listing | `templates/archive/archive-service.php` | Templates → Theme Builder → Add New → **Archive** → Condition: Archive → Service |
| Service Detail | `templates/single-service.php` | Templates → Theme Builder → Add New → **Single** → Condition: Singular → Service |
| Blog Listing | `index.php` | Templates → Theme Builder → Add New → **Archive** → Condition: Archive → Blog / Posts |
| Blog Detail | `single.php` | Templates → Theme Builder → Add New → **Single** → Condition: Singular → Post |

Once a matching Theme Builder template is published, Elementor automatically
takes priority over the PHP fallback for that page type — no theme code
needs to change or be removed.

## Services Listing (Archive → Service)

1. **Loop Grid** widget → Post Type: `Service` → Query: All, or filtered by
   `service_category` taxonomy using the widget's Query > Include controls.
2. Loop item template: Image (dynamic: Featured Image) → Heading (dynamic:
   Post Title) → Text Editor (dynamic: ACF Field → `short_description`) →
   Button (dynamic: Post URL) labeled "Learn More".
3. Above the grid: Heading ("Our Services") + Text Editor intro paragraph,
   both static.
4. Optional category filter: Elementor Pro's Loop Grid supports a
   taxonomy filter bar widget, or link a manual Nav Menu built from
   `service_category` terms above the grid.
5. Below the grid: reuse the "Final CTA" section pattern from `home.json`
   (copy that container via Templates → Saved Templates → Export/Import,
   or copy-paste between the two templates in the editor).

## Service Detail (Single → Service)

Build sections in this order (matches `templates/single-service.php` 1:1):

1. Breadcrumbs — Shortcode widget: `[devhems_breadcrumbs]`.
2. Hero — Heading (dynamic: Post Title) + Text Editor (dynamic: ACF Field →
   `hero_subtitle`).
3. Overview — Text Editor (dynamic: Post Content, "the_content").
4. Business Problems Addressed / Services Included — ACF Repeater fields
   (`problems_addressed`, `services_included`): use a **Loop Grid** widget
   with Post Type = Current Query and an ACF Repeater dynamic tag, or
   Elementor Pro's native "ACF Repeater" dynamic tag support inside a Loop
   Grid bound to the repeater rows.
5. Benefits — same Loop Grid + ACF Repeater pattern for the `benefits`
   repeater (icon, title, description sub-fields).
6. Work Process — same pattern for `process_steps`.
7. Technologies — same pattern for `technologies`.
8. Relevant Case Studies — Loop Grid, Post Type: Case Study, Query source:
   ACF Relationship Field → `related_case_studies`.
9. Why Choose DevHems — Shortcode widget wrapping a Saved Template
   (`[elementor-template id="X"]`) built once and reused across Home,
   About, Service Detail and Case Study Detail.
10. FAQs — native **Accordion** widget, populated per-service via ACF
    Repeater → Loop Grid (or a Pro dynamic-content Accordion binding).
11. Related Services — Loop Grid, Post Type: Service, Query source: ACF
    Relationship Field → `related_services`.
12. Service Enquiry Form — Shortcode widget:
    `[devhems_service_enquiry_form]` (this theme's wrapper auto-fills the
    "Interested Service" field with the current service's title — do not
    use Elementor's own CF7 widget here, since it can't do that binding).
13. Final CTA — same reusable CTA pattern as elsewhere.

## Blog Listing (Archive → Post)

Same pattern as Services Listing: Loop Grid, Post Type: Post, item template
= Featured Image + Category (dynamic: Post Info → Terms) + Title + Excerpt
+ "Read More" button. Add a category filter nav above it built from the
`category` taxonomy.

## Blog Detail (Single → Post)

1. Breadcrumbs — `[devhems_breadcrumbs]`.
2. Hero — Post Info widget (category, author, date) + Heading (dynamic:
   Post Title) + Featured Image.
3. Content — Text Editor (dynamic: Post Content).
4. Author Box — Author Box widget (Pro) or a manual container with
   Image (dynamic: Author Avatar) + Text (dynamic: Author Bio).
5. Related Posts — Loop Grid, Post Type: Post, Query: Related by Category.
6. Final CTA + Comments — same reusable CTA pattern; native WordPress
   Comments widget if comments are enabled.

## Reusable components referenced above

Build each of these once as an Elementor **Saved Template** (type: Section
or Container) so every page inserts the same instance via
`[elementor-template id="X"]`, matching the "reusable Elementor components"
requirement:

- Final CTA section
- Service card / Blog card / Case study card
- Testimonial card
- FAQ accordion
- Why Choose DevHems block
- Service/Career enquiry form wrapper

## Widgets that need Elementor Pro (flagged as requested)

The Home page JSON in this repo deliberately avoids these — it uses a
single static hero and Elementor's free Tabs/Testimonial widgets instead —
so the site works fully on free Elementor. Add these only once Pro is
licensed:

| Feature | Free Elementor alternative shipped | Pro widget to upgrade to |
|---|---|---|
| Hero image/video **slider** (2–3 rotating slides) | Single static hero (`home.json`) | **Slides** widget |
| Header "Get Free Consultation" **popup modal** | Custom modal, no Pro needed — `inc/consultation-modal.php` + `[devhems_consultation_trigger]` | Popup Builder (optional alternative; not required) |
| Case study **tabs** | Already free — Elementor's native **Tabs** widget (`home.json`) | — (no upgrade needed) |
| Testimonial **carousel** | Static Testimonial widgets side by side (`home.json`) | **Testimonial Carousel** widget |
| Blog **slider** on Home | Elementor's native **Posts** widget in grid layout, or `index.php`'s grid | **Loop Carousel** widget |
| Sticky **Table of Contents** on Blog Detail | Not built — add manually per post via `the_content` headings, or a lightweight TOC plugin (e.g. "Table of Contents Plus") | **Table of Contents** widget (auto-generates + sticky) |
