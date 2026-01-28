import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import TestApi from '@/views/TestApi.vue'
import RegisterView from '@/views/auth/RegisterView.vue'
import LoginView from '@/views/auth/LoginView.vue'
import DashboardView from '@/views/DashboardView.vue'
import ForgotPasswordView from '@/views/auth/ForgotPasswordView.vue'
import ResetPasswordView from '@/views/auth/ResetPasswordView.vue'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/test',
      name: 'test',
      component: () => TestApi,
    },
    {
      path: '/register',
      name: 'register',
      component: () => RegisterView,
      meta: { guest: true }, // Only accessible when NOT logged in
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { guest: true }, // Only accessible when NOT logged in
    },
    {
      path: '/forgot-password',
      name: 'forgot-password',
      component: () => ForgotPasswordView,
      meta: { guest: true },
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: () => ResetPasswordView,
      meta: { guest: true },
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => DashboardView,
      meta: { requiresAuth: true, roles: ['job_seeker', 'employer'] },
    },
    {
      path: '/employer/dashboard',
      name: 'employer-dashboard',
      component: () => import('../views/employer/EmployerDashboardView.vue'),
      meta: { requiresAuth: true, roles: ['employer'] },
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
    },
  ],
})

// Global navigation guard
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth)
  const guestOnly = to.matched.some((record) => record.meta.guest)

  // For guest-only routes (login, register) when already logged in
  if (guestOnly) {
    if (authStore.user) {
      if (authStore.user.role === 'employer') {
        return next('/employer/dashboard')
      } else {
        return next('/dashboard')
      }
    }
    return next()
  }

  // For protected routes when not logged in
  if (requiresAuth) {
    if (!authStore.user) {
      return next('/login')
    }

    // Check role-based access
    if (to.meta.roles) {
      const allowedRoles = to.meta.roles as string[]
      if (!allowedRoles.includes(authStore.user.role)) {
        return next('/')
      }
    }
  }

  next()
})

export default router
