import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1',
  timeout: 15000,
  headers: { Accept: 'application/json' },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('scaffoldsync.access_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

export async function apiRequest(config) {
  try {
    const response = await api.request(config)
    return { ok: true, data: response.data }
  } catch (error) {
    const status = error.response?.status
    return { ok: false, status, message: error.response?.data?.message || error.message || 'Tidak dapat terhubung ke server.' }
  }
}

export default api
