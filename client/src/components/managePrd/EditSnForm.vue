<template>
  <div class="container py-4">
    <div class="card shadow-lg">
      <div class="card-header">
        <h3 class="card-title">แก้ไขหมายเลขประจำสินค้า (Serial Number)</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="updateSerialNumber">
          <div class="form-group mb-3">
            <label for="Serial_SerialNumber">หมายเลขประจำสินค้า</label>
            <input
              type="text"
              v-model="serial.Serial_SerialNumber"
              class="form-control"
              id="Serial_SerialNumber"
              required
            />
          </div>

          <div class="form-group mb-3">
            <label for="Serial_Status">สถานะ</label>
            <select v-model="serial.Serial_Status" class="form-control" id="Serial_Status" required>
              <option value="1">อยู่ในคลัง</option>
              <option value="0">ขายแล้ว</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        </form>
      </div>
    </div>

    <!-- Toast สำหรับแสดง error หรือ success -->
    <CToaster class="p-3" placement="top-end">
      <CToast v-if="successMessage" :visible="true" autohide :delay="5000" class="bg-success text-white">
        <CToastHeader closeButton class="bg-success text-white">
          <span class="me-auto fw-bold">สำเร็จ</span>
        </CToastHeader>
        <CToastBody>{{ successMessage }}</CToastBody>
      </CToast>

      <CToast v-if="errorMessage" :visible="true" autohide :delay="5000" class="bg-danger text-white">
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
import { useRoute } from 'vue-router';

export default {
  data() {
    return {
      serial: {
        Serial_SerialNumber: '',
        Serial_Status: '',
      },
      successMessage: '',
      errorMessage: '',
    };
  },
  methods: {
    async fetchSerialDetails() {
      const authStore = useAuthStore();
      const token = authStore.token;
      const route = useRoute();
      const serialId = this.$route.params.id;
      try {
        const response = await axios.get(`http://localhost:8000/api/serials/${serialId}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.serial = response.data.data; // โหลดข้อมูล Serial ที่จะถูกแก้ไข
      } catch (error) {
        console.error('Error fetching serial details:', error);
        this.errorMessage = 'เกิดข้อผิดพลาดในการดึงข้อมูลหมายเลขประจำสินค้า';
      }
    },
    async updateSerialNumber() {
      const authStore = useAuthStore();
      const token = authStore.token;
      const route = useRoute();
      const serialId = this.$route.params.id;

      try {
        const response = await axios.put(`http://localhost:8000/api/serials/${serialId}`, this.serial, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.successMessage = 'บันทึกการแก้ไขหมายเลขประจำสินค้าเรียบร้อย!';
        setTimeout(() => {
          this.$router.push('/manageProduct'); 
        }, 3000);
      } catch (error) {
        console.error('Error updating serial number:', error);
        this.errorMessage = error.response.data.message || 'เกิดข้อผิดพลาดในการแก้ไขหมายเลขประจำสินค้า';
      }
    },
  },
  mounted() {
    this.fetchSerialDetails(); 
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

.text-danger {
  font-size: 0.9rem;
}
</style>
