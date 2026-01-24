<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

const message = ref('')
const error = ref('')
const loading = ref(false)

const testApi = async () => {
  loading.value = true
  message.value = ''
  error.value = ''

  try {
    console.log('Making API request...')
    const response = await axios.get('/api/test')
    console.log('Response:', response.data)
    message.value = response.data.message
  } catch (err: any) {
    console.error('Error:', err)
    error.value = 'API Error: ' + (err.message || 'Unknown error')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-base-200">
    <div class="card w-96 bg-base-100 shadow-xl">
      <div class="card-body">
        <h2 class="card-title">API Test</h2>

        <button @click="testApi" class="btn btn-primary" :disabled="loading">
          <span v-if="loading" class="loading loading-spinner"></span>
          {{ loading ? 'Testing...' : 'Test API Connection' }}
        </button>

        <div v-if="message" class="alert alert-success mt-4">
          <span>✓ {{ message }}</span>
        </div>

        <div v-if="error" class="alert alert-error mt-4">
          <span>✗ {{ error }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
