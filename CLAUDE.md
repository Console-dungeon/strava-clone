# AGENTS.md

Guidelines for AI agents working on this codebase. Read this before making any changes.

## Project Overview

Strava clone — fitness tracking application. Users log activities manually or import from Garmin Connect. Key features: dashboard with stats/charts, activity CRUD, Garmin integration, Polish/English i18n, dark mode.

**Repository:** https://github.com/Console-dungeon/strava-clone  
**Stack:** Laravel 12 (PHP 8.5) + Vue 3 + Inertia.js + PostgreSQL 18 + Tailwind CSS v4

---

## Essential Commands

### Development

```bash
# Start full-stack dev (Laravel + queue + Vite concurrently)
composer dev

```

### Linting & Formatting

```bash
# PHP
./vendor/bin/pint   # Format PHP (Laravel Pint)

# TypeScript/Vue
pnpm lint           # ESLint with auto-fix
pnpm format         # Prettier with auto-fix
pnpm format:check   # Check formatting (used in CI)
```

Pre-commit hooks run `pnpm lint-staged` automatically (Husky). DO NOT BYPASS WITH `--no-verify`.

---

## Architecture

### Backend (Laravel)

```
app/
├── Http/Controllers/    # Route handlers — thin, delegate to Services
│   └── Auth/            # Laravel Breeze auth controllers — do not modify
├── Http/Requests/       # Form request validation
├── Models/              # Eloquent: User, Activity
├── Services/            # Business logic: GarminService, DashboardService
└── Providers/
```

**Pattern:** Controllers stay thin — business logic goes in Services.

**Auth:** Laravel Breeze (login, register, password reset, email verification). Controllers in `app/Http/Controllers/Auth/`, pages in `resources/js/Pages/Auth/`. The auth system is complete — do not reimplement it.

**`ActivityController::main()`** — dedicated list view at `/activities/main` with pagination and type filtering (`?type=running`). Use this route/method rather than reimplementing.

**Inertia.js** bridges Laravel routes and Vue pages. Backend returns `Inertia::render('PageName', $props)`, not JSON. Do not create separate REST API endpoints unless specifically asked.

### Frontend (Vue 3 + TypeScript)

```
resources/js/
├── Pages/          # Inertia page components (map 1:1 to routes)
├── Components/     # Reusable Vue components
│   └── ui/         # Reka UI / shadcn-vue primitives — DO NOT edit
├── Layouts/        # Layout components (AppLayout, GuestLayout)
├── i18n/           # Translation files: pl.ts, en.ts, index.ts
└── app.ts          # Entry point
```

**Always use `<script setup lang="ts">`** — no Options API, no `defineComponent`.

Path alias `@/` maps to `resources/js/`.

**Available UI primitives** (`@/Components/ui/` — do not edit, extend/wrap instead): `Avatar`, `Button`, `Card`, `Chart`, `Dialog`, `DropdownMenu`, `Input`, `Label`, `NavigationMenu`, `Sonner`, `Spinner`, `Table`.

### Database

PostgreSQL 18 in Docker (`sail`/`password`/`strava_clone`). Migrations in `database/migrations/`.

When adding columns/tables: always write a new migration, never edit existing ones.

---

## Technology Conventions

### PHP / Laravel

- PHP 8.5 features are available (typed properties, match, fibers, etc.)
- Use Laravel 12 conventions — check official docs for newer APIs
- Eloquent relationships: `User` hasMany `Activity`; always scope activity queries to `auth()->user()`
- **Pobieranie ID zalogowanego użytkownika:** używaj `auth()->guard()->id()` (a dla obiektu — `auth()->guard()->user()`). To jest przyjęta w projekcie poprawna forma — nie zastępuj jej skrótem `auth()->id()` / `auth()->user()`.
- Form validation in `Http/Requests/` classes, not inline in controllers
- Queue driver: `database` (local), `sync` (tests)
- Session/cache driver: `database` (local), `array` (tests)

### Vue / TypeScript

- **Vue 3 Composition API only** — `<script setup lang="ts">`
- **TypeScript strict mode** — no `any`, type everything explicitly
- **Reka UI** for headless primitives (components in `@/Components/ui/`) — do not edit these
- **Tailwind CSS v4** — use utility classes; no custom CSS unless unavoidable
- **Tailwind Merge** (`twMerge`/`cn`) for conditional class merging
- **Lucide Vue** for icons — import from `lucide-vue-next`
- **Vue i18n** — use `$t('key')` in templates, `t('key')` in `<script setup>`. Add keys to both `resources/js/i18n/pl.ts` and `en.ts` (TypeScript exports, not JSON). Loaded via `resources/js/i18n/index.ts`
- **Vue Sonner** for toast notifications
- **Unovis** for charts (line charts on dashboard)
- **TanStack Vue Table** for data tables

### Routing

- Backend: `routes/web.php` and `routes/auth.php`
- Frontend URL generation: **Ziggy** (`route('name')`) — do not hardcode URLs
- Inertia links: use `<Link>` component, not `<a>` tags, for SPA navigation

### Styling

- Prettier config: single quotes, semicolons, 80 char width, 2-space indent
- EditorConfig: UTF-8, LF line endings, 4-space indent for PHP, 2-space for YAML/JSON
- Tailwind class order is enforced by `prettier-plugin-tailwindcss`

---

## Key Patterns & Gotchas

- **Garmin import** — `GarminService` uses `alexoid/laravel-garmin`. Always instantiate directly in the controller: `new GarminService($user->garmin_email, $user->garmin_password)` — the AppServiceProvider singleton is broken (missing constructor args). Speed values from Garmin are in m/s — multiply × 3.6 for km/h. Import limit: 1000 activities.
- **Toast / flash notifications** — backend: `Inertia::flash('toast', ['type' => 'success', 'message' => '...'])`. Frontend reads from `usePage().props.flash`, handled in `AppLayout`. Do not build a custom flash system.
- **Shared props** — `HandleInertiaRequests` middleware exposes `auth.user` to every Inertia page. Access it in Vue via `usePage().props.auth.user`.
- **i18n** — App locale is `pl` by default (`APP_LOCALE=pl`). Every user-visible string needs a translation key in both `resources/js/i18n/pl.ts` and `en.ts`.
- **Dark mode** — Tailwind dark mode is in use. New UI components must support both light and dark variants.
- **shadcn-vue components** (`@/Components/ui/`) are excluded from ESLint. Do not modify them directly — extend or wrap instead.
- **Activity scoping** — every query on the `activities` table must be scoped to the authenticated user (`Activity::where('user_id', auth()->id())`).
- **No weather data** — `weather_json` column was removed in the latest migration. Do not reference it.

---

## Testing Guidelines

- Do not write any tests unless specifically requested. This project is not test-covered, and adding tests is out of scope.

---

## What NOT to Do

- Do not run `php artisan migrate:fresh` in production or against shared databases
- Do not commit and read `.env` or real credentials
- Do not edit files under `@/Components/ui/` directly
- Do not bypass Husky hooks (`--no-verify`)
- Do not hardcode Polish/English strings in Vue templates — always use i18n keys
- Do not create REST API endpoints unless specifically requested — use Inertia patterns
- Do not use Options API or `defineComponent` in Vue files
