# FFIS Implementation Plan

## Current State

**v1.0.0 released.** All 13 original phases (0–12) are complete. Laravel 12 backend with 20 seeded concepts, React 19 + TypeScript SPA, Sanctum auth, AI assistant, full animation engine. 129 Pest tests passing. Phases 13–16 below are the v1.1 learning experience enhancements.

---

## Available Agents

All agents are FFIS-specific. Definitions live in `.claude/agents/`.

| Agent | Role in FFIS |
|-------|--------------|
| **database-architect** | Migrations, Eloquent models, indexes, JSON column schema, MariaDB queries |
| **test-engineer** | Pest 4 tests for API endpoints, models, AI service mocking, auth flows |
| **security-reviewer** | Sanctum SPA auth, API input validation, rate limiting, OWASP, AI proxy security |
| **code-reviewer** | Review diffs for quality, SOLID, DRY, Laravel + React/TypeScript best practices |
| **performance-auditor** | Query optimization, Redis caching, index audit, SVG rendering, bundle size |

### Container Commands (all agents use these)
```bash
docker compose exec -T backend php artisan migrate        # Migrations
docker compose exec -T backend ./vendor/bin/pest          # Tests
docker compose exec -T backend ./vendor/bin/pint          # Code style
docker compose exec -T backend php artisan tinker         # REPL
```

---

## Phase 0 — Foundation & Dev Environment

**Goal:** Get Docker stack running, `.env` configured, verify all services healthy.

### Tasks
- [ ] Create `.env` from docker-compose defaults
- [ ] Fix docker/node/Dockerfile to serve the React SPA (currently points at root `package.json` with no React)
- [ ] Verify `make up && make install` works end-to-end
- [ ] Install missing backend packages: `laravel/sanctum`, `laravel/horizon`

### Agent assignments
- None — manual setup / main assistant

---

## Phase 1 — Database Schema & Models

**Goal:** Create all migrations and Eloquent models per the architecture spec.

### Tables to create
1. `concepts` — core unit (slug, label, category, tags JSON, difficulty, layers JSON, roster JSON, phases JSON, counters JSON, related JSON, ai_context)
2. `user_bookmarks` — pivot: user ↔ concept (unique constraint)
3. `user_progress` — pivot: user ↔ concept completed_at
4. `custom_plays` — user-created plays (Phase 2, scaffold now)
5. `ai_sessions` — conversation history (session_key, messages JSON, concept_ids JSON)

### Models
- `Concept` — casts for JSON columns (tags, layers, roster, phases, counters, related)
- `UserBookmark` — belongsTo User, belongsTo Concept
- `UserProgress` — belongsTo User, belongsTo Concept
- `CustomPlay` — belongsTo User, JSON casts
- `AiSession` — belongsTo User (nullable), JSON casts
- Update `User` — add hasMany bookmarks, progress, custom_plays, ai_sessions

### Agent assignments
- **`database-architect`** — Create all migrations with proper indexes, foreign keys, JSON columns. Create Eloquent models with relationships, casts, and fillable.
- **`test-engineer`** — Write model unit tests (relationship assertions, JSON cast verification, factory definitions).

---

## Phase 2 — API Routes & Controllers

**Goal:** Implement all REST endpoints per the API contract.

### Endpoints
```
GET    /api/concepts                  → ConceptController@index
GET    /api/concepts/{slug}           → ConceptController@show
POST   /api/user/bookmarks            → BookmarkController@store
DELETE /api/user/bookmarks/{id}       → BookmarkController@destroy
GET    /api/user/bookmarks            → BookmarkController@index
POST   /api/user/progress             → ProgressController@store
GET    /api/user/progress             → ProgressController@index
POST   /api/ai/query                  → AiController@query
GET    /api/ai/session/{key}          → AiController@session
POST   /api/user/migrate-guest        → GuestMigrationController@migrate
```

### Implementation details
- `ConceptController` — index with filtering (category, tags, q search), show by slug
- API Resources for consistent JSON shape (summary vs full)
- Form Requests for validation
- Route groups: public (concepts) vs auth:sanctum (bookmarks, progress, ai, migrate)

### Agent assignments
- **Main assistant** — Build controllers, form requests, API resources, route definitions.
- **`test-engineer`** — Feature tests for every endpoint (happy path + validation + auth).
- **`security-reviewer`** — Review auth middleware, input validation, mass assignment protection.

---

## Phase 3 — Authentication (Sanctum SPA)

**Goal:** SPA cookie-based auth with Sanctum. Guest experience works without auth.

### Tasks
- [ ] Install & configure Sanctum for SPA authentication
- [ ] Registration endpoint (POST /api/register)
- [ ] Login endpoint (POST /api/login)
- [ ] Logout endpoint (POST /api/logout)
- [ ] Current user endpoint (GET /api/user)
- [ ] Guest migration endpoint (POST /api/user/migrate-guest)
- [ ] Configure CORS for localhost:5173

### Agent assignments
- **`security-reviewer`** — Audit Sanctum config, CORS, session security, CSRF cookie flow.
- **`test-engineer`** — Auth flow tests (register, login, logout, guest migration).

---

## Phase 4 — AI Service (Anthropic Proxy)

**Goal:** Bounded tactical advisor proxied through Laravel. Never exposes API key to client.

### Components
- `AiService` — handles intent parsing, concept retrieval, context building, Anthropic API call
- `AiController` — rate limiting, session management, request/response shaping
- System prompt per spec (6 rules, context injection)
- Intent detection: explain, compare, counter, pre-snap-read, recommend
- Rate limiting: 20/hour (auth), 5/session (guest) via Redis

### Agent assignments
- **Main assistant** — Build AiService, AiController, rate limiting middleware.
- **`security-reviewer`** — Verify API key never leaks, rate limits enforced, input sanitized.
- **`test-engineer`** — Mock Anthropic API responses, test rate limiting, test intent routing.
- **`performance-auditor`** — Review concept retrieval queries, caching strategy for repeated concept lookups.

---

## Phase 5 — Concept Seeder

**Goal:** Populate the database with 15–20 real football concepts across all categories.

### Concepts to seed
- **Formations (offense):** Shotgun Spread, I-Formation, Pistol, 11 Personnel
- **Formations (defense):** 4-3 Base, 3-4 Base, Nickel, Dime
- **Coverages:** Cover 0, Cover 1, Cover 2, Cover 3, Cover 4 (Quarters)
- **Blitz:** Zone Blitz, A-Gap Blitz
- **Route concepts:** Mesh, Four Verticals, Smash, Slant-Flat
- **Geometry:** Pursuit Angle Triangle, Catch-Point Geometry

Each concept includes: full roster with positions, 3–5 animation phases with player coordinates, motion paths, overlays, annotations, AI context text.

### Agent assignments
- **Main assistant** — Build ConceptSeeder with realistic football data.
- **`database-architect`** — Verify JSON structure validates against schema, optimize seeder performance.

---

## Phase 6 — Frontend Scaffold (React SPA)

**Goal:** Initialize the React 18 + TypeScript SPA in `frontend/` with full tooling.

### Tasks
- [ ] Scaffold React 18 + TypeScript + Vite in project root (using existing vite.config.js)
- [ ] Install: zustand, react-router-dom v6, framer-motion, tailwindcss, lucide-react, axios
- [ ] Configure Tailwind with retro palette (dark backgrounds, vivid token colors)
- [ ] Set up project structure:
  ```
  src/
  ├── api/           # Axios client, API hooks
  ├── components/    # Shared UI components
  ├── features/      # Feature modules (lesson, compare, geometry-lab)
  ├── stores/        # Zustand stores
  ├── types/         # TypeScript interfaces (from arch spec)
  ├── hooks/         # Custom React hooks
  └── styles/        # Tailwind config, global styles
  ```
- [ ] Define all TypeScript interfaces from the architecture spec
- [ ] Create API client with Sanctum CSRF cookie handling
- [ ] Set up React Router with mode routes (/, /lesson/:slug, /compare, /geometry-lab)

### Agent assignments
- **Main assistant** — All frontend scaffolding (no backend agents needed here).

---

## Phase 7 — Field Renderer (SVG Core)

**Goal:** Build the interactive football field SVG component — the heart of the app.

### Components
- `FootballField` — SVG viewBox 0 0 1200 533, yard lines, hash marks, numbers
- `PlayerToken` — renders shape (circle/square/triangle/diamond) by role with correct colors
- `MotionPath` — renders SVG path elements (straight, quadratic, cubic, arc)
- `ZoneOverlay` — semi-transparent polygon overlays
- `GeometryOverlay` — line segments with custom styles
- `HighlightOverlay` — pulsing highlights on player groups
- `Annotation` — positioned text labels, callouts, brackets
- `BallToken` — small circle with optional trajectory arc

### Design tokens (CSS variables)
```css
--field-green: #1a472a;
--field-line: #ffffff22;
--field-bg: #0f1419;
--panel-bg: #1a1d23;
--text-primary: #e2e8f0;
--text-secondary: #94a3b8;
```

### Agent assignments
- **Main assistant** — All React/SVG component work.

---

## Phase 8 — Animation Engine

**Goal:** Phase-based animation system with playback controls.

### Zustand stores
- `usePlaybackStore` — currentPhase, isPlaying, speed (0.5x/1x/2x), play/pause/step
- `useConceptStore` — loaded concept data, loading state
- `useCompareStore` — slotA, slotB, synced toggle, shared timeline controller

### Animation logic
- Framer Motion `animate` on player positions between phases
- Path drawing animation (stroke-dashoffset technique)
- Ball trajectory animation with arc interpolation
- Phase transition with configurable duration per phase
- Playback strip: prev/play/pause/next, speed selector, phase scrubber

### Agent assignments
- **Main assistant** — Animation engine, Zustand stores, playback UI.

---

## Phase 9 — Application Modes

**Goal:** Implement the three application modes.

### Lesson Mode (`/lesson/:slug`)
- Full concept loaded via API
- Left panel: animated field
- Right panel: explanation (markdown rendered), phase descriptions
- AI sidebar (collapsible)

### Compare Mode (`/compare`)
- Two concept selectors
- Side-by-side fields
- Sync toggle (default on) with shared playback strip
- Phase-aligned playback per sync rules

### Geometry Lab (`/geometry-lab`)
- Static/slow spatial overlays
- Slider controls for angle/distance variables
- Pursuit angle triangle, catch-point geometry, leverage diagrams

### Agent assignments
- **Main assistant** — All frontend mode implementation.

---

## Phase 10 — AI Chat Sidebar

**Goal:** Frontend chat interface that communicates with the AI proxy.

### Components
- `AiSidebar` — collapsible panel, message list, input
- `AiMessage` — formatted response display (supports markdown-like structure)
- Guest session tracking (localStorage session key)
- Rate limit indicator (queries remaining)
- Context awareness: auto-includes loaded concept(s) in queries

### Agent assignments
- **Main assistant** — React chat UI.

---

## Phase 11 — User State & Persistence

**Goal:** Guest localStorage + authenticated server sync.

### Zustand persistence
- `useUserStore` — bookmarks, completed, preferences
- Guest: `zustand/persist` with localStorage
- Auth: sync to API on change (debounced 2s)
- Migration on signup: POST localStorage state to `/api/user/migrate-guest`

### Agent assignments
- **Main assistant** — Zustand persistence layer.
- **`test-engineer`** — Test guest migration endpoint, bookmark/progress sync.

---

## Phase 12 — Polish & Quality Gates

**Goal:** Final review, optimization, and hardening before launch.

### Tasks
- [ ] Full security audit
- [ ] Performance audit (query counts, caching, indexes)
- [ ] Code review across all backend code
- [ ] Responsive layout verification
- [ ] Accessibility basics (keyboard nav, screen reader labels on SVG)
- [ ] Error handling (API failures, loading states, empty states)

### Agent assignments
- **`security-reviewer`** — Full OWASP audit of all endpoints, auth flow, AI proxy.
- **`performance-auditor`** — Query profiling, Redis cache hit rates, index coverage.
- **`code-reviewer`** — Full codebase review for SOLID, DRY, Laravel best practices.
- **`test-engineer`** — Coverage report, missing edge cases, integration test gaps.

---

## Execution Order & Dependencies

```
Phase 0 (Foundation)
  ↓
Phase 1 (Database) ←── database-architect, test-engineer
  ↓
Phase 2 (API) + Phase 3 (Auth) ←── security-reviewer, test-engineer
  ↓
Phase 4 (AI Service) ←── security-reviewer, test-engineer, performance-auditor
  ↓
Phase 5 (Seeder) ←── database-architect
  ↓
Phase 6 (Frontend Scaffold)
  ↓
Phase 7 (Field Renderer) → Phase 8 (Animation) → Phase 9 (Modes)
  ↓
Phase 10 (AI Chat) + Phase 11 (User State)
  ↓
Phase 12 (Polish) ←── ALL agents for final review
```

Phases 2 & 3 can run in parallel. Phases 10 & 11 can run in parallel.
Frontend phases (6–11) are independent of backend after Phase 5.

---

# v1.1 — Learning Experience Enhancements

Phases 13–16 address core pedagogical gaps identified after v1.0 release: the library lacks learning progression, there's no foundational primer for newcomers, terminology is assumed but never defined, and the `counters` data exists but is invisible to learners.

## Release & PR Process

Each phase is developed on a feature branch, submitted as a PR, reviewed, and merged to `main`. After merge, a SemVer tag is created. The version ladder continues from `v1.0.0`:

| Phase | Branch | Version | Scope |
|-------|--------|---------|-------|
| 13 | `feature/phase-13-difficulty-sort` | `v1.1.0` | Library sorting (new feature) |
| 14 | `feature/phase-14-primer` | `v1.2.0` | Football 101 primer (new feature) |
| 15 | `feature/phase-15-glossary` | `v1.3.0` | Terminology glossary (new feature) |
| 16 | `feature/phase-16-counter-viz` | `v1.4.0` | Counter visualization (new feature) |

**SemVer rules:**
- **PATCH** (`x.x.1`) — bug fixes, typo corrections, minor tweaks within a phase
- **MINOR** (`x.1.0`) — each completed phase (new feature, backward compatible)
- **MAJOR** (`2.0.0`) — breaking API or data model changes (not expected in v1.x)

**PR workflow per phase:**
1. Create feature branch from `main`
2. Implement all tasks (backend agents + main assistant frontend work)
3. Run tests: `docker compose exec -T backend ./vendor/bin/pest`
4. Run Pint: `docker compose exec -T backend ./vendor/bin/pint`
5. Create PR targeting `main` with phase summary
6. Review (code-reviewer agent or manual)
7. Merge PR
8. Tag release: `git tag vX.Y.Z && git push origin vX.Y.Z`
9. Create GitHub release from tag

---

## Phase 13 — Difficulty-Based Library Sorting & Grouping

**Goal:** Organize the concept library as a learning path — beginner → intermediate → advanced — instead of a flat alphabetical list.

### Backend
- [ ] Change `ConceptController@index` ordering from `orderBy('label')` to `orderByRaw` with difficulty weight: beginner=1, intermediate=2, advanced=3
- [ ] Add secondary sort by category, then label within each difficulty tier
- [ ] Add optional `?sort=difficulty` and `?sort=alpha` query param so the API supports both (default: difficulty)

### Frontend
- [ ] Add difficulty section headers in the library grid (Beginner, Intermediate, Advanced)
- [ ] Show concept count per section
- [ ] Add sort toggle (difficulty / alphabetical) to the filter bar
- [ ] Persist sort preference in user store

### Agent assignments
- **Main assistant** — Frontend grouping UI, sort toggle
- **`database-architect`** — Backend sort query optimization
- **`test-engineer`** — Test sort ordering in API response, test both sort modes

### Delivery
- Branch: `feature/phase-13-difficulty-sort`
- PR → merge to `main` → tag `v1.1.0` → GitHub release

---

## Phase 14 — Football 101 Primer

**Goal:** A dedicated primer page that teaches the absolute basics before a user touches any concept. One SVG, all 22 players, token legend, field anatomy.

### Content sections
1. **The Field** — SVG showing full field with labeled zones: end zones, red zone, yard lines, hash marks, sidelines, line of scrimmage
2. **The Teams** — All 22 players in a standard I-Formation vs 4-3 defense, every token labeled with role name and shape/color key
3. **Token Legend** — Visual reference card: role → shape → color (the same mapping used everywhere in the app)
4. **Basic Flow** — Downs system, possession, scoring (text + simple diagrams)
5. **How to Use This App** — Brief guide: "pick a concept, watch the phases, read the explanation, ask the AI"

### Implementation
- [ ] New route: `/primer`
- [ ] `PrimerPage` component with scrollable sections
- [ ] Full-field SVG with all 22 players and annotations
- [ ] Token legend component (reusable — also useful in lesson sidebar)
- [ ] Navigation link in header and as CTA on home page for new users
- [ ] No backend changes — all static content in the frontend

### Agent assignments
- **Main assistant** — All frontend work (React components, SVG, content)

### Delivery
- Branch: `feature/phase-14-primer`
- PR → merge to `main` → tag `v1.2.0` → GitHub release

---

## Phase 15 — Terminology Glossary

**Goal:** Searchable glossary of football terminology used throughout the app. Terms are defined once and can later power tooltips.

### Backend
- [ ] New `glossary_terms` table: `id`, `term` (unique), `definition`, `category` (offense/defense/general/scheme), `related_terms` (JSON array of term strings), `related_concepts` (JSON array of concept slugs), `created_at`, `updated_at`
- [ ] `GlossaryTerm` model with casts and relationships
- [ ] `GlossaryTermSeeder` — seed 40–60 terms covering: positions, formations, coverages, routes, blitz concepts, spatial terms, down-and-distance, general football
- [ ] `GlossaryController` — `index` (searchable, filterable by category), `show` by term slug
- [ ] API routes: `GET /api/glossary`, `GET /api/glossary/{term}`

### Frontend
- [ ] New route: `/glossary`
- [ ] `GlossaryPage` component with search bar and category filter
- [ ] Terms displayed as expandable cards or accordion
- [ ] Related concepts link to `/lesson/{slug}`
- [ ] Related terms cross-link within glossary
- [ ] Navigation link in header

### Agent assignments
- **`database-architect`** — Migration, model, indexes
- **`test-engineer`** — API endpoint tests, search/filter tests
- **Main assistant** — Seeder content, controller, frontend page

### Delivery
- Branch: `feature/phase-15-glossary`
- PR → merge to `main` → tag `v1.3.0` → GitHub release

---

## Phase 16 — Counter Visualization in Lessons

**Goal:** When viewing any concept, show why it works and what beats it. The `counters` array already exists on every concept — surface it in the UI with a visual, animated counter panel.

### Design
- In lesson mode, add a "What Beats This" section below the explanation panel
- Shows the primary counter concept as a mini-field preview (half-size SVG)
- Click to expand into a split-view with synced playback (reuse Compare mode's sync engine)
- Counter concept's explanation excerpt shown alongside
- If the current concept is a counter to something else, show "This Concept Counters" section too (reverse lookup)

### Backend
- [ ] New endpoint: `GET /api/concepts/{slug}/counters` — returns full concept data for all counter slugs
- [ ] Add reverse-counter lookup: find concepts where `counters` JSON contains the current slug
- [ ] Ensure counter slugs in seeder data are accurate and bidirectional where appropriate

### Frontend
- [ ] `CounterPanel` component — collapsible section in LessonPage
- [ ] Mini field preview (reuse `AnimatedField` at reduced scale)
- [ ] "Expand" button to enter split-view with synced playback
- [ ] `CounteredByPanel` — reverse lookup showing "This concept counters: X, Y"
- [ ] Load counter concept data lazily (fetch on expand)
- [ ] Visual indicator on concept cards in library showing counter count

### Agent assignments
- **Main assistant** — Frontend counter panel, split-view, lazy loading
- **`database-architect`** — Counter endpoint, reverse lookup query
- **`test-engineer`** — Counter endpoint tests, reverse lookup accuracy
- **`performance-auditor`** — Ensure counter queries don't cause N+1, review caching strategy

### Delivery
- Branch: `feature/phase-16-counter-viz`
- PR → merge to `main` → tag `v1.4.0` → GitHub release

---

## Phase 13–16 Execution Order & Dependencies

```
Phase 13 (Library Sorting)     — standalone, quick win
Phase 14 (Primer)              — standalone, no backend
Phase 15 (Glossary)            — standalone, backend + frontend
Phase 16 (Counter Viz)         — depends on existing concept data, heaviest feature

Phases 13 + 14 can run in parallel.
Phase 15 can run in parallel with 13/14.
Phase 16 should run last (benefits from all other improvements being in place).
```

---

## Agent Invocation Notes

All agents are purpose-built for FFIS. Key conventions:

1. **Container commands** — always `docker compose exec -T backend` (not `php`, not `exec php`)
2. **RefreshDatabase is safe** — this project has complete migrations from scratch
3. **No Livewire/Filament/Blade** — frontend is React 19 SPA, agents handle backend only
4. **JSON columns are central** — concepts store roster, phases, counters, tags as JSON; agents know MariaDB JSON functions
5. **Orchestration model** — main assistant handles all frontend work and orchestrates agent invocations; agents handle backend tasks in parallel where possible
