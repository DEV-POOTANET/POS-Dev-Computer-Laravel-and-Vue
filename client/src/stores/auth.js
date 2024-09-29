// stores/auth.js
import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null) // ดึง token จาก localStorage
  const role = ref(localStorage.getItem('role') || null) // ดึง role จาก localStorage
  
  const login = async (email, password) => {
    try {
      const response = await axios.post('http://localhost:8000/api/login', {
        email,
        password
      })
      
      user.value = response.data.user
      token.value = response.data.token
      role.value = response.data.user.role
      
      // เก็บ token และ role ลง localStorage
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('role', response.data.user.role) // เก็บ role ด้วย
    } catch (error) {
      console.error('Login failed', error)
      throw error
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    role.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('role') // ลบ role ออกจาก localStorage ด้วย
  }

  return {
    user,
    token,
    role,
    login,
    logout
  }
})
