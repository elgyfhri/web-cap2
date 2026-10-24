# CAP-2 Project Rules

## Product

CAP-2 is Champion Ambassador Project Fase 2. Public users scan QR codes at participant booths to view participant profiles and projects. Admin manages participants, activity documentation, and articles.

## Official terminology

- Use `Peserta` for people.
- Use `Cluster/Talent` for participant categories.
- Use `Project` for participant work.
- Avoid mixing `Champion`, `Ambassador`, `Inovator`, and `Pionir` in public UI unless source content requires it.
- Brand text: `CAP-2` with subtitle `Talenta Bogor`.

## Design system

Reference files:

- `code3.html`: homepage, hero, gallery, participant highlights, clusters, articles, collaboration CTA.
- `code.html`: participant directory, search, filters, cards.
- `code2.html`: articles and activity documentation.
- `code4.html`: participant detail page.

Use Plus Jakarta Sans and Material Symbols Outlined.

Colors:

- `primary`: `#006948`
- `primary-container`: `#00855d`
- `secondary-fixed`: `#6ffbbe`
- `surface`: `#faf8ff`
- `surface-container`: `#eaedff`
- `surface-container-low`: `#f2f3ff`
- `surface-container-lowest`: `#ffffff`
- `on-surface`: `#131b2e`
- `on-surface-variant`: `#3d4a42`
- `outline`: `#6d7a72`
- `outline-variant`: `#bccac0`

Layout rules:

- Use max-width containers around `1360px`.
- Use rounded `xl` buttons and `2xl` cards.
- Use white cards on the light surface background.
- Use consistent spacing: `gap-4`, `gap-5`, `gap-6`, `gap-8`.
- Use subtle borders and shadows; stronger shadow only on hover.
- Use `object-cover` and fixed image heights for card images.
- Every image needs useful `alt` text and a fallback when empty.
- Keep cards equal height; put bottom actions in a flex footer with `mt-auto`.
- Use `line-clamp-2` for titles and `line-clamp-3` for summaries.
- Keep desktop grids aligned and responsive: 1 column mobile, 2 tablet, 3 desktop where appropriate.

## Public pages

- Homepage: `resources/views/public/home.blade.php`
- Directory: `resources/views/public/ambassadors.blade.php`
- Participant detail: `resources/views/public/ambassador.blade.php`
- Article list: `resources/views/public/articles.blade.php`
- Article detail: `resources/views/public/article.blade.php`
- Public layout: `resources/views/layouts/app.blade.php`

Public pages must share navbar, typography, colors, button styles, card styles, image behavior, footer, and responsive rules.

## Admin pages

- Admin layout: `resources/views/layouts/admin.blade.php`
- Admin uses a separate dark sidebar and light content area.
- Admin navigation must not use the public navbar.
- Admin can add, edit, delete, publish, and unpublish participants and articles.
- Keep admin tables responsive with status badges and clear actions.
- Preserve validation errors and success messages.

## Participant data

Participant records use:

- participant code, for example `CAP2-001`
- name
- city
- cluster/talent
- project title
- project summary
- project detail
- participant image
- project documentation
- publish status

Current dummy data comes from `database/seeders/ContentSeeder.php` and contains 25 CAP2 participants.

## QR requirement

Each published participant must have a public detail URL using the participant slug. QR codes must point to that URL and must not expose unpublished records.

## Admin content

Activity documentation needs title, description, image, date/order, and publish status. Articles need category, cover image, title, excerpt, body, publication date, and publish status.

## Code rules

- Prefer existing Laravel, Blade, Tailwind CDN, and installed dependencies.
- Keep diffs small and avoid unnecessary abstractions.
- Do not add comments unless requested.
- Do not hardcode public participant data in views; use models and seeders.
- Do not use placeholder `href="#"` for working actions.
- Validate all admin input.
- Never expose secrets, credentials, or debug details in public pages.
- Run `php artisan view:cache` after Blade changes.
- Run PHP syntax checks and relevant route checks after controller, route, or seeder changes.
