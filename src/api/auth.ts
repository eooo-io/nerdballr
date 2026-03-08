import client, { initCsrf, resetCsrf } from './client';
import type { AuthUser } from '@/types';

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface LoginData {
  email: string;
  password: string;
}

export async function register(data: RegisterData): Promise<AuthUser> {
  await initCsrf();
  const response = await client.post<{ user: AuthUser }>('/register', data);
  return response.data.user;
}

export async function login(data: LoginData): Promise<AuthUser> {
  await initCsrf();
  const response = await client.post<{ user: AuthUser }>('/login', data);
  return response.data.user;
}

export async function logout(): Promise<void> {
  await client.post('/logout');
  resetCsrf();
}

export async function getUser(): Promise<AuthUser> {
  const { data } = await client.get<{ user: AuthUser }>('/user');
  return data.user;
}
