import type { ReactNode } from 'react';

const FIELD_WIDTH = 1200;
const FIELD_HEIGHT = 533;
const YARD = 10; // 1 yard = 10 logical units

interface FootballFieldProps {
  children?: ReactNode;
  className?: string;
}

export function FootballField({ children, className = '' }: FootballFieldProps) {
  return (
    <svg
      viewBox={`0 0 ${FIELD_WIDTH} ${FIELD_HEIGHT}`}
      xmlns="http://www.w3.org/2000/svg"
      className={`w-full h-full ${className}`}
      style={{ background: 'var(--color-field-bg, #0f1419)' }}
    >
      <defs>
        <pattern id="field-hash" x="0" y="0" width={FIELD_WIDTH} height={FIELD_HEIGHT} patternUnits="userSpaceOnUse">
          {/* Hash marks rendered inline below */}
        </pattern>
      </defs>

      {/* Field surface */}
      <rect width={FIELD_WIDTH} height={FIELD_HEIGHT} fill="var(--color-field-green, #1a472a)" />

      {/* End zones */}
      <rect x={0} y={0} width={100} height={FIELD_HEIGHT} fill="#162d1e" opacity="0.6" />
      <rect x={1100} y={0} width={100} height={FIELD_HEIGHT} fill="#162d1e" opacity="0.6" />

      {/* End zone labels */}
      <text
        x={50} y={FIELD_HEIGHT / 2}
        fill="rgba(255,255,255,0.06)"
        fontSize="60"
        fontWeight="900"
        fontFamily="Arial Black, sans-serif"
        textAnchor="middle"
        dominantBaseline="central"
        transform={`rotate(-90, 50, ${FIELD_HEIGHT / 2})`}
      >
        END ZONE
      </text>
      <text
        x={1150} y={FIELD_HEIGHT / 2}
        fill="rgba(255,255,255,0.06)"
        fontSize="60"
        fontWeight="900"
        fontFamily="Arial Black, sans-serif"
        textAnchor="middle"
        dominantBaseline="central"
        transform={`rotate(90, 1150, ${FIELD_HEIGHT / 2})`}
      >
        END ZONE
      </text>

      {/* Sidelines */}
      <line x1={0} y1={0} x2={FIELD_WIDTH} y2={0} stroke="white" strokeWidth="2" opacity="0.3" />
      <line x1={0} y1={FIELD_HEIGHT} x2={FIELD_WIDTH} y2={FIELD_HEIGHT} stroke="white" strokeWidth="2" opacity="0.3" />
      <line x1={0} y1={0} x2={0} y2={FIELD_HEIGHT} stroke="white" strokeWidth="2" opacity="0.3" />
      <line x1={FIELD_WIDTH} y1={0} x2={FIELD_WIDTH} y2={FIELD_HEIGHT} stroke="white" strokeWidth="2" opacity="0.3" />

      {/* End zone lines */}
      <line x1={100} y1={0} x2={100} y2={FIELD_HEIGHT} stroke="white" strokeWidth="2" opacity="0.4" />
      <line x1={1100} y1={0} x2={1100} y2={FIELD_HEIGHT} stroke="white" strokeWidth="2" opacity="0.4" />

      {/* Yard lines every 5 yards (50 units) */}
      {Array.from({ length: 19 }, (_, i) => {
        const x = 150 + i * 50;
        const isTenYard = i % 2 === 0;
        return (
          <line
            key={`yl-${i}`}
            x1={x} y1={0} x2={x} y2={FIELD_HEIGHT}
            stroke="white"
            strokeWidth={isTenYard ? 1.5 : 0.8}
            opacity={isTenYard ? 0.25 : 0.12}
          />
        );
      })}

      {/* Hash marks */}
      {Array.from({ length: 99 }, (_, i) => {
        const x = 100 + (i + 1) * YARD;
        if (x % 50 === 0) return null; // skip yard lines
        const topHash = FIELD_HEIGHT * 0.295; // ~15.7 yards from sideline
        const bottomHash = FIELD_HEIGHT * 0.705;
        return (
          <g key={`hash-${i}`}>
            <line x1={x} y1={topHash - 3} x2={x} y2={topHash + 3} stroke="white" strokeWidth="0.8" opacity="0.15" />
            <line x1={x} y1={bottomHash - 3} x2={x} y2={bottomHash + 3} stroke="white" strokeWidth="0.8" opacity="0.15" />
          </g>
        );
      })}

      {/* Yard numbers */}
      {[10, 20, 30, 40, 50, 40, 30, 20, 10].map((num, i) => {
        const x = 200 + i * 100;
        return (
          <g key={`num-${i}`}>
            <text
              x={x} y={FIELD_HEIGHT * 0.15}
              fill="rgba(255,255,255,0.12)"
              fontSize="22"
              fontWeight="900"
              fontFamily="Arial Black, sans-serif"
              textAnchor="middle"
              dominantBaseline="central"
            >
              {num}
            </text>
            <text
              x={x} y={FIELD_HEIGHT * 0.85}
              fill="rgba(255,255,255,0.12)"
              fontSize="22"
              fontWeight="900"
              fontFamily="Arial Black, sans-serif"
              textAnchor="middle"
              dominantBaseline="central"
              transform={`rotate(180, ${x}, ${FIELD_HEIGHT * 0.85})`}
            >
              {num}
            </text>
          </g>
        );
      })}

      {/* Directional arrows */}
      {[1, 2, 3, 4].map((i) => {
        const xLeft = 100 + i * 100 + 50;
        const xRight = 1100 - i * 100 - 50;
        return (
          <g key={`arrow-${i}`}>
            {/* Left side arrows point right */}
            <path
              d={`M${xLeft - 6},${FIELD_HEIGHT * 0.15 + 18} L${xLeft + 6},${FIELD_HEIGHT * 0.15 + 18} L${xLeft},${FIELD_HEIGHT * 0.15 + 12} Z`}
              fill="rgba(255,255,255,0.08)"
              transform={`rotate(90, ${xLeft}, ${FIELD_HEIGHT * 0.15 + 15})`}
            />
            {/* Right side arrows point left */}
            <path
              d={`M${xRight - 6},${FIELD_HEIGHT * 0.15 + 18} L${xRight + 6},${FIELD_HEIGHT * 0.15 + 18} L${xRight},${FIELD_HEIGHT * 0.15 + 12} Z`}
              fill="rgba(255,255,255,0.08)"
              transform={`rotate(-90, ${xRight}, ${FIELD_HEIGHT * 0.15 + 15})`}
            />
          </g>
        );
      })}

      {/* Centre field logo */}
      <text
        x={FIELD_WIDTH / 2} y={FIELD_HEIGHT / 2}
        fill="rgba(0,229,255,0.04)"
        fontSize="40"
        fontWeight="bold"
        fontFamily="monospace"
        textAnchor="middle"
        dominantBaseline="central"
      >
        NB
      </text>

      {/* Children (player tokens, overlays, etc.) rendered on top */}
      {children}
    </svg>
  );
}
