import { apiRequest } from './api'

// Contract-first resource helpers. Confirm endpoint paths and payloads with backend before enabling.
export const authApi = {
  login: (payload) => apiRequest({ method: 'POST', url: '/auth/login', data: payload }),
  logout: () => apiRequest({ method: 'POST', url: '/auth/logout' }),
  me: () => apiRequest({ method: 'GET', url: '/auth/me' }),
  registerCustomer: (payload) => apiRequest({ method: 'POST', url: '/auth/register', data: payload }),
  requestPasswordReset: (payload) => apiRequest({ method: 'POST', url: '/auth/password/forgot', data: payload }),
}

export const inventoryApi = {
  list: (params = {}) => apiRequest({ method: 'GET', url: '/inventory', params }),
  detail: (id) => apiRequest({ method: 'GET', url: `/inventory/${encodeURIComponent(id)}` }),
  create: (payload) => apiRequest({ method: 'POST', url: '/inventory', data: payload }),
  update: (id, payload) => apiRequest({ method: 'PUT', url: `/inventory/${encodeURIComponent(id)}`, data: payload }),
}

export const orderApi = {
  list: (params = {}) => apiRequest({ method: 'GET', url: '/orders', params }),
  detail: (id) => apiRequest({ method: 'GET', url: `/orders/${encodeURIComponent(id)}` }),
  createRentalRequest: (payload) => apiRequest({ method: 'POST', url: '/orders', data: payload }),
  updateStatus: (id, payload) => apiRequest({ method: 'PATCH', url: `/orders/${encodeURIComponent(id)}/status`, data: payload }),
  checkAvailability: (params) => apiRequest({ method: 'GET', url: '/inventory/availability', params }),
}
