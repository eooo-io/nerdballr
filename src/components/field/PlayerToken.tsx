import { TOKEN_MAP } from '@/types';
import type { PlayerRole, Vector2D } from '@/types';

interface PlayerTokenProps {
  role: PlayerRole;
  position: Vector2D;
  label?: string;
  highlighted?: boolean;
  opacity?: number;
}

function CircleToken({ x, y, fill, stroke, size, label }: {
  x: number; y: number; fill: string; stroke: string; size: number; label?: string;
}) {
  return (
    <g>
      <circle cx={x} cy={y} r={size} fill={fill} stroke={stroke} strokeWidth="2" />
      {label && (
        <text
          x={x} y={y + 1}
          fill="white"
          fontSize="9"
          fontWeight="bold"
          fontFamily="monospace"
          textAnchor="middle"
          dominantBaseline="central"
        >
          {label}
        </text>
      )}
    </g>
  );
}

function SquareToken({ x, y, fill, stroke, size, label }: {
  x: number; y: number; fill: string; stroke: string; size: number; label?: string;
}) {
  return (
    <g>
      <rect
        x={x - size} y={y - size}
        width={size * 2} height={size * 2}
        rx="2"
        fill={fill} stroke={stroke} strokeWidth="1.5"
      />
      {label && (
        <text
          x={x} y={y + 1}
          fill="white"
          fontSize="9"
          fontWeight="bold"
          fontFamily="monospace"
          textAnchor="middle"
          dominantBaseline="central"
        >
          {label}
        </text>
      )}
    </g>
  );
}

function TriangleToken({ x, y, fill, stroke, size, label }: {
  x: number; y: number; fill: string; stroke: string; size: number; label?: string;
}) {
  const points = [
    `${x},${y - size}`,
    `${x + size},${y + size * 0.8}`,
    `${x - size},${y + size * 0.8}`,
  ].join(' ');

  return (
    <g>
      <polygon points={points} fill={fill} stroke={stroke} strokeWidth="1.5" />
      {label && (
        <text
          x={x} y={y + size * 0.2 + 1}
          fill="white"
          fontSize="8"
          fontWeight="bold"
          fontFamily="monospace"
          textAnchor="middle"
          dominantBaseline="central"
        >
          {label}
        </text>
      )}
    </g>
  );
}

function DiamondToken({ x, y, fill, stroke, size, label }: {
  x: number; y: number; fill: string; stroke: string; size: number; label?: string;
}) {
  const points = [
    `${x},${y - size}`,
    `${x + size},${y}`,
    `${x},${y + size}`,
    `${x - size},${y}`,
  ].join(' ');

  return (
    <g>
      <polygon points={points} fill={fill} stroke={stroke} strokeWidth="1.5" />
      {label && (
        <text
          x={x} y={y + 1}
          fill="white"
          fontSize="8"
          fontWeight="bold"
          fontFamily="monospace"
          textAnchor="middle"
          dominantBaseline="central"
        >
          {label}
        </text>
      )}
    </g>
  );
}

const SHAPE_COMPONENTS = {
  circle: CircleToken,
  square: SquareToken,
  triangle: TriangleToken,
  diamond: DiamondToken,
} as const;

export function PlayerToken({ role, position, label, highlighted, opacity = 1 }: PlayerTokenProps) {
  const token = TOKEN_MAP[role];
  const ShapeComponent = SHAPE_COMPONENTS[token.shape];

  return (
    <g
      opacity={opacity}
      filter={highlighted ? 'url(#playerHighlight)' : undefined}
      style={{ transition: 'opacity 0.3s ease' }}
    >
      {highlighted && (
        <>
          <defs>
            <filter id="playerHighlight" x="-50%" y="-50%" width="200%" height="200%">
              <feGaussianBlur stdDeviation="4" result="blur" />
              <feComposite in="SourceGraphic" in2="blur" operator="over" />
            </filter>
          </defs>
          <circle
            cx={position.x} cy={position.y}
            r={token.size + 6}
            fill="none"
            stroke={token.fill}
            strokeWidth="1.5"
            opacity="0.4"
          >
            <animate attributeName="r" values={`${token.size + 4};${token.size + 8};${token.size + 4}`} dur="1.5s" repeatCount="indefinite" />
            <animate attributeName="opacity" values="0.4;0.15;0.4" dur="1.5s" repeatCount="indefinite" />
          </circle>
        </>
      )}
      <ShapeComponent
        x={position.x}
        y={position.y}
        fill={token.fill}
        stroke={token.stroke}
        size={token.size}
        label={label}
      />
    </g>
  );
}
