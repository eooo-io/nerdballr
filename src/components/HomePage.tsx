import { Link } from 'react-router-dom';
import { useAuthStore } from '@/stores/authStore';

export function HomePage() {
  const user = useAuthStore((s) => s.user);

  return (
    <div className="flex flex-col items-center justify-center min-h-screen gap-6 p-8">
      <h1 className="font-display text-3xl font-bold tracking-tight text-text-primary">
        NerdBall<span className="text-amber">r</span>
      </h1>
      <p className="font-system text-xs tracking-widest uppercase text-cyan-dim">
        Football Field Intelligence System
      </p>
      {user && (
        <p className="font-mono text-xs text-text-secondary">
          Welcome, {user.name}
        </p>
      )}
      <div className="flex gap-4 mt-4">
        <Link
          to="/lesson/cover-two"
          className="font-system text-xs tracking-wider uppercase text-cyan border border-navy-border px-4 py-2 hover:bg-cyan/5 transition-colors"
        >
          [ Lesson ]
        </Link>
        <Link
          to="/compare"
          className="font-system text-xs tracking-wider uppercase text-cyan border border-navy-border px-4 py-2 hover:bg-cyan/5 transition-colors"
        >
          [ Compare ]
        </Link>
        <Link
          to="/geometry-lab"
          className="font-system text-xs tracking-wider uppercase text-cyan border border-navy-border px-4 py-2 hover:bg-cyan/5 transition-colors"
        >
          [ Geometry Lab ]
        </Link>
      </div>
      <p className="font-mono text-xs text-text-secondary/40 mt-8">
        Concept library &middot; Field renderer &middot; Animation engine — coming soon
      </p>
    </div>
  );
}
