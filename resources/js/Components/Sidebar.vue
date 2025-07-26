<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <div
      class="bg-dark text-white vh-100 d-flex flex-column align-items-center"
      :style="{ width: isVisible ? '220px' : '50px', transition: 'width 0.3s' }"
    >
      <!-- Toggle Button -->
      <div class="flex w-100 p-2 items-center justify-content-between">
        <div v-if="isVisible">Tool Name</div>

        <button
          class="btn btn-sm btn-light"
          @click="toggleSidebar"
          :title="isVisible ? 'Collapse' : 'Expand'"
        >
          <i class="bi" :class="isVisible ? 'bi-chevron-left' : 'bi-chevron-right'"></i>
        </button>
      </div>

      <!-- Menu Items -->
      <Link
        v-for="item in menu"
        :key="item.label"
        :href="item.route"
        class="sidebar-item d-flex align-items-center w-100 px-2 py-2 text-white text-decoration-none"
        :class="{
          'justify-content-center': !isVisible,
          'justify-content-start': isVisible
        }"
      >
        <i :class="item.icon" class="fs-5"></i>
        <span v-if="isVisible" class="ms-2">{{ item.label }}</span>
      </Link>
    </div>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useSidebarStore } from '../stores/sidebar'
import { Link } from '@inertiajs/vue3'

const sidebarStore = useSidebarStore()
const { isVisible } = storeToRefs(sidebarStore)
const toggleSidebar = sidebarStore.toggle

const menu = [
  { label: 'home', icon: 'bi bi-grid-1x2', route: '/' },
  { label: 'Dashboard', icon: 'bi bi-speedometer2', route: '/dashboard' },
  { label: 'Users', icon: 'bi bi-people', route: '/users' },
  { label: 'Settings', icon: 'bi bi-gear', route: '/settings' },
  { label: 'DB performance', icon: 'bi bi-database', route: '/db_test' }
]
</script>

<style scoped>
.sidebar-item {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  cursor: pointer;
}

.sidebar-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 5px;
}
</style>
