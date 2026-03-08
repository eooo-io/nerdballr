import { create } from 'zustand';
import type { Concept } from '@/types';
import { getConcept } from '@/api/concepts';

interface CompareState {
  slotA: Concept | null;
  slotB: Concept | null;
  synced: boolean;
  isLoadingA: boolean;
  isLoadingB: boolean;

  loadSlotA: (slug: string) => Promise<void>;
  loadSlotB: (slug: string) => Promise<void>;
  setSynced: (synced: boolean) => void;
  toggleSynced: () => void;
  clearSlots: () => void;
}

export const useCompareStore = create<CompareState>((set) => ({
  slotA: null,
  slotB: null,
  synced: true,
  isLoadingA: false,
  isLoadingB: false,

  loadSlotA: async (slug: string) => {
    set({ isLoadingA: true });
    try {
      const concept = await getConcept(slug);
      set({ slotA: concept, isLoadingA: false });
    } catch {
      set({ isLoadingA: false });
    }
  },

  loadSlotB: async (slug: string) => {
    set({ isLoadingB: true });
    try {
      const concept = await getConcept(slug);
      set({ slotB: concept, isLoadingB: false });
    } catch {
      set({ isLoadingB: false });
    }
  },

  setSynced: (synced) => set({ synced }),
  toggleSynced: () => set((s) => ({ synced: !s.synced })),
  clearSlots: () => set({ slotA: null, slotB: null, isLoadingA: false, isLoadingB: false }),
}));
