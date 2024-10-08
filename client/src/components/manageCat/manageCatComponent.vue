<template>
    <div class="container py-2">
      <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">หมวดหมู่สินค้า</h3>
          <button class="btn btn-primary" @click="addCategory">เพิ่มหมวดหมู่</button>
        </div>
  
        <div class="card-body table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>ชื่อหมวดหมู่</th>
                <th>สถานะ</th>
                <th>แก้ไข</th>
                <th>อัพเดทสถานะ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="cat in categories" :key="cat.id">
                <td>{{ cat.id }}</td>
                <td>{{ cat.Cat_Name }}</td>
                <td>
                <span 
                  :class="cat.Cat_Status == 1 ? 'status-active' : 'status-inactive'"
                  class="status-dot">
                </span>
                {{ cat.Cat_Status == 1 ? 'Active' : 'Inactive' }}
                </td>
                <td>
                  <button class="btn btn-warning btn-sm mx-2" @click="editCategory(cat.id)">
                    แก้ไข
                  </button>
                </td>
                <td>
                  <button class="btn btn-danger btn-sm mx-2" @click="toggleStatus(cat.id)">
                    {{ cat.Cat_Status ? 'ระงับ' : 'ยกเลิกการระงับ' }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <CToaster class="p-3" placement="top-end">
        <CToast 
            v-if="successMessage" 
            visible 
            autohide 
            :delay="5000"
            class="bg-success text-white">
            <CToastHeader closeButton class="bg-success text-white">
                <span class="me-auto fw-bold">สำเร็จ</span>
            </CToastHeader>
            <CToastBody>{{ successMessage }}</CToastBody>
         </CToast>
  
        <CToast 
            v-if="errorMessage" 
            visible 
            autohide 
            :delay="5000"  
            class="bg-danger text-white">
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
        categories: [],
        successMessage: '',
        errorMessage: '',
      };
    },
    methods: {
      async fetchCategories() {
        const authStore = useAuthStore();
        const token = authStore.token;

        try {
          const response = await axios.get('http://localhost:8000/api/cat',{
            headers: {
            Authorization: `Bearer ${token}`,
            },
          });
          this.categories = response.data.data;
          console.log(this.categories)
        } catch (error) {
          this.errorMessage = 'ไม่สามารถดึงข้อมูลหมวดหมู่ได้';
          console.error('Error fetching categories:', error);
        }
      },
  
      async addCategory() {
        this.$router.push('/AddCat');
      },
  
      async editCategory(catId) {
        this.$router.push(`/editCategory/${catId}`);
      },
  
      async toggleStatus(catId) {
        const authStore = useAuthStore();
        const token = authStore.token;

        try {
          const response = await axios.put(`http://localhost:8000/api/catUpdateStatus/${catId}`,{},{
            headers: {
                Authorization: `Bearer ${token}`, 
            },
          });
          this.successMessage = 'อัพเดทสถานะสำเร็จ!';
          this.fetchCategories();
        } catch (error) {
          this.errorMessage = 'เกิดข้อผิดพลาดในการอัพเดทสถานะ';
          console.error('Error updating status:', error);
        }
      }
    },
    mounted() {
      this.fetchCategories();
    },
  };
  </script>
  
  <style scoped>
  .table {
    width: 100%;
    margin-top: 20px;
  }

  .status-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%; /* ทำให้เป็นวงกลม */
  margin-right: 5px; /* เว้นระยะห่างจากข้อความ */
}

.status-active {
  background-color: green; /* สีเขียวสำหรับ Active */
}

.status-inactive {
  background-color: red; /* สีแดงสำหรับ Inactive */
}
  </style>
  