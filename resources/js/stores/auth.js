import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null
  }),
  actions: {
    async fetchUser() {
      try {
        const res = await axios.get('/api/me')
        this.user = res.data
      } catch {
        this.user = null
      }
    },
    async logout() {
      await axios.post('/api/logout')
      this.user = null
    }
  }
})
