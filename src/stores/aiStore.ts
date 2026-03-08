import { create } from 'zustand';
import { queryAi } from '@/api/ai';

export interface ChatMessage {
  id: string;
  role: 'user' | 'assistant';
  content: string;
  intent?: string;
  timestamp: number;
}

interface AiState {
  messages: ChatMessage[];
  sessionKey: string | null;
  isLoading: boolean;
  error: string | null;
  queriesUsed: number;
  isOpen: boolean;

  toggle: () => void;
  open: () => void;
  close: () => void;
  sendQuery: (query: string, conceptSlugs: string[]) => Promise<void>;
  clearChat: () => void;
}

function getSessionKey(): string {
  const KEY = 'ffis_session_key';
  let key = localStorage.getItem(KEY);
  if (!key) {
    key = `guest_${Date.now()}_${Math.random().toString(36).slice(2, 10)}`;
    localStorage.setItem(KEY, key);
  }
  return key;
}

export const useAiStore = create<AiState>((set, get) => ({
  messages: [],
  sessionKey: getSessionKey(),
  isLoading: false,
  error: null,
  queriesUsed: 0,
  isOpen: false,

  toggle: () => set((s) => ({ isOpen: !s.isOpen })),
  open: () => set({ isOpen: true }),
  close: () => set({ isOpen: false }),

  sendQuery: async (query, conceptSlugs) => {
    const { sessionKey, messages, queriesUsed } = get();

    const userMsg: ChatMessage = {
      id: `user_${Date.now()}`,
      role: 'user',
      content: query,
      timestamp: Date.now(),
    };

    set({
      messages: [...messages, userMsg],
      isLoading: true,
      error: null,
    });

    try {
      const response = await queryAi({
        query,
        concept_slugs: conceptSlugs,
        session_key: sessionKey ?? undefined,
      });

      const assistantMsg: ChatMessage = {
        id: `assistant_${Date.now()}`,
        role: 'assistant',
        content: response.data.response,
        intent: response.data.intent,
        timestamp: Date.now(),
      };

      set((s) => ({
        messages: [...s.messages, assistantMsg],
        sessionKey: response.session_key || s.sessionKey,
        isLoading: false,
        queriesUsed: queriesUsed + 1,
      }));
    } catch {
      set({
        isLoading: false,
        error: 'Failed to get response. Try again.',
      });
    }
  },

  clearChat: () => set({ messages: [], error: null }),
}));
