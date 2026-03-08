import { usePlaybackStore } from '@/stores/playbackStore';
import type { PlaybackSpeed } from '@/stores/playbackStore';
import type { Phase } from '@/types';
import './PlaybackControls.css';

interface PlaybackControlsProps {
  phases: Phase[];
}

const SPEEDS: PlaybackSpeed[] = [0.5, 1, 2];

export function PlaybackControls({ phases }: PlaybackControlsProps) {
  const {
    currentPhase,
    isPlaying,
    speed,
    togglePlay,
    nextPhase,
    prevPhase,
    goToPhase,
    setSpeed,
  } = usePlaybackStore();

  const totalPhases = phases.length;
  const phase = phases[currentPhase];
  const atStart = currentPhase === 0;
  const atEnd = currentPhase >= totalPhases - 1;

  return (
    <div className="playback-strip">
      {/* Phase label */}
      <div className="playback-phase-label">
        <span className="playback-phase-index">{currentPhase + 1}/{totalPhases}</span>
        <span className="playback-phase-name">{phase?.label ?? ''}</span>
      </div>

      {/* Controls row */}
      <div className="playback-controls">
        {/* Prev */}
        <button
          className="playback-btn"
          onClick={prevPhase}
          disabled={atStart}
          title="Previous phase"
        >
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M10 2L4 7l6 5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>

        {/* Play/Pause */}
        <button
          className="playback-btn playback-btn-main"
          onClick={togglePlay}
          title={isPlaying ? 'Pause' : 'Play'}
        >
          {isPlaying ? (
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <rect x="3" y="2" width="4" height="12" rx="1" fill="currentColor" />
              <rect x="9" y="2" width="4" height="12" rx="1" fill="currentColor" />
            </svg>
          ) : (
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M4 2l10 6-10 6V2z" fill="currentColor" />
            </svg>
          )}
        </button>

        {/* Next */}
        <button
          className="playback-btn"
          onClick={nextPhase}
          disabled={atEnd}
          title="Next phase"
        >
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M4 2l6 5-6 5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>
      </div>

      {/* Phase scrubber */}
      <div className="playback-scrubber">
        {phases.map((p, i) => (
          <button
            key={p.id}
            className={`scrubber-dot ${i === currentPhase ? 'active' : ''} ${i < currentPhase ? 'passed' : ''}`}
            onClick={() => goToPhase(i)}
            title={p.label}
          />
        ))}
      </div>

      {/* Speed selector */}
      <div className="playback-speed">
        {SPEEDS.map(s => (
          <button
            key={s}
            className={`speed-btn ${s === speed ? 'active' : ''}`}
            onClick={() => setSpeed(s)}
          >
            {s}x
          </button>
        ))}
      </div>

      {/* Phase description */}
      {phase?.description && (
        <div className="playback-description">{phase.description}</div>
      )}
    </div>
  );
}
