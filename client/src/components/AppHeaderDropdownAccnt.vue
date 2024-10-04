<script setup>
import avatar from '@/assets/images/user.png'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'


const username = localStorage.getItem('name') 
const authStore = useAuthStore()
const router = useRouter()

const handleLogout = async () => {  
  await authStore.logout()  // เรียกใช้ฟังก์ชัน logout
  router.push('/login')
}
</script>

<template>
  <CDropdown placement="bottom-end" variant="nav-item">
    <CDropdownToggle class="py-0 pe-0" :caret="false">
      <CAvatar :src="avatar" size="md" />
    </CDropdownToggle>

    <CDropdownMenu class="pt-0">
      <CDropdownHeader
        component="h6"
        class="bg-body-secondary text-body-secondary fw-semibold mb-2 rounded-top"
      >
        Account
      </CDropdownHeader>
      
      <CDropdownItem>
        <h5>Name : {{ username }}</h5>
      </CDropdownItem>

     
      <CDropdownDivider />

      <CDropdownItem @click="handleLogout">
        <CIcon icon="cil-lock-locked" /> Logout
     </CDropdownItem>
    </CDropdownMenu>
  </CDropdown>
</template>
