<template>
  <div class="container py-4">
    <div class="card shadow-lg">
      <div class="card-header">
        <h3 class="card-title">เพิ่มหมายเลข Serial</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="addSerialToList">
          <div class="form-group mb-3">
            <label for="Serial_SerialNumber">หมายเลข Serial</label>
            <input type="text" v-model="newSerial.Serial_SerialNumber" class="form-control" id="Serial_SerialNumber" required />
          </div>

          <div class="form-group mb-3">
            <label for="Serial_Status">สถานะ</label>
            <select v-model="newSerial.Serial_Status" class="form-control" id="Serial_Status" required>
              <option value="1">พร้อมใช้งาน</option>
              <option value="0">ไม่พร้อมใช้งาน</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">เพิ่มหมายเลข Serial</button>
        </form>

        <h4 class="mt-4">รายการหมายเลข Serial ที่เพิ่มแล้ว</h4>
        <table class="table table-bordered mt-3">
          <thead>
            <tr>
              <th>หมายเลข Serial</th>
              <th>สถานะ</th>
              <th>จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(serial, index) in serialList" :key="index">
              <td>{{ serial.Serial_SerialNumber }}</td>
              <td>{{ serial.Serial_Status === 1 ? 'พร้อมใช้งาน' : 'ไม่พร้อมใช้งาน' }}</td>
              <td>
                <button class="btn btn-danger btn-sm" @click="removeSerial(index)">ลบ</button>
              </td>
            </tr>
          </tbody>
        </table>

        <button class="btn btn-success mt-3" @click="saveSerials">บันทึกหมายเลข Serial</button>
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
import { useRoute } from 'vue-router'; 
import { useAuthStore } from '@/stores/auth';

export default {
  data() {
    return {
      newSerial: {
        Serial_SerialNumber: '',
        Serial_Status: 1,
        Prd_id: '',
      },
      serialList: [], // รายการหมายเลข Serial ที่เพิ่มเข้าไป
      successMessage: '',
      errorMessage: '',
    };
  },
  methods: {
    addSerialToList() {
      // เพิ่ม serial ใหม่เข้าไปในรายการ
      const serialToAdd = { ...this.newSerial };
      const route = useRoute();
      serialToAdd.Prd_id = this.$route.params.id;
      this.serialList.push(serialToAdd);
      this.clearNewSerial();
    },
    removeSerial(index) {
      // ลบ serial ออกจากรายการ
      this.serialList.splice(index, 1);
    },
    async saveSerials() {
      const authStore = useAuthStore();
      const token = authStore.token;

      try {
        const response = await axios.post('http://localhost:8000/api/serials', { serials: this.serialList }, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.successMessage = 'บันทึกหมายเลข Serial สำเร็จ!';
        setTimeout(() => {
          this.clearAll();
        }, 3000);
       
      } catch (error) {
        console.error('Error saving serials:', error);
        this.errorMessage = error.response.data.message || 'เกิดข้อผิดพลาดในการบันทึกหมายเลข Serial';
      }
    },
    clearNewSerial() {
      this.newSerial = {
        Serial_SerialNumber: '',
        Serial_Status: 1,
        Prd_id: '',
      };
    },
    clearAll() {
      this.serialList = [];
      this.clearNewSerial();
      this.successMessage = '';
      this.errorMessage = '';
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

.text-danger {
  font-size: 0.9rem;
}
</style>
