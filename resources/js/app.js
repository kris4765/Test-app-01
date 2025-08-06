// ✅ Bootstrap & CSS
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap'
import './bootstrap'
import '../css/app.css'

// ✅ Vue + Inertia
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'


// ✅ Pinia
import { createPinia } from 'pinia'

// ✅ App Name (default fallback)
const appName = import.meta.env.VITE_APP_NAME || 'Laravel'





if ('serviceWorker' in navigator) {
  navigator.serviceWorker
    .register('/firebase-messaging-sw.js')
    .then((registration) => {
      console.log('✅ Service Worker registered:', registration);
    })
    .catch((err) => {
      console.error('❌ Service Worker registration failed:', err);
    });
}




// ✅ Inertia App Setup
createInertiaApp({
  title: (title) => `${title} - ${appName}`,

  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),

  setup({ el, App, props, plugin }) {
    const vueApp = createApp({ render: () => h(App, props) })
    vueApp.use(plugin)
    vueApp.use(ZiggyVue)
    vueApp.use(createPinia()) // 👈 This enables Pinia globally
    vueApp.mount(el)
  },

  progress: {
    color: '#4B5563',
  },
})
