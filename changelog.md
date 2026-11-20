# Changelog

All notable changes to **Valoduction** will be documented in this file.

The format follows Semantic Versioning (`vMAJOR.MINOR.PATCH` for major, minor, and macro/patch updates).

## [v1.0.0] - 2026-11-20

### Added
- Initial Laravel 13 and Filament 5 admin panel setup (`AdminPanelProvider`).
- Public changelog timeline page at `/changelog` powered by `changelog.md`.
- Version badge pill displayed beside the brand name in Filament (sign-in page and admin panel), linking to `/changelog`.
- Persistent `memory.md` file to track architecture and changes for AI agents.
- Agent workflow rules in `AGENTS.md` and `CLAUDE.md` for `ntfy.sh/vd-tasks` notifications, `memory.md`, `changelog.md`, and automatic git commit & push.
