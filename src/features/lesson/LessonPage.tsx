import { useParams } from 'react-router-dom';

export function LessonPage() {
  const { slug } = useParams<{ slug: string }>();
  return (
    <div className="flex items-center justify-center min-h-screen">
      <p className="font-mono text-cyan-dim text-sm tracking-wider uppercase">
        Lesson: {slug ?? 'none'} — coming in Phase 7
      </p>
    </div>
  );
}
