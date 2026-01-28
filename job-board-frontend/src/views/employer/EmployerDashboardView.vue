<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

onMounted(async () => {
  if (!authStore.user) {
    try {
      await authStore.fetchUser()
    } catch (error) {
      router.push('/login')
    }
  }
})

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-base-200">
    <div class="navbar bg-base-100 shadow-lg">
      <div class="flex-1">
        <a class="btn btn-ghost text-xl">Job Board - Employer</a>
      </div>
      <div class="flex-none">
        <button @click="handleLogout" class="btn btn-ghost">Logout</button>
      </div>
    </div>

    <div class="container mx-auto p-8">
      <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
          <h2 class="card-title text-2xl mb-4">Employer Dashboard</h2>

          <div v-if="authStore.user" class="space-y-4">
            <div class="alert alert-info">
              <span
                >Welcome to the Employer Dashboard! Here you can post jobs and manage
                applications.</span
              >
            </div>

            <div>
              <span class="font-semibold">Name:</span>
              {{ authStore.user.name }}
            </div>
            <div>
              <span class="font-semibold">Email:</span>
              {{ authStore.user.email }}
            </div>
            <div>
              <span class="font-semibold">Role:</span>
              <span class="badge badge-primary">{{ authStore.user.role }}</span>
            </div>
          </div>

          <div v-else class="text-center">
            <span class="loading loading-spinner loading-lg"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
