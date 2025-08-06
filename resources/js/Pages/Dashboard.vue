<template>
  <div class="d-flex">
    <div class="p-4">
      Dashboard content

      <button class="btn btn-primary mt-3" @click="sendTestNotification">Send Test Notification</button>
    </div>

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


const sendTestNotification = () => {
  axios.post('/api/test-fcm', {
  })
}

const logout = () => {
  router.post('/logout')
}



</script>