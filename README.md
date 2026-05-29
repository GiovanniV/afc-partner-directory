# AFC Partner Directory

## Overview

AFC Partner Directory is a custom WordPress plugin for managing and displaying partner organizations. It provides a maintainable admin workflow, a dynamic Gutenberg directory block, public partner profile pages, and a normalized REST API surface for integrations.

The repository includes a **DDEV** local environment, the plugin source, a lightweight demo theme for branded presentation, and helper scripts so reviewers can run the project reproducibly.

Another developer or reviewer can clone the repository, start DDEV, run the setup scripts, and immediately test the full WordPress plugin experience—admin workflow, frontend directory, partner profiles, and REST API—without manual WordPress configuration or hand-entered demo content.

**Included in this submission:**

- Custom `partner` post type with structured metadata
- Admin meta box (logo, website URL, category)
- Dynamic Gutenberg block (`afc/partner-directory`)
- Partner profile pages at `/partners/{slug}/`
- REST field extensions on the core `partner` endpoint
- AFC-inspired branded demo shell (header, hero, footer)
- Local DDEV setup scripts

## Features

- **Custom post type:** `partner` with title, editor, featured image, and archive
- **Metadata:** website URL, category label, logo attachment ID
- **Admin UI:** WordPress meta box, Media Library logo picker, secure save handling
- **Frontend:** Server-rendered partner directory grid with responsive layout
- **Profile pages:** Internal permalinks; external website link on profile only
- **REST API:** Enriched `wp/v2/partner` responses with `afc_partner`, `logo_url`, `featured_image_url`
- **Demo theme:** `afc-partner-shell` delegates branded chrome to plugin templates
- **Local dev:** DDEV (PHP 8.3, MariaDB), WP-CLI, optional npm build for block assets

## Local development setup

### Local environment bootstrap

The repository separates two concerns:

```text
Application logic
  → WordPress plugin: CPT, metadata, admin UI, REST, Gutenberg block, profiles, frontend shell

Local bootstrap layer
  → DDEV + WP-CLI scripts to spin up and demonstrate the project quickly
```

Within the bootstrap layer:

```text
Docker / DDEV          → reproducible local WordPress runtime
WP-CLI scripts         → repeatable setup automation (install, activate, flush)
Seed script            → predictable demo content for reviewers and developers
GitHub Actions / CI    → validation pipeline for builds, linting, packaging (planned)
```

| Layer | Role in this repo |
|-------|------------------|
| **DDEV** | Standardized PHP, MariaDB, and nginx-fpm environment |
| **`scripts/setup-local.sh`** | WordPress install (if needed) and plugin activation |
| **`scripts/activate-demo-theme.sh`** | Demo theme + front page routing for the directory |
| **`scripts/seed-demo-partners.sh`** | Stable six-partner demo dataset (idempotent; `--fresh` reset) |
| **Production** | **WordPress Admin → Partners** — not the seed script |
| **CI/CD (future)** | Repository validation via GitHub Actions (build, PHPCS, asset compile, packaging) |

The seed script is part of the **local bootstrap workflow**, not application runtime logic. It provisions demo partner posts for local review only. In production, partner records would be created and maintained through the WordPress admin.

`./scripts/seed-demo-partners.sh --fresh` is how a reviewer or developer gets a **clean demo dataset locally**—not how production users manage data. The script is **idempotent**; **`--fresh`** resets seeded demo records before walkthroughs without duplicates.

In CI/CD, the same repository can be validated through automated checks for build integrity, PHP standards, asset compilation, and packaging (see [What I would improve with more time](#what-i-would-improve-with-more-time)).

### Requirements

- [Docker](https://docs.docker.com/get-docker/) (Desktop or compatible runtime)
- [DDEV](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/) 1.22+
- [Git](https://git-scm.com/)
- [Node.js](https://nodejs.org/) 18+ and npm (only if rebuilding the Gutenberg block)

### Quick start (canonical reviewer setup)

```bash
git clone https://github.com/GiovanniV/afc-partner-directory.git
cd afc-partner-directory

export DDEV_NONINTERACTIVE=true   # optional; avoids interactive DDEV prompts in scripts

ddev start
./scripts/setup-local.sh
./scripts/seed-demo-partners.sh --fresh
./scripts/activate-demo-theme.sh   # creates Partner Directory Demo page, sets homepage, flushes rewrites
```

`activate-demo-theme.sh` provisions the demo page with the `afc/partner-directory` block and sets it as the front page. Run `./scripts/seed-demo-partners.sh --fresh` before or after theme activation; partners must be seeded for cards to appear.

Then open http://afc-partner-directory.ddev.site/

**Expected demo state:**

- 6 partner cards
- 6+ hero metric
- 6 published REST partners
- No duplicate demo records
- No test-named partners
- Partner profile routes working (`/partners/{slug}/`)

For day-to-day development after the first setup, `./scripts/seed-demo-partners.sh` (without `--fresh`) updates existing demo partners without duplicating them.

**Manual equivalent:**

```bash
ddev start
ddev wp core download --locale=en_US   # if WordPress is not installed yet
ddev wp core install \
  --url="https://afc-partner-directory.ddev.site" \
  --title="AFC Partner Directory (Local)" \
  --admin_user=admin \
  --admin_password=admin \
  --admin_email=admin@example.com \
  --skip-email
ddev wp plugin activate afc-partner-directory
ddev wp theme activate afc-partner-shell
```

### Demo page

1. In wp-admin, go to **Pages → Add New**.
2. Title the page (e.g. “Partner Directory Demo”).
3. Insert the **Partner Directory** block (`afc/partner-directory`).
4. Publish and open the page on the front end.

If you already have a published page containing the block (e.g. `/partner-directory-demo/`), use that URL.

## Demo data seeding

The seed script is part of the **local bootstrap workflow**, not application runtime logic. It provisions demo partner posts for local review only. In production, partner records would be created and maintained through the WordPress admin (**Partners**).

### Start the local environment

```bash
ddev start
```

### Seed demo partners

```bash
./scripts/seed-demo-partners.sh
```

This creates or updates the six demo partners used by the Partner Directory. The script is **safe to run multiple times**: it uses stable partner slugs and updates existing seeded records instead of creating duplicates.

### Reset demo partners

```bash
./scripts/seed-demo-partners.sh --fresh
```

This removes existing seeded demo partner records (identified by `_afc_partner_seeded_demo` and known demo slugs) and recreates a clean dataset. Use this before a live walkthrough for a predictable state.

### Verify seeded partners

```bash
ddev wp post list --post_type=partner --post_status=publish --format=table
```

Expected result — six published partners:

- Arizona Scholarship Alliance
- Community Foundation for Education
- DonorBridge Giving Platform
- EdChoice Resource Center
- Florida School Choice Network
- Nevada Education Partners

### Demo URLs

```text
http://afc-partner-directory.ddev.site/
http://afc-partner-directory.ddev.site/partners/arizona-scholarship-alliance/
http://afc-partner-directory.ddev.site/wp-json/wp/v2/partner?per_page=100&status=publish
```

### Recommended demo reset flow

Before a walkthrough, use the same sequence as [Quick start](#quick-start-canonical-reviewer-setup):

```bash
ddev start
./scripts/setup-local.sh
./scripts/seed-demo-partners.sh --fresh
./scripts/activate-demo-theme.sh
ddev wp rewrite flush
```

See [Seed script reliability and demo reset flow](#seed-script-reliability-and-demo-reset-flow) for idempotent vs `--fresh` behavior and verification steps.

### URLs (local defaults)

| Resource | URL |
|----------|-----|
| Site | https://afc-partner-directory.ddev.site |
| Admin | https://afc-partner-directory.ddev.site/wp-admin |
| Credentials | `admin` / `admin` (local only) |
| Partner archive | `/partners/` |
| Example profile | `/partners/arizona-scholarship-alliance/` (after seeding) |

### Block build (optional)

Committed files under `build/partner-directory/` allow the block to run **without** npm. Rebuild after changing block source:

```bash
cd wp-content/plugins/afc-partner-directory
npm install
npm run build
```

### Common commands

| Task | Command |
|------|---------|
| Start / stop | `ddev start` / `ddev stop` |
| WP-CLI | `ddev wp <command>` |
| Plugin status | `ddev wp plugin list` |
| Flush rewrites | `ddev wp rewrite flush` |
| Set demo as homepage | `ddev wp option update show_on_front page && ddev wp option update page_on_front 8` |

WordPress core is installed locally via WP-CLI and is **not** committed (see `.gitignore`).

### Troubleshooting

**Empty homepage (header/footer only, no directory):** Run `./scripts/activate-demo-theme.sh` or set the front page to the Partner Directory Demo page (see table above).

**Card count does not match REST or hero metric:** Run `./scripts/seed-demo-partners.sh --fresh` for a clean six-partner dataset, or list partners with `ddev wp post list --post_type=partner --post_status=any --format=table` and remove stray records manually. The directory block only queries `post_status => publish` (see `blocks/partner-directory/render.php`).

## Architecture

```
wp-content/plugins/afc-partner-directory/
├── afc-partner-directory.php      # Bootstrap, constants, module loader
├── includes/
│   ├── plugin.php                 # Hooks registration
│   ├── lifecycle.php              # Activation / deactivation (rewrite flush)
│   ├── helpers.php                # Paths, partner display data, brand logo
│   ├── cpt.php                    # CPT + register_post_meta
│   ├── admin.php                  # Meta box, save handler, admin assets
│   ├── rest.php                   # REST computed fields
│   ├── blocks.php                 # Block registration
│   ├── partner-profile.php        # Single partner rendering
│   ├── frontend-shell.php         # Branded shell wrap + assets
│   └── templates.php              # Template loader
├── blocks/partner-directory/      # Block source (JS, SCSS, render.php)
├── build/partner-directory/       # Compiled block assets (committed)
├── templates/
│   ├── single-partner.php
│   └── partials/                  # header, footer, hero, sections, profile
└── assets/                        # CSS, JS, brand image

wp-content/themes/afc-partner-shell/
├── style.css, functions.php
├── header.php / footer.php        # Delegates to plugin shell partials
└── index.php, page.php, single.php
```

**Separation of concerns:** Business logic, data, REST, and templates live in the **plugin**. The **theme** is a thin presentation wrapper so the demo can show AFC-inspired branding without turning the assessment into a full custom theme build.

## Data model

| Field | Storage | Notes |
|-------|---------|--------|
| Partner name | Post title | `post_type = partner` |
| Logo | Post meta `_afc_partner_logo_id` | Attachment ID; resolved to URL in REST and templates |
| Website URL | Post meta `_afc_partner_website_url` | Sanitized with `esc_url_raw` on save |
| Category | Post meta `_afc_partner_category` | Free-text label (not a taxonomy) |
| Body content | Post editor | Optional; shown on profile when present |
| Featured image | Post thumbnail | Fallback when no logo meta |

## REST API

There is **no** custom `/wp-json/custom/v1/partners` route. Partners are exposed through WordPress core REST for the CPT:

```http
GET /wp-json/wp/v2/partner?per_page=100&status=publish
GET /wp-json/wp/v2/partner/{id}
```

**Additional fields** registered in `includes/rest.php`:

| Field | Description |
|-------|-------------|
| `afc_partner` | Object: `website_url`, `category`, `logo_id` |
| `logo_url` | Resolved logo image URL |
| `featured_image_url` | Post thumbnail URL |

Published partners are readable without authentication, consistent with public directory data. For a production deployment, I would evaluate caching, filtering, and stricter permission boundaries depending on the integration.

### REST route decision

The assignment suggested a custom endpoint such as `/wp-json/custom/v1/partners`. I chose to expose partners through the native WordPress CPT REST endpoint, `/wp-json/wp/v2/partner`, and extend that response with registered computed fields.

For this scope, that kept the implementation smaller, easier to review, and aligned with WordPress conventions while still providing structured partner data for frontend or headless consumers.

In production, I would consider adding a versioned custom endpoint such as `/wp-json/afc/v1/partners` if the API needed a stable public contract, custom authentication, rate limiting, response shaping, or backward-compatible schema versioning.

See [docs/headless-consumer-example.md](docs/headless-consumer-example.md) for a minimal fetch example.

## Frontend display

- **Directory block:** Responsive grid (3 → 2 → 1 columns), partner cards with logo or initial fallback, category, and “View partner profile” CTA
- **Profile pages:** Branded hero, optional editor content, related partners (same category)
- **Demo shell:** Sticky header with mobile nav, hero metrics, institutional sections, footer (active when the shell theme is used and the directory block is on the page)

Card logos are decorative when a visible partner title is present; link labels use `aria-label` for screen reader context.

## Security considerations

Implemented in the current codebase:

- **Admin saves:** Nonce verification, autosave/revision guards, `edit_post` capability check
- **Input:** `esc_url_raw` / `sanitize_text_field` / `absint` with attachment type validation for logo IDs
- **Output:** `esc_html`, `esc_attr`, `esc_url` in templates; controlled REST field callbacks
- **Meta auth:** `auth_callback` on registered meta limits REST meta exposure appropriately for published content

Not implemented (documented as future work): automated security scanning, rate limiting, REST authentication for public reads.

## CI/CD, quality assurance, and production readiness

This assessment uses **DDEV** and **WP-CLI** as the operational layer described in [Local environment bootstrap](#local-environment-bootstrap). Automated CI (GitHub Actions, PHPCS, PHPUnit) is listed under [What I would improve with more time](#what-i-would-improve-with-more-time); the sections below document manual QA and demo reproducibility completed for submission.

### Final routing QA

A final automated QA pass was completed against the local DDEV site.

The pass verified:

- DDEV web and database services were running
- The `afc-partner-directory` plugin was active
- The `afc-partner-shell` theme was active
- The homepage renders the branded Partner Directory experience
- Partner cards link to internal `/partners/{slug}/` profile pages
- Individual partner profile pages return `200`
- The WordPress REST partner endpoint returns partner data
- The logo asset loads successfully
- The `/partners/` archive no longer displays a blank shell
- No PHP fatal errors were detected in rendered HTML

Two routing issues were identified and corrected:

1. The homepage was still using the default blog index, which produced a shell without the directory block.
2. The `/partners/` archive was rendering through a default loop without meaningful partner content.

The demo now sets the Partner Directory Demo page as the front page and redirects the `/partners/` archive to the branded directory experience.

### Seed script reliability and demo reset flow

During final QA, the demo seed workflow was reviewed for repeatability.

The original seed script could create duplicate partner records when run multiple times. That created a risk during local setup or live demo preparation because repeated seeding could cause the frontend directory, REST endpoint, and admin partner list to drift out of alignment.

The seed workflow was updated to be **idempotent**.

Running:

```bash
./scripts/seed-demo-partners.sh
```

now creates or updates the six intended demo partners using stable slugs. Existing partner records are updated instead of duplicated.

A fresh reset mode was also added:

```bash
./scripts/seed-demo-partners.sh --fresh
```

This mode deletes seeded demo records and recreates the six-partner demo dataset from a clean state.

Seeded demo records are marked using:

```text
_afc_partner_seeded_demo
```

The plugin defines this key as `AFC_PARTNER_META_SEEDED_DEMO` in `includes/cpt.php`. The seed script sets the same meta value via WP-CLI so demo-owned records can be identified during `--fresh` cleanup without affecting unrelated partner posts.

The final seeded dataset includes:

- Arizona Scholarship Alliance
- Community Foundation for Education
- DonorBridge Giving Platform
- EdChoice Resource Center
- Florida School Choice Network
- Nevada Education Partners

Final verification confirmed:

- Running the seed script multiple times keeps the published seeded partner count at **six**
- Running `--fresh` deletes and recreates the six demo partners
- The homepage renders six partner cards
- The hero metric displays **6+**
- The REST endpoint returns six published partners
- No duplicate demo partners are created

**Recommended demo preparation flow:** same as [Quick start](#quick-start-canonical-reviewer-setup) (`ddev start` → `setup-local.sh` → `seed-demo-partners.sh --fresh` → `activate-demo-theme.sh` → `rewrite flush`). This gives the reviewer a clean, predictable local demo state.

## Testing and verification

Manual checklist used for this submission:

- [ ] Create/edit partners in **Partners** admin; save logo, URL, category
- [ ] Confirm directory page renders **six** demo partner cards (count matches hero metric and REST)
- [ ] Open partner profile via card CTA (`/partners/{slug}/`)
- [ ] `curl` or browser: `GET /wp-json/wp/v2/partner?per_page=100&status=publish`
- [ ] Homepage `/` and `/partners/` (redirect) show the full directory
- [ ] Responsive check at ~390px, 768px, 1280px (no horizontal scroll)
- [ ] Card DOM: partner name appears once as visible heading
- [ ] Keyboard: mobile menu toggle, focus visible on links/buttons

## Tradeoffs and decisions

This project was scoped as a focused **4–6 hour engineering exercise**. I prioritized clean WordPress architecture, local reproducibility, secure metadata handling, a working REST surface, and a polished frontend demo over optional enhancements.

### Directory link behavior

The assignment asked for partner cards to include a link to the partner website. I implemented the primary card CTA as an internal “View partner profile” link and placed the external website CTA on the profile page.

This was a product/demo decision: it keeps reviewers inside the WordPress application, demonstrates that each partner is a real CPT record with its own permalink, and avoids sending users directly away from the local demo.

If strict spec compliance were preferred, this could be adjusted by either making the card CTA link directly to the website or adding a secondary “Visit website” link on each card.

| Decision | Rationale |
|----------|-----------|
| CPT + post meta | Fits WordPress conventions; avoids custom tables for this scope |
| Dynamic block + PHP render | Editor-friendly, server-rendered, no required client framework |
| Core REST + `register_rest_field` | Less code than a custom controller; still delivers a stable contract |
| Committed `build/` output | Reviewers can run without Node |
| Branded shell in plugin | Demonstrates product thinking without a full theme rebuild |
| No automated test suite | Time budget; manual verification documented above |

## What I would improve with more time

The current submission focuses on the core evaluation goals: a maintainable WordPress plugin, secure partner management, Gutenberg rendering, REST exposure, local reproducibility, and clear documentation.

With additional time, I would focus on production hardening and long-term extensibility:

- Add PHPUnit / WordPress integration tests and GitHub Actions CI to validate CPT registration, metadata saving, REST fields, and block rendering automatically.
- Add PHPCS with WordPress Coding Standards to enforce consistent formatting, escaping patterns, naming conventions, and review hygiene.
- Add category filtering to the directory and REST layer, likely moving from a simple category meta field to a `partner_category` taxonomy if categories become reusable editorial data.
- Add transient caching for partner directory queries and REST responses, with cache invalidation when partner records are created or updated.
- Expand Gutenberg block attributes for editor-controlled layout options, filtering, sorting, and empty-state messaging.
- Document a production deployment approach covering object cache, CDN usage, environment configuration, logging, backups, and REST hardening.
- Run a formal accessibility audit using keyboard testing, contrast validation, screen reader checks, and a WCAG checklist.

## AI Usage Notes

### Tools Used

I used ChatGPT and Cursor as senior development aids for planning, review, debugging, documentation, and implementation quality checks.

### How I Used AI

AI was used as a pair-programming and review aid, not as an autopilot. I used it to help plan the plugin architecture, review WordPress implementation patterns, organize the README, debug local setup issues, improve responsive layout decisions, and think through verification steps.

The final implementation decisions were made manually, with emphasis on WordPress conventions, security, maintainability, and reproducibility.

### What I Reviewed, Changed, or Rejected

I reviewed AI-generated suggestions before accepting them into the project. In several cases, I modified or rejected the initial output to better match WordPress standards and the actual project requirements.

Examples:

- I verified that the plugin used WordPress-native APIs for the partner custom post type, metadata registration, admin hooks, Gutenberg block registration, and REST extensions.
- I corrected card markup so partner names did not render redundantly in the DOM while still preserving accessible link labels.
- I reviewed the admin save flow to make sure nonce verification, autosave/revision guards, capability checks, and sanitization were present.
- I kept the REST documentation aligned with the actual implemented WordPress CPT endpoint instead of overstating a custom endpoint that was not shipped.
- I preserved the approved AFC-branded visual direction and logo assets instead of allowing AI-generated design suggestions to replace the intended brand presentation.
- I refined the seed workflow after discovering that repeated runs could create duplicate demo partners.

### How I Verified Correctness

I verified the project through manual local testing in DDEV and WordPress.

The verification included:

- Starting the DDEV environment from a clean setup.
- Running the setup, seed, and demo-theme activation scripts.
- Confirming the plugin and theme were active.
- Creating and reviewing partner records in the WordPress admin.
- Confirming the homepage rendered the branded partner directory.
- Confirming six seeded partners appeared consistently in the frontend, hero metric, admin list, and REST response.
- Opening individual `/partners/{slug}/` profile pages.
- Checking the WordPress REST partner endpoint in the browser.
- Flushing rewrite rules and confirming partner profile routing worked.
- Reviewing rendered HTML for duplicate content, broken links, and PHP fatal errors.

### AI Limitations or Mistakes

One useful example was the partner card markup. An early implementation either introduced or failed to catch duplicate partner-name rendering in the card output. The page looked acceptable visually, but DOM inspection showed repeated partner text. I corrected the render logic so the partner name appears once as the visible heading while the logo area remains decorative and the profile link has an appropriate accessible label.

Another example was documentation around the REST API. AI suggestions can sometimes drift toward generic or imaginary endpoints. I reviewed the actual implementation and documented the real API surface used by the project: the WordPress core CPT REST endpoint with additional registered fields.

### Security and Maintainability Review

I specifically reviewed AI-assisted code for common WordPress security issues:

- Nonce verification on admin saves.
- Capability checks before saving metadata.
- Autosave and revision guards.
- Sanitization of incoming values using functions such as `sanitize_text_field`, `esc_url_raw`, and `absint`.
- Output escaping in templates using `esc_html`, `esc_attr`, and `esc_url`.
- Attachment validation for logo IDs.
- Controlled REST field callbacks instead of exposing unrestricted raw metadata.

I also reviewed maintainability concerns such as separation of concerns across plugin modules, keeping business logic in the plugin rather than the demo theme, using repeatable setup scripts, and keeping the seed script idempotent for reviewers.

### Ownership Statement

AI helped accelerate planning, review, debugging, and documentation, but I treated all AI output as draft material. I reviewed, tested, corrected, and adapted the final code myself. I can explain the shipped implementation, the tradeoffs made, and the areas I would improve with more time.

## Project layout

```
.
├── .ddev/                          # Local environment configuration
├── docs/
│   └── headless-consumer-example.md
├── scripts/
│   ├── setup-local.sh
│   ├── activate-demo-theme.sh
│   └── seed-demo-partners.sh
├── wp-content/
│   ├── plugins/afc-partner-directory/
│   └── themes/afc-partner-shell/
└── README.md
```

## License

GPL-2.0-or-later (consistent with WordPress plugin distribution norms).
