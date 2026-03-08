import { useEffect, useCallback } from 'react';
import { motion } from 'framer-motion';
import { FootballField } from './FootballField';
import { PlayerToken } from './PlayerToken';
import { MotionPathRenderer, PathArrowDefs } from './MotionPathRenderer';
import { ZoneOverlayComponent } from './ZoneOverlay';
import { GeometryOverlayComponent } from './GeometryOverlay';
import { HighlightOverlayComponent } from './HighlightOverlay';
import { AnnotationComponent } from './Annotation';
import { BallToken } from './BallToken';
import { usePlaybackStore } from '@/stores/playbackStore';
import type { Concept, Phase, Vector2D, Player } from '@/types';

interface AnimatedFieldProps {
  concept: Concept;
  className?: string;
}

/**
 * Resolves the full position map for a given phase by carrying forward
 * positions from earlier phases (players not mentioned in a phase
 * keep their last known position).
 */
function resolvePlayerPositions(phases: Phase[], currentPhaseIndex: number): Map<string, Vector2D> {
  const positions = new Map<string, Vector2D>();
  for (let i = 0; i <= currentPhaseIndex; i++) {
    for (const ps of phases[i].players) {
      positions.set(ps.playerId, ps.position);
    }
  }
  return positions;
}

function getPlayerLabel(player: Player): string {
  return player.label || player.id;
}

export function AnimatedField({ concept, className = '' }: AnimatedFieldProps) {
  const { currentPhase, isPlaying, speed, nextPhase, setTotalPhases } = usePlaybackStore();

  // Set total phases when concept changes
  useEffect(() => {
    setTotalPhases(concept.phases.length);
  }, [concept, setTotalPhases]);

  // Auto-advance phase when playing
  const advancePhase = useCallback(() => {
    nextPhase();
  }, [nextPhase]);

  useEffect(() => {
    if (!isPlaying) return;

    const phase = concept.phases[currentPhase];
    if (!phase) return;

    const duration = phase.durationMs / speed;
    const timer = setTimeout(advancePhase, duration);
    return () => clearTimeout(timer);
  }, [isPlaying, currentPhase, speed, concept.phases, advancePhase]);

  const phase = concept.phases[currentPhase];
  if (!phase) return null;

  const positions = resolvePlayerPositions(concept.phases, currentPhase);
  const rosterMap = new Map(concept.roster.map(p => [p.id, p]));
  const transitionDuration = (phase.durationMs / 1000) / speed;

  return (
    <div className={className}>
      <FootballField>
        <defs>
          <PathArrowDefs />
        </defs>

        {/* Zone overlays */}
        {phase.overlays?.filter(o => o.type === 'zone').map(o => (
          <ZoneOverlayComponent key={o.id} overlay={o} />
        ))}

        {/* Geometry overlays */}
        {phase.overlays?.filter(o => o.type === 'geometry').map(o => (
          <GeometryOverlayComponent key={o.id} overlay={o} />
        ))}

        {/* Motion paths for current phase */}
        {phase.players.map(ps =>
          ps.paths?.map((path, pi) => (
            <MotionPathRenderer
              key={`${ps.playerId}-path-${pi}`}
              path={path}
              animated={currentPhase > 0}
            />
          ))
        )}

        {/* Ball trajectory */}
        {phase.ball?.trajectory && (
          <MotionPathRenderer path={phase.ball.trajectory.path} animated />
        )}

        {/* Player tokens with Framer Motion animation */}
        {Array.from(positions.entries()).map(([playerId, pos]) => {
          const player = rosterMap.get(playerId);
          if (!player) return null;

          const playerState = phase.players.find(ps => ps.playerId === playerId);
          const highlighted = playerState?.highlighted ?? false;
          const opacity = playerState?.opacity ?? 1;

          return (
            <motion.g
              key={playerId}
              animate={{ x: pos.x, y: pos.y }}
              initial={false}
              transition={{
                duration: transitionDuration,
                ease: 'easeInOut',
              }}
            >
              {/* Render at 0,0 since motion.g handles translation */}
              <PlayerToken
                role={player.role}
                position={{ x: 0, y: 0 }}
                label={getPlayerLabel(player)}
                highlighted={highlighted}
                opacity={opacity}
              />
            </motion.g>
          );
        })}

        {/* Ball */}
        {phase.ball && (
          <motion.g
            animate={{ x: phase.ball.position.x, y: phase.ball.position.y }}
            initial={false}
            transition={{ duration: transitionDuration, ease: 'easeInOut' }}
          >
            <BallToken ball={{ ...phase.ball, position: { x: 0, y: 0 } }} />
          </motion.g>
        )}

        {/* Highlight overlays */}
        {phase.overlays?.filter(o => o.type === 'highlight').map(o => (
          <HighlightOverlayComponent
            key={o.id}
            overlay={o}
            playerPositions={positions}
          />
        ))}

        {/* Annotations */}
        {phase.annotations?.map(a => (
          <AnnotationComponent key={a.id} annotation={a} />
        ))}
      </FootballField>
    </div>
  );
}
