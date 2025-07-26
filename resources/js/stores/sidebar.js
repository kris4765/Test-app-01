import { defineStore } from 'pinia'

export const useSidebarStore = defineStore('sidebar', {
  state: () => ({
    isVisible: JSON.parse(localStorage.getItem('sidebar-visible')) ?? true,
  }),

  actions: {
    toggle() {
      this.isVisible = !this.isVisible
      localStorage.setItem('sidebar-visible', JSON.stringify(this.isVisible))
    },
    set(value) {
      this.isVisible = value
      localStorage.setItem('sidebar-visible', JSON.stringify(value))
    }
  }
})
