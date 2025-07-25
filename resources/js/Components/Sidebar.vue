<template>
  <div class="d-flex">
    <!-- Sidebar -->

    <div class="bg-dark text-white vh-100 d-flex flex-column align-items-center"
      :style="{ width: isVisible ? '220px' : '50px', transition: 'width 0.3s' }">
      <!-- Toggle Button -->


      <div class="flex w-100 p-2 items-center justify-content-between">

        <div class="" v-if="isVisible">Tool Name</div>


        <button class="btn btn-sm btn-light" @click="isVisible = !isVisible" :title="isVisible ? 'Collapse' : 'Expand'">
          <i class="bi" :class="isVisible ? 'bi-chevron-left' : 'bi-chevron-right'"></i>
        </button>
      </div>


      <!-- Menu Items -->
      <Link v-for="item in menu" :key="item.id" :href="item.route"
        class="sidebar-item d-flex align-items-center w-100 px-2 py-2 text-white text-decoration-none"
        :class="{ 'justify-content-center': !isVisible, 'justify-content-start': isVisible }">
      <i :class="item.icon" class="fs-5"></i>
      <span v-if="isVisible" class="ms-2">{{ item.label }}</span>
      </Link>

    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const isVisible = ref(true)

const menu = [
  { id: 1, label: 'Dashboard', icon: 'bi bi-speedometer2', route: '/dashboard' },
  { id: 2, label: 'Users', icon: 'bi bi-people', route: '/users' },
  { id: 3, label: 'Settings', icon: 'bi bi-gear', route: '/settings' },
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
