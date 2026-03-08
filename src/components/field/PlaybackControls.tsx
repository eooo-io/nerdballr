import { useEffect, useCallback } from 'react';
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

  // Keyboard shortcuts
  const handleKeyDown = useCallback((e: KeyboardEvent) => {
    if (e.target instanceof HTMLInputElement || e.target instanceof HTMLTextAreaElement) return;
    switch (e.key) {
      case ' ':
        e.preventDefault();
        togglePlay();
        break;
      case 'ArrowLeft':
        e.preventDefault();
        prevPhase();
        break;
      case 'ArrowRight':
        e.preventDefault();
        nextPhase();
        break;
    }
  }, [togglePlay, prevPhase, nextPhase]);

  useEffect(() => {
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [handleKeyDown]);

  return (
    <div className="playback-strip" role="toolbar" aria-label="Playback controls">
      {/* Phase label */}
      <div className="playback-phase-label">
        <span className="playback-phase-index">{currentPhase + 1}/{totalPhases}</span>
        <span className="playback-phase-name">{phase?.label ?? ''}</span>
      </div>

      {/* Controls row */}
      <div className="playback-controls">
        <button
          className="playback-btn"
          onClick={prevPhase}
          disabled={atStart}
          title="Previous phase (Left arrow)"
          aria-label="Previous phase"
        >
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M10 2L4 7l6 5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>

        <button
          className="playback-btn playback-btn-main"
          onClick={togglePlay}
          title={isPlaying ? 'Pause (Space)' : 'Play (Space)'}
          aria-label={isPlaying ? 'Pause' : 'Play'}
        >
          {isPlaying ? (
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
              <rect x="3" y="2" width="4" height="12" rx="1" fill="currentColor" />
              <rect x="9" y="2" width="4" height="12" rx="1" fill="currentColor" />
            </svg>
          ) : (
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
              <path d="M4 2l10 6-10 6V2z" fill="currentColor" />
            </svg>
          )}
        </button>

        <button
          className="playback-btn"
          onClick={nextPhase}
          disabled={atEnd}
          title="Next phase (Right arrow)"
          aria-label="Next phase"
        >
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M4 2l6 5-6 5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>
      </div>

      {/* Phase scrubber */}
      <div className="playback-scrubber" role="tablist" aria-label="Phase scrubber">
        {phases.map((p, i) => (
          <button
            key={p.id}
            className={`scrubber-dot ${i === currentPhase ? 'active' : ''} ${i < currentPhase ? 'passed' : ''}`}
            onClick={() => goToPhase(i)}
            title={p.label}
            role="tab"
            aria-selected={i === currentPhase}
            aria-label={`Phase ${i + 1}: ${p.label}`}
          />
        ))}
      </div>

      {/* Speed selector */}
      <div className="playback-speed" role="group" aria-label="Playback speed">
        {SPEEDS.map(s => (
          <button
            key={s}
            className={`speed-btn ${s === speed ? 'active' : ''}`}
            onClick={() => setSpeed(s)}
            aria-pressed={s === speed}
          >
            {s}x
          </button>
        ))}
      </div>

      {/* Phase description */}
      {phase?.description && (
        <div className="playback-description" aria-live="polite">{phase.description}</div>
      )}
    </div>
  );
}
