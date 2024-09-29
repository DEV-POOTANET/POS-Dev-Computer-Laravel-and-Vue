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
        path: '/sell',
        name: 'ขายสินค้า',
        component: () => import('../views/sell/sell.vue'),
        meta: { requiresAuth: true, roles: [1, 2] },
      },
      // {
      //   path: '/widgets',
      //   name: 'Widgets',
      //   component: () => import('../views/widgets/Widgets.vue'),
      //   meta: { requiresAuth: true, roles: [1, 2] },
      // },
      {
        path: '/manageProduct',
        name: 'จัดการสินค้า',
        component: () => import('../views/manageProduct/manageProduct.vue'),
        meta: { requiresAuth: true, roles: [1, 2] },
      },
      {
        path: '/manageCat',
        name: 'จัดการหมวดหมู่',
        component: () => import('../views/manageProduct/manageCat.vue'),
        meta: { requiresAuth: true, roles: [1, 2] },
      },
      {
        path: '/manageBrand',
        name: 'จัดการยี่ห้อ',
        component: () => import('../views/manageProduct/manageBrand.vue'),
        meta: { requiresAuth: true, roles: [1, 2] },
      },
      {
        path: '/typography',
        name: 'Typography',
        component: () => import('../views/widgets/WidgetsStatsTypeA.vue'),
        meta: { requiresAuth: true, roles: [1] },  // เฉพาะ role 1 เท่านั้น
      },
      {
        path: '/manageEmp',
        name: 'จัดการพนักงาน',
        component: () => import('../views/manageUser/manageEmp.vue'),
        meta: { requiresAuth: true, roles: [1] },  // เฉพาะ role 1 เท่านั้น
      },
      {
        path: '/manageCus',
        name: 'จัดการลูกค้า',
        component: () => import('../views/manageUser/manageCus.vue'),
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
  const authStore = useAuthStore();
  console.log('Token:', authStore.token); // แสดง token ใน console
  // console.log(to.meta.roles && !to.meta.roles.includes(authStore.role));
  // ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
  if (authStore.token) {
    // ถ้าผู้ใช้ล็อกอินแล้ว และกำลังพยายามเข้าถึงหน้า Login
    if (to.name === 'Login') {
      next({ name: 'Dashboard' }); // เปลี่ยนเส้นทางไปที่แดชบอร์ด
    } else {
      next(); // อนุญาตให้เข้าใช้เส้นทางอื่นๆ
    }

    if (to.meta.roles && !to.meta.roles.includes(authStore.role)) {
      // ตรวจสอบ role ว่าสามารถเข้าถึงเส้นทางได้หรือไม่
      next({ name: 'Dashboard' }); // ถ้าบทบาทไม่ถูกต้อง เปลี่ยนเส้นทางไปที่แดชบอร์ด
    }else {
      next(); // อนุญาตให้เข้าใช้เส้นทางอื่นๆ
    }
  } else {
    // ตรวจสอบว่าเส้นทางต้องการการล็อกอินหรือไม่
    if (to.meta.requiresAuth && !authStore.token) {
      next({ name: 'Login' }); // เปลี่ยนเส้นทางไปที่เข้าสู่ระบบถ้าไม่ได้ล็อกอิน
    }  else {
      next(); // ให้ดำเนินการไปตามเส้นทางปกติ
    }
  }
});


export default router
