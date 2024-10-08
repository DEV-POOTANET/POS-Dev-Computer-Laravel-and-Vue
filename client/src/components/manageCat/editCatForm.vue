<template>
  <div class="container py-2">
    <div class="card shadow-lg">
      <div class="card-header">
        <h3 class="card-title">แก้ไขหมวดหมู่สินค้า</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="updateCategory">
          <div class="mb-3">
            <label for="Cat_Name" class="form-label">ชื่อหมวดหมู่</label>
            <input type="text" class="form-control" id="Cat_Name" v-model="category.Cat_Name" required>
          </div>
          <div class="mb-3">
            <label for="Cat_Status" class="form-label">สถานะ</label>
            <select class="form-select" id="Cat_Status" v-model="category.Cat_Status" required>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">บันทึก</button>
        </form>

        <!-- Toast สำหรับแสดง error หรือ success -->
        <CToaster class="p-3" placement="top-end">
          <CToast v-if="successMessage" visible autohide :delay="5000" class="bg-success text-white">
            <CToastHeader closeButton class="bg-success text-white">
              <span class="me-auto fw-bold">สำเร็จ</span>
            </CToastHeader>
            <CToastBody>{{ successMessage }}</CToastBody>
          </CToast>

          <CToast v-if="errorMessage" visible autohide :delay="5000" class="bg-danger text-white">
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
      category: {
        Cat_Name: '',
        Cat_Status: 1, // Default to Active
      },
      successMessage: '',
      errorMessage: '',
    };
  },
  methods: {
    async fetchCategory() {
      const authStore = useAuthStore();
      const token = authStore.token;
      const categoryId = this.$route.params.id;

      try {
        const response = await axios.get(`http://localhost:8000/api/cat/${categoryId}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.category = response.data.data;
        console.log(this.category)
      } catch (error) {
        console.error('Error fetching category:', error);
      }
    },
    async updateCategory() {
      const authStore = useAuthStore();
      const token = authStore.token;
      const categoryId = this.$route.params.id;

      try {
        await axios.put(`http://localhost:8000/api/cat/${categoryId}`, this.category, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.successMessage = 'ข้อมูลหมวดหมู่ถูกปรับปรุงเรียบร้อยแล้ว';
        this.errorMessage = '';
        this.$router.push('/manageCat'); // เปลี่ยนเส้นทางหลังจากการอัพเดต
      } catch (error) {
        this.successMessage = '';
        if (error.response && error.response.data.errors) {
          this.errorMessage = Object.values(error.response.data.errors).flat().join(', ');
        } else {
          this.errorMessage = 'เกิดข้อผิดพลาดในการปรับปรุงข้อมูลหมวดหมู่';
        }
        console.error('Error updating category:', error);
      }
    },
  },
  mounted() {
    this.fetchCategory();
  },
};
</script>

<style scoped>
  .container {
    max-width: 600px;
    margin: 0 auto;
  }
  
</style>
