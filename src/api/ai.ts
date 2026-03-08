import client from './client';
import type { AiQueryRequest, AiQueryResponse } from '@/types';

export async function queryAi(request: AiQueryRequest): Promise<AiQueryResponse> {
  const { data } = await client.post<AiQueryResponse>('/ai/query', request);
  return data;
}

export interface AiSessionResponse {
  data: {
    session_key: string;
    messages: Array<{ role: 'user' | 'assistant'; content: string }>;
    concept_ids: string[];
  };
}

export async function getAiSession(key: string): Promise<AiSessionResponse> {
  const { data } = await client.get<AiSessionResponse>(`/ai/session/${key}`);
  return data;
}
