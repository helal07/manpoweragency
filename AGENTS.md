# AGENTS.md
Standing instructions for any AI agent (Antigravity) working in this workspace.
Read this file fully before starting any task. Follow `PRD.md` for feature scope.

## Project
Laravel website for an overseas manpower recruitment company. See `PRD.md` for full requirements. Build in the milestone order listed at the bottom of the PRD unless told otherwise.

## Stack & Conventions
- Framework: **Laravel** (latest LTS stable version at time of setup).
- Templating: **Blade** (+ Livewire is fine for interactive pieces like filters/forms; do not introduce a separate SPA frontend unless asked).
- Admin panel: **Filament** — build all CRUD (settings, hero banners, leaders, services, clients, job circulars, notices) as Filament Resources.
- Auth: **Laravel Breeze** for both Admin and Applicant login, separated by guard (`admin` guard for staff, default `web`/`applicant` guard for job seekers). Do not mix admin and applicant into a single undifferentiated users table — keep them distinct (either separate tables or a `role` column with route-level guard middleware, agent should pick the cleaner one and note the choice in a commit message).
- Media/uploads (logo, banners, leader photos, client logos, notice/circular attachments): use **spatie/laravel-medialibrary**.
- Roles/permissions: **spatie/laravel-permission** if more than one admin role is needed.
- Styling: Tailwind CSS (Laravel's default scaffolding). Keep design clean/corporate — this is a trust-and-credibility-driven brand (manpower/recruitment agency), avoid flashy/startup-style design; prefer a professional, formal look.
- Database: MySQL.
- Follow Laravel naming conventions (singular model, plural table, PascalCase model, snake_case columns).

## Non-negotiables
1. **Site name and logo must be dynamic.** Never hardcode the company name or logo path in Blade views — always pull from the `settings` model/table via a shared view composer or config-cache-style helper, so changing it in the admin panel updates it everywhere (nav, footer, favicon, page titles) without a code deploy.
2. Every content block described in the PRD's "Home Page" section (Hero, Organizers, Services, About teaser, Clients, Footer) must be editable from the admin panel — no hardcoded marketing copy in Blade files.
3. Job Circulars must support a **deadline/expiry** and reflect open/closed status automatically (e.g. via a scope/accessor comparing `deadline` to `now()`), not just a manual toggle.
4. Keep applicant-only actions (like "Apply") gated behind the applicant auth guard; unauthenticated visitors should be redirected to Applicant Login, not blocked with a generic 403.
5. Do not add payment processing, CV-upload/application-tracking, or multi-language support unless explicitly asked — these are flagged as open questions / phase 2 in the PRD. Ask before building them.

## Workflow expectations
- Use **Planning Mode** for any multi-file feature (e.g., "build the Job Circular module") — propose the plan (migrations, models, routes, views, Filament resource) before writing code.
- After scaffolding a module, seed demo/dummy data via a Seeder so the pages are viewable immediately (e.g., 3 sample leaders, 5 sample services, 6 sample clients, 3 sample job circulars, 2 sample notices).
- Run `php artisan migrate` and confirm no errors before moving to the next milestone.
- Write concise commit-style summaries of what changed after each milestone.
- If a requirement is ambiguous, check "Open Questions" in `PRD.md` first; if still unclear, ask the user rather than guessing on anything affecting data model or legal/compliance fields (e.g., NID/passport storage, license number).

## File/Folder hints
- Public site controllers: `app/Http/Controllers/Site/`
- Applicant auth controllers: `app/Http/Controllers/Auth/` (Breeze default, scoped to applicant guard)
- Filament resources: `app/Filament/Resources/`
- Models: `app/Models/`
- Public Blade views: `resources/views/site/`
- Shared layout partials (navbar/footer): `resources/views/layouts/` + `resources/views/partials/`
- Migrations: one per model, in build-order matching the PRD milestones.

## Do not
- Do not remove or rename the navigation items specified in the PRD (Home / About / Clients / Services / Job Circular / Notice / Applicant Login) without confirmation.
- Do not hardcode any leadership names, service names, or client names as placeholders in production Blade files — use seeders/database only, so the client can replace demo content from the admin panel.
