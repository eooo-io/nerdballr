import type { MotionPath } from '@/types';

interface MotionPathRendererProps {
  path: MotionPath;
  animated?: boolean;
}

function buildPathD(path: MotionPath): string {
  switch (path.type) {
    case 'straight':
      return `M${path.from.x},${path.from.y} L${path.to.x},${path.to.y}`;
    case 'quadratic':
      return `M${path.from.x},${path.from.y} Q${path.control.x},${path.control.y} ${path.to.x},${path.to.y}`;
    case 'cubic':
      return `M${path.from.x},${path.from.y} C${path.control1.x},${path.control1.y} ${path.control2.x},${path.control2.y} ${path.to.x},${path.to.y}`;
    case 'arc': {
      // Approximate arc via quadratic using apex as control point
      return `M${path.from.x},${path.from.y} Q${path.apex.x},${path.apex.y} ${path.to.x},${path.to.y}`;
    }
  }
}

export function MotionPathRenderer({ path, animated = false }: MotionPathRendererProps) {
  const d = buildPathD(path);
  const { style } = path;

  const dashArray = animated ? '400' : style.dashArray?.join(',') ?? undefined;

  return (
    <path
      d={d}
      fill="none"
      stroke={style.color}
      strokeWidth={style.width}
      strokeDasharray={dashArray}
      strokeDashoffset={animated ? '400' : undefined}
      opacity={style.opacity}
      markerEnd={style.arrowHead ? `url(#arrow-${style.color.replace('#', '')})` : undefined}
      style={animated ? { animation: 'drawPath 1.2s cubic-bezier(0.4,0,0.2,1) forwards' } : undefined}
    />
  );
}

/**
 * Renders arrow marker definitions for commonly used path colors.
 * Include this once inside an SVG <defs> block.
 */
export function PathArrowDefs() {
  const colors = [
    { id: '00e5ff', color: '#00e5ff' },
    { id: 'ffb800', color: '#ffb800' },
    { id: 'ef4444', color: '#ef4444' },
    { id: '22c55e', color: '#22c55e' },
    { id: '3b82f6', color: '#3b82f6' },
    { id: 'a855f7', color: '#a855f7' },
    { id: 'f97316', color: '#f97316' },
    { id: 'eab308', color: '#eab308' },
    { id: '9ca3af', color: '#9ca3af' },
  ];

  return (
    <>
      {colors.map(({ id, color }) => (
        <marker
          key={id}
          id={`arrow-${id}`}
          markerWidth="8"
          markerHeight="8"
          refX="6"
          refY="4"
          orient="auto"
        >
          <polygon points="0 0, 8 4, 0 8" fill={color} opacity="0.9" />
        </marker>
      ))}
    </>
  );
}
