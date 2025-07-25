<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3 vh-100" style="width: 220px;">
      <h4>Menu</h4>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a href="#" class="nav-link text-white" @click="show = 'list'">View Todos</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link text-white" @click="show = 'create'">Create Todo</a>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
      <h2 v-if="show === 'list'">Todo List</h2>
      <h2 v-if="show === 'create'">Add New Todo</h2>

      <!-- Create Form -->
      <form v-if="show === 'create'" @submit.prevent="addTodo" class="mb-4 d-flex gap-2">
        <input v-model="newTodo" placeholder="New Todo" class="form-control" />
        <button type="submit" class="btn btn-primary">Add Todo</button>
      </form>

      <!-- Todo List -->
      <ul v-if="show === 'list'" class="list-group">
        <li
          v-for="todo in todos"
          :key="todo.id"
          class="list-group-item d-flex justify-content-between align-items-center"
        >
          <div>
            {{ todo.title }}
            <span class="badge ms-2" :class="todo.completed ? 'bg-success' : 'bg-secondary'">
              {{ todo.completed ? 'Done' : 'Pending' }}
            </span>
          </div>
          <button class="btn btn-danger btn-sm" @click="deleteTodo(todo.id)">Delete</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const todos = ref([])
const newTodo = ref('')
const show = ref('list') // Toggle between "list" and "create"

const fetchTodos = async () => {
  const res = await axios.get('/api/todos')
  todos.value = res.data
}

const addTodo = async () => {
  if (newTodo.value.trim() === '') return

  await axios.post('/api/todos', { title: newTodo.value })
  newTodo.value = ''
  fetchTodos()
  show.value = 'list'
}

const deleteTodo = async (id) => {
  await axios.delete(`/api/todos/${id}`)
  fetchTodos()
}

onMounted(fetchTodos)
</script>
