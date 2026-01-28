<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '@/api/axios'

const route = useRoute()
const router = useRouter()

const form = ref({
  token: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = ref<Record<string, string[]>>({})
const loading = ref(false)

onMounted(() => {
  // Get token and email from URL query parameters
  form.value.token = (route.query.token as string) || ''
  form.value.email = (route.query.email as string) || ''
})

const resetPassword = async () => {
  loading.value = true
  errors.value = {}

  try {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/reset-password', form.value)

    alert('Password reset successful! You can now login.')
    router.push('/login')
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-base-200">
    <div class="card w-full max-w-md bg-base-100 shadow-xl">
      <div class="card-body">
        <h2 class="card-title text-2xl mb-4">Reset Password</h2>

        <form @submit.prevent="resetPassword">
          <!-- Email Field (readonly) -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">Email</span>
            </label>
            <input v-model="form.email" type="email" class="input input-bordered" readonly />
          </div>

          <!-- Password Field -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">New Password</span>
            </label>
            <input
              v-model="form.password"
              type="password"
              placeholder=""
              class="input input-bordered"
              :class="{ 'input-error': errors.password }"
            />
            <label v-if="errors.password" class="label">
              <span class="label-text-alt text-error">{{ errors.password[0] }}</span>
            </label>
          </div>

          <!-- Confirm Password Field -->
          <div class="form-control mb-6">
            <label class="label">
              <span class="label-text">Confirm New Password</span>
            </label>
            <input
              v-model="form.password_confirmation"
              type="password"
              placeholder=""
              class="input input-bordered"
            />
          </div>

          <!-- Submit Button -->
          <div class="form-control">
            <button type="submit" class="btn btn-primary w-full" :disabled="loading">
              <span v-if="loading" class="loading loading-spinner"></span>
              {{ loading ? 'Resetting...' : 'Reset Password' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
