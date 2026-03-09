import { useEffect } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { Layout } from '@/components/Layout';
import { LoginPage } from '@/components/LoginPage';
import { HomePage } from '@/components/HomePage';
import { LessonPage } from '@/features/lesson/LessonPage';
import { ComparePage } from '@/features/compare/ComparePage';
import { GeometryLabPage } from '@/features/geometry-lab/GeometryLabPage';
import { PrimerPage } from '@/components/PrimerPage';
import { ErrorBoundary } from '@/components/ErrorBoundary';
import { useAuthStore } from '@/stores/authStore';
import { useUserStore } from '@/stores/userStore';
import { getUser } from '@/api/auth';

function AppInit() {
  const setUser = useAuthStore((s) => s.setUser);
  const setLoading = useAuthStore((s) => s.setLoading);
  const syncFromServer = useUserStore((s) => s.syncFromServer);

  useEffect(() => {
    // Try to restore authenticated session
    getUser()
      .then((user) => {
        setUser(user);
        syncFromServer();
      })
      .catch(() => {
        setUser(null);
      })
      .finally(() => {
        setLoading(false);
      });
  }, [setUser, setLoading, syncFromServer]);

  return null;
}

export function App() {
  return (
    <BrowserRouter>
      <ErrorBoundary>
        <AppInit />
        <Routes>
          <Route path="/login" element={<LoginPage />} />
          <Route element={<Layout />}>
            <Route path="/" element={<HomePage />} />
            <Route path="/lesson/:slug" element={<LessonPage />} />
            <Route path="/compare" element={<ComparePage />} />
            <Route path="/geometry-lab" element={<GeometryLabPage />} />
            <Route path="/primer" element={<PrimerPage />} />
          </Route>
        </Routes>
      </ErrorBoundary>
    </BrowserRouter>
  );
}
