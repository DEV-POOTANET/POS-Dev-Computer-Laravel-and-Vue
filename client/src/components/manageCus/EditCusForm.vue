<template>
  <div class="container py-2">
    <div class="card shadow-lg">
      <div class="card-header">
        <h3 class="card-title">แก้ไขข้อมูลลูกค้า</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="updateCustomer">
          <div class="mb-3">
            <label for="fullname" class="form-label">ชื่อ</label>
            <input
              type="text"
              class="form-control"
              id="fullname"
              v-model="customer.Cus_fullname"
              required
            />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input
              type="email"
              class="form-control"
              id="email"
              v-model="customer.Cus_email"
              required
            />
          </div>
          <div class="mb-3">
            <label for="tel" class="form-label">เบอร์โทร</label>
            <input
              type="tel"
              class="form-control"
              id="tel"
              v-model="customer.Cus_tel"
              required
            />
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">สถานะ</label>
            <select
              class="form-select"
              id="status"
              v-model="customer.Cus_Status"
              required
            >
              <option value="1">Active</option>
              <option value="0">InActive</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">บันทึก</button>
        </form>

        <!-- Toast Notifications -->
        <CToaster class="p-3" placement="top-end">
          <CToast
            v-if="successMessage"
            visible
            autohide
            :delay="5000"
            class="bg-success text-white"
          >
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
            class="bg-danger text-white"
          >
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
      customer: {
        Cus_fullname: '',
        Cus_email: '',
        Cus_tel: '',
        Cus_Status: '', 
      },
      successMessage: '',
      errorMessage: '',
    };
  },
  methods: {
    async fetchCustomer() {
      const authStore = useAuthStore();
      const token = authStore.token;
      const customerId = this.$route.params.id;

      try {
        const response = await axios.get(
          `http://localhost:8000/api/customer/${customerId}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        this.customer = response.data.data;
      } catch (error) {
        console.error('Error fetching customer:', error);
      }
    },
    async updateCustomer() {
      const authStore = useAuthStore();
      const token = authStore.token;
      const customerId = this.$route.params.id;

      try {
        await axios.put(
          `http://localhost:8000/api/customer/${customerId}`,
          this.customer,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        this.successMessage = 'ข้อมูลลูกค้าถูกปรับปรุงเรียบร้อยแล้ว';
        this.errorMessage = '';
        this.$router.push('/manageCus');
      } catch (error) {
        this.successMessage = '';
        if (error.response && error.response.data.errors) {
          this.errorMessage = Object.values(error.response.data.errors)
            .flat()
            .join(', ');
        } else {
          this.errorMessage = 'เกิดข้อผิดพลาดในการปรับปรุงข้อมูลลูกค้า';
        }
        console.error('Error updating customer:', error);
      }
    },
  },
  mounted() {
    this.fetchCustomer();
  },
};
</script>

<style scoped>
</style>
