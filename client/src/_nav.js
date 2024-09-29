
export default [
  {
    component: 'CNavItem',
    name: 'Dashboard',
    to: '/dashboard',    
    icon: 'cil-speedometer',
    badge: {
      color: 'primary',
      text: 'NEW',
    },
  },
  {
    component: 'CNavItem',
    name: 'ขายสินค้า',
    to: '/sell',    
    icon: 'cil-notes',
  },
  {
    component: 'CNavTitle',
    name: 'จัดการสินค้า',
  },
  {
    component: 'CNavItem',
    name: 'จัดการสินค้า',
    to: '/manageProduct',
    icon: 'cil-notes',
  },
  {
    component: 'CNavItem',
    name: 'จัดการหมวดหมู่',
    to: '/manageCat',
    icon: 'cil-pencil',
  },
  {
    component: 'CNavItem',
    name: 'จัดการยี่ห้อ',
    to: '/manageBrand',
    icon: 'cil-pencil',
  },
  {
    component: 'CNavTitle',
    name: 'จัดการผู้ใช้',
  },
  {
    component: 'CNavItem',
    name: 'จัดการพนักงาน',
    to: '/manageEmp',
    icon: 'cil-people',
  },
  {
    component: 'CNavItem',
    name: 'จัดการลูกค้า',
    to: '/manageCus',
    icon: 'cil-people',
  },
]
