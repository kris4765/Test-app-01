<template>
  <div class="d-flex">

    <div class="p-4 flex-grow-1">
      <h2 class="mb-4">User Management</h2>

      <button class="btn btn-primary mb-3" @click="openModal('add')">Add User</button>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role }}</td>
            <td>
              <button class="btn btn-sm btn-info me-1" @click="openModal('view', user)">View</button>
              <button class="btn btn-sm btn-warning me-1" @click="openModal('edit', user)">Edit</button>
              <button class="btn btn-sm btn-danger" @click="deleteUser(user.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal fade show d-block" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="modal-dialog">
        <div class="modal-content p-3">
          <div class="modal-header">
            <h5 class="modal-title text-capitalize">{{ modalMode }} User</h5>
            <button type="button" class="btn-close" @click="closeModal()"></button>
          </div>

          <div class="modal-body">
            <div class="mb-3">
              <label>Email</label>
              <input
                v-model="form.email"
                type="email"
                class="form-control"
                :readonly="modalMode === 'view'"
              />
            </div>

            <div class="mb-3" v-if="modalMode === 'add'">
              <label>Password</label>
              <input
                v-model="form.password"
                type="password"
                class="form-control"
                :readonly="modalMode === 'view'"
              />
            </div>

            <div class="mb-3">
              <label>Role</label>
              <input
                v-model="form.role"
                type="text"
                class="form-control"
                :readonly="modalMode === 'view'"
              />
            </div>
          </div>

          <div class="modal-footer" v-if="modalMode !== 'view'">
            <button class="btn btn-secondary" @click="closeModal()">Cancel</button>
            <button class="btn btn-primary" @click="handleSubmit()">
              {{ modalMode === 'add' ? 'Add' : 'Update' }}
            </button>
          </div>

          <div class="modal-footer" v-else>
            <button class="btn btn-secondary" @click="closeModal()">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Sidebar from '@/Components/Sidebar.vue'



defineOptions({
  layout: Sidebar
})

const users = ref([])
const showModal = ref(false)
const modalMode = ref('add') // 'add', 'edit', 'view'
const form = ref({
  id: null,
  email: '',
  password: '',
  role: ''
})

const fetchUsers = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/users')
    users.value = res.data
  } catch (err) {
    console.error('Fetch error:', err)
  }
}

const openModal = (mode, user = null) => {
  modalMode.value = mode
  showModal.value = true

  if (user) {
    form.value = {
      id: user.id,
      email: user.email,
      password: '',
      role: user.role
    }
  } else {
    form.value = {
      id: null,
      email: '',
      password: '',
      role: ''
    }
  }
}

const closeModal = () => {
  showModal.value = false
}

const handleSubmit = async () => {
  try {
    if (modalMode.value === 'add') {
      await axios.post('http://127.0.0.1:8000/api/users', form.value)
    } else if (modalMode.value === 'edit') {
      await axios.put(`http://127.0.0.1:8000/api/users/${form.value.id}`, {
        email: form.value.email,
        role: form.value.role
      })
    }
    closeModal()
    fetchUsers()
  } catch (err) {
    console.error('Submit error:', err.response?.data || err)
  }
}

const deleteUser = async (id) => {
  if (confirm('Are you sure you want to delete this user?')) {
    try {
      await axios.delete(`http://127.0.0.1:8000/api/users/${id}`)
      fetchUsers()
    } catch (err) {
      console.error('Delete error:', err)
    }
  }
}

onMounted(fetchUsers)
</script>
