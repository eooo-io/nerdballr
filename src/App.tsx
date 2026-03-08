import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { Layout } from '@/components/Layout';
import { LoginPage } from '@/components/LoginPage';
import { HomePage } from '@/components/HomePage';
import { LessonPage } from '@/features/lesson/LessonPage';
import { ComparePage } from '@/features/compare/ComparePage';
import { GeometryLabPage } from '@/features/geometry-lab/GeometryLabPage';

export function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={<LoginPage />} />
        <Route element={<Layout />}>
          <Route path="/" element={<HomePage />} />
          <Route path="/lesson/:slug" element={<LessonPage />} />
          <Route path="/compare" element={<ComparePage />} />
          <Route path="/geometry-lab" element={<GeometryLabPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}
