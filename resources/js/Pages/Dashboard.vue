<template>
  <div class="d-flex">
    <div class="p-4">Dashboard content</div>

    <form method="POST" @submit.prevent="logout">
      <button type="submit" class="btn btn-danger mt-2">Logout</button>
    </form>
  </div>
</template>
<script setup>
import Sidebar from '@/Components/Sidebar.vue'
import { router } from '@inertiajs/vue3'

defineOptions({
  layout: Sidebar
})

const logout = () => {
  router.post('/logout')
}
</script>