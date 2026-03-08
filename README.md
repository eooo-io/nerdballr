<p align="center">
  <img src="https://img.shields.io/badge/FFIS-Football_Field_Intelligence_System-0D1117?style=for-the-badge&labelColor=1D4ED8" alt="FFIS">
</p>

<h1 align="center">NerdBallr</h1>

<p align="center">
  An interactive tactical visualization simulator for understanding<br>
  American football formations, strategy, and spatial geometry.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/React-18-61DAFB?style=flat-square&logo=react&logoColor=black" alt="React 18">
  <img src="https://img.shields.io/badge/TypeScript-5-3178C6?style=flat-square&logo=typescript&logoColor=white" alt="TypeScript">
  <img src="https://img.shields.io/badge/Vite-6-646CFF?style=flat-square&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/MariaDB-11-003545?style=flat-square&logo=mariadb&logoColor=white" alt="MariaDB 11">
  <img src="https://img.shields.io/badge/Redis-7-DC382D?style=flat-square&logo=redis&logoColor=white" alt="Redis 7">
  <img src="https://img.shields.io/badge/Docker-Compose-2496ED?style=flat-square&logo=docker&logoColor=white" alt="Docker Compose">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Framer_Motion-Animation-0055FF?style=flat-square&logo=framer&logoColor=white" alt="Framer Motion">
  <img src="https://img.shields.io/badge/Zustand-State-443E38?style=flat-square" alt="Zustand">
  <img src="https://img.shields.io/badge/TailwindCSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Sanctum-Auth-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Sanctum">
  <img src="https://img.shields.io/badge/Claude_API-AI-D4A574?style=flat-square&logo=anthropic&logoColor=white" alt="Claude API">
  <img src="https://img.shields.io/badge/MinIO-Storage-C72E49?style=flat-square&logo=minio&logoColor=white" alt="MinIO">
  <img src="https://img.shields.io/badge/Pest-Testing-F28D1A?style=flat-square" alt="Pest">
</p>

---

## What is this?

NerdBallr is a **teaching and exploration tool** that lets users visually study how football tactics work. It is not a game, not a stats platform, and not a fantasy sports app.

Think of it as a **coach's tactical workstation** — retro-inspired (Amiga aesthetic), animation-driven, and built around a structured concept library.

### What you can explore

- Offensive and defensive **formations**
- **Coverage shells** (Cover 0 through Cover 6)
- **Blitz** patterns and pressure packages
- **Route concepts** — individual routes and combination concepts
- **Pocket mechanics** and quarterback decision timing
- **Ball trajectory physics** — line-drive, rainbow, screen, handoff
- **Spatial geometry** — pursuit angles, catch-point triangles, leverage
- **Pre-snap reads** — what the QB sees before the snap

---

## Application Modes

| Mode | Description |
|------|-------------|
| **Lesson** | Single concept with full explanation panel, phased animation, and AI assistant |
| **Compare** | Two concepts side-by-side with synchronized playback (sync on by default) |
| **Geometry Lab** | Static/slow-animated spatial mechanics with slider controls |
| **Custom Lab** | Drag-and-drop play builder with save/share *(Phase 2)* |

---

## Architecture

```
┌─────────────────────────────────────────────────┐
│                   Nginx Proxy                   │
│         /api/* → Laravel    /* → Vite SPA       │
├────────────────────┬────────────────────────────┤
│                    │                            │
│   Laravel 12 API   │     React 18 + Vite SPA    │
│   ─────────────    │     ────────────────────    │
│   Sanctum Auth     │     SVG Field Rendering    │
│   Concept CRUD     │     Framer Motion Anim.    │
│   AI Proxy (RAG)   │     Zustand State Mgmt     │
│   Rate Limiting    │     TailwindCSS Styling    │
│                    │                            │
├────────────────────┴────────────────────────────┤
│  MariaDB 11  │  Redis 7  │  MinIO  │  Horizon  │
└─────────────────────────────────────────────────┘
```

The frontend is a **standalone SPA** that communicates with the backend exclusively via REST API. The AI assistant proxies all requests through Laravel — the Anthropic API key is never exposed to the client.

---

## AI Assistant

A **bounded tactical advisor** that reasons only over structured concept data retrieved from the database. It never invents formations or generates plays from scratch.

| Intent | Example | Response |
|--------|---------|----------|
| Explain | *"How does Cover 2 work?"* | Paragraph + key reads |
| Compare | *"Cover 2 vs Cover 3"* | Offense / Defense / Key read |
| Counter | *"How do you beat Cover 2?"* | Concept + mechanical reason |
| Pre-snap read | *"What should the QB see?"* | Ordered visual cue list |
| Recommend | *"What should I study next?"* | Concept list with reasons |

---

## Getting Started

### Prerequisites

- Docker & Docker Compose
- An Anthropic API key (for the AI assistant)

### Setup

```bash
cp .env.example .env          # Fill in secrets
make build                    # Build Docker images
make up                       # Start all services
make install                  # Composer install + migrate + seed
```

### Daily Development

```bash
make up                       # Start services
make logs                     # Follow all logs
make logs s=backend           # Follow one service
make migrate                  # Run new migrations
make fresh                    # Drop + rebuild DB (destructive)
make tinker                   # Laravel Tinker REPL
make shell-backend            # Shell into Laravel container
make shell-frontend           # Shell into Node container
```

### Services

| Service | URL |
|---------|-----|
| App (via Nginx) | http://localhost |
| Vite Dev Server | http://localhost:5173 |
| MariaDB | localhost:3306 |
| Redis | localhost:6379 |
| MinIO API | http://localhost:9000 |
| MinIO Console | http://localhost:9001 |
| Mailpit | http://localhost:8025 |

---

## Player Token System

Every player role maps to a consistent visual token that never changes across concepts:

| Role | Shape | Color |
|------|-------|-------|
| QB | Circle | `#3B82F6` Blue |
| RB | Square | `#22C55E` Green |
| WR | Triangle | `#EAB308` Yellow |
| TE | Diamond | `#EF4444` Red |
| OL | Square (lg) | `#9CA3AF` Gray |
| DL | Square (lg) | `#374151` Dark |
| LB | Square | `#A855F7` Purple |
| CB | Circle | `#EF4444` Red |
| S | Circle | `#F97316` Orange |

---

## License

[MIT](LICENSE)
