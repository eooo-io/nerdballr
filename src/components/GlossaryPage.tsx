import { useEffect, useState, useMemo } from 'react';
import { Link } from 'react-router-dom';
import { listGlossaryTerms } from '@/api/glossary';
import type { GlossaryTerm, GlossaryCategory } from '@/api/glossary';
import './GlossaryPage.css';

const CATEGORIES: { value: GlossaryCategory | ''; label: string }[] = [
  { value: '', label: 'All' },
  { value: 'offense', label: 'Offense' },
  { value: 'defense', label: 'Defense' },
  { value: 'scheme', label: 'Scheme' },
  { value: 'general', label: 'General' },
];

const CATEGORY_COLORS: Record<GlossaryCategory, string> = {
  offense: 'var(--color-cyan)',
  defense: '#ef4444',
  scheme: 'var(--color-amber)',
  general: 'var(--color-text-secondary)',
};

export function GlossaryPage() {
  const [terms, setTerms] = useState<GlossaryTerm[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [category, setCategory] = useState<GlossaryCategory | ''>('');
  const [expandedSlug, setExpandedSlug] = useState<string | null>(null);

  useEffect(() => {
    listGlossaryTerms()
      .then(setTerms)
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  const filtered = useMemo(() => {
    let list = terms;
    if (category) {
      list = list.filter((t) => t.category === category);
    }
    if (search.trim()) {
      const q = search.toLowerCase();
      list = list.filter(
        (t) =>
          t.term.toLowerCase().includes(q) ||
          t.definition.toLowerCase().includes(q),
      );
    }
    return list;
  }, [terms, category, search]);

  const toggleExpand = (slug: string) => {
    setExpandedSlug((prev) => (prev === slug ? null : slug));
  };

  return (
    <div className="glossary-container">
      {/* Header */}
      <header className="glossary-header">
        <Link to="/" className="glossary-back">&larr; Library</Link>
        <h1 className="glossary-title">Glossary</h1>
        <span className="glossary-subtitle">{terms.length} terms</span>
      </header>

      {/* Filters */}
      <div className="glossary-filters">
        <input
          type="text"
          className="glossary-search"
          placeholder="Search terms..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          aria-label="Search glossary terms"
        />
        <div className="glossary-categories">
          {CATEGORIES.map((cat) => (
            <button
              key={cat.value}
              className={`glossary-cat-btn ${category === cat.value ? 'active' : ''}`}
              onClick={() => setCategory(cat.value)}
            >
              {cat.label}
            </button>
          ))}
        </div>
      </div>

      {/* Term list */}
      <main className="glossary-content">
        {loading && (
          <div className="glossary-loading">Loading glossary...</div>
        )}
        {!loading && filtered.length === 0 && (
          <div className="glossary-empty">No terms found.</div>
        )}
        {!loading && filtered.length > 0 && (
          <div className="glossary-list">
            {filtered.map((term) => {
              const isExpanded = expandedSlug === term.slug;
              return (
                <div
                  key={term.slug}
                  className={`glossary-term ${isExpanded ? 'expanded' : ''}`}
                >
                  <button
                    className="glossary-term-header"
                    onClick={() => toggleExpand(term.slug)}
                    aria-expanded={isExpanded}
                  >
                    <span className="glossary-term-name">{term.term}</span>
                    <span
                      className="glossary-term-category"
                      style={{ color: CATEGORY_COLORS[term.category] }}
                    >
                      {term.category}
                    </span>
                    <span className="glossary-term-chevron">
                      {isExpanded ? '−' : '+'}
                    </span>
                  </button>
                  {isExpanded && (
                    <div className="glossary-term-body">
                      <p className="glossary-term-definition">{term.definition}</p>
                      {term.related_concepts.length > 0 && (
                        <div className="glossary-related">
                          <span className="glossary-related-label">Related concepts:</span>
                          <div className="glossary-related-links">
                            {term.related_concepts.map((slug) => (
                              <Link
                                key={slug}
                                to={`/lesson/${slug}`}
                                className="glossary-related-link"
                              >
                                {slug.replace(/-/g, ' ')}
                              </Link>
                            ))}
                          </div>
                        </div>
                      )}
                      {term.related_terms.length > 0 && (
                        <div className="glossary-related">
                          <span className="glossary-related-label">See also:</span>
                          <div className="glossary-related-links">
                            {term.related_terms.map((rt) => {
                              const matchedTerm = terms.find((t) => t.slug === rt);
                              return matchedTerm ? (
                                <button
                                  key={rt}
                                  className="glossary-related-term"
                                  onClick={() => {
                                    setExpandedSlug(rt);
                                    document.getElementById(`term-${rt}`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                  }}
                                >
                                  {matchedTerm.term}
                                </button>
                              ) : (
                                <span key={rt} className="glossary-related-term-text">
                                  {rt.replace(/-/g, ' ')}
                                </span>
                              );
                            })}
                          </div>
                        </div>
                      )}
                    </div>
                  )}
                  {/* Anchor for scrolling */}
                  <div id={`term-${term.slug}`} />
                </div>
              );
            })}
          </div>
        )}
      </main>
    </div>
  );
}
