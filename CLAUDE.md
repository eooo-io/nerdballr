# FFIS — Football Field Intelligence System

Interactive tactical visualization simulator for understanding American football formations, strategy, and spatial geometry. A teaching tool — not a game, analytics platform, or fantasy sports app.

## Project Identity

- **Name**: Football Field Intelligence System (FFIS) / NerdBallr
- **Purpose**: Visual teaching and exploration of football tactics
- **Aesthetic**: Retro coach's workstation (Amiga-inspired). Dark backgrounds, vivid tokens, crisp grid, disciplined palette. Not a consumer sports app.

## Tech Stack

### Backend — Laravel 12 (project root)
- PHP 8.2+, Laravel 12, Pest 4 for testing
- MariaDB 11, Redis 7, MinIO (S3-compatible storage)
- Laravel Sanctum (SPA auth), Laravel Horizon (queues)
- Anthropic Claude API for AI assistant (proxied, key never exposed to client)

### Frontend — React 18 SPA (to be scaffolded in `frontend/`)
- React 18 + TypeScript, Vite, SVG rendering, Framer Motion
- Zustand (state), React Router v6, TailwindCSS, Lucide React
- Communicates with backend exclusively via REST API

### Dev Environment — Docker Compose
- Services: nginx, frontend (Vite), backend (php-fpm), mariadb, redis, minio, horizon, mailpit
- `make up` / `make down` / `make install` / `make fresh` / `make logs`
- Nginx routes: `/api/*` → Laravel, `/*` → Vite dev server

## Project Structure

```
NerdBallr/                    # Laravel 12 app at root
├── app/                      # Laravel application code
│   ├── Http/Controllers/     # ConceptController, AiController, etc.
│   ├── Models/               # Concept, User, UserBookmark, etc.
│   └── Services/             # AiService (Anthropic proxy)
├── database/migrations/
├── routes/                   # API routes
├── docker-compose.yml        # Full dev environment
├── docker/                   # Dockerfiles and config
├── Makefile                  # Dev convenience commands
├── frontend/                 # React SPA (separate build)
└── .claude/docs/             # Architecture docs and specs
```

## Core Data Model

### Field Coordinates
- X: 0–1200 (1 unit = 0.1 yards, 120 yards total)
- Y: 0–533 (53.3 yards width)
- SVG viewBox maps logical units directly to viewport

### Concept — The Core Unit
Everything revolves around `Concept`: a formation, coverage, route concept, etc. with:
- Metadata: slug, label, category, difficulty, tags, layers
- Roster: array of Players with role/side/label
- Phases: ordered animation frames with player positions, motion paths, ball state, overlays, annotations
- AI context: plain text injected into RAG queries

### Player Token System (never changes between concepts)
| Role | Shape | Color |
|------|-------|-------|
| QB | Circle | Blue #3B82F6 |
| RB | Square | Green #22C55E |
| WR | Triangle | Yellow #EAB308 |
| TE | Diamond | Red #EF4444 |
| OL | Square (lg) | Gray #9CA3AF |
| DL | Square (lg) | Dark #374151 |
| LB | Square | Purple #A855F7 |
| CB | Circle | Red #EF4444 |
| S | Circle | Orange #F97316 |

### Categories
`formation-offense`, `formation-defense`, `coverage`, `blitz`, `route-concept`, `pocket-mechanics`, `ball-physics`, `geometry`

### Tactical Layers
1. Fundamentals — formations, fronts, positions
2. Tactical Interaction — blitz vs screen, coverage matchups
3. Spatial Geometry — pursuit angles, catch-point triangle
4. Ball Physics — trajectory types, underthrown geometry

## Application Modes

- **Lesson**: Single concept, explanation panel, phased animation, AI sidebar
- **Compare**: Two concepts side-by-side, sync toggle (default on), shared playback
- **Geometry Lab**: Static/slow spatial mechanics, slider controls
- **Custom Lab** (Phase 2): Drag-and-drop, path drawing, save/share

## API Contracts

```
GET  /api/concepts                  List (summary, no phases)
GET  /api/concepts/{slug}           Full concept with phases
GET  /api/concepts?category=        Filter by category
GET  /api/concepts?tags=            Filter by tags
GET  /api/concepts?q=               Keyword search
POST /api/user/bookmarks            Add bookmark
DEL  /api/user/bookmarks/{id}       Remove bookmark
POST /api/user/progress             Mark concept complete
POST /api/ai/query                  AI query { query, concept_slugs[] }
POST /api/user/migrate-guest        Migrate guest state on signup
```

## AI Assistant

Bounded tactical advisor — reasons only over concept data from the DB, never invents.

- Intent types: explain, compare, counter, pre-snap-read, recommend
- Rate limits: 20/hour (auth), 5/session (guest)
- Context cap: 4,000 tokens of concept data per request
- Response cap: 600 tokens
- All calls proxied through Laravel controller

## Key Principles

1. **Clarity over complexity** — every visual must aid understanding
2. **Schema-first** — concept data model is source of truth
3. **Bounded AI** — assistant explains library contents, never generates plays
4. **Sync as default** — compare mode starts synced
5. **Graceful guest experience** — full simulator works without auth
6. **Token consistency** — shapes/colors never change between concepts
7. **Retro elegance** — coach's workstation, not consumer sports app

## Development Notes

- Backend is Laravel 12 at project root (docs reference Laravel 11 — actual is 12)
- Docker Compose references `./backend` and `./frontend` — these need to align with actual structure
- Testing: Pest 4 (`composer test` or `php artisan test`)
- Code style: Laravel Pint
- Full architecture spec: `.claude/docs/FFIS_architecture.md`
- Project instructions: `.claude/FFIS_PROJECT_INSTRUCTIONS.md`
