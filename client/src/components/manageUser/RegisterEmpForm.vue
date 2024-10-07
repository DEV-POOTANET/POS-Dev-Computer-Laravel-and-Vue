<template>
  <div class="container py-4">
    <div class="card shadow-lg">
      <div class="card-header">
        <h3 class="card-title">ลงทะเบียนพนักงาน</h3>
      </div>

      <div class="card-body">
        <form @submit.prevent="registerEmployee">
          <div class="form-group mb-3">
            <label for="fullname">ชื่อ-นามสกุล</label>
            <input type="text" v-model="employee.fullname" class="form-control" id="fullname" required />
          </div>

          <div class="form-group mb-3">
            <label for="email">อีเมล</label>
            <input type="email" v-model="employee.email" class="form-control" id="email" required />
          </div>

          <div class="form-group mb-3">
            <label for="password">รหัสผ่าน</label>
            <input type="password" v-model="employee.password" class="form-control" id="password" required />
          </div>

          <div class="form-group mb-3">
            <label for="password_confirmation">ยืนยันรหัสผ่าน</label>
            <input type="password" v-model="employee.password_confirmation" class="form-control" id="password_confirmation" required />
            <small v-if="passwordMismatch" class="text-danger">รหัสผ่านไม่ตรงกัน</small>
          </div>

          <div class="form-group mb-3">
            <label for="address">ที่อยู่</label>
            <input type="text" v-model="employee.address" class="form-control" id="address" required />
          </div>

          <div class="form-group mb-3">
            <label for="tel">เบอร์โทร</label>
            <input type="text" v-model="employee.tel" class="form-control" id="tel" required />
          </div>

          <div class="form-group mb-3">
            <label for="role">บทบาท</label>
            <select v-model="employee.role" class="form-control" id="role" required>
              <option value="1">Admin</option>
              <option value="2">User</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">ลงทะเบียน</button>
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
import axios from 'axios'

export default {
  data() {
    return {
      employee: {
        fullname: '',
        email: '',
        password: '',
        password_confirmation: '',
        address: '',
        tel: '',
        role: 2, 
      },
      successMessage: '', 
      errorMessage: '',
      passwordMismatch: false, // ตัวแปรสำหรับเช็คว่ารหัสผ่านตรงกันไหม
    }
  },
  watch: {
    'employee.password_confirmation': function (newVal) {
      this.passwordMismatch = newVal !== this.employee.password;
    }
  },
  methods: {
    async registerEmployee() {
      if (this.passwordMismatch) {
        this.errorMessage = 'กรุณาตรวจสอบรหัสผ่านให้ตรงกัน';
        return;
      }
      try {
        const response = await axios.post('http://localhost:8000/api/register', this.employee)
        this.successMessage = 'ลงทะเบียนสำเร็จ!'
        setTimeout(() => {
          this.clearForm();
        }, 5000);        
      } catch (error) {
        console.error('Error registering employee:', error)
        this.errorMessage = error.response.data.message || 'เกิดข้อผิดพลาดในการลงทะเบียน'
      }
    },
    clearForm() {
      this.employee = {
        fullname: '',
        email: '',
        password: '',
        password_confirmation: '',
        address: '',
        tel: '',
        role: 2,
      }
      this.errorMessage = ''
      this.successMessage = ''
      this.passwordMismatch = false; // รีเซ็ตการเช็ครหัสผ่าน
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

.text-danger {
  font-size: 0.9rem;
}
</style>
