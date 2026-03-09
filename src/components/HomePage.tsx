import { useEffect, useState, useMemo, useCallback } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuthStore } from '@/stores/authStore';
import { useUserStore } from '@/stores/userStore';
import { listConcepts } from '@/api/concepts';
import { logout } from '@/api/auth';
import type { ConceptSummary, ConceptCategory, Difficulty } from '@/types';
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

const DIFFICULTY_ORDER: Difficulty[] = ['beginner', 'intermediate', 'advanced'];

const DIFFICULTY_LABELS: Record<Difficulty, string> = {
  beginner: 'Beginner',
  intermediate: 'Intermediate',
  advanced: 'Advanced',
};

export function HomePage() {
  const user = useAuthStore((s) => s.user);
  const setUser = useAuthStore((s) => s.setUser);
  const [concepts, setConcepts] = useState<ConceptSummary[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [category, setCategory] = useState<ConceptCategory | ''>('');
  const bookmarkedSlugs = useUserStore((s) => s.bookmarkedSlugs);
  const completedSlugs = useUserStore((s) => s.completedSlugs);
  const sortPreference = useUserStore((s) => s.sortPreference);
  const setSortPreference = useUserStore((s) => s.setSortPreference);
  const clearLocal = useUserStore((s) => s.clearLocal);
  const navigate = useNavigate();

  const handleLogout = useCallback(async () => {
    try {
      await logout();
    } catch { /* ignore */ }
    setUser(null);
    clearLocal();
    navigate('/login');
  }, [setUser, clearLocal, navigate]);

  useEffect(() => {
    listConcepts({ sort: sortPreference })
      .then((res) => setConcepts(res.data))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, [sortPreference]);

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

  // Group by difficulty when in difficulty sort mode
  const groupedByDifficulty = useMemo(() => {
    if (sortPreference !== 'difficulty') return null;

    const groups: Record<Difficulty, ConceptSummary[]> = {
      beginner: [],
      intermediate: [],
      advanced: [],
    };

    for (const c of filtered) {
      groups[c.difficulty].push(c);
    }

    return groups;
  }, [filtered, sortPreference]);

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
          <Link to="/matchup" className="home-nav-link">Matchup</Link>
          <Link to="/primer" className="home-nav-link home-nav-primer">Football 101</Link>
          <Link to="/glossary" className="home-nav-link">Glossary</Link>
          {user ? (
            <>
              <span className="home-user">{user.name}</span>
              <button className="home-nav-link home-nav-logout" onClick={handleLogout}>
                Logout
              </button>
            </>
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
        <div className="home-sort-toggle">
          <button
            className={`home-sort-btn ${sortPreference === 'difficulty' ? 'active' : ''}`}
            onClick={() => setSortPreference('difficulty')}
            title="Sort by difficulty"
          >
            Level
          </button>
          <button
            className={`home-sort-btn ${sortPreference === 'alpha' ? 'active' : ''}`}
            onClick={() => setSortPreference('alpha')}
            title="Sort alphabetically"
          >
            A–Z
          </button>
        </div>
      </div>

      {/* Primer CTA */}
      <div className="home-primer-cta">
        <Link to="/primer" className="home-primer-cta-inner">
          <span className="home-primer-cta-label">New to football?</span>
          <span className="home-primer-cta-text">Start with Football 101 — learn the field, positions, and how to read diagrams</span>
          <span className="home-primer-cta-arrow">&rarr;</span>
        </Link>
      </div>

      {/* Concept grid */}
      <main className="home-grid-container">
        {loading && (
          <div className="home-loading">Loading concepts...</div>
        )}
        {!loading && filtered.length === 0 && (
          <div className="home-empty">No concepts found.</div>
        )}
        {!loading && filtered.length > 0 && sortPreference === 'difficulty' && groupedByDifficulty && (
          <div className="home-grouped">
            {DIFFICULTY_ORDER.map((diff) => {
              const group = groupedByDifficulty[diff];
              if (group.length === 0) return null;
              return (
                <section key={diff} className="home-difficulty-section">
                  <div className="home-section-header">
                    <span
                      className="home-section-title"
                      style={{ color: DIFFICULTY_COLORS[diff] }}
                    >
                      {DIFFICULTY_LABELS[diff]}
                    </span>
                    <span className="home-section-count">{group.length}</span>
                    <span className="home-section-line" style={{ borderColor: DIFFICULTY_COLORS[diff] }} />
                  </div>
                  <div className="home-grid">
                    {group.map((c) => (
                      <ConceptCard
                        key={c.slug}
                        concept={c}
                        isBookmarked={bookmarkedSlugs.includes(c.slug)}
                        isCompleted={completedSlugs.includes(c.slug)}
                      />
                    ))}
                  </div>
                </section>
              );
            })}
          </div>
        )}
        {!loading && filtered.length > 0 && sortPreference === 'alpha' && (
          <div className="home-grid">
            {filtered.map((c) => (
              <ConceptCard
                key={c.slug}
                concept={c}
                isBookmarked={bookmarkedSlugs.includes(c.slug)}
                isCompleted={completedSlugs.includes(c.slug)}
              />
            ))}
          </div>
        )}
      </main>
    </div>
  );
}

function ConceptCard({
  concept: c,
  isBookmarked,
  isCompleted,
}: {
  concept: ConceptSummary;
  isBookmarked: boolean;
  isCompleted: boolean;
}) {
  return (
    <Link to={`/lesson/${c.slug}`} className="concept-card">
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
          {isBookmarked && (
            <span className="concept-card-bookmarked">BM</span>
          )}
          {isCompleted && (
            <span className="concept-card-completed">OK</span>
          )}
        </div>
      </div>
    </Link>
  );
}
