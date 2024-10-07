<template>
    <div class="container py-2">
      <div class="card shadow-lg">
        <div class="card-header">
          <h3 class="card-title">แก้ไขข้อมูลพนักงาน</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="updateEmployee">
            <div class="mb-3">
              <label for="fullname" class="form-label">ชื่อ</label>
              <input type="text" class="form-control" id="fullname" v-model="employee.fullname" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">อีเมล</label>
              <input type="email" class="form-control" id="email" v-model="employee.email" required>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">ที่อยู่</label>
              <textarea class="form-control" id="address" v-model="employee.address" required></textarea>
            </div>
            <div class="mb-3">
              <label for="tel" class="form-label">เบอร์โทร</label>
              <input type="tel" class="form-control" id="tel" v-model="employee.tel" required>
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">บทบาท</label>
              <select class="form-select" id="role" v-model="employee.role" required>
                <option value="1">Admin</option>
                <option value="2">User</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
          </form>
  
          <!-- Toast สำหรับแสดง error หรือ success -->
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
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { useAuthStore } from '@/stores/auth';
  
  export default {
    data() {
      return {
        employee: {
          fullname: '',
          email: '',
          address: '',
          tel: '',
          role: '', 
        },
        successMessage: '',
        errorMessage: '',
      };
    },
    methods: {
      async fetchEmployee() {
        const authStore = useAuthStore();
        const token = authStore.token;
        const employeeId = this.$route.params.id; 
  
        try {
          const response = await axios.get(`http://localhost:8000/api/getUser/${employeeId}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          console.log(response.data.data)
          this.employee = response.data.data;
        } catch (error) {
          console.error('Error fetching employee:', error);
        }
      },
      async updateEmployee() {
        const authStore = useAuthStore();
        const token = authStore.token;
        const employeeId = this.$route.params.id;

        console.log(this.employee); // ตรวจสอบค่าที่จะส่ง

        try {
            await axios.put(`http://localhost:8000/api/updateUser/${employeeId}`, this.employee, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            this.successMessage = 'ข้อมูลพนักงานถูกปรับปรุงเรียบร้อยแล้ว';
            this.errorMessage = '';
            this.$router.push('/manageEmp'); // เปลี่ยนเส้นทางหลังจากการอัพเดต
        } catch (error) {
            this.successMessage = '';
            if (error.response && error.response.data.errors) {
                this.errorMessage = Object.values(error.response.data.errors).flat().join(', ');
            } else {
                this.errorMessage = 'เกิดข้อผิดพลาดในการปรับปรุงข้อมูลพนักงาน';
            }
            console.error('Error updating employee:', error);
        }
    },

    },
    mounted() {
      this.fetchEmployee(); 
    },
  };
  </script>
  
  <style scoped>
 
  </style>
  