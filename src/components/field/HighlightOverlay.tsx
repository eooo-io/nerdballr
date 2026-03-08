import type { HighlightOverlay as HighlightOverlayType, Vector2D } from '@/types';

interface HighlightOverlayProps {
  overlay: HighlightOverlayType;
  playerPositions: Map<string, Vector2D>;
}

export function HighlightOverlayComponent({ overlay, playerPositions }: HighlightOverlayProps) {
  return (
    <g>
      {overlay.playerIds.map(playerId => {
        const pos = playerPositions.get(playerId);
        if (!pos) return null;

        return (
          <circle
            key={`highlight-${overlay.id}-${playerId}`}
            cx={pos.x}
            cy={pos.y}
            r={22}
            fill="none"
            stroke={overlay.color}
            strokeWidth="2"
            opacity="0.5"
          >
            {overlay.pulse && (
              <>
                <animate
                  attributeName="r"
                  values="18;24;18"
                  dur="1.5s"
                  repeatCount="indefinite"
                />
                <animate
                  attributeName="opacity"
                  values="0.5;0.2;0.5"
                  dur="1.5s"
                  repeatCount="indefinite"
                />
              </>
            )}
          </circle>
        );
      })}
    </g>
  );
}
