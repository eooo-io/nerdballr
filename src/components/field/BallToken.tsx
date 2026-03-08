import type { BallState } from '@/types';

interface BallTokenProps {
  ball: BallState;
}

export function BallToken({ ball }: BallTokenProps) {
  return (
    <g>
      {/* Ball glow */}
      <circle
        cx={ball.position.x}
        cy={ball.position.y}
        r="8"
        fill="#8B4513"
        fillOpacity="0.15"
      />
      {/* Ball */}
      <circle
        cx={ball.position.x}
        cy={ball.position.y}
        r="5"
        fill="#8B4513"
        stroke="#5C2D0E"
        strokeWidth="1.5"
      />
      {/* Laces */}
      <line
        x1={ball.position.x - 2}
        y1={ball.position.y - 1}
        x2={ball.position.x + 2}
        y2={ball.position.y - 1}
        stroke="white"
        strokeWidth="0.8"
        opacity="0.6"
      />
      <line
        x1={ball.position.x - 2}
        y1={ball.position.y + 1}
        x2={ball.position.x + 2}
        y2={ball.position.y + 1}
        stroke="white"
        strokeWidth="0.8"
        opacity="0.6"
      />
    </g>
  );
}
