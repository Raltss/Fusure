import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from '@/api/axios'

interface User {
  id: number
  name: string
  email: string
  role: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const isAuthenticated = ref(false)

  // Fetch authenticated user
  const fetchUser = async () => {
    try {
      const response = await axios.get('/api/user')
      user.value = response.data
      isAuthenticated.value = true
    } catch (error) {
      user.value = null
      isAuthenticated.value = false
    }
  }

  // Login
  const login = async (credentials: { email: string; password: string; remember: boolean }) => {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/login', credentials)
    await fetchUser()
  }

  // Logout
  const logout = async () => {
    await axios.post('/logout')
    user.value = null
    isAuthenticated.value = false
  }

  return {
    user,
    isAuthenticated,
    fetchUser,
    login,
    logout,
  }
})
