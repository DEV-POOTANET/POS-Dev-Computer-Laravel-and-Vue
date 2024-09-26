
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
    component: 'CNavTitle',
    name: 'Theme',
  },
  {
    component: 'CNavItem',
    name: 'Widgets',
    to: '/widgets',
    icon: '',
  },
  {
    component: 'CNavItem',
    name: 'Typography',
    to: '/typography',
    icon: 'cil-pencil',
  },
  
]
