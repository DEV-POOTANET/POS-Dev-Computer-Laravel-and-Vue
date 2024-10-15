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
      // {
      //   path: '/typography',
      //   name: 'Typography',
      //   component: () => import('../views/widgets/WidgetsStatsTypeA.vue'),
      //   meta: { requiresAuth: true, roles: [1] },  // เฉพาะ role 1 เท่านั้น
      // },
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
        meta: { requiresAuth: true, roles: [1,2] },  
      },
      {
        path: '/registerEmp',
        name: 'ลงทะเบียนพนักงาน',
        component: () => import('../components/manageUser/RegisterEmpForm.vue'),
        meta: { requiresAuth: true, roles: [1] },  
      },
      {
        path: '/EditEmp/:id',
        name: 'แก้ไขข้อมูลพนักงาน',
        component: () => import('../components/manageUser/EditEmpForm.vue'),
        meta: { requiresAuth: true, roles: [1] },  
      },
      {
        path: '/AddCat',
        name: 'เพิ่มหมวดหมู่',
        component: () => import('../components/manageCat/addCatForm.vue'),
        meta: { requiresAuth: true, roles: [1,2] },  
      },
      {
        path: '/editCategory/:id',
        name: 'แก้ไขหมวดหมู่',
        component: () => import('../components/manageCat/editCatForm.vue'),
        meta: { requiresAuth: true, roles: [1,2] },  
      },
      {
        path: '/AddPrd',
        name: 'เพิ่มสินค่า',
        component: () => import('../components/managePrd/AddPrdForm.vue'),
        meta: { requiresAuth: true, roles: [1,2] },  
      },
      {
        path: '/EditPrd/:id',
        name: 'แก้ไขสินค่า',
        component: () => import('../components/managePrd/EditPrdForm.vue'),
        meta: { requiresAuth: true, roles: [1,2] },  
      },
      {
        path: '/EditSn/:id',
        name: 'แก้ไขSerialNumbers',
        component: () => import('../components/managePrd/EditSnForm.vue'),
        meta: { requiresAuth: true, roles: [1,2] },  
      },
      {
        path: '/AddSn/:id',
        name: 'เพิ่มSerialNumbers',
        component: () => import('../components/managePrd/AddSnForm.vue'),
        meta: { requiresAuth: true, roles: [1,2] },  
      },
      {
        path: '/EditCus/:id',
        name: 'แก้ไขข้อมูลลูกคค้า',
        component: () => import('../components/manageCus/EditCusForm.vue'),
        meta: { requiresAuth: true, roles: [1,2] },  
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

  // ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
  if (authStore.token) {
    // ถ้าผู้ใช้ล็อกอินแล้ว และกำลังพยายามเข้าถึงหน้า Login
    if (to.name === 'Login') {
      return next({ name: 'Dashboard' });
    }
    
    // ตรวจสอบ role ว่าสามารถเข้าถึงเส้นทางได้หรือไม่
    if (to.meta.roles && to.meta.roles.filter( el => el == authStore.role).length <= 0) {
      // ตรวจสอบว่าเราไม่ได้อยู่ในหน้า Dashboard อยู่แล้ว
      if (to.name !== 'Dashboard') {
        return next({ name: 'Dashboard' }); // เปลี่ยนเส้นทางไปที่แดชบอร์ดถ้าบทบาทไม่ถูกต้อง
      } else {
        return next(false); // ยกเลิกการเปลี่ยนเส้นทางถ้าพยายามเปลี่ยนไปหน้าเดิม
      }
    }

    return next(); // ถ้า role ถูกต้อง อนุญาตให้เข้าถึง
  } else {
    // ตรวจสอบว่าเส้นทางต้องการการล็อกอินหรือไม่
    if (to.meta.requiresAuth) {
      return next({ name: 'Login' }); // เปลี่ยนเส้นทางไปที่เข้าสู่ระบบถ้าไม่ได้ล็อกอิน
    }

    return next(); // ให้ดำเนินการไปตามเส้นทางปกติ
  }
});




export default router
