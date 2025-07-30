<template>
  <div class="d-flex">

    <div class="container p-4">
      <h2>DB Performance Test</h2>

      <!-- Insert Form -->
      <div class="mb-3">
        <label>How many records to insert?</label>
        <input type="number" v-model="recordCount" class="form-control w-25" />
        <button @click="generateRecords" class="btn btn-primary mt-2">Insert Records</button>
      </div>

      <div v-if="insertTime" class="alert alert-success">
        Inserted in: {{ insertTime.toFixed(2) }} sec
      </div>

      <!-- Load Records -->
      <div class="my-4">
        <button class="btn btn-secondary" @click="store.fetchPage(store.currentPage)">Load Records</button>
        <div v-if="store.loadTime" class="mt-2">Loaded in: {{ store.loadTime.toFixed(2) }} sec</div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Slug</th>
              <th>Type</th>
              <th>Status</th>
              <th>Rating</th>
              <th>Views</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="rec in store.records" :key="rec.id">
              <td>{{ rec.id }}</td>
              <td>{{ rec.title }}</td>
              <td>{{ rec.slug }}</td>
              <td>
                <span class="badge bg-info text-dark">{{ rec.type }}</span>
              </td>
              <td>
                <span class="badge" :class="rec.status ? 'bg-success' : 'bg-danger'">
                  {{ rec.status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td>{{ rec.rating }}</td>
              <td>{{ rec.view_count }}</td>
              <td>{{ new Date(rec.created_at).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav v-if="store.pagination?.last_page > 1" class="mt-4">
        <ul class="pagination">
          <li class="page-item" :class="{ disabled: store.currentPage === 1 }">
            <a class="page-link" href="#" @click.prevent="changePage(store.currentPage - 1)">Previous</a>
          </li>

          <li
            class="page-item"
            v-for="page in store.pagination.last_page"
            :key="page"
            :class="{ active: page === store.currentPage }"
          >
            <a class="page-link" href="#" @click.prevent="changePage(page)">
              {{ page }}
            </a>
          </li>

          <li class="page-item" :class="{ disabled: store.currentPage === store.pagination.last_page }">
            <a class="page-link" href="#" @click.prevent="changePage(store.currentPage + 1)">Next</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Sidebar from '@/Components/Sidebar.vue'
import axios from 'axios'
import { useDbprofTestStore } from '@/stores/dbprofTest'

const store = useDbprofTestStore()
const recordCount = ref(1000)
const insertTime = ref(null)

const generateRecords = async () => {
  const response = await axios.post('/api/dbproftest/generate', { count: recordCount.value })
  insertTime.value = response.data.insert_time
  await store.clearCache()
  await store.fetchPage(1)
}

const changePage = (page) => {
  if (page >= 1 && page <= store.pagination.last_page) {
    store.fetchPage(page)
  }
}

onMounted(() => {
//   store.fetchPage(1)
})


defineOptions({
  layout: Sidebar
})  
</script>
