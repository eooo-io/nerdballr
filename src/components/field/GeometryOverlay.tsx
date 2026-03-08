import type { GeometryOverlay as GeometryOverlayType } from '@/types';

interface GeometryOverlayProps {
  overlay: GeometryOverlayType;
}

export function GeometryOverlayComponent({ overlay }: GeometryOverlayProps) {
  return (
    <g>
      {overlay.lines.map((line, i) => (
        <line
          key={`${overlay.id}-line-${i}`}
          x1={line.from.x} y1={line.from.y}
          x2={line.to.x} y2={line.to.y}
          stroke={line.style.color}
          strokeWidth={line.style.width}
          strokeDasharray={line.style.dashArray?.join(',') ?? undefined}
          opacity={line.style.opacity}
          markerEnd={line.style.arrowHead ? `url(#arrow-${line.style.color.replace('#', '')})` : undefined}
        />
      ))}
      {overlay.label && overlay.lines.length > 0 && (() => {
        const first = overlay.lines[0];
        const midX = (first.from.x + first.to.x) / 2;
        const midY = (first.from.y + first.to.y) / 2;
        return (
          <text
            x={midX} y={midY - 12}
            fill={first.style.color}
            fontSize="9"
            fontFamily="monospace"
            textAnchor="middle"
            opacity={0.7}
          >
            {overlay.label}
          </text>
        );
      })()}
    </g>
  );
}
