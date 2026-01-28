<script setup lang="ts">
import { ref } from 'vue'
import axios from '@/api/axios'

const email = ref('')
const errors = ref<Record<string, string[]>>({})
const loading = ref(false)
const success = ref(false)

const sendResetLink = async () => {
  loading.value = true
  errors.value = {}
  success.value = false

  try {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/forgot-password', { email: email.value })

    success.value = true
    email.value = ''
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
        <h2 class="card-title text-2xl mb-4">Forgot Password</h2>

        <p class="text-sm mb-4">
          Enter your email address and we'll send you a link to reset your password.
        </p>

        <div v-if="success" class="alert alert-success mb-4">
          <span>Password reset link sent! Check your email.</span>
        </div>

        <form @submit.prevent="sendResetLink">
          <!-- Email Field -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">Email</span>
            </label>
            <input
              v-model="email"
              type="email"
              placeholder="email@example.com"
              class="input input-bordered"
              :class="{ 'input-error': errors.email }"
            />
            <label v-if="errors.email" class="label">
              <span class="label-text-alt text-error">{{ errors.email[0] }}</span>
            </label>
          </div>

          <!-- Submit Button -->
          <div class="form-control mb-4">
            <button type="submit" class="btn btn-primary w-full" :disabled="loading">
              <span v-if="loading" class="loading loading-spinner"></span>
              {{ loading ? 'Sending...' : 'Send Reset Link' }}
            </button>
          </div>
        </form>

        <div class="divider"></div>

        <p class="text-center">
          Remember your password?
          <router-link to="/login" class="link link-primary">Login</router-link>
        </p>
      </div>
    </div>
  </div>
</template>
