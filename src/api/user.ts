import client, { initCsrf } from './client';

// ─── Bookmarks ──────────────────────────────────────────────

export interface Bookmark {
  id: number;
  concept_id: string;
  concept_slug: string;
  created_at: string;
}

export async function listBookmarks(): Promise<Bookmark[]> {
  const { data } = await client.get<{ data: Bookmark[] }>('/user/bookmarks');
  return data.data;
}

export async function addBookmark(conceptSlug: string): Promise<Bookmark> {
  await initCsrf();
  const { data } = await client.post<{ data: Bookmark }>('/user/bookmarks', {
    concept_slug: conceptSlug,
  });
  return data.data;
}

export async function removeBookmark(id: number): Promise<void> {
  await initCsrf();
  await client.delete(`/user/bookmarks/${id}`);
}

// ─── Progress ───────────────────────────────────────────────

export interface ProgressEntry {
  concept_slug: string;
  completed_at: string;
}

export async function listProgress(): Promise<ProgressEntry[]> {
  const { data } = await client.get<{ data: ProgressEntry[] }>('/user/progress');
  return data.data;
}

export async function markComplete(conceptSlug: string): Promise<ProgressEntry> {
  await initCsrf();
  const { data } = await client.post<{ data: ProgressEntry }>('/user/progress', {
    concept_slug: conceptSlug,
  });
  return data.data;
}

// ─── Guest Migration ────────────────────────────────────────

export interface GuestMigrationPayload {
  bookmarked_slugs: string[];
  completed_slugs: string[];
}

export async function migrateGuest(payload: GuestMigrationPayload): Promise<void> {
  await initCsrf();
  await client.post('/user/migrate-guest', payload);
}
