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
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
})

const submit = async () => {
  await axios.get('/sanctum/csrf-cookie')  // Important!
  form.post('/login')                      // Then login
}

</script>
