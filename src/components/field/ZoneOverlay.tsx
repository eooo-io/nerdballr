import type { ZoneOverlay as ZoneOverlayType } from '@/types';

interface ZoneOverlayProps {
  overlay: ZoneOverlayType;
}

export function ZoneOverlayComponent({ overlay }: ZoneOverlayProps) {
  const points = overlay.points.map(p => `${p.x},${p.y}`).join(' ');

  return (
    <g>
      <polygon
        points={points}
        fill={overlay.fill}
        fillOpacity={overlay.opacity}
        stroke={overlay.fill}
        strokeWidth="1"
        strokeOpacity={overlay.opacity * 0.6}
      />
      {overlay.label && overlay.points.length > 0 && (
        <text
          x={overlay.points.reduce((sum, p) => sum + p.x, 0) / overlay.points.length}
          y={overlay.points.reduce((sum, p) => sum + p.y, 0) / overlay.points.length}
          fill={overlay.fill}
          fontSize="10"
          fontFamily="monospace"
          fontWeight="bold"
          textAnchor="middle"
          dominantBaseline="central"
          opacity={overlay.opacity * 1.5}
        >
          {overlay.label}
        </text>
      )}
    </g>
  );
}
