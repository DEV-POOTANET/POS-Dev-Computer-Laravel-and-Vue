<template>
  <div class="container py-2">
    <div class="card shadow-lg">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">ข้อมูลพนักงาน</h3>
        <button class="btn btn-primary" @click="$router.push('/registerEmp')">
          เพิ่มพนักงาน
        </button>
      </div>

      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>รูป</th>
              <th>ชื่อ</th>
              <th>อีเมล</th>
              <th>ที่อยู่</th>
              <th>เบอร์โทร</th>
              <th>สถานะ</th>
              <th>บทบาท</th>
              <th>แก้ไข</th>
              <th>ระงับการใช้งาน</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.id }}</td>
              <td>
                <img 
                  :src="getImagePath(user.img)" 
                  alt="User Image" 
                  class="user-img" 
                  @error="handleImageError"
                >
              </td>
              <td>{{ user.fullname }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.address }}</td>
              <td>{{ user.Tel }}</td>
              <td>
                <span 
                  :class="user.status == 1 ? 'status-active' : 'status-inactive'"
                  class="status-dot">
                </span>
                {{ user.status == 1 ? 'Active' : 'Inactive' }}
              </td>
              <td>{{ user.role == 1 ? 'Admin' : 'User' }}</td>
              <td>                  
                <button class="btn btn-warning btn-sm mx-2" @click="$router.push(`/EditEmp/${user.id}`)">
                  แก้ไข
                </button>                
              </td>
              <td>
                <button class="btn btn-danger btn-sm mx-2" @click="suspendUser(user.id)">
                  {{ user.status === 1 ? 'ระงับบัญชี' : 'ยกเลิกการระงับ' }}
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
import defaultImage from '@/assets/images/user.png'; // นำเข้าภาพเบื้องต้น

export default {
  data() {
    return {
      users: [],
      successMessage: '',
      errorMessage: '',
    };
  },
  methods: {
    getImagePath(image) {
      // ระบุ path ของรูปภาพที่เก็บใน src/assets/images
      return new URL(`/src/assets/images/${image}`, import.meta.url).href;  
    },
    async fetchUsers() {
      const authStore = useAuthStore();
      const token = authStore.token;

      try {
        const response = await axios.get('http://localhost:8000/api/getAllUser', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.users = response.data.data;
      } catch (error) {
        console.error('Error fetching users:', error);
        alert('ไม่สามารถดึงข้อมูลพนักงานได้ กรุณาลองใหม่อีกครั้ง');
      }
    },
    handleImageError(event) {
      // เปลี่ยนไปยังภาพเบื้องต้นหากไม่พบภาพ
      event.target.src = defaultImage; 
    },

    async suspendUser(userId) {
      try {
        const authStore = useAuthStore();
        const token = authStore.token;

        const response = await axios.put(`http://localhost:8000/api/suspendUser/${userId}`, {}, {
          headers: {
            Authorization: `Bearer ${token}`, 
          },
        });
        this.successMessage = 'สำเร็จ!';
        this.errorMessage = ''; 
        
        console.log(`User ${userId} suspended`, response.data);
        this.fetchUsers();
      } catch (error) {
        this.errorMessage = 'เกิดข้อผิดพลาดในการระงับผู้ใช้!';
        this.successMessage = ''; 
        console.error('Error suspending user:', error);
      }
    },
   
  },
  mounted() {
    this.fetchUsers();
  },
};
</script>

<style scoped>
.table {
  width: 100%;
  margin-top: 20px;
}

.user-img {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
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
