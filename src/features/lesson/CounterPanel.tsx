import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { getConceptCounters } from '@/api/concepts';
import type { ConceptSummary } from '@/types';
import './CounterPanel.css';

const DIFFICULTY_COLORS: Record<string, string> = {
  beginner: 'var(--color-cyan)',
  intermediate: 'var(--color-amber)',
  advanced: '#ef4444',
};

interface CounterPanelProps {
  slug: string;
}

function CounterCard({ concept }: { concept: ConceptSummary }) {
  return (
    <Link to={`/lesson/${concept.slug}`} className="counter-card">
      <div className="counter-card-header">
        <span className="counter-card-category">
          {concept.category.replace(/-/g, ' ')}
        </span>
        <span
          className="counter-card-difficulty"
          style={{ color: DIFFICULTY_COLORS[concept.difficulty] }}
        >
          {concept.difficulty}
        </span>
      </div>
      <h4 className="counter-card-title">{concept.label}</h4>
      <p className="counter-card-desc">{concept.description}</p>
    </Link>
  );
}

export function CounterPanel({ slug }: CounterPanelProps) {
  const [counters, setCounters] = useState<ConceptSummary[]>([]);
  const [counteredBy, setCounteredBy] = useState<ConceptSummary[]>([]);
  const [loading, setLoading] = useState(true);
  const [expanded, setExpanded] = useState(false);

  useEffect(() => {
    setLoading(true);
    setExpanded(false);
    getConceptCounters(slug)
      .then((data) => {
        setCounters(data.counters);
        setCounteredBy(data.countered_by);
      })
      .catch(() => {
        setCounters([]);
        setCounteredBy([]);
      })
      .finally(() => setLoading(false));
  }, [slug]);

  if (loading) return null;
  if (counters.length === 0 && counteredBy.length === 0) return null;

  return (
    <div className="counter-panel">
      {/* What Beats This */}
      {counters.length > 0 && (
        <section className="counter-section">
          <button
            className="counter-section-header"
            onClick={() => setExpanded(!expanded)}
            aria-expanded={expanded}
          >
            <span className="counter-section-icon">&#9650;</span>
            <h2 className="counter-section-title">What Beats This</h2>
            <span className="counter-section-count">{counters.length}</span>
            <span className="counter-section-chevron">{expanded ? '−' : '+'}</span>
          </button>
          {!expanded && (
            <div className="counter-preview">
              {counters.map((c) => (
                <Link
                  key={c.slug}
                  to={`/lesson/${c.slug}`}
                  className="counter-pill"
                >
                  {c.label}
                </Link>
              ))}
            </div>
          )}
          {expanded && (
            <div className="counter-cards">
              {counters.map((c) => (
                <CounterCard key={c.slug} concept={c} />
              ))}
            </div>
          )}
        </section>
      )}

      {/* This Concept Counters */}
      {counteredBy.length > 0 && (
        <section className="counter-section">
          <div className="counter-section-header counter-section-header-static">
            <span className="counter-section-icon counter-icon-green">&#9660;</span>
            <h2 className="counter-section-title">This Concept Counters</h2>
            <span className="counter-section-count">{counteredBy.length}</span>
          </div>
          <div className="counter-preview">
            {counteredBy.map((c) => (
              <Link
                key={c.slug}
                to={`/lesson/${c.slug}`}
                className="counter-pill counter-pill-green"
              >
                {c.label}
              </Link>
            ))}
          </div>
        </section>
      )}
    </div>
  );
}
