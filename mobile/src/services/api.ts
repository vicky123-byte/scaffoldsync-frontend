/**
 * API adapter placeholder.
 * Ganti BASE_URL dan tambahkan endpoint setelah kontrak backend final disepakati.
 * UI prototype belum mengirimkan data ke server.
 */
export const API_BASE_URL = 'http://localhost:8000/api';

export interface ApiResult<T> {
  success: boolean;
  data?: T;
  message?: string;
}

export async function apiRequest<T>(path: string, init?: RequestInit): Promise<ApiResult<T>> {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    ...init,
    headers: { Accept: 'application/json', 'Content-Type': 'application/json', ...(init?.headers ?? {}) },
  });
  const payload: unknown = await response.json().catch(() => ({}));
  if (!response.ok) {
    const message = typeof payload === 'object' && payload !== null && 'message' in payload
      ? String((payload as { message?: unknown }).message)
      : `Permintaan gagal (${response.status}).`;
    throw new Error(message);
  }
  return payload as ApiResult<T>;
}
