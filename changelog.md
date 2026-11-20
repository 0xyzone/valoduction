# Changelog

All notable changes to **Valoduction** will be documented in this file.

The format follows Semantic Versioning (`vMAJOR.MINOR.PATCH` for major, minor, and macro/patch updates).

## [v1.3.3] - 2026-11-21

### Added
- Live `ReverbStatusIndicator` pill in the Filament topbar (`Reverb Live` / `Reverb Offline`, polling every 15s and clickable for instant recheck) backed by `App\Support\ReverbStatus`.

## [v1.3.2] - 2026-11-21

### Fixed
- Fixed CSS stacking-context overlap where `backdrop-filter` on `.fi-section` inside slideover modals clipped/occluded `ColorPicker` and dropdown popovers behind subsequent sections.

### Added
- Added `ColorPicker::withSwatches()` macro (`AppServiceProvider`) and `resources/views/filament/components/color-swatches.blade.php` providing quick preset color swatches alongside the custom `ColorPicker` on `TeamForm`.

## [v1.3.1] - 2026-11-21

### Added
- `reverb:ensure-running` Artisan command (`EnsureReverbRunning`) scheduled every minute in `routes/console.php` (`withoutOverlapping()`) to automatically start and keep Laravel Reverb running via a single cPanel `schedule:run` cron job.

## [v1.3.0] - 2026-11-21

### Added
- Tournament domain models, migrations, and factories: `Tournament`, `Team`, `Player` (reusable across tournaments via `tournament_team`), `GameMatch`, `MatchVeto`, `MatchMap`, and `PlayerMatchStat` (ACS, K/D/A, ADR, HS%, FK/FD, Credits Spent, Agent).
- Filament `ManageRecords` (`7xl` slideover) resources for `TournamentResource`, `TeamResource`, `PlayerResource`, and `GameMatchResource` (including nested live Map Veto and Map/Player Scoreboard repeaters).
- Bulk CSV `ImportAction` and `ExportAction` for Teams (with optional attach-to-tournament option) and Players.
- Installed Laravel Reverb (`laravel/reverb`) with `MatchUpdated` and `VetoUpdated` real-time broadcast events (`ShouldBroadcastNow`).
- Public transparent OBS Browser Source overlays at `/overlays/matches/{gameMatch}/veto` and `/overlays/matches/{gameMatch}/post-match` listening to Reverb WebSockets in real time.

## [v1.2.0] - 2026-11-20

### Changed
- Configured `CreateAction`, `EditAction`, and `ViewAction` globally as `7xl` slideover modals (`AppServiceProvider`) and converted `UserResource` to `ManageUsers` (`ManageRecords`) with `ViewAction` and `EditAction`.
- Upgraded the Filament panel, sign-in page, `/changelog`, and `/` welcome page to a warm Valorant-inspired glassmorphic mobile-card UI (`#ECE8E1` warm cream light default, `#0F1923` dark mode, `#FF4655` Valorant Red primary).
- Documented slideover modal, form layout, and UI theme conventions in `AGENTS.md`, `CLAUDE.md`, and `memory.md`.

## [v1.1.0] - 2026-11-20

### Added
- Filament `UserResource` for managing application users (profile, access & security, active filter, avatar upload).
- User profile fields: `username` (unique), `primary_phone` (nullable), `is_active` (boolean toggle, default `true`), `avatar` (nullable), and nullable `email`.
- Username-based authentication via custom Filament `Login` page (`App\Filament\Pages\Auth\Login`).
- Custom `make:filament-user` Artisan command (`MakeFilamentUserCommand`) using `username` instead of requiring `email`.

## [v1.0.0] - 2026-11-20

### Added
- Initial Laravel 13 and Filament 5 admin panel setup (`AdminPanelProvider`).
- Public changelog timeline page at `/changelog` powered by `changelog.md`.
- Version badge pill displayed beside the brand name in Filament (sign-in page and admin panel), linking to `/changelog`.
- Persistent `memory.md` file to track architecture and changes for AI agents.
- Agent workflow rules in `AGENTS.md` and `CLAUDE.md` for `ntfy.sh/vd-tasks` notifications, `memory.md`, `changelog.md`, and automatic git commit & push.
