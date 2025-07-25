<!-- resources/js/Pages/Home.vue -->

<template>
  <div class="container">
    <h1>Todo List</h1>

    <form @submit.prevent="addTodo">
      <input v-model="newTodo" placeholder="New Todo" class="form-control mb-2" />
      <button class="btn btn-primary">Add Todo</button>
    </form>

    <ul class="list-group mt-3">
      <li v-for="todo in todos" :key="todo.id"
        class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          {{ todo.title }}
          <span class="badge" :class="todo.completed ? 'bg-success' : 'bg-secondary'" @click="updatestatus(todo.id)">
            {{ todo.completed ? 'Done' : 'Pending' }}
          </span>
        </div>
        <button class="btn btn-danger btn-sm" @click="deleteTodo(todo.id)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const todos = ref([])
const newTodo = ref('')

// Fetch all todos from API
const fetchTodos = async () => {
  const res = await axios.get('/api/todos')
  todos.value = res.data
}

// Add new todo
const addTodo = async () => {
  if (newTodo.value.trim() === '') return

  await axios.post('/api/todos', { title: newTodo.value })
  newTodo.value = ''
  fetchTodos()
}


const updatestatus = async (id) => {
  console.log(id)
  await axios.patch(`/api/todos/${id}/toggle-status`) // ✅ correct
  fetchTodos()

}

// Delete a todo
const deleteTodo = async (id) => {
  await axios.delete(`/api/todos/${id}`)
  fetchTodos()
}

// Fetch on page load
onMounted(fetchTodos)
</script>
