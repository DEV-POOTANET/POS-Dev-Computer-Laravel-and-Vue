// stores/auth.js
import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(null)
  const role = ref(null)
  
  const login = async (email, password) => {
    try {
      const response = await axios.post('http://localhost:8000/api/login', {
        email,
        password
      })
      
      user.value = response.data.user
      token.value = response.data.token
      role.value = response.data.user.role
      
      // เก็บ token ลง localStorage
      localStorage.setItem('token', response.data.token)
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
  }

  return {
    user,
    token,
    role,
    login,
    logout
  }
})
