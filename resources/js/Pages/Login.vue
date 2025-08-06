<template>
  <div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4">Login</h2>

    <form @submit.prevent="submit">
      <div class="mb-3">
        <label>Email</label>
        <input v-model="form.email" type="email" class="form-control" />
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input v-model="form.password" type="password" class="form-control" />
      </div>

      <button class="btn btn-primary w-100" :disabled="form.processing">
        Login
      </button>

      <div v-if="form.errors.email" class="text-danger mt-2">{{ form.errors.email }}</div>
      <div v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</div>
      <div v-if="form.errors.message" class="text-danger">{{ form.errors.message }}</div>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import axios from 'axios'

const form = useForm({
  email: '',
  password: '',
})

// Listen for user after login
const submit = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie')

    form.post('/login', {
      onSuccess: async () => {
        const user = usePage().props.auth.user

        if (user.role === 'employee') {
          try {
            await axios.post('/api/notify-admins-login', {
              email: form.email,
            });
            console.log('✅ Admins notified of employee login')
          } catch (e) {
            console.warn('⚠️ Failed to notify admins', e)
          }
        }
      }
    })
  } catch (e) {
    console.error('Login failed:', e)
  }
}
</script>
