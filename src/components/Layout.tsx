import { Outlet } from 'react-router-dom';

export function Layout() {
  return (
    <div className="min-h-screen bg-navy text-text-primary">
      <a
        href="#main-content"
        className="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[999] focus:bg-navy focus:text-cyan focus:border focus:border-cyan focus:px-4 focus:py-2 focus:font-system focus:text-xs focus:uppercase focus:tracking-wider"
      >
        Skip to content
      </a>
      <main id="main-content">
        <Outlet />
      </main>
    </div>
  );
}
