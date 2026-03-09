import client from './client';

export interface GlossaryTerm {
  id: number;
  term: string;
  slug: string;
  definition: string;
  category: 'offense' | 'defense' | 'general' | 'scheme';
  related_terms: string[];
  related_concepts: string[];
}

export type GlossaryCategory = GlossaryTerm['category'];

export interface GlossaryListParams {
  category?: GlossaryCategory;
  q?: string;
}

export async function listGlossaryTerms(params: GlossaryListParams = {}): Promise<GlossaryTerm[]> {
  const query: Record<string, string> = {};
  if (params.category) query.category = params.category;
  if (params.q) query.q = params.q;

  const { data } = await client.get<{ data: GlossaryTerm[] }>('/glossary', { params: query });
  return data.data;
}

export async function getGlossaryTerm(slug: string): Promise<GlossaryTerm> {
  const { data } = await client.get<{ data: GlossaryTerm }>(`/glossary/${slug}`);
  return data.data;
}
