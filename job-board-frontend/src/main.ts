import './assets/main.css'
import './style.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import { useAuthStore } from './stores/auth'

const app = createApp(App)

app.use(createPinia())

const authStore = useAuthStore()

// Initialize auth before mounting
authStore
  .fetchUser()
  .catch(() => {
    // No active session
  })
  .finally(() => {
    app.use(router)
    app.mount('#app')
  })
