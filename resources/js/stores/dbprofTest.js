// resources/js/stores/dbprofTest.js
import { defineStore } from 'pinia'
import axios from 'axios'

export const useDbprofTestStore = defineStore('dbprofTest', {
  state: () => ({
    cache: {}, // { pageNumber: { records, pagination, load_time, timestamp } }
    records: [],
    loadTime: null,
    currentPage: 1,
    pagination: null,
    freshnessDuration: 1000 * 60 * 5 // 5 minutes
  }),

  actions: {
    isFresh(page) {
      const cached = this.cache[page]
      if (!cached) return false
      const now = new Date().getTime()
      return (now - cached.timestamp) < this.freshnessDuration
    },

    async fetchPage(page = 1) {
      if (this.isFresh(page)) {
        const cached = this.cache[page]
        this.records = cached.records
        this.loadTime = cached.load_time
        this.pagination = cached.pagination
        this.currentPage = page
        return
      }

      const response = await axios.get(`/api/dbproftest?page=${page}`)
      this.records = response.data.records.data
      this.loadTime = response.data.load_time
      this.pagination = response.data.records
      this.currentPage = page

      this.cache[page] = {
        records: this.records,
        pagination: this.pagination,
        load_time: this.loadTime,
        timestamp: new Date().getTime()
      }
    },

    clearCache() {
      this.cache = {}
    }
  }
})
