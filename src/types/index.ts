// ─── Primitives ──────────────────────────────────────────────

export interface Vector2D {
  x: number; // 0–1200 logical units (1 unit = 0.1 yards)
  y: number; // 0–533 logical units
}

export type PlayerRole = 'QB' | 'RB' | 'WR' | 'TE' | 'OL' | 'DL' | 'LB' | 'CB' | 'S';
export type Side = 'offense' | 'defense';
export type Difficulty = 'beginner' | 'intermediate' | 'advanced';
export type TacticalLayer = 1 | 2 | 3 | 4;

export type ConceptCategory =
  | 'formation-offense'
  | 'formation-defense'
  | 'coverage'
  | 'blitz'
  | 'route-concept'
  | 'pocket-mechanics'
  | 'ball-physics'
  | 'geometry';

// ─── Token System ────────────────────────────────────────────

export interface TokenDefinition {
  shape: 'circle' | 'square' | 'triangle' | 'diamond';
  fill: string;
  stroke: string;
  size: number;
}

export const TOKEN_MAP: Record<PlayerRole, TokenDefinition> = {
  QB: { shape: 'circle', fill: '#3B82F6', stroke: '#1D4ED8', size: 16 },
  RB: { shape: 'square', fill: '#22C55E', stroke: '#15803D', size: 14 },
  WR: { shape: 'triangle', fill: '#EAB308', stroke: '#A16207', size: 14 },
  TE: { shape: 'diamond', fill: '#EF4444', stroke: '#B91C1C', size: 14 },
  OL: { shape: 'square', fill: '#9CA3AF', stroke: '#4B5563', size: 18 },
  DL: { shape: 'square', fill: '#374151', stroke: '#111827', size: 18 },
  LB: { shape: 'square', fill: '#A855F7', stroke: '#7E22CE', size: 16 },
  CB: { shape: 'circle', fill: '#EF4444', stroke: '#B91C1C', size: 14 },
  S: { shape: 'circle', fill: '#F97316', stroke: '#C2410C', size: 16 },
};

// ─── Player ──────────────────────────────────────────────────

export interface Player {
  id: string;
  role: PlayerRole;
  label: string;
  side: Side;
}

// ─── Paths ───────────────────────────────────────────────────

export interface PathStyle {
  color: string;
  width: number;
  dashArray?: number[];
  arrowHead?: boolean;
  opacity: number;
}

export interface StraightPath {
  type: 'straight';
  from: Vector2D;
  to: Vector2D;
  style: PathStyle;
}

export interface QuadraticPath {
  type: 'quadratic';
  from: Vector2D;
  control: Vector2D;
  to: Vector2D;
  style: PathStyle;
}

export interface CubicPath {
  type: 'cubic';
  from: Vector2D;
  control1: Vector2D;
  control2: Vector2D;
  to: Vector2D;
  style: PathStyle;
}

export interface ArcPath {
  type: 'arc';
  from: Vector2D;
  apex: Vector2D;
  to: Vector2D;
  style: PathStyle;
}

export type MotionPath = StraightPath | QuadraticPath | CubicPath | ArcPath;

// ─── Ball ────────────────────────────────────────────────────

export type BallTrajectoryType = 'line-drive' | 'rainbow' | 'screen' | 'handoff' | 'scramble';

export interface BallState {
  position: Vector2D;
  trajectory?: {
    type: BallTrajectoryType;
    path: ArcPath | StraightPath;
    airtimeMs: number;
    peakHeight: number;
  };
}

// ─── Overlays ────────────────────────────────────────────────

export interface ZoneOverlay {
  type: 'zone';
  id: string;
  label: string;
  points: Vector2D[];
  fill: string;
  opacity: number;
}

export interface GeometryOverlay {
  type: 'geometry';
  id: string;
  label: string;
  lines: Array<{
    from: Vector2D;
    to: Vector2D;
    style: PathStyle;
  }>;
}

export interface HighlightOverlay {
  type: 'highlight';
  id: string;
  playerIds: string[];
  color: string;
  pulse: boolean;
}

export type Overlay = ZoneOverlay | GeometryOverlay | HighlightOverlay;

// ─── Annotations ─────────────────────────────────────────────

export type AnnotationStyle = 'label' | 'callout' | 'arrow-label' | 'bracket';

export interface Annotation {
  id: string;
  position: Vector2D;
  text: string;
  style: AnnotationStyle;
  color?: string;
}

// ─── Phase (Animation Frame) ─────────────────────────────────

export interface PlayerState {
  playerId: string;
  position: Vector2D;
  paths?: MotionPath[];
  highlighted?: boolean;
  opacity?: number;
}

export interface Phase {
  id: number;
  label: string;
  description: string;
  durationMs: number;
  players: PlayerState[];
  ball?: BallState;
  overlays?: Overlay[];
  annotations?: Annotation[];
}

// ─── Concept (Core Unit) ─────────────────────────────────────

export interface Concept {
  id: string;
  slug: string;
  label: string;
  category: ConceptCategory;
  subcategory?: string;
  tags: string[];
  difficulty: Difficulty;
  layers: TacticalLayer[];
  description: string;
  explanation: string;
  roster: Player[];
  phases: Phase[];
  counters?: string[];
  related?: string[];
  ai_context?: string;
  created_at: string;
  updated_at: string;
}

// ─── Concept Summary (list endpoint) ─────────────────────────

export interface ConceptSummary {
  id: string;
  slug: string;
  label: string;
  category: ConceptCategory;
  subcategory?: string;
  tags: string[];
  difficulty: Difficulty;
  layers: TacticalLayer[];
  description: string;
}

// ─── API Response Types ──────────────────────────────────────

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
}

export interface AiQueryRequest {
  query: string;
  concept_slugs: string[];
  session_key?: string;
}

export interface AiQueryResponse {
  data: {
    response: string;
    intent: 'explain' | 'compare' | 'counter' | 'pre-snap-read' | 'recommend';
    concepts_used: string[];
  };
  session_key: string;
}

export interface AuthUser {
  id: number;
  name: string;
  email: string;
}
