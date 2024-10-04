// stores/auth.js
import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null) // ดึง token จาก localStorage
  const role = ref(localStorage.getItem('role') || null) // ดึง role จาก localStorage
  const name = ref(localStorage.getItem('name') || null)
  const uid = ref(localStorage.getItem('uid') || null)


  const login = async (email, password) => {
    try {
      const response = await axios.post('http://localhost:8000/api/login', {
        email,
        password
      })
      
      user.value = response.data.user
      token.value = response.data.token
      role.value = response.data.user.role
      name.value = response.data.user.fullname
      uid.value = response.data.user.id
      

      
      // เก็บ token และ role ลง localStorage
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('role', response.data.user.role) // เก็บ role ด้วย
      localStorage.setItem('name', response.data.user.fullname) 
      localStorage.setItem('uid', response.data.user.id) 
    } catch (error) {
      console.error('Login failed', error)
      throw error
    }
  }

  const logout = async () => {
    try {
      // เรียก API ลบ token ของผู้ใช้ฝั่ง backend
      await axios.post('http://localhost:8000/api/logout', {}, {
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      })
    } catch (error) {
      console.error('Logout failed', error)
    } finally {
      // ล้างข้อมูลใน auth store และ localStorage
      user.value = null
      token.value = null
      role.value = null
      localStorage.removeItem('token')
      localStorage.removeItem('role')
      
    }
  }
  

  return {
    user,
    token,
    role,
    login,
    logout
  }
})
