import DefaultLayout from '@/layouts/DefaultLayout'
import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Define routes
const routes = [
  {
    path: '/',
    name: 'Home',
    component: DefaultLayout,
    redirect: '/login',
    children: [
      {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('../views/pages/Dashboard.vue'),
        meta: { requiresAuth: true, roles: [1, 2] },
      },
      {
        path: '/widgets',
        name: 'Widgets',
        component: () => import('../views/widgets/Widgets.vue'),
        meta: { requiresAuth: true, roles: [1, 2] },
      },
      {
        path: '/typography',
        name: 'Typography',
        component: () => import('../views/widgets/WidgetsStatsTypeA.vue'),
        meta: { requiresAuth: true, roles: [1] },  // เฉพาะ role 1 เท่านั้น
      },
    ],
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/pages/Login.vue'),
  },
]

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  // ตรวจสอบว่าเส้นทางต้องการการล็อกอินหรือไม่
  if (to.meta.requiresAuth && !authStore.token) {
    next({ name: 'Login' })
  } else if (to.meta.roles && !to.meta.roles.includes(authStore.role)) {
    // ตรวจสอบ role ว่าสามารถเข้าถึงเส้นทางได้หรือไม่
    next({ name: 'Dashboard' }) // หรือไปยังเส้นทางที่อนุญาต
  } else {
    next()
  }
})

export default router
