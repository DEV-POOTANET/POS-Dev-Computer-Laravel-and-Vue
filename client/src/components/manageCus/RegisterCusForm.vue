<template>
  <div class="container py-4">
    <div class="card shadow-lg">
      <div class="card-header">
        <h3 class="card-title">ลงทะเบียนลูกค้า</h3>
      </div>

      <div class="card-body">
        <form @submit.prevent="registerCustomer">
          <div class="form-group mb-3">
            <label for="fullname">ชื่อ-นามสกุล</label>
            <input type="text" v-model="customer.Cus_fullname" class="form-control" id="fullname" required />
          </div>

          <div class="form-group mb-3">
            <label for="email">อีเมล</label>
            <input type="email" v-model="customer.Cus_email" class="form-control" id="email" required />
          </div>

          <div class="form-group mb-3">
            <label for="tel">เบอร์โทร</label>
            <input type="text" v-model="customer.Cus_tel" class="form-control" id="tel" required />
          </div>

          <div class="form-group mb-3">
            <label for="status">สถานะ</label>
            <select v-model="customer.Cus_Status" class="form-control" id="status" required>
              <option :value="1">Active</option>
              <option :value="0">Inactive</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">ลงทะเบียน</button>
        </form>
      </div>
    </div>

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
import axios from 'axios'
import { useAuthStore } from '@/stores/auth';
export default {
  data() {
    return {
      customer: {
        Cus_fullname: '',
        Cus_email: '',
        Cus_tel: '',
        Cus_Status: 1,
      },
      successMessage: '',
      errorMessage: '',
    }
  },
  methods: {
    async registerCustomer() {
      try {
        const authStore = useAuthStore();
        const token = authStore.token;

        const response = await axios.post('http://localhost:8000/api/customer', this.customer,{
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
        this.successMessage = 'ลงทะเบียนลูกค้าสำเร็จ!'
        setTimeout(() => {
          this.clearForm()
        }, 5000)
      } catch (error) {
        console.error('Error registering customer:', error)
        this.errorMessage = error.response.data.message || 'เกิดข้อผิดพลาดในการลงทะเบียน'
      }
    },
    clearForm() {
      this.customer = {
        Cus_fullname: '',
        Cus_email: '',
        Cus_tel: '',
        Cus_Status: 1,
      }
      this.errorMessage = ''
      this.successMessage = ''
    },
  },
}

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
