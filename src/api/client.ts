import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost/api';
const BASE_URL = API_URL.replace(/\/api$/, '');

const client = axios.create({
  baseURL: API_URL,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
  withCredentials: true,
});

/**
 * Fetch the Sanctum CSRF cookie before mutating requests.
 * Only needs to be called once per session.
 */
let csrfInitialized = false;

export async function initCsrf(): Promise<void> {
  if (csrfInitialized) return;
  await axios.get(`${BASE_URL}/sanctum/csrf-cookie`, {
    withCredentials: true,
  });
  csrfInitialized = true;
}

export function resetCsrf(): void {
  csrfInitialized = false;
}

export default client;
