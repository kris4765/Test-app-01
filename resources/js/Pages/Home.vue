<template>
  <!-- Flex wrapper for sidebar + main -->
  <div class="d-flex" style="min-height: 100vh;">

    <!-- Sidebar on the left -->
    <Sidebar />




    



    <!-- Main content on the right -->
    <div class="flex-grow-1 p-4">
      <h1 class="mb-4">Todo List</h1>

      <!-- Add todo form -->
      <form @submit.prevent="addTodo" class="mb-3">
        <input
          v-model="newTodo"
          placeholder="New Todo"
          class="form-control mb-2"
        />
        <button class="btn btn-primary">Add Todo</button>
      </form>

      <!-- Todo list -->
      <ul class="list-group">
        <li
          v-for="todo in todos"
          :key="todo.id"
          class="list-group-item d-flex justify-content-between align-items-center"
        >
          <div>
            {{ todo.title }}
            <span
              class="badge ms-2"
              :class="todo.completed ? 'bg-success' : 'bg-secondary'"
              @click="updatestatus(todo.id)"
              style="cursor: pointer"
            >
              {{ todo.completed ? 'Done' : 'Pending' }}
            </span>
          </div>
          <button class="btn btn-danger btn-sm" @click="deleteTodo(todo.id)">
            Delete
          </button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Sidebar from '@/Components/Sidebar.vue'

const todos = ref([])
const newTodo = ref('')

const fetchTodos = async () => {
  const res = await axios.get('/api/todos')
  todos.value = res.data
}

const addTodo = async () => {
  if (newTodo.value.trim() === '') return
  await axios.post('/api/todos', { title: newTodo.value })
  newTodo.value = ''
  fetchTodos()
}

const updatestatus = async (id) => {
  await axios.patch(`/api/todos/${id}/toggle-status`)
  fetchTodos()
}

const deleteTodo = async (id) => {
  await axios.delete(`/api/todos/${id}`)
  fetchTodos()
}

onMounted(fetchTodos)
</script>
