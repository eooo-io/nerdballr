import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import {
  listBookmarks,
  addBookmark,
  removeBookmark,
  listProgress,
  markComplete,
  migrateGuest,
} from '@/api/user';
import type { Bookmark } from '@/api/user';

type SortPreference = 'difficulty' | 'alpha';

interface UserState {
  // Local state (persisted in localStorage for guests)
  bookmarkedSlugs: string[];
  completedSlugs: string[];
  sortPreference: SortPreference;

  // Server bookmark IDs (for authenticated delete)
  bookmarkMap: Record<string, number>; // slug → bookmark id

  // Sync state
  isSyncing: boolean;

  // Actions
  toggleBookmark: (slug: string, authenticated: boolean) => Promise<void>;
  markConceptComplete: (slug: string, authenticated: boolean) => Promise<void>;
  isBookmarked: (slug: string) => boolean;
  isCompleted: (slug: string) => boolean;
  setSortPreference: (pref: SortPreference) => void;
  syncFromServer: () => Promise<void>;
  migrateGuestState: () => Promise<void>;
  clearLocal: () => void;
}

export const useUserStore = create<UserState>()(
  persist(
    (set, get) => ({
      bookmarkedSlugs: [],
      completedSlugs: [],
      sortPreference: 'difficulty' as SortPreference,
      bookmarkMap: {},
      isSyncing: false,

      toggleBookmark: async (slug, authenticated) => {
        const { bookmarkedSlugs, bookmarkMap } = get();
        const isCurrently = bookmarkedSlugs.includes(slug);

        if (isCurrently) {
          // Remove
          set({
            bookmarkedSlugs: bookmarkedSlugs.filter((s) => s !== slug),
          });
          if (authenticated && bookmarkMap[slug]) {
            try {
              await removeBookmark(bookmarkMap[slug]);
              const newMap = { ...get().bookmarkMap };
              delete newMap[slug];
              set({ bookmarkMap: newMap });
            } catch { /* already removed locally */ }
          }
        } else {
          // Add
          set({
            bookmarkedSlugs: [...bookmarkedSlugs, slug],
          });
          if (authenticated) {
            try {
              const bm = await addBookmark(slug);
              set({
                bookmarkMap: { ...get().bookmarkMap, [slug]: bm.id },
              });
            } catch { /* already added locally */ }
          }
        }
      },

      markConceptComplete: async (slug, authenticated) => {
        const { completedSlugs } = get();
        if (completedSlugs.includes(slug)) return;

        set({ completedSlugs: [...completedSlugs, slug] });

        if (authenticated) {
          try {
            await markComplete(slug);
          } catch { /* already stored locally */ }
        }
      },

      isBookmarked: (slug) => get().bookmarkedSlugs.includes(slug),
      isCompleted: (slug) => get().completedSlugs.includes(slug),
      setSortPreference: (pref) => set({ sortPreference: pref }),

      syncFromServer: async () => {
        set({ isSyncing: true });
        try {
          const [bookmarks, progress] = await Promise.all([
            listBookmarks(),
            listProgress(),
          ]);

          const bookmarkMap: Record<string, number> = {};
          const bookmarkedSlugs = bookmarks.map((b: Bookmark) => {
            bookmarkMap[b.concept_slug] = b.id;
            return b.concept_slug;
          });

          const completedSlugs = progress.map((p) => p.concept_slug);

          set({ bookmarkedSlugs, completedSlugs, bookmarkMap, isSyncing: false });
        } catch {
          set({ isSyncing: false });
        }
      },

      migrateGuestState: async () => {
        const { bookmarkedSlugs, completedSlugs } = get();

        // Only migrate if there's guest data to send
        if (bookmarkedSlugs.length === 0 && completedSlugs.length === 0) return;

        try {
          await migrateGuest({
            bookmarks: bookmarkedSlugs,
            completed: completedSlugs,
          });
          // After migration, sync from server to get proper IDs
          await get().syncFromServer();
        } catch { /* migration failed, keep local state */ }
      },

      clearLocal: () => set({
        bookmarkedSlugs: [],
        completedSlugs: [],
        bookmarkMap: {},
      }),
    }),
    {
      name: 'ffis_user_state',
      partialize: (state) => ({
        bookmarkedSlugs: state.bookmarkedSlugs,
        completedSlugs: state.completedSlugs,
        sortPreference: state.sortPreference,
      }),
    },
  ),
);
