import { useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { useConceptStore } from '@/stores/conceptStore';
import { usePlaybackStore } from '@/stores/playbackStore';
import { AnimatedField, PlaybackControls } from '@/components/field';
import { AiSidebar } from '@/components/ai';
import './LessonPage.css';

export function LessonPage() {
  const { slug } = useParams<{ slug: string }>();
  const { concept, isLoading, error, loadConcept, clearConcept } = useConceptStore();
  const reset = usePlaybackStore((s) => s.reset);
  const currentPhaseIndex = usePlaybackStore((s) => s.currentPhase);

  useEffect(() => {
    if (slug) loadConcept(slug);
    return () => {
      clearConcept();
      reset();
    };
  }, [slug, loadConcept, clearConcept, reset]);

  if (isLoading) {
    return (
      <div className="lesson-loading">
        <div className="lesson-loading-spinner" />
        <span className="lesson-loading-text">Loading concept...</span>
      </div>
    );
  }

  if (error || !concept) {
    return (
      <div className="lesson-error">
        <span className="lesson-error-icon">!</span>
        <p className="lesson-error-text">{error || 'Concept not found.'}</p>
        <Link to="/" className="lesson-error-link">Back to library</Link>
      </div>
    );
  }

  const phase = concept.phases[currentPhaseIndex];

  return (
    <div className="lesson-container">
      {/* Header bar */}
      <header className="lesson-header">
        <Link to="/" className="lesson-back">&larr; Library</Link>
        <div className="lesson-header-info">
          <h1 className="lesson-title">{concept.label}</h1>
          <div className="lesson-meta">
            <span className="lesson-category">{concept.category.replace('-', ' ')}</span>
            <span className="lesson-difficulty">{concept.difficulty}</span>
            {concept.tags.map((t) => (
              <span key={t} className="lesson-tag">{t}</span>
            ))}
          </div>
        </div>
      </header>

      {/* Main content */}
      <div className="lesson-body">
        {/* Left: field + controls */}
        <div className="lesson-field-panel">
          <AnimatedField concept={concept} className="lesson-field" />
          <PlaybackControls phases={concept.phases} />
        </div>

        {/* Right: explanation panel */}
        <aside className="lesson-info-panel">
          <section className="lesson-section">
            <h2 className="lesson-section-title">Overview</h2>
            <p className="lesson-description">{concept.description}</p>
          </section>

          <section className="lesson-section">
            <h2 className="lesson-section-title">Explanation</h2>
            <p className="lesson-explanation">{concept.explanation}</p>
          </section>

          {phase && (
            <section className="lesson-section">
              <h2 className="lesson-section-title">
                Phase {currentPhaseIndex + 1}: {phase.label}
              </h2>
              <p className="lesson-phase-desc">{phase.description}</p>
            </section>
          )}

          {concept.counters && concept.counters.length > 0 && (
            <section className="lesson-section">
              <h2 className="lesson-section-title">Counters</h2>
              <div className="lesson-links">
                {concept.counters.map((c) => (
                  <Link key={c} to={`/lesson/${c}`} className="lesson-link">{c}</Link>
                ))}
              </div>
            </section>
          )}

          {concept.related && concept.related.length > 0 && (
            <section className="lesson-section">
              <h2 className="lesson-section-title">Related</h2>
              <div className="lesson-links">
                {concept.related.map((r) => (
                  <Link key={r} to={`/lesson/${r}`} className="lesson-link">{r}</Link>
                ))}
              </div>
            </section>
          )}
        </aside>
      </div>

      {/* AI Assistant */}
      <AiSidebar conceptSlugs={[concept.slug]} />
    </div>
  );
}
