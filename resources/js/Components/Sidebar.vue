  <template>
    <div class="d-flex flex-column vh-100">

      <!-- Top Header -->
      <header class="navbar navbar-dark bg-primary px-4">
        <span class="navbar-brand">My ERP</span>

        <!-- Notification Bell -->
        <div class="dropdown">
          <button class="btn btn-light position-relative" @click="toggleNotifications">
            <i class="bi bi-bell"></i>
            <span v-if="unreadCount > 0"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ unreadCount }}
            </span>
          </button>

          <!-- Dropdown Menu -->
          <div v-if="showNotifications" class="dropdown-menu dropdown-menu-end p-3 shadow show"
            style="width: 300px; max-height: 350px; overflow-y: auto; right: 0;">
            <template v-if="notifications.length === 0">
              <div class="text-center text-muted">No notifications</div>
            </template>
            <template v-else>
              <!-- <div v-for="n in notifications" :key="n.id" class="mb-3 pb-2 border-bottom small">
                <div><strong>From:</strong> {{ n.send_from }}</div>
                <div><strong>Message:</strong> {{ n.data }}</div>
                <div class="text-muted">{{ timeAgo(n.created_at) }}</div>
              </div> -->


              <div v-for="n in notifications" :key="n.id" class="mb-3 pb-2 border-bottom small">
                <div><strong>From:</strong> {{ n.send_from }}</div>
                <div><strong>Message:</strong> {{ n.data }}</div>
                <div class="text-muted">{{ timeAgo(n.created_at) }}</div>

                <button v-if="!n.is_read" class="btn btn-sm btn-outline-success mt-1" @click="markAsRead(n.id)">
                  Mark as Read
                </button>
              </div>



            </template>
          </div>
        </div>
      </header>

      <!-- Main Layout -->
      <div class="d-flex flex-grow-1">
        <!-- Sidebar -->
        <div class="bg-dark text-white d-flex flex-column"
          :style="{ width: isVisible ? '220px' : '50px', transition: 'width 0.3s' }">
          <div class="d-flex justify-content-between align-items-center p-2">
            <div v-if="isVisible">{{ user?.role }}</div>
            <button class="btn btn-sm btn-light" @click="toggleSidebar">
              <i class="bi" :class="isVisible ? 'bi-chevron-left' : 'bi-chevron-right'"></i>
            </button>
          </div>

          <Link v-for="item in menu" :key="item.label" :href="item.route"
            class="sidebar-item d-flex align-items-center w-100 px-2 py-2 text-white text-decoration-none"
            :class="{ 'justify-content-center': !isVisible, 'justify-content-start': isVisible }">
          <i :class="item.icon" class="fs-5"></i>
          <span v-if="isVisible" class="ms-2">{{ item.label }}</span>
          </Link>


          <button class="btn btn-primary mt-3" @click="sendTestNotification">Send Test Notification</button>

          <form method="POST" @submit.prevent="logout" class="mt-auto p-2">
            <button type="submit" class="btn btn-danger btn-sm w-100">Logout</button>

          </form>
        </div>



        <!-- Content Area -->
        <main class="flex-grow-1 p-4 bg-light">
          <slot />
        </main>


      </div>
    </div>
  </template>



<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useSidebarStore } from '../stores/sidebar'
import { Link, usePage, router } from '@inertiajs/vue3'
import axios from 'axios'

import { messaging, getToken, onMessage } from '../firebase'



// Sidebar toggle logic
const sidebarStore = useSidebarStore()
const { isVisible } = storeToRefs(sidebarStore)
const toggleSidebar = sidebarStore.toggle

const page = usePage()
const user = computed(() => page.props.auth.user)

// Dynamic menu based on role
const menu = ref([])
watch(() => user.value, newUser => {
  if (!newUser) return
  menu.value = newUser.role === 'admin'
    ? [
      { label: 'Home', icon: 'bi bi-house', route: '/home' },
      { label: 'Dashboard', icon: 'bi bi-speedometer2', route: '/dashboard' },
      { label: 'Users', icon: 'bi bi-people', route: '/users' },
      { label: 'DB performance', icon: 'bi bi-database', route: '/db_test' },
      { label: 'Settings', icon: 'bi bi-gear', route: '/settings' },
      { label: 'Chat', icon: 'bi bi-gear', route: '/chat' }
    ]
    : [
      { label: 'Chat', icon: 'bi bi-gear', route: '/chat' },
      { label: 'Home', icon: 'bi bi-house', route: '/home' }

    ]
}, { immediate: true })

const logout = () => router.post('/logout')

// Notifications
const notifications = ref([])
const showNotifications = ref(false)
const unreadCount = computed(() => notifications.value.filter(n => !n.is_read).length)
const toggleNotifications = () => showNotifications.value = !showNotifications.value

const timeAgo = (timestamp) => {
  const now = new Date()
  const created = new Date(timestamp)
  const seconds = Math.floor((now - created) / 1000)

  if (seconds < 60) return 'Just now'
  if (seconds < 3600) return `${Math.floor(seconds / 60)} min ago`
  if (seconds < 86400) return `${Math.floor(seconds / 3600)} hour ago`
  return `${Math.floor(seconds / 86400)} day ago`
}



const userEmail = ref('admin@example.com'); // Replace this with real user email

const sendTestNotification = async () => {
  try {
    const response = await axios.post('/api/test-fcm', {
      email: userEmail.value,
    });
    alert(response.data.message);
  } catch (error) {
    console.error(error);
    alert('Failed to send notification');
  }
};


const markAsRead = async (id) => {
  try {
    await axios.post(`/api/notifications/${id}/read`)
    const notification = notifications.value.find(n => n.id === id)
    if (notification) notification.is_read = true
  } catch (error) {
    console.error('Error marking notification as read:', error)
  }

  showNotifications.value = false; // Close the dropdown after marking as read
  fetchNotifications(); // Refresh notifications
}


const askNotificationPermission = async () => {
  const permission = await Notification.requestPermission();

  if (permission !== 'granted') {
    console.warn('❌ Notification permission denied.');
    return;
  }
  console.log('✅ Notification permission granted.');

  if (permission === 'granted') {
    const token = await getToken(messaging, {
      vapidKey: 'BBANH6xa064NLHMBAGQs6Y2-dahc67cmBXN68hRs1Qy8Kaeq841wuxd3w-XsO4EXZYysxzlNLkt5cm5kVdPQFek',
    });

    if (token) {
      console.log('✅ FCM Token:', token);

      // Send to backend (you must have `user.value.email` available here)
      await axios.post('/api/save-fcm-token', {
        email: user.value.email, // or dynamically grab from store
        token: token,
      });


    } else {
      console.warn('⚠️ No registration token received.');
    }
  } else {
    console.warn('❌ Notification permission denied.');
  }
};


askNotificationPermission();



const fetchNotifications = async () => {
  try {
    const res = await axios.get('/api/notifications', {
      params: {
        email: user.value.email, // since you're not using auth middleware
      },
    });
    notifications.value = res.data;
  } catch (error) {
    console.error('Error fetching notifications:', error);
  }
};


onMounted(() => {
  fetchNotifications();
});



</script>
