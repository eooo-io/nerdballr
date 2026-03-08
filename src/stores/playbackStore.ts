import { create } from 'zustand';

export type PlaybackSpeed = 0.5 | 1 | 2;

interface PlaybackState {
  currentPhase: number;
  totalPhases: number;
  isPlaying: boolean;
  speed: PlaybackSpeed;

  setTotalPhases: (total: number) => void;
  play: () => void;
  pause: () => void;
  togglePlay: () => void;
  nextPhase: () => void;
  prevPhase: () => void;
  goToPhase: (phase: number) => void;
  setSpeed: (speed: PlaybackSpeed) => void;
  reset: () => void;
}

export const usePlaybackStore = create<PlaybackState>((set, get) => ({
  currentPhase: 0,
  totalPhases: 0,
  isPlaying: false,
  speed: 1,

  setTotalPhases: (total) => set({ totalPhases: total, currentPhase: 0, isPlaying: false }),

  play: () => {
    const { currentPhase, totalPhases } = get();
    if (currentPhase >= totalPhases - 1) {
      set({ currentPhase: 0, isPlaying: true });
    } else {
      set({ isPlaying: true });
    }
  },

  pause: () => set({ isPlaying: false }),

  togglePlay: () => {
    const { isPlaying } = get();
    if (isPlaying) {
      get().pause();
    } else {
      get().play();
    }
  },

  nextPhase: () => {
    const { currentPhase, totalPhases } = get();
    if (currentPhase < totalPhases - 1) {
      set({ currentPhase: currentPhase + 1 });
    } else {
      set({ isPlaying: false });
    }
  },

  prevPhase: () => {
    const { currentPhase } = get();
    if (currentPhase > 0) {
      set({ currentPhase: currentPhase - 1 });
    }
  },

  goToPhase: (phase) => {
    const { totalPhases } = get();
    const clamped = Math.max(0, Math.min(phase, totalPhases - 1));
    set({ currentPhase: clamped, isPlaying: false });
  },

  setSpeed: (speed) => set({ speed }),

  reset: () => set({ currentPhase: 0, totalPhases: 0, isPlaying: false, speed: 1 }),
}));
