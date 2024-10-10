<template>
  <div class="container py-4">
    <div class="card shadow-lg">
      <div class="card-header">
        <h3 class="card-title">แก้ไขสินค้า</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="updateProduct">
          <div class="form-group mb-3">
            <label for="Prd_Name">ชื่อสินค้า</label>
            <input type="text" v-model="product.Prd_Name" class="form-control" id="Prd_Name" required />
          </div>

          <div class="form-group mb-3">
            <label for="Prd_details">รายละเอียดสินค้า</label>
            <textarea v-model="product.Prd_details" class="form-control" id="Prd_details" required></textarea>
          </div>

          <div class="form-group mb-3">
            <label for="Cat_id">หมวดหมู่</label>
            <select v-model="product.Cat_id" class="form-control" id="Cat_id" required>
              <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.Cat_Name }}</option>
            </select>
          </div>

          <div class="form-group mb-3">
            <label for="Prd_Price">ราคาสินค้า</label>
            <input type="number" v-model="product.Prd_Price" class="form-control" id="Prd_Price" required />
          </div>

          <div class="form-group mb-3">
            <label for="Prd_CostPrice">ราคาทุน</label>
            <input type="number" v-model="product.Prd_CostPrice" class="form-control" id="Prd_CostPrice" required />
          </div>

          <div class="form-group mb-3">
            <label for="Prd_Img">ลิงค์รูปภาพ</label>
            <input type="text" v-model="product.Prd_Img" class="form-control" id="Prd_Img" />
          </div>

          <div class="form-group mb-3">
            <label for="Prd_Status">สถานะสินค้า</label>
            <select v-model="product.Prd_Status" class="form-control" id="Prd_Status" required>
              <option value="1">พร้อมขาย</option>
              <option value="0">ไม่พร้อมขาย</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">บันทึกการแก้ไขสินค้า</button>
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
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import { useRoute } from 'vue-router';

export default {
  data() {
    return {
      product: {
        Prd_Name: '',
        Prd_details: '',
        Cat_id: '',
        Prd_Price: '',
        Prd_CostPrice: '',
        Prd_Img: '',
        Prd_Status: '',
      },
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
        const response = await axios.get('http://localhost:8000/api/cat', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.categories = response.data.data;
      } catch (error) {
        console.error('Error fetching categories:', error);
        this.errorMessage = 'เกิดข้อผิดพลาดในการดึงข้อมูลหมวดหมู่';
      }
    },
    async fetchProductDetails(id) {
      const authStore = useAuthStore();
      const token = authStore.token;
      try {
        const response = await axios.get(`http://localhost:8000/api/Product/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.product = response.data.data; 
     
      } catch (error) {
        console.error('Error fetching product details:', error);
        this.errorMessage = 'เกิดข้อผิดพลาดในการดึงข้อมูลสินค้า';
      }
    },
    async updateProduct() {
      const authStore = useAuthStore();
      const token = authStore.token;
      try {
        const response = await axios.put(`http://localhost:8000/api/Product/${this.product.id}`, this.product, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.successMessage = 'บันทึกการแก้ไขสินค้าสำเร็จ!';
        setTimeout(() => {
          this.$router.push('/manageProduct')
          }, 3000);   
      } catch (error) {
        console.error('Error updating product:', error);
        this.errorMessage = error.response.data.message || 'เกิดข้อผิดพลาดในการแก้ไขสินค้า';
      }
    },
  },
  mounted() {
    const route = useRoute();
    const productId = route.params.id; // รับ id ของสินค้าจากพารามิเตอร์ของ route
    this.fetchCategories(); // เรียกข้อมูลหมวดหมู่สินค้าตอนโหลดหน้า
    this.fetchProductDetails(productId); // โหลดข้อมูลสินค้าที่จะแก้ไข
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
