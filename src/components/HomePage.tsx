import { useEffect, useState, useMemo } from 'react';
import { Link } from 'react-router-dom';
import { useAuthStore } from '@/stores/authStore';
import { useUserStore } from '@/stores/userStore';
import { listConcepts } from '@/api/concepts';
import type { ConceptSummary, ConceptCategory } from '@/types';
import './HomePage.css';

const CATEGORIES: { value: ConceptCategory | ''; label: string }[] = [
  { value: '', label: 'All' },
  { value: 'formation-offense', label: 'Offense' },
  { value: 'formation-defense', label: 'Defense' },
  { value: 'coverage', label: 'Coverage' },
  { value: 'blitz', label: 'Blitz' },
  { value: 'route-concept', label: 'Routes' },
  { value: 'geometry', label: 'Geometry' },
];

const DIFFICULTY_COLORS: Record<string, string> = {
  beginner: 'var(--color-cyan)',
  intermediate: 'var(--color-amber)',
  advanced: '#ef4444',
};

export function HomePage() {
  const user = useAuthStore((s) => s.user);
  const [concepts, setConcepts] = useState<ConceptSummary[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [category, setCategory] = useState<ConceptCategory | ''>('');
  const bookmarkedSlugs = useUserStore((s) => s.bookmarkedSlugs);
  const completedSlugs = useUserStore((s) => s.completedSlugs);

  useEffect(() => {
    listConcepts()
      .then((res) => setConcepts(res.data))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  const filtered = useMemo(() => {
    let list = concepts;
    if (category) {
      list = list.filter((c) => c.category === category);
    }
    if (search.trim()) {
      const q = search.toLowerCase();
      list = list.filter(
        (c) =>
          c.label.toLowerCase().includes(q) ||
          c.description.toLowerCase().includes(q) ||
          c.tags.some((t) => t.toLowerCase().includes(q)),
      );
    }
    return list;
  }, [concepts, category, search]);

  return (
    <div className="home-container">
      {/* Header */}
      <header className="home-header">
        <div className="home-brand">
          <h1 className="home-logo">
            NerdBall<span className="home-logo-accent">r</span>
          </h1>
          <span className="home-subtitle">Football Field Intelligence System</span>
        </div>
        <nav className="home-nav">
          <Link to="/compare" className="home-nav-link">Compare</Link>
          <Link to="/geometry-lab" className="home-nav-link">Geometry Lab</Link>
          {user ? (
            <span className="home-user">{user.name}</span>
          ) : (
            <Link to="/login" className="home-nav-link home-nav-login">Sign In</Link>
          )}
        </nav>
      </header>

      {/* Filters */}
      <div className="home-filters">
        <input
          type="text"
          className="home-search"
          placeholder="Search concepts..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
        <div className="home-categories">
          {CATEGORIES.map((cat) => (
            <button
              key={cat.value}
              className={`home-cat-btn ${category === cat.value ? 'active' : ''}`}
              onClick={() => setCategory(cat.value)}
            >
              {cat.label}
            </button>
          ))}
        </div>
      </div>

      {/* Concept grid */}
      <main className="home-grid-container">
        {loading && (
          <div className="home-loading">Loading concepts...</div>
        )}
        {!loading && filtered.length === 0 && (
          <div className="home-empty">No concepts found.</div>
        )}
        {!loading && filtered.length > 0 && (
          <div className="home-grid">
            {filtered.map((c) => (
              <Link key={c.slug} to={`/lesson/${c.slug}`} className="concept-card">
                <div className="concept-card-header">
                  <span className="concept-card-category">
                    {c.category.replace('-', ' ')}
                  </span>
                  <span
                    className="concept-card-difficulty"
                    style={{ color: DIFFICULTY_COLORS[c.difficulty] }}
                  >
                    {c.difficulty}
                  </span>
                </div>
                <h3 className="concept-card-title">{c.label}</h3>
                <p className="concept-card-desc">{c.description}</p>
                <div className="concept-card-tags">
                  {c.tags.slice(0, 4).map((t) => (
                    <span key={t} className="concept-card-tag">{t}</span>
                  ))}
                </div>
                <div className="concept-card-footer">
                  <div className="concept-card-layers">
                    {c.layers.map((l) => (
                      <span key={l} className="concept-card-layer">L{l}</span>
                    ))}
                  </div>
                  <div className="concept-card-badges">
                    {bookmarkedSlugs.includes(c.slug) && (
                      <span className="concept-card-bookmarked">BM</span>
                    )}
                    {completedSlugs.includes(c.slug) && (
                      <span className="concept-card-completed">OK</span>
                    )}
                  </div>
                </div>
              </Link>
            ))}
          </div>
        )}
      </main>
    </div>
  );
}
