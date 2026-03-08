import { create } from 'zustand';
import type { Concept } from '@/types';
import { getConcept } from '@/api/concepts';

interface ConceptState {
  concept: Concept | null;
  isLoading: boolean;
  error: string | null;

  loadConcept: (slug: string) => Promise<void>;
  clearConcept: () => void;
}

export const useConceptStore = create<ConceptState>((set) => ({
  concept: null,
  isLoading: false,
  error: null,

  loadConcept: async (slug: string) => {
    set({ isLoading: true, error: null });
    try {
      const concept = await getConcept(slug);
      set({ concept, isLoading: false });
    } catch {
      set({ error: 'Failed to load concept.', isLoading: false, concept: null });
    }
  },

  clearConcept: () => set({ concept: null, isLoading: false, error: null }),
}));
