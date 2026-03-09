import { TOKEN_MAP } from '@/types';
import type { PlayerRole } from '@/types';

const SHAPE_LABELS: Record<string, string> = {
  circle: 'Circle',
  square: 'Square',
  triangle: 'Triangle',
  diamond: 'Diamond',
};

interface TokenEntry {
  role: PlayerRole;
  name: string;
}

const OFFENSE_TOKENS: TokenEntry[] = [
  { role: 'QB', name: 'Quarterback' },
  { role: 'RB', name: 'Running Back' },
  { role: 'WR', name: 'Wide Receiver' },
  { role: 'TE', name: 'Tight End' },
  { role: 'OL', name: 'Offensive Line' },
];

const DEFENSE_TOKENS: TokenEntry[] = [
  { role: 'DL', name: 'Defensive Line' },
  { role: 'LB', name: 'Linebacker' },
  { role: 'CB', name: 'Cornerback' },
  { role: 'S', name: 'Safety' },
];

function MiniToken({ role, size = 18 }: { role: PlayerRole; size?: number }) {
  const token = TOKEN_MAP[role];
  const cx = size / 2;
  const cy = size / 2;
  const s = size * 0.35;

  return (
    <svg width={size} height={size} viewBox={`0 0 ${size} ${size}`} aria-hidden="true">
      {token.shape === 'circle' && (
        <circle cx={cx} cy={cy} r={s} fill={token.fill} stroke={token.stroke} strokeWidth="1.5" />
      )}
      {token.shape === 'square' && (
        <rect x={cx - s} y={cy - s} width={s * 2} height={s * 2} rx="1" fill={token.fill} stroke={token.stroke} strokeWidth="1" />
      )}
      {token.shape === 'triangle' && (
        <polygon
          points={`${cx},${cy - s} ${cx + s},${cy + s * 0.8} ${cx - s},${cy + s * 0.8}`}
          fill={token.fill} stroke={token.stroke} strokeWidth="1"
        />
      )}
      {token.shape === 'diamond' && (
        <polygon
          points={`${cx},${cy - s} ${cx + s},${cy} ${cx},${cy + s} ${cx - s},${cy}`}
          fill={token.fill} stroke={token.stroke} strokeWidth="1"
        />
      )}
    </svg>
  );
}

function TokenRow({ entry }: { entry: TokenEntry }) {
  const token = TOKEN_MAP[entry.role];
  return (
    <div className="token-legend-row">
      <MiniToken role={entry.role} />
      <span className="token-legend-role">{entry.role}</span>
      <span className="token-legend-name">{entry.name}</span>
      <span className="token-legend-shape">{SHAPE_LABELS[token.shape]}</span>
    </div>
  );
}

export function TokenLegend({ compact = false }: { compact?: boolean }) {
  return (
    <div className={`token-legend ${compact ? 'token-legend-compact' : ''}`}>
      <div className="token-legend-group">
        <h4 className="token-legend-group-title" style={{ color: 'var(--color-cyan)' }}>Offense</h4>
        {OFFENSE_TOKENS.map((entry) => (
          <TokenRow key={entry.role} entry={entry} />
        ))}
      </div>
      <div className="token-legend-group">
        <h4 className="token-legend-group-title" style={{ color: '#ef4444' }}>Defense</h4>
        {DEFENSE_TOKENS.map((entry) => (
          <TokenRow key={entry.role} entry={entry} />
        ))}
      </div>
    </div>
  );
}
