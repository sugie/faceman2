# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this project is

Laravel 12 / PHP 8.4 portfolio project (`faceman2`) hosting several small independent web "診断" (diagnosis quiz) apps plus scratch demo pages. All UI text, comments and docs are Japanese.

Sub-apps mounted in `routes/web.php`:

| Route prefix | Feature | State |
|---|---|---|
| `/bikefit` | バイクジャンル診断 — full controller + model + service stack | Implemented |
| `/tyukosyaerabi` | 中古車選び診断 — DB schema + seed migrated, views are static closures only | Schema only |
| `/llc`, `/llc/search` | 生涯学習センター検索 — Livewire component with hard-coded demo dataset | Demo |
| `/test1`–`/test3` | Canvas / face drawing scratch pages | Scratch |

## Everything runs inside Docker

The app is **not** run with `php artisan serve`. `docker-compose.yml` defines four containers on network `fm2_network`; the repo root is bind-mounted at `/var/www/html` in `fm2_httpd` (Apache DocumentRoot `/var/www/html/public`).

| Container | Purpose | Host port |
|---|---|---|
| `fm2_httpd` | php:8.4-apache + Composer — run all artisan/composer/phpunit here | 6344 |
| `fm2_mysql` | MySQL (general log on, `utf8mb4`) | 6342 |
| `fm2_phpmyadmin` | phpMyAdmin | 6343 |
| `fm2_redis` | Redis | 6345 |

`vendor/` and `storage/framework/` are **named volumes**, not bind mounts — `composer install` run on the Windows host does not populate what the container sees, and vice versa. Run Composer inside `fm2_httpd`.

```bash
docker compose up -d
docker compose exec fm2_httpd composer install
docker compose exec fm2_httpd php artisan migrate
docker compose exec fm2_httpd php artisan test
docker compose exec fm2_httpd vendor/bin/pint          # PSR-12 formatting
docker compose logs -f fm2_mysql
```

Inside `fm2_httpd` the shell has aliases: `ms` (mycli to fm2_mysql), `msm` (mysql client), `llog` (tail the newest `storage/logs/laravel*.log`).

Frontend assets: `npm run dev` / `npm run build` (Vite 7 + Tailwind 4). Most views are **not** wired to Vite — `layouts/app.blade.php` and `bikefit/index.blade.php` inline their own `<style>` deliberately, and `public/` has no `build/`. Don't add `@vite` directives unless you are also building.

`.env` is gitignored and already present, pointing at `DB_HOST=fm2_mysql` / `DB_DATABASE=fm2_testing`. Host-side helper scripts `localhost_6344.*` just open Waterfox at the app URL.

## Testing

`phpunit.xml` registers only two suites, `tests/Unit` and `tests/Feature`. **`tests/Services/` is not in any suite** — `BikefitServiceTest` only runs if you name the path explicitly:

```bash
docker compose exec fm2_httpd php artisan test tests/Services/Bikefit/BikefitServiceTest.php
docker compose exec fm2_httpd php artisan test --filter=test_getBestOne
```

Test DB conventions come from `.junie/guidelines.md` and are deliberate:

- **Do not use `RefreshDatabase`** — considered too slow.
- Tests create their own data at start of the run and **do not clean up afterwards**; each test deletes the previous run's rows instead. Leftover rows are wanted for debugging.
- Consequence: **never run the DB-touching tests against production.**
- Test fixture files (CSV/XML) go under `tests/data/`.
- `phpunit.xml` sets `APP_ENV=oreore_testing` and `LARAVEL_ENV_PATH=.env.oreore_testing`. That env file is gitignored and not present in a fresh checkout — a template lives at `docker_settings/httpd/_env_oreore_testing` (its values are stale, from another project). `KEEP.s1103.phpunit.xml` is the older sqlite-in-memory variant, kept for reference only.

To avoid DB round-trips in unit tests, `BfWeight` exposes `setTestWeights()` / `resetTestWeights()` and `getDiagnostic()` accepts an injected answer collection.

## BikeFit architecture

Session-driven wizard; there is no auth. Anonymous visitors are identified by a `visitor_id` string generated with `uniqid('visitor_', true)`.

Flow across three controllers in `app/Http/Controllers/BikeFit/`:

1. `TopController::index` — on first visit, mints `visitor_id` into session (`TopController::VISITOR_SESSION_KEY`), creates a `bf_users` row and an empty `bf_diagnoses` row, stores the diagnosis id in `TopController::BIKEFIT_DIAGNOSIS_ID_KEY`, resets progress to 0.
2. `AnswerController::index/store` — one question per page. The ordered question id list is cached in session key `bikefit_question_id_list`; `AnswerController::PROGRESS_SESSION_KEY` is the index into it. Each POST writes a `bf_answers` row; when progress passes the end of the list it redirects to the result page.
3. `ResultController::show` — `BfWeight::getDiagnostic($diagnosis_id)` sums `bf_weights.score` per `genre_id` over the diagnosis's answers, `BikefitService::getBestOne()` picks the top genre, `BikefitService::getResultDescription()` returns its Japanese blurb.

Every controller re-checks the visitor session and redirects to `bikefit.index` when it is missing.

Data model (`app/Models/BikeFit/`, tables prefixed `bf_`, ER diagram in `docs/bikefit_er.md`): `bf_users` → `bf_diagnoses` → `bf_answers`, with `bf_questions` → `bf_options` → `bf_weights` → `bf_genres` supplying the scoring matrix, plus `bf_diagnosis_scores` and `bf_recommendations` (tables exist, not yet written to by the app).

**Questionnaire content lives in migrations, not seeders.** `database/seeders/` is empty of real data; `2025_10_26_010000_seed_bikefit.php` inserts questions/options/weights with hard-coded ids (questions 100, 200, …; options 1001, 1002, …; genres 8010–8150) and carries provenance comments pointing back to `docs/bikefit_questionnaire.xlsx`. Changing the questionnaire means editing that migration and re-migrating. `tyukosyaerabi` mirrors the same schema and seed pattern with a `te_` prefix.

Genre ids 8010–8150 are duplicated as a `switch` in `BikefitService` and as rows in the seed migration — keep them in sync.

## Conventions (from `.junie/guidelines.md`)

- PSR-12; Pint is the formatter.
- Gitflow. Feature branches are `feature/<機能概要>` (existing: `feature/create_bikefit_models`, `feature/shindan_function`, `feature/tyukosyaerabi`).
- Comments in Japanese. Error/log messages carry a short code identifying the site, e.g. `#RC31:`, `#BFT01:`, `#LLC01:` — follow this when adding logs.
- Variable names spell out the class rather than abbreviating; collections end in `_list` (not a plural `s`).
- No magic numbers — hoist to a named constant.

Note the codebase mixes `snake_case` locals (`$bf_progress`, `$question_id_list`) with `camelCase` (`$bfUser`, `$bestOneGenruId`); match the surrounding file.

## Non-obvious layout notes

- `DocumentRoot/` at the repo root is a stray copy of Bootstrap 5 CSS/JS — it is **not** the Apache document root (that is `public/`) and nothing currently references it.
- `docs/` holds the planning material (企画書, ER diagram, the questionnaire xlsx that the seed migration is generated from). `memo/` holds dated working notes, including the LLM prompts used to produce the genre description text.
