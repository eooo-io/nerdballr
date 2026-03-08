# FFIS Implementation Plan

## Current State

Fresh Laravel 12 scaffold. Docker Compose infrastructure is fully configured (nginx, php-fpm, mariadb, redis, minio, horizon, mailpit). No application code, models, API routes, or frontend exist yet. Default Laravel migrations only (users, cache, jobs).

---

## Available Agents

| Agent | Relevant? | Role in FFIS |
|-------|-----------|--------------|
| **database-architect** | Yes | Migrations, Eloquent models, indexes, JSON column schema |
| **security-reviewer** | Yes | Sanctum SPA auth, API input validation, rate limiting, OWASP |
| **test-engineer** | Yes | Pest tests for models, API endpoints, AI service |
| **code-reviewer** | Yes | Review after each phase for quality, SOLID, DRY |
| **performance-auditor** | Yes | Query optimization, Redis caching, index audit |
| **livewire-developer** | No | FFIS uses React SPA, not Livewire/TALL stack |
| **filament-specialist** | No | No admin panel in Phase 1 |
| **module-scaffolder** | No | No nwidart modules needed |

> **Note:** The agent definitions contain Immotege-specific instructions (German fintech, Europace, 186+ models). Agents will need to be directed to apply their *general* Laravel expertise to FFIS context, ignoring Immotege-specific conventions.

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

## Agent Adaptation Notes

The available agents were built for the **Immotege** fintech platform. When invoking them for FFIS:

1. **Ignore Immotege-specific context** — no Europace, no German fintech, no 186+ models, no Spatie Permission, no `ep_` prefix
2. **Ignore container-first mandate** — FFIS uses `docker compose exec backend` not `docker compose exec php`
3. **Keep general Laravel expertise** — migrations, Eloquent, Pest, Sanctum, SOLID, PSR-12, security patterns
4. **Adapt testing rules** — FFIS can use `RefreshDatabase` safely (fresh project with proper migrations)
5. **Skip Livewire/Filament/PowerGrid** — FFIS frontend is React SPA
