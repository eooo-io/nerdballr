import { Link } from 'react-router-dom';
import { FootballField } from '@/components/field/FootballField';
import { PlayerToken } from '@/components/field/PlayerToken';
import { TokenLegend } from '@/components/TokenLegend';
import './PrimerPage.css';
import './TokenLegend.css';

// ─── Standard I-Formation Offense vs 4-3 Defense ────────────
// All 22 players positioned at the 50 yard line (x=600)

const OFFENSE_PLAYERS = [
  { id: 'C', role: 'OL' as const, label: 'C', x: 600, y: 266 },
  { id: 'LG', role: 'OL' as const, label: 'LG', x: 600, y: 240 },
  { id: 'RG', role: 'OL' as const, label: 'RG', x: 600, y: 292 },
  { id: 'LT', role: 'OL' as const, label: 'LT', x: 600, y: 214 },
  { id: 'RT', role: 'OL' as const, label: 'RT', x: 600, y: 318 },
  { id: 'QB', role: 'QB' as const, label: 'QB', x: 560, y: 266 },
  { id: 'FB', role: 'RB' as const, label: 'FB', x: 530, y: 266 },
  { id: 'TB', role: 'RB' as const, label: 'TB', x: 500, y: 266 },
  { id: 'WR1', role: 'WR' as const, label: 'X', x: 600, y: 50 },
  { id: 'WR2', role: 'WR' as const, label: 'Z', x: 600, y: 483 },
  { id: 'TE', role: 'TE' as const, label: 'TE', x: 600, y: 340 },
];

const DEFENSE_PLAYERS = [
  { id: 'DT1', role: 'DL' as const, label: 'DT', x: 630, y: 250 },
  { id: 'DT2', role: 'DL' as const, label: 'DT', x: 630, y: 282 },
  { id: 'DE1', role: 'DL' as const, label: 'DE', x: 630, y: 218 },
  { id: 'DE2', role: 'DL' as const, label: 'DE', x: 630, y: 330 },
  { id: 'WILL', role: 'LB' as const, label: 'W', x: 660, y: 220 },
  { id: 'MIKE', role: 'LB' as const, label: 'M', x: 660, y: 266 },
  { id: 'SAM', role: 'LB' as const, label: 'S', x: 660, y: 320 },
  { id: 'CB1', role: 'CB' as const, label: 'CB', x: 640, y: 60 },
  { id: 'CB2', role: 'CB' as const, label: 'CB', x: 640, y: 473 },
  { id: 'FS', role: 'S' as const, label: 'FS', x: 720, y: 220 },
  { id: 'SS', role: 'S' as const, label: 'SS', x: 720, y: 310 },
];

export function PrimerPage() {
  return (
    <div className="primer-container">
      {/* Header */}
      <header className="primer-header">
        <Link to="/" className="primer-back">&larr; Library</Link>
        <h1 className="primer-title">Football 101</h1>
        <span className="primer-subtitle">The basics before you dive in</span>
      </header>

      <div className="primer-content">

        {/* ─── Section 1: The Field ───────────────────────── */}
        <section className="primer-section">
          <h2 className="primer-section-title">
            <span className="primer-section-number">01</span>
            The Field
          </h2>
          <div className="primer-field-wrapper">
            <FootballField>
              {/* Line of scrimmage highlight */}
              <line x1={600} y1={0} x2={600} y2={533} stroke="var(--color-cyan)" strokeWidth="2" opacity="0.4" strokeDasharray="8 4" />
              <text x={600} y={20} fill="var(--color-cyan)" fontSize="8" fontFamily="monospace" textAnchor="middle" opacity="0.7">
                LINE OF SCRIMMAGE
              </text>

              {/* Red zone indicators */}
              <rect x={100} y={0} width={200} height={533} fill="rgba(239, 68, 68, 0.04)" />
              <rect x={900} y={0} width={200} height={533} fill="rgba(239, 68, 68, 0.04)" />
              <text x={200} y={520} fill="rgba(239,68,68,0.3)" fontSize="8" fontFamily="monospace" textAnchor="middle">RED ZONE</text>
              <text x={1000} y={520} fill="rgba(239,68,68,0.3)" fontSize="8" fontFamily="monospace" textAnchor="middle">RED ZONE</text>

              {/* Sideline labels */}
              <text x={600} y={10} fill="rgba(255,255,255,0.2)" fontSize="7" fontFamily="monospace" textAnchor="middle">SIDELINE</text>
              <text x={600} y={528} fill="rgba(255,255,255,0.2)" fontSize="7" fontFamily="monospace" textAnchor="middle">SIDELINE</text>
            </FootballField>
          </div>
          <div className="primer-text">
            <p>A football field is <strong>100 yards</strong> long with a <strong>10-yard end zone</strong> at each end (120 yards total). The field is 53⅓ yards wide.</p>
            <p>The <strong>line of scrimmage</strong> is where each play starts — it moves up the field as the offense advances. The <strong>red zone</strong> is the last 20 yards before the end zone, where scoring is most likely.</p>
            <p><strong>Hash marks</strong> run down the field and mark each yard. They're important because the ball is always placed on or between the hashes to start each play.</p>
          </div>
        </section>

        {/* ─── Section 2: The Teams ───────────────────────── */}
        <section className="primer-section">
          <h2 className="primer-section-title">
            <span className="primer-section-number">02</span>
            The Teams
          </h2>
          <div className="primer-field-wrapper">
            <FootballField>
              {/* Line of scrimmage */}
              <line x1={600} y1={0} x2={600} y2={533} stroke="var(--color-cyan)" strokeWidth="1.5" opacity="0.3" strokeDasharray="8 4" />

              {/* Side labels */}
              <text x={530} y={148} fill="var(--color-cyan)" fontSize="10" fontFamily="monospace" textAnchor="middle" opacity="0.5" fontWeight="bold">
                OFFENSE →
              </text>
              <text x={680} y={148} fill="rgba(239,68,68,0.5)" fontSize="10" fontFamily="monospace" textAnchor="middle" fontWeight="bold">
                ← DEFENSE
              </text>

              {/* Offense */}
              {OFFENSE_PLAYERS.map((p) => (
                <PlayerToken key={p.id} role={p.role} position={{ x: p.x, y: p.y }} label={p.label} />
              ))}

              {/* Defense */}
              {DEFENSE_PLAYERS.map((p) => (
                <PlayerToken key={p.id} role={p.role} position={{ x: p.x, y: p.y }} label={p.label} />
              ))}

              {/* Position group brackets */}
              <text x={565} y={195} fill="rgba(156,163,175,0.3)" fontSize="7" fontFamily="monospace" textAnchor="end">O-LINE</text>
              <text x={488} y={250} fill="rgba(156,163,175,0.3)" fontSize="7" fontFamily="monospace" textAnchor="end">BACKFIELD</text>
              <text x={665} y={195} fill="rgba(156,163,175,0.3)" fontSize="7" fontFamily="monospace">D-LINE</text>
              <text x={695} y={255} fill="rgba(156,163,175,0.3)" fontSize="7" fontFamily="monospace">LINEBACKERS</text>
              <text x={750} y={264} fill="rgba(156,163,175,0.3)" fontSize="7" fontFamily="monospace">SAFETIES</text>
            </FootballField>
          </div>
          <div className="primer-text">
            <p>Each team has <strong>11 players</strong> on the field. The <strong>offense</strong> tries to move the ball toward the opponent's end zone. The <strong>defense</strong> tries to stop them.</p>
            <p>This shows an <strong>I-Formation</strong> (offense) against a <strong>4-3 Defense</strong> — the most fundamental matchup in football. The offense lines up behind the line of scrimmage, and the defense faces them.</p>
          </div>
        </section>

        {/* ─── Section 3: Token Legend ────────────────────── */}
        <section className="primer-section">
          <h2 className="primer-section-title">
            <span className="primer-section-number">03</span>
            Reading the Tokens
          </h2>
          <div className="primer-text">
            <p>Every player position has a unique <strong>shape</strong> and <strong>color</strong>. This system is consistent across the entire app — once you learn it, you can read any diagram instantly.</p>
          </div>
          <div className="primer-legend-wrapper">
            <TokenLegend />
          </div>
          <div className="primer-text primer-text-note">
            <p>Notice: cornerbacks (CB) share the circle shape with QB and Safety but have a distinct red color. Defensive linemen (DL) and offensive linemen (OL) both use squares but differ in shade — dark vs. light gray.</p>
          </div>
        </section>

        {/* ─── Section 4: Basic Flow ─────────────────────── */}
        <section className="primer-section">
          <h2 className="primer-section-title">
            <span className="primer-section-number">04</span>
            How Football Works
          </h2>
          <div className="primer-text">
            <div className="primer-concept-grid">
              <div className="primer-concept-card">
                <h4 className="primer-concept-label">Downs</h4>
                <p>The offense gets <strong>4 attempts (downs)</strong> to move the ball 10 yards. If they make it, they get 4 more tries. If not, the other team takes over.</p>
              </div>
              <div className="primer-concept-card">
                <h4 className="primer-concept-label">Scoring</h4>
                <p><strong>Touchdown = 6 pts</strong> (carry or catch the ball into the end zone). Then attempt an extra point (1 pt kick) or two-point conversion. <strong>Field goal = 3 pts</strong> (kick through the uprights).</p>
              </div>
              <div className="primer-concept-card">
                <h4 className="primer-concept-label">Possession</h4>
                <p>Teams take turns on offense and defense. Turnovers (interceptions, fumbles) give the ball to the other team immediately — that's why they're so impactful.</p>
              </div>
              <div className="primer-concept-card">
                <h4 className="primer-concept-label">Play Clock</h4>
                <p>The offense has <strong>40 seconds</strong> between plays to line up and snap the ball. Each play lasts only a few seconds — most of the strategy happens before the snap.</p>
              </div>
            </div>
          </div>
        </section>

        {/* ─── Section 5: Using the App ──────────────────── */}
        <section className="primer-section">
          <h2 className="primer-section-title">
            <span className="primer-section-number">05</span>
            Using NerdBallr
          </h2>
          <div className="primer-text">
            <div className="primer-steps">
              <div className="primer-step">
                <span className="primer-step-num">1</span>
                <div>
                  <strong>Browse the Library</strong> — concepts are organized by difficulty (beginner → advanced) and category (offense, defense, coverage, routes, etc.)
                </div>
              </div>
              <div className="primer-step">
                <span className="primer-step-num">2</span>
                <div>
                  <strong>Watch the Phases</strong> — each concept plays out in animated phases. Use the playback controls to step through at your own pace.
                </div>
              </div>
              <div className="primer-step">
                <span className="primer-step-num">3</span>
                <div>
                  <strong>Read the Explanation</strong> — every concept has a detailed breakdown explaining what's happening and why it works.
                </div>
              </div>
              <div className="primer-step">
                <span className="primer-step-num">4</span>
                <div>
                  <strong>Ask the AI</strong> — the tactical assistant can explain concepts, compare formations, and suggest what to study next.
                </div>
              </div>
              <div className="primer-step">
                <span className="primer-step-num">5</span>
                <div>
                  <strong>Compare Side-by-Side</strong> — use Compare mode to see how two concepts interact. Synced playback shows both diagrams animating together.
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* CTA */}
        <div className="primer-cta">
          <Link to="/" className="primer-cta-btn">
            Start Exploring the Library →
          </Link>
        </div>
      </div>
    </div>
  );
}
