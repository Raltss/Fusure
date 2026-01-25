<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const errors = ref<Record<string, string[]>>({})
const loading = ref(false)

const login = async () => {
  loading.value = true
  errors.value = {}

  try {
    await authStore.login(form.value)

    // Redirect based on role
    if (authStore.user?.role === 'employer') {
      router.push('/employer/dashboard')
    } else {
      router.push('/dashboard')
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else if (error.response?.data?.message) {
      errors.value = { email: [error.response.data.message] }
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
        <h2 class="card-title text-2xl mb-4">Login</h2>

        <form @submit.prevent="login">
          <!-- Email Field -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">Email</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder=""
              class="input input-bordered p-2 md:mx-3"
              :class="{ 'input-error': errors.email }"
            />
            <label v-if="errors.email" class="label">
              <span class="label-text-alt text-error">{{ errors.email[0] }}</span>
            </label>
          </div>

          <!-- Password Field -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">Password</span>
            </label>
            <input
              v-model="form.password"
              type="password"
              placeholder=""
              class="input input-bordered p-2 md:mx-2"
              :class="{ 'input-error': errors.password }"
            />
            <label v-if="errors.password" class="label">
              <span class="label-text-alt text-error">{{ errors.password[0] }}</span>
            </label>
          </div>

          <!-- Remember Me Checkbox -->
          <div class="form-control mb-4">
            <label class="label cursor-pointer justify-start">
              <input
                v-model="form.remember"
                type="checkbox"
                class="checkbox checkbox-primary mr-2"
              />
              <span class="label-text">Remember me</span>
            </label>
          </div>

          <!-- Submit Button -->
          <div class="form-control mb-4">
            <button type="submit" class="btn btn-primary w-full" :disabled="loading">
              <span v-if="loading" class="loading loading-spinner"></span>
              {{ loading ? 'Logging in...' : 'Login' }}
            </button>
          </div>

          <!-- Forgot Password Link -->
          <div class="text-center mb-4">
            <router-link to="/forgot-password" class="link link-primary text-sm">
              Forgot password?
            </router-link>
          </div>
        </form>

        <div class="divider">OR</div>

        <p class="text-center">
          Don't have an account?
          <router-link to="/register" class="link link-primary">Register</router-link>
        </p>
      </div>
    </div>
  </div>
</template>
