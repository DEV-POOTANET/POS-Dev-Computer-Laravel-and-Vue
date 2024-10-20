
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
    roles: [1, 2]
  },
  {
    component: 'CNavTitle',
    name: 'การขายสินค้า',
  },
  {
    component: 'CNavItem',
    name: 'ขายสินค้า',
    to: '/sell',    
    icon: 'cil-notes',
    roles: [1, 2]
  },
  {
    component: 'CNavItem',
    name: 'รายการขาย',
    to: '/order',    
    icon: 'cil-notes',
    roles: [1, 2]
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
    roles: [1, 2]
  },
  {
    component: 'CNavItem',
    name: 'จัดการหมวดหมู่',
    to: '/manageCat',
    icon: 'cil-pencil',
    roles: [1, 2]
  },
  {
    component: 'CNavTitle',
    name: 'จัดการผู้ใช้',
    roles: [1, 2]
  },
  {
    component: 'CNavItem',
    name: 'จัดการพนักงาน',
    to: '/manageEmp',
    icon: 'cil-people',
    roles: [1]
  },
  {
    component: 'CNavItem',
    name: 'จัดการลูกค้า',
    to: '/manageCus',
    icon: 'cil-people',
    roles: [1,2]
  },
]
