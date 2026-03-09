import { useEffect, useState, useCallback, useMemo } from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { listConcepts, getConcept } from '@/api/concepts';
import { FootballField } from '@/components/field/FootballField';
import { PlayerToken } from '@/components/field/PlayerToken';
import { MotionPathRenderer, PathArrowDefs } from '@/components/field/MotionPathRenderer';
import { ZoneOverlayComponent } from '@/components/field/ZoneOverlay';
import { AnnotationComponent } from '@/components/field/Annotation';
import { BallToken } from '@/components/field/BallToken';
import { AiSidebar } from '@/components/ai';
import type { Concept, ConceptSummary, Phase, Vector2D } from '@/types';
import './MatchupPage.css';

const OFFENSE_CATEGORIES = ['formation-offense', 'route-concept'];
const DEFENSE_CATEGORIES = ['formation-defense', 'coverage', 'blitz'];

// Push each side away from the LOS so they don't overlap
const LOS = 600;
const SPREAD = 60; // 6 yards of extra separation per side

function resolvePositions(phases: Phase[], index: number): Map<string, Vector2D> {
  const positions = new Map<string, Vector2D>();
  for (let i = 0; i <= index; i++) {
    for (const ps of phases[i].players) {
      positions.set(ps.playerId, ps.position);
    }
  }
  return positions;
}

export function MatchupPage() {
  const [concepts, setConcepts] = useState<ConceptSummary[]>([]);
  const [catalogLoading, setCatalogLoading] = useState(true);

  const [offense, setOffense] = useState<Concept | null>(null);
  const [defense, setDefense] = useState<Concept | null>(null);
  const [loadingO, setLoadingO] = useState(false);
  const [loadingD, setLoadingD] = useState(false);

  // Playback state (local, not shared with other pages)
  const [currentPhase, setCurrentPhase] = useState(0);
  const [isPlaying, setIsPlaying] = useState(false);
  const [speed, setSpeed] = useState(1);

  useEffect(() => {
    listConcepts({ sort: 'alpha' })
      .then((res) => setConcepts(res.data))
      .catch(() => {})
      .finally(() => setCatalogLoading(false));
  }, []);

  const offenseConcepts = useMemo(
    () => concepts.filter((c) => OFFENSE_CATEGORIES.includes(c.category)),
    [concepts],
  );
  const defenseConcepts = useMemo(
    () => concepts.filter((c) => DEFENSE_CATEGORIES.includes(c.category)),
    [concepts],
  );

  const handleSelectOffense = useCallback((slug: string) => {
    if (!slug) { setOffense(null); return; }
    setLoadingO(true);
    setCurrentPhase(0);
    setIsPlaying(false);
    getConcept(slug)
      .then(setOffense)
      .catch(() => setOffense(null))
      .finally(() => setLoadingO(false));
  }, []);

  const handleSelectDefense = useCallback((slug: string) => {
    if (!slug) { setDefense(null); return; }
    setLoadingD(true);
    setCurrentPhase(0);
    setIsPlaying(false);
    getConcept(slug)
      .then(setDefense)
      .catch(() => setDefense(null))
      .finally(() => setLoadingD(false));
  }, []);

  // Phase count = max of both concepts
  const totalPhases = Math.max(
    offense?.phases.length ?? 0,
    defense?.phases.length ?? 0,
  );

  // Auto-advance
  useEffect(() => {
    if (!isPlaying || totalPhases === 0) return;

    const offPhase = offense?.phases[Math.min(currentPhase, (offense?.phases.length ?? 1) - 1)];
    const defPhase = defense?.phases[Math.min(currentPhase, (defense?.phases.length ?? 1) - 1)];
    const duration = Math.max(offPhase?.durationMs ?? 1000, defPhase?.durationMs ?? 1000) / speed;

    const timer = setTimeout(() => {
      if (currentPhase < totalPhases - 1) {
        setCurrentPhase((p) => p + 1);
      } else {
        setIsPlaying(false);
      }
    }, duration);
    return () => clearTimeout(timer);
  }, [isPlaying, currentPhase, totalPhases, speed, offense, defense]);

  const conceptSlugs = useMemo(() => {
    const slugs: string[] = [];
    if (offense) slugs.push(offense.slug);
    if (defense) slugs.push(defense.slug);
    return slugs;
  }, [offense, defense]);

  // Resolve positions for both sides
  const offPhaseIdx = offense ? Math.min(currentPhase, offense.phases.length - 1) : 0;
  const defPhaseIdx = defense ? Math.min(currentPhase, defense.phases.length - 1) : 0;

  const offPositions = offense ? resolvePositions(offense.phases, offPhaseIdx) : new Map();
  const defPositions = defense ? resolvePositions(defense.phases, defPhaseIdx) : new Map();

  const offRosterMap = useMemo(() => new Map(offense?.roster.map((p) => [p.id, p]) ?? []), [offense]);
  const defRosterMap = useMemo(() => new Map(defense?.roster.map((p) => [p.id, p]) ?? []), [defense]);

  const offPhase = offense?.phases[offPhaseIdx];
  const defPhase = defense?.phases[defPhaseIdx];
  const transitionDuration = (() => {
    const d = Math.max(offPhase?.durationMs ?? 1000, defPhase?.durationMs ?? 1000);
    return (d / 1000) / speed;
  })();

  return (
    <div className="matchup-container">
      {/* Header */}
      <header className="matchup-header">
        <Link to="/" className="matchup-back">&larr; Library</Link>
        <h1 className="matchup-title">Matchup</h1>
        <span className="matchup-subtitle">Offense vs Defense on one field</span>
      </header>

      {/* Selectors */}
      <div className="matchup-selectors">
        <div className="matchup-selector">
          <label className="matchup-selector-label matchup-label-offense">Offense</label>
          <select
            className="matchup-selector-select"
            value={offense?.slug ?? ''}
            onChange={(e) => handleSelectOffense(e.target.value)}
            disabled={catalogLoading}
          >
            <option value="">— Choose offense —</option>
            {offenseConcepts.map((c) => (
              <option key={c.slug} value={c.slug}>
                {c.label}
              </option>
            ))}
          </select>
        </div>

        <div className="matchup-vs">VS</div>

        <div className="matchup-selector">
          <label className="matchup-selector-label matchup-label-defense">Defense</label>
          <select
            className="matchup-selector-select"
            value={defense?.slug ?? ''}
            onChange={(e) => handleSelectDefense(e.target.value)}
            disabled={catalogLoading}
          >
            <option value="">— Choose defense —</option>
            {defenseConcepts.map((c) => (
              <option key={c.slug} value={c.slug}>
                {c.label}
              </option>
            ))}
          </select>
        </div>
      </div>

      {/* Field */}
      <div className="matchup-field-wrap">
        {(loadingO || loadingD) && (
          <div className="matchup-loading">Loading...</div>
        )}

        {!loadingO && !loadingD && !offense && !defense && (
          <div className="matchup-empty">Select an offense and defense to see them face off</div>
        )}

        {(offense || defense) && !loadingO && !loadingD && (
          <FootballField>
            <defs>
              <PathArrowDefs />
            </defs>

            {/* Line of scrimmage */}
            <line x1={600} y1={0} x2={600} y2={533} stroke="var(--color-cyan)" strokeWidth="1.5" opacity="0.25" strokeDasharray="8 4" />

            {/* Side labels */}
            {offense && (
              <text x={LOS - SPREAD - 70} y={16} fill="var(--color-cyan)" fontSize="9" fontFamily="monospace" textAnchor="middle" opacity="0.5" fontWeight="bold">
                {offense.label.toUpperCase()} →
              </text>
            )}
            {defense && (
              <text x={LOS + SPREAD + 70} y={16} fill="rgba(239,68,68,0.5)" fontSize="9" fontFamily="monospace" textAnchor="middle" fontWeight="bold">
                ← {defense.label.toUpperCase()}
              </text>
            )}

            {/* ── Offense group (shifted left) ── */}
            <g transform={`translate(${-SPREAD}, 0)`}>
              {offPhase?.overlays?.filter((o) => o.type === 'zone').map((o) => (
                <ZoneOverlayComponent key={`off-${o.id}`} overlay={o} />
              ))}

              {offPhase?.players.map((ps) =>
                ps.paths?.map((path, pi) => (
                  <MotionPathRenderer
                    key={`off-${ps.playerId}-path-${pi}`}
                    path={path}
                    animated={currentPhase > 0}
                  />
                )),
              )}

              {offPhase?.ball && (
                <motion.g
                  animate={{ x: offPhase.ball.position.x, y: offPhase.ball.position.y }}
                  initial={false}
                  transition={{ duration: transitionDuration, ease: 'easeInOut' }}
                >
                  <BallToken ball={{ ...offPhase.ball, position: { x: 0, y: 0 } }} />
                </motion.g>
              )}

              {Array.from(offPositions.entries()).map(([playerId, pos]) => {
                const player = offRosterMap.get(playerId);
                if (!player) return null;
                const ps = offPhase?.players.find((p) => p.playerId === playerId);
                return (
                  <motion.g
                    key={`off-${playerId}`}
                    animate={{ x: pos.x, y: pos.y }}
                    initial={false}
                    transition={{ duration: transitionDuration, ease: 'easeInOut' }}
                  >
                    <PlayerToken
                      role={player.role}
                      position={{ x: 0, y: 0 }}
                      label={player.label || player.id}
                      highlighted={ps?.highlighted ?? false}
                      opacity={ps?.opacity ?? 1}
                    />
                  </motion.g>
                );
              })}

              {offPhase?.annotations?.map((a) => (
                <AnnotationComponent key={`off-${a.id}`} annotation={a} />
              ))}
            </g>

            {/* ── Defense group (shifted right) ── */}
            <g transform={`translate(${SPREAD}, 0)`}>
              {defPhase?.overlays?.filter((o) => o.type === 'zone').map((o) => (
                <ZoneOverlayComponent key={`def-${o.id}`} overlay={o} />
              ))}

              {defPhase?.players.map((ps) =>
                ps.paths?.map((path, pi) => (
                  <MotionPathRenderer
                    key={`def-${ps.playerId}-path-${pi}`}
                    path={path}
                    animated={currentPhase > 0}
                  />
                )),
              )}

              {Array.from(defPositions.entries()).map(([playerId, pos]) => {
                const player = defRosterMap.get(playerId);
                if (!player) return null;
                const ps = defPhase?.players.find((p) => p.playerId === playerId);
                return (
                  <motion.g
                    key={`def-${playerId}`}
                    animate={{ x: pos.x, y: pos.y }}
                    initial={false}
                    transition={{ duration: transitionDuration, ease: 'easeInOut' }}
                  >
                    <PlayerToken
                      role={player.role}
                      position={{ x: 0, y: 0 }}
                      label={player.label || player.id}
                      highlighted={ps?.highlighted ?? false}
                      opacity={ps?.opacity ?? 1}
                    />
                  </motion.g>
                );
              })}

              {defPhase?.annotations?.map((a) => (
                <AnnotationComponent key={`def-${a.id}`} annotation={a} />
              ))}
            </g>
          </FootballField>
        )}
      </div>

      {/* Playback controls */}
      {totalPhases > 0 && (
        <div className="matchup-playback">
          <button
            className="matchup-pb-btn"
            onClick={() => setCurrentPhase(Math.max(0, currentPhase - 1))}
            disabled={currentPhase === 0}
          >
            ◀ Prev
          </button>
          <button
            className="matchup-pb-btn matchup-pb-play"
            onClick={() => setIsPlaying(!isPlaying)}
          >
            {isPlaying ? '⏸ Pause' : '▶ Play'}
          </button>
          <button
            className="matchup-pb-btn"
            onClick={() => setCurrentPhase(Math.min(totalPhases - 1, currentPhase + 1))}
            disabled={currentPhase >= totalPhases - 1}
          >
            Next ▶
          </button>
          <span className="matchup-pb-phase">
            Phase {currentPhase + 1} / {totalPhases}
          </span>
          <div className="matchup-pb-speed">
            {[0.5, 1, 2].map((s) => (
              <button
                key={s}
                className={`matchup-speed-btn ${speed === s ? 'active' : ''}`}
                onClick={() => setSpeed(s)}
              >
                {s}x
              </button>
            ))}
          </div>
          <button
            className="matchup-pb-btn"
            onClick={() => { setCurrentPhase(0); setIsPlaying(false); }}
          >
            ⟲ Reset
          </button>
        </div>
      )}

      {/* Phase descriptions */}
      {(offense || defense) && !loadingO && !loadingD && (
        <div className="matchup-phase-info">
          {offense && offPhase && (
            <div className="matchup-phase-desc matchup-phase-offense">
              <span className="matchup-phase-label">Offense — {offPhase.label}</span>
              <p>{offPhase.description}</p>
            </div>
          )}
          {defense && defPhase && (
            <div className="matchup-phase-desc matchup-phase-defense">
              <span className="matchup-phase-label">Defense — {defPhase.label}</span>
              <p>{defPhase.description}</p>
            </div>
          )}
        </div>
      )}

      {/* AI Assistant */}
      {conceptSlugs.length > 0 && (
        <AiSidebar conceptSlugs={conceptSlugs} />
      )}
    </div>
  );
}
