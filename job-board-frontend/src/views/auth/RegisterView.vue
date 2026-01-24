<script setup lang="ts">
import { ref } from 'vue'
import axios from '@/api/axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'job_seeker',
})

const errors = ref<Record<string, string[]>>({})
const loading = ref(false)

const register = async () => {
  loading.value = true
  errors.value = {}

  try {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/register', form.value)

    alert('Registration successful!')
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
        <h2 class="card-title text-2xl mb-4">Create Account</h2>

        <form @submit.prevent="register">
          <!-- Name -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">Full Name</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              placeholder=""
              class="input input-bordered md:ml-3 px-2"
              :class="{ 'input-error': errors.name }"
            />
            <label v-if="errors.name" class="label">
              <span class="label-text-alt text-error">{{ errors.name[0] }}</span>
            </label>
          </div>

          <!-- Email -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">Email</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder=""
              class="input input-bordered md:ml-3 px-2"
              :class="{ 'input-error': errors.email }"
            />
            <label v-if="errors.email" class="label">
              <span class="label-text-alt text-error">{{ errors.email[0] }}</span>
            </label>
          </div>

          <!-- Role -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">I am a</span>
            </label>
            <select v-model="form.role" class="select select-bordered md:ml-3 px-2">
              <option value="job_seeker">Job Seeker</option>
              <option value="employer">Employer</option>
            </select>
          </div>

          <!-- Password -->
          <div class="form-control mb-4">
            <label class="label">
              <span class="label-text">Password</span>
            </label>
            <input
              v-model="form.password"
              type="password"
              placeholder=""
              class="input input-bordered md:ml-3 px-2"
              :class="{ 'input-error': errors.password }"
            />
            <label v-if="errors.password" class="label">
              <span class="label-text-alt text-error">{{ errors.password[0] }}</span>
            </label>
          </div>

          <!-- Confirm Password -->
          <div class="form-control mb-6">
            <label class="label">
              <span class="label-text">Confirm Password</span>
            </label>
            <input
              v-model="form.password_confirmation"
              type="password"
              placeholder=""
              class="input input-bordered px-2"
            />
          </div>

          <!-- Submit Button -->
          <div class="form-control flex items-center justify-center">
            <button type="submit" class="btn btn-primary w-1/2" :disabled="loading">
              <span v-if="loading" class="loading loading-spinner"></span>
              {{ loading ? 'Creating Account...' : 'Register' }}
            </button>
          </div>
        </form>

        <div class="divider">OR</div>

        <p class="text-center">
          Already have an account?
          <router-link to="/login" class="link link-primary">Login</router-link>
        </p>
      </div>
    </div>
  </div>
</template>
