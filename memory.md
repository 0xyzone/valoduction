# Project Memory — Valoduction

> **Purpose:** Persistent architectural and change log for AI agents so they do not need to re-analyze the entire repository on every task. Read this file first, and update it at the end of every completed task.

## Stack & Environment
- **Framework:** Laravel 13 (`laravel/framework: ^13.17`)
- **Admin Panel:** Filament 5 (`filament/filament: ^5.0`)
- **PHP:** `^8.3` (running on PHP 8.4)
- **App Name / URL:** `Valoduction` / `http://vd`
- **Testing:** PHPUnit 12 (`php artisan test --compact`)
- **Formatting:** Laravel Pint (`vendor/bin/pint --dirty --format agent`)
- **Strict Rule:** Never run `npm run build` (or equivalent production asset builds).

## Key Files & Architecture
- `app/Providers/AppServiceProvider.php` — Globally configures `CreateAction`, `EditAction`, and `ViewAction` as `7xl` slideover modals (`Width::SevenExtraLarge`), and registers `ColorPicker::withSwatches()` macro (`resources/views/filament/components/color-swatches.blade.php`).
- `app/Providers/Filament/AdminPanelProvider.php` — Main Filament `admin` panel (`/admin`, Valorant Red `#FF4655` primary, Warm Gold `#C89B3C` warning, `Plus Jakarta Sans`, collapsible sidebar, custom `App\Filament\Pages\Auth\Login`, `public/css/filament-theme.css` hook). Uses `brandLogo` pointing to `resources/views/filament/brand.blade.php` with `Changelog::latestVersion()`.
- `public/css/filament-theme.css` — Warm Valorant Glassmorphic Mobile-Card UI stylesheet (warm cream `#ECE8E1` light default, `#0F1923` dark mode, frosted glass panels/modals/cards).
- `app/Filament/Pages/Auth/Login.php` — Custom Filament 5 login page authenticating via `username` instead of `email`.
- `app/Filament/Resources/Users/` — Filament `UserResource` (`UserForm`, `UsersTable`, `ManageUsers`) managing `avatar`, `name`, `username`, `primary_phone`, `email`, `password`, and `is_active` via `7xl` slideover modals.
- `app/Console/Commands/MakeFilamentUserCommand.php` — Overrides `make:filament-user` to prompt for `username`, optional `primary-phone` and `email`, `name`, and `password`.
- `app/Models/` — `User`, `Tournament`, `Team`, `Player` (reusable across tournaments via `tournament_team`), `GameMatch`, `MatchVeto`, `MatchMap`, `PlayerMatchStat` (with model `booted()` hooks dispatching `VetoUpdated`/`MatchUpdated` safely via `MatchPayload`).
- `app/Support/Valorant.php` — Canonical Valorant maps, roles, and agents lists.
- `app/Support/MatchPayload.php` — Serializes `GameMatch` + vetos + maps + player stats and safely dispatches Reverb broadcasts.
- `app/Events/MatchUpdated.php` & `app/Events/VetoUpdated.php` — `ShouldBroadcastNow` events on public `matches.{id}` channel via Laravel Reverb.
- `app/Support/ReverbStatus.php` & `app/Livewire/ReverbStatusIndicator.php` — Shared Reverb TCP socket status checker and live-polling (`15s`) Filament topbar pill (`PanelsRenderHook::USER_MENU_BEFORE`).
- `app/Console/Commands/EnsureReverbRunning.php` & `routes/console.php` — `reverb:ensure-running` command scheduled every minute (`withoutOverlapping()`) for cPanel `schedule:run` cron jobs to keep Laravel Reverb running.
- `app/Http/Controllers/OverlayController.php` & `resources/views/overlays/{veto,post-match}.blade.php` — Transparent OBS Browser Source views with zero-build native WebSocket Reverb listeners.
- `app/Filament/Resources/{Tournaments,Teams,Players,GameMatches}/` — `ManageRecords` (`7xl` slideover) resources under `Tournament Ops` nav group, plus `TeamImporter`/`TeamExporter` (with attach-to-tournament option) and `PlayerImporter`/`PlayerExporter`.
- `database/migrations/2026_11_20_073426_add_profile_fields_to_users_table.php` — Adds `username` (unique), `primary_phone` (nullable), `is_active` (boolean, default `true`), `avatar` (nullable), and makes `email` nullable.
- `resources/views/filament/brand.blade.php` — Renders the `Valoduction` brand name alongside the clickable version pill (`v1.0.0`), visible on both `/admin/login` and inside the `/admin` panel, opening `/changelog` in a new tab.
- `app/Support/Changelog.php` — Parses `changelog.md` for `latestVersion()`, `releases()`, and safe inline markdown formatting (`formatInline()`).
- `app/Http/Controllers/ChangelogController.php` — Invokable controller serving `/changelog`.
- `resources/views/changelog.blade.php` — Self-contained public changelog timeline view (light/dark mode, no Vite build dependency).
- `routes/web.php` — Defines `/` and `/changelog` (`name('changelog')`).
- `changelog.md` — Semantic versioning release log (`vMAJOR.MINOR.PATCH`).
- `AGENTS.md` & `CLAUDE.md` — Agent instructions including Laravel Boost guidelines and project workflow rules (`ntfy.sh/vd-tasks`, `memory.md`, `changelog.md`, git commit & push).

## Task History
- **2026-11-21 (`v1.3.4`):** Added dummy sample rows (`->examples()`) to `TeamImporter` and `PlayerImporter` for Filament's example CSV download (keeping strict team lookup validation on player import).
- **2026-11-21 (`v1.3.3`):** Added live `ReverbStatusIndicator` pill (`Reverb Live` / `Reverb Offline`) in the Filament topbar backed by `App\Support\ReverbStatus`.
- **2026-11-21 (`v1.3.2`):** Fixed `.fi-section` `backdrop-filter` stacking-context overlap on `ColorPicker`/dropdown popovers inside slideover modals, and added `ColorPicker::withSwatches()` preset swatches alongside custom color picking.
- **2026-11-21 (`v1.3.1`):** Added `reverb:ensure-running` command and scheduled it every minute in `routes/console.php` for shared cPanel cron automation.
- **2026-11-21 (`v1.3.0`):** Built reusable Tournament/Team/Player/GameMatch/MatchVeto/MatchMap/PlayerMatchStat models & Filament `ManageRecords` resources, Team/Player CSV Import/Export, installed Laravel Reverb, and created real-time OBS Browser Source overlays for Map Veto and Post-Match Scoreboards.
- **2026-11-20 (`v1.2.0`):** Configured global `7xl` slideover modals for Create/Edit/View actions, converted `UserResource` to `ManageUsers`, and upgraded Filament, `/changelog`, and `/` to a Warm Valorant Glassmorphic Mobile-Card UI (`#ECE8E1` / `#0F1923` + `#FF4655`).
- **2026-11-20 (`v1.1.0`):** Added `username`, `primary_phone`, `is_active`, `avatar`, and nullable `email` to `users`; built Filament `UserResource`, username-based `Login` page, `FilamentUser`/`HasAvatar` on `User`, and overrode `make:filament-user` to use `username`.
- **2026-11-20 (`v1.0.0`):** Initialized project workflow automation (`ntfy.sh/vd-tasks` notification rule, `memory.md`, `AGENTS.md`/`CLAUDE.md` rules, automatic git commit & push), `changelog.md`, public `/changelog` timeline page, and Filament brand version pill on sign-in and panel views.
