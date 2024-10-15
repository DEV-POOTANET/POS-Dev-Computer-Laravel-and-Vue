<template>
  <div class="container py-2">
    <div class="card shadow-lg">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">ข้อมูลลูกค้า</h3>
        <button class="btn btn-primary" @click="$router.push('/addCustomer')">
          เพิ่มลูกค้า
        </button>
      </div>

      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>ชื่อ</th>
              <th>อีเมล</th>
              <th>เบอร์โทร</th>
              <th>สถานะ</th>
              <th>แก้ไข</th>
              <th>ระงับการใช้งาน</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="customer in customers" :key="customer.id">
              <td>{{ customer.id }}</td>
              <td>{{ customer.Cus_fullname }}</td>
              <td>{{ customer.Cus_email }}</td>
              <td>{{ customer.Cus_tel }}</td>
              <td>
                <span 
                  :class="customer.Cus_Status == 1 ? 'status-active' : 'status-inactive'"
                  class="status-dot">
                </span>
                {{ customer.Cus_Status == 1 ? 'Active' : 'Inactive' }}
              </td>
              <td>
                <button class="btn btn-warning btn-sm mx-2" @click="$router.push(`/EditCus/${customer.id}`)">
                  แก้ไข
                </button>
              </td>
              <td>
                <button class="btn btn-danger btn-sm mx-2" @click="suspendCustomer(customer.id)">
                  {{ customer.Cus_Status === 1 ? 'ระงับลูกค้า' : 'ยกเลิกการระงับ' }}
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
import { useAuthStore } from '@/stores/auth';
import axios from 'axios';

export default {
  data() {
    return {
      customers: [],
      successMessage: '',
      errorMessage: '',
    };
  },
  methods: {
    async fetchCustomers() {
      const authStore = useAuthStore();
      const token = authStore.token;

      try {
        const response = await axios.get('http://localhost:8000/api/customer', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.customers = response.data.data;
      } catch (error) {
        console.error('Error fetching customers:', error);
        this.errorMessage = 'ไม่สามารถดึงข้อมูลลูกค้าได้ กรุณาลองใหม่อีกครั้ง';
      }
    },

    async suspendCustomer(customerId) {
      try {
        const authStore = useAuthStore();
        const token = authStore.token;

        const response = await axios.put(`http://localhost:8000/api/suspendCus/${customerId}`, {}, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.successMessage = 'สำเร็จ!';
        this.errorMessage = '';

        console.log(`Customer ${customerId} suspended`, response.data);
        this.fetchCustomers();
      } catch (error) {
        this.errorMessage = 'เกิดข้อผิดพลาดในการระงับผู้ใช้!';
        this.successMessage = '';
        console.error('Error suspending customer:', error);
      }
    },
  },
  mounted() {
    this.fetchCustomers();
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
  border-radius: 50%;
  margin-right: 5px;
}

.status-active {
  background-color: green;
}

.status-inactive {
  background-color: red;
}
</style>
