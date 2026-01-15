import api from './api';

export const ahpService = {
  getKriteria: async () => {
    const response = await api.get('/ahp/kriteria');
    return response.data;
  },

  getSessions: async (params = {}) => {
    const response = await api.get('/ahp/sessions', { params });
    return response.data;
  },

  createSession: async (data) => {
    const response = await api.post('/ahp/sessions', data);
    return response.data;
  },

  savePairwiseComparisons: async (sessionId, comparisons) => {
    const response = await api.post(`/ahp/sessions/${sessionId}/comparisons`, {
      comparisons
    });
    return response.data;
  },

  calculate: async (sessionId) => {
    const response = await api.post(`/ahp/sessions/${sessionId}/calculate`);
    return response.data;
  },

  getResults: async (sessionId) => {
    const response = await api.get(`/ahp/sessions/${sessionId}/results`);
    return response.data;
  }
};
