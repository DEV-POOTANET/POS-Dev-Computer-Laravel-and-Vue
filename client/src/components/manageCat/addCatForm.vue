<template>
    <div class="container py-4">
      <div class="card shadow-lg">
        <div class="card-header">
          <h3 class="card-title">เพิ่มหมวดหมู่สินค้า</h3>
        </div>
  
        <div class="card-body">
          <form @submit.prevent="addCategory">
            <div class="form-group mb-3">
              <label for="Cat_Name">ชื่อหมวดหมู่</label>
              <input type="text" v-model="category.Cat_Name" class="form-control" id="Cat_Name" required />
            </div>
  
            <div class="form-group mb-3">
              <label for="Cat_Status">สถานะ</label>
              <select v-model="category.Cat_Status" class="form-control" id="Cat_Status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
  
            <button type="submit" class="btn btn-primary">เพิ่มหมวดหมู่</button>
          </form>
        </div>
      </div>
  
      <!-- Toast สำหรับแสดง error หรือ success -->
      <CToaster class="p-3" placement="top-end">
        <CToast v-if="successMessage" visible autohide delay="5000" class="bg-success text-white">
          <CToastHeader closeButton class="bg-success text-white">
            <span class="me-auto fw-bold">สำเร็จ</span>
          </CToastHeader>
          <CToastBody>{{ successMessage }}</CToastBody>
        </CToast>
  
        <CToast v-if="errorMessage" visible autohide delay="5000" class="bg-danger text-white">
          <CToastHeader closeButton class="bg-danger text-white">
            <span class="me-auto fw-bold">Error</span>
          </CToastHeader>
          <CToastBody>{{ errorMessage }}</CToastBody>
        </CToast>
      </CToaster>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { useAuthStore } from '@/stores/auth';
  export default {
    data() {
      return {
        category: {
          Cat_Name: '',
          Cat_Status: 1, 
        },
        successMessage: '',
        errorMessage: '',
      };
    },
    methods: {
      async addCategory() {
        const authStore = useAuthStore();
        const token = authStore.token;

        try {
          const response = await axios.post('http://localhost:8000/api/cat', this.category,{
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.successMessage = 'เพิ่มหมวดหมู่สำเร็จ!';
          setTimeout(() => {
            this.clearForm();
          }, 5000);
        } catch (error) {
          console.error('Error adding category:', error);
          this.errorMessage = error.response.data.message || 'เกิดข้อผิดพลาดในการเพิ่มหมวดหมู่';
        }
      },
      clearForm() {
        this.category = {
          Cat_Name: '',
          Cat_Status: 1, // Reset to Active
        };
        this.errorMessage = '';
        this.successMessage = '';
      },
    },
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
    margin: 0 auto;
  }
  
  .form-control {
    margin-bottom: 10px;
  }
  
  .btn {
    width: 100%;
  }
  </style>
  