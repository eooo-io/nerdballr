import client from './client';
import type { Concept, ConceptSummary, PaginatedResponse, ConceptCategory } from '@/types';

export type ConceptSortMode = 'difficulty' | 'alpha';

export interface ConceptListParams {
  category?: ConceptCategory;
  tags?: string[];
  q?: string;
  page?: number;
  sort?: ConceptSortMode;
}

export async function listConcepts(params: ConceptListParams = {}): Promise<PaginatedResponse<ConceptSummary>> {
  const query: Record<string, string> = {};
  if (params.category) query.category = params.category;
  if (params.tags?.length) query.tags = params.tags.join(',');
  if (params.q) query.q = params.q;
  if (params.page) query.page = String(params.page);
  if (params.sort) query.sort = params.sort;

  const { data } = await client.get<PaginatedResponse<ConceptSummary>>('/concepts', { params: query });
  return data;
}

export async function getConcept(slug: string): Promise<Concept> {
  const { data } = await client.get<{ data: Concept }>(`/concepts/${slug}`);
  return data.data;
}
