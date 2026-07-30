# Product Requirements Document (PRD)
## Overseas Manpower Recruitment Company — Corporate Website

**Version:** 1.0
**Date:** July 27, 2026
**Stack:** Laravel (PHP), Blade / Livewire or Laravel + Vue-React (optional), MySQL
**Target IDE:** Google Antigravity (agentic IDE)

---

## 1. Project Overview

### 1.1 Purpose
Build a corporate marketing + recruitment website for a **manpower / overseas employment agency**. The company recruits and sends workers abroad for jobs. The site must present the company as trustworthy and established, showcase leadership, publish job circulars and notices, and let applicants log in to view/apply for jobs.

### 1.2 Goals
- Present a professional, trust-building brand image (manpower agencies compete heavily on credibility).
- Publish and manage **Job Circulars** and **Notices** dynamically from an admin panel.
- Allow **Applicants** to register/login and view job details.
- Allow the company owner to change **site name/logo, hero banner, services, about content, clients, and leadership team** without touching code (full CMS-style admin panel).
- Make the **site logo dynamic** — replaceable from the backend/admin without a code deploy.

### 1.3 Out of Scope (v1)
- Full applicant workflow (CV upload, application tracking, payment) — only stub/login + job listing unless client confirms otherwise.
- Multi-language (Bangla/English) — flag as a **future consideration** (recommended given the industry, see Open Questions).
- Payment gateway integration.

---

## 2. Target Users / Roles

| Role | Description | Access |
|---|---|---|
| **Super Admin** | Company owner/staff managing content | Full admin panel |
| **Editor (optional)** | Staff who can post notices/circulars only | Limited admin panel |
| **Applicant** | Job seeker | Register/Login, view job circulars, view own profile |
| **Guest / Visitor** | General public | View all public pages, cannot see applicant-only content |

---

## 3. Information Architecture / Navigation

Top navigation bar (as specified):

1. **Home**
2. **About**
3. **Clients**
4. **Services**
5. **Job Circular**
6. **Notice**
7. **Applicant Login**

Logo/site name sits at the far left of the nav and is **fully dynamic** (pulled from `settings` table / admin panel) — including site name text, logo image, and favicon.

---

## 4. Page-by-Page Requirements

### 4.1 Home Page
Sections in order (top to bottom):

1. **Hero Section**
   - Large banner image/background (admin-uploadable, could support a slider of 3–5 images).
   - Overlaid heading + subheading text (editable from admin) — tone: authoritative, "we place skilled manpower abroad" type messaging.
   - Primary CTA button(s), e.g. "View Job Circulars" / "Contact Us" (editable label + link).
2. **Company Organizer / Leadership Section**
   - Cards for **Chairman**, **Managing Director**, **Director(s)** (supports multiple directors — repeatable).
   - Each card: photo, name, designation, short bio/message (editable), optional social/contact link.
3. **Services Section**
   - Grid/list of services the company offers (e.g., manpower supply by country/sector, visa processing support, training, documentation). Each service: icon, title, short description — all admin-manageable (add/edit/delete/reorder).
4. **About (summary) Section**
   - Short "About Us" teaser (mission/vision, years of experience, licenses/certifications, stats like "X workers placed", "X countries") with a "Read More" link to the full About page.
5. **Clients Section**
   - Logo grid / carousel of partner companies or destination-country employer clients (admin can add/remove/reorder logos + optional client name/link).
6. **Footer**
   - Company contact info (address, phone, email — dynamic from settings), quick links (mirrors nav), social media icons, license/registration number (common requirement for manpower agencies, e.g., BMET/RL license no. in Bangladesh), copyright line.

### 4.2 About Page
- Full company history/story (rich text, admin-editable).
- Mission, Vision, Core Values.
- License / government registration details (important for credibility in this industry).
- Optionally repeat leadership section in more detail (or link to same data source as home page organizer section).
- Optional: milestones/timeline, gallery.

### 4.3 Clients Page
- Full grid of all client logos/names with pagination if needed.
- Optional filter by country/sector.
- Each client is a manageable record (name, logo, website link, description).

### 4.4 Services Page
- Full list of services with detailed descriptions (expands on home page teaser).
- Optionally group by category (e.g., "Skilled Labor", "Domestic Worker Placement", "Documentation & Visa Support").
- Each service should support its own detail view/slug if the client wants SEO-friendly service pages (recommend this).

### 4.5 Job Circular Page
- List of active job circulars (title, country, position, deadline, vacancy count, salary range, posted date).
- Filters: by country, by category/sector, by status (open/closed).
- Detail page per circular: full requirements, salary, benefits, deadline, "how to apply" instructions, downloadable PDF (optional), Apply button (visible/enabled only to logged-in Applicants — or redirects to login).
- Admin CRUD: create/edit/delete/publish/unpublish/close circular, set expiry date (auto-mark as closed after deadline).

### 4.6 Notice Page
- Simple chronological notice board (title, date, short description, optional attached file/PDF, optional "important/pinned" flag).
- Admin CRUD for notices.
- Detail view per notice if content is long.

### 4.7 Applicant Login / Register
- Applicant registration (name, email, phone, password, maybe NID/passport number depending on legal requirements — confirm with client).
- Login / Forgot password / Reset password (standard Laravel auth, e.g. Laravel Breeze or Jetstream).
- Applicant Dashboard (v1 minimal): view profile, view list of job circulars, view/download applied circulars. (Full "Apply with CV upload" flow can be phase 2 — flag as **Open Question** below.)

### 4.8 Admin Panel (not in nav, but required)
Recommend using **Laravel Filament** (fast, modern admin panel package) or a custom Blade admin. Should manage:
- **Site Settings**: site name, logo (upload), favicon, contact info, social links, footer text.
- **Hero/Banner**: image(s), heading, subheading, CTA text/link, ordering (if slider).
- **Leadership/Organizers**: CRUD (name, designation, photo, bio, order).
- **Services**: CRUD (title, icon/image, description, order, optional slug page).
- **About Content**: rich text editor for About page body, mission/vision/values.
- **Clients**: CRUD (name, logo, link).
- **Job Circulars**: CRUD + status + expiry.
- **Notices**: CRUD + attachments.
- **Applicants**: list/view registered applicants.
- **Users/Roles**: manage admin/editor accounts and permissions.

---

## 5. Functional Requirements Summary

| # | Requirement | Priority |
|---|---|---|
| FR1 | Dynamic site name + logo managed from admin, reflected across all pages/nav/footer/favicon | Must |
| FR2 | Hero section with editable banner image(s) + text + CTA | Must |
| FR3 | Leadership/organizer profiles (Chairman, MD, Director) manageable, unlimited entries | Must |
| FR4 | Services list manageable (CRUD) | Must |
| FR5 | About content manageable via rich text | Must |
| FR6 | Clients/logo grid manageable | Must |
| FR7 | Job Circular CRUD with filtering, status, expiry | Must |
| FR8 | Notice board CRUD with optional attachments | Must |
| FR9 | Applicant registration/login/password reset | Must |
| FR10 | Applicant dashboard (view circulars, basic profile) | Should |
| FR11 | Full "Apply" flow with CV upload & tracking | Could (Phase 2) |
| FR12 | Role-based admin access (Super Admin / Editor) | Should |
| FR13 | SEO-friendly URLs (slugs) for services, job circulars, notices | Should |
| FR14 | Responsive design (mobile-first) | Must |
| FR15 | Contact form on About/Footer sending email to admin | Should |

---

## 6. Non-Functional Requirements

- **Performance**: Home page should load fast; use image optimization for hero banners.
- **Responsive/Mobile-first**: Majority of applicants likely browse via mobile.
- **SEO**: Meta title/description per page (manageable from admin), clean URLs, sitemap.xml, OG tags for social sharing of job circulars.
- **Security**: Standard Laravel auth hashing, CSRF protection, admin panel behind auth + role middleware, rate-limit login attempts.
- **Accessibility**: Reasonable color contrast, alt text fields for all images (logo, banners, leadership photos).
- **Maintainability**: Clean MVC structure, seeders for demo data, documented `.env.example`.

---

## 7. Suggested Data Model (simplified)

- `settings` (key-value or single row: site_name, logo_path, favicon_path, phone, email, address, social links, footer_text)
- `sliders` / `hero_banners` (id, image, heading, subheading, cta_text, cta_link, order, is_active)
- `leaders` (id, name, designation [enum: chairman/managing_director/director/other], photo, bio, order, is_active)
- `services` (id, title, slug, icon, short_desc, body, order, is_active)
- `about_content` (single row or key-value: mission, vision, values, history_body, license_no)
- `clients` (id, name, logo, website_url, order, is_active)
- `job_circulars` (id, title, slug, country, category, vacancy, salary_range, deadline, status[open/closed], body, attachment, published_at)
- `notices` (id, title, slug, body, attachment, is_pinned, published_at)
- `users` (standard Laravel users table — used for Admin/Editor)
- `applicants` (id, user_id or standalone: name, email, phone, password, nid/passport [confirm legality], created_at)
- `roles` / `permissions` (if using Spatie Laravel-Permission package)

---

## 8. Recommended Laravel Packages

| Need | Package |
|---|---|
| Admin panel | **Filament** (`filament/filament`) — fast CRUD scaffolding |
| Auth (applicant + admin) | **Laravel Breeze** or **Laravel Fortify** |
| Roles/Permissions | **spatie/laravel-permission** |
| Image handling | **spatie/laravel-medialibrary** (great fit for logo, banners, leader photos, client logos) |
| SEO meta | **artesaos/seotools** (optional) |
| Rich text editor (About/Services body) | Filament's built-in rich editor, or TipTap |
| Slugs | **spatie/laravel-sluggable** |

---

## 9. Open Questions (confirm with stakeholder before/while building)

1. Does "Applicant Login" need a **full application/CV submission workflow** in v1, or is v1 just "register, browse, view details" (with actual applying done offline/via phone/office visit)?
2. Should the site support **Bangla + English** (very common for this industry/audience)?
3. Is a **government license/registration number** (e.g., recruiting license) required to be displayed for legal compliance?
4. Do Directors need to support **multiple entries** (e.g., 3 directors) — assumed yes, confirm.
5. Should Job Circulars be **downloadable as PDF/image** (many agencies post circulars as scanned images/PDFs rather than structured text)?
6. Contact form — where should submissions go (email, stored in DB + admin inbox, or both)?
7. Do you need a **testimonials** section (successful placed workers) — not currently in your list, flagging as a common addition for this industry.

---

## 10. Milestones (suggested build order for the AI agent)

1. Laravel project scaffold + auth (Breeze) + database + `.env` setup.
2. Site `settings` table/model + admin ability to change logo/site name (dynamic branding — do this early since it touches every layout file).
3. Public layout: navbar (dynamic logo) + footer (dynamic contact info).
4. Home page: Hero → Organizers → Services → About teaser → Clients → Footer.
5. About, Clients, Services full pages.
6. Job Circular module (model, admin CRUD, public list/detail, filtering).
7. Notice module (model, admin CRUD, public list/detail).
8. Applicant auth (register/login/dashboard).
9. Admin panel wiring (Filament resources for all above).
10. Polish: SEO meta, responsive QA, seed demo data, deploy instructions.
