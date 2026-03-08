import { useEffect, useState, useMemo, useCallback } from 'react';
import { Link } from 'react-router-dom';
import { listConcepts, getConcept } from '@/api/concepts';
import { FootballField } from '@/components/field';
import { GeometryOverlayComponent, ZoneOverlayComponent, AnnotationComponent, PlayerToken } from '@/components/field';
import type { ConceptSummary, Concept, Vector2D } from '@/types';
import './GeometryLabPage.css';

export function GeometryLabPage() {
  const [concepts, setConcepts] = useState<ConceptSummary[]>([]);
  const [selectedSlug, setSelectedSlug] = useState<string>('');
  const [concept, setConcept] = useState<Concept | null>(null);
  const [loading, setLoading] = useState(false);
  const [phaseIndex, setPhaseIndex] = useState(0);

  // Slider controls for geometry manipulation
  const [angle, setAngle] = useState(45);
  const [distance, setDistance] = useState(200);

  // Load geometry concepts
  useEffect(() => {
    listConcepts({ category: 'geometry' })
      .then((res) => {
        setConcepts(res.data);
        if (res.data.length > 0) {
          setSelectedSlug(res.data[0].slug);
        }
      })
      .catch(() => {});
  }, []);

  // Load selected concept
  useEffect(() => {
    if (!selectedSlug) return;
    setLoading(true);
    getConcept(selectedSlug)
      .then((c) => {
        setConcept(c);
        setPhaseIndex(0);
      })
      .catch(() => {})
      .finally(() => setLoading(false));
  }, [selectedSlug]);

  const phase = concept?.phases[phaseIndex];
  const rosterMap = useMemo(
    () => new Map(concept?.roster.map((p) => [p.id, p]) ?? []),
    [concept],
  );

  // Compute dynamic geometry overlay based on slider values
  const dynamicOverlay = useMemo(() => {
    if (!concept || !phase) return null;

    // Create an angle-based geometry from the slider values
    const rad = (angle * Math.PI) / 180;
    const cx = 600;
    const cy = 266;
    const endX = cx + distance * Math.cos(rad);
    const endY = cy - distance * Math.sin(rad);

    return {
      type: 'geometry' as const,
      id: 'dynamic-angle',
      label: `${angle}°`,
      lines: [
        {
          from: { x: cx, y: cy } as Vector2D,
          to: { x: cx + distance, y: cy } as Vector2D,
          style: { color: '#94a3b8', width: 1.5, dashArray: [4, 4], opacity: 0.5 },
        },
        {
          from: { x: cx, y: cy } as Vector2D,
          to: { x: endX, y: endY } as Vector2D,
          style: { color: '#00e5ff', width: 2, opacity: 0.8 },
        },
      ],
    };
  }, [angle, distance, concept, phase]);

  const handleConceptChange = useCallback((slug: string) => {
    setSelectedSlug(slug);
  }, []);

  return (
    <div className="geolab-container">
      {/* Header */}
      <header className="geolab-header">
        <Link to="/" className="geolab-back">&larr; Library</Link>
        <h1 className="geolab-title">Geometry Lab</h1>
      </header>

      <div className="geolab-body">
        {/* Field */}
        <div className="geolab-field-panel">
          {loading && <div className="geolab-loading">Loading...</div>}
          {concept && phase && !loading && (
            <FootballField>
              {/* Static overlays from the concept phase */}
              {phase.overlays?.filter((o) => o.type === 'zone').map((o) => (
                <ZoneOverlayComponent key={o.id} overlay={o} />
              ))}
              {phase.overlays?.filter((o) => o.type === 'geometry').map((o) => (
                <GeometryOverlayComponent key={o.id} overlay={o} />
              ))}

              {/* Dynamic angle overlay from sliders */}
              {dynamicOverlay && (
                <GeometryOverlayComponent overlay={dynamicOverlay} />
              )}

              {/* Static player positions */}
              {phase.players.map((ps) => {
                const player = rosterMap.get(ps.playerId);
                if (!player) return null;
                return (
                  <g key={ps.playerId} transform={`translate(${ps.position.x},${ps.position.y})`}>
                    <PlayerToken
                      role={player.role}
                      position={{ x: 0, y: 0 }}
                      label={player.label || player.id}
                    />
                  </g>
                );
              })}

              {/* Annotations */}
              {phase.annotations?.map((a) => (
                <AnnotationComponent key={a.id} annotation={a} />
              ))}
            </FootballField>
          )}
          {!concept && !loading && (
            <div className="geolab-empty">
              No geometry concepts available. Seed the database first.
            </div>
          )}
        </div>

        {/* Controls panel */}
        <aside className="geolab-controls">
          {/* Concept selector */}
          <div className="geolab-section">
            <label className="geolab-label">Concept</label>
            <select
              className="geolab-select"
              value={selectedSlug}
              onChange={(e) => handleConceptChange(e.target.value)}
            >
              {concepts.map((c) => (
                <option key={c.slug} value={c.slug}>{c.label}</option>
              ))}
            </select>
          </div>

          {/* Phase selector */}
          {concept && concept.phases.length > 1 && (
            <div className="geolab-section">
              <label className="geolab-label">Phase</label>
              <div className="geolab-phase-btns">
                {concept.phases.map((p, i) => (
                  <button
                    key={p.id}
                    className={`geolab-phase-btn ${i === phaseIndex ? 'active' : ''}`}
                    onClick={() => setPhaseIndex(i)}
                  >
                    {i + 1}
                  </button>
                ))}
              </div>
            </div>
          )}

          {/* Description */}
          {concept && (
            <div className="geolab-section">
              <label className="geolab-label">Description</label>
              <p className="geolab-desc">{concept.description}</p>
              {phase && <p className="geolab-phase-desc">{phase.description}</p>}
            </div>
          )}

          {/* Angle slider */}
          <div className="geolab-section">
            <label className="geolab-label">
              Angle: <span className="geolab-value">{angle}°</span>
            </label>
            <input
              type="range"
              min="0"
              max="90"
              value={angle}
              onChange={(e) => setAngle(Number(e.target.value))}
              className="geolab-slider"
            />
          </div>

          {/* Distance slider */}
          <div className="geolab-section">
            <label className="geolab-label">
              Distance: <span className="geolab-value">{distance} units</span>
            </label>
            <input
              type="range"
              min="50"
              max="500"
              value={distance}
              onChange={(e) => setDistance(Number(e.target.value))}
              className="geolab-slider"
            />
          </div>

          {/* Explanation */}
          {concept && (
            <div className="geolab-section">
              <label className="geolab-label">Explanation</label>
              <p className="geolab-explanation">{concept.explanation}</p>
            </div>
          )}
        </aside>
      </div>
    </div>
  );
}
