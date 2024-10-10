<template>
    <div class="container py-2">
        <div class="card shadow-lg">
            <div class="card-header">
                <h3 class="card-title">จัดการสินค้า</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="card shadow-lg">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">ข้อมูลสินค้า</h3>
                                <button class="btn btn-primary" @click="$router.push('/AddPrd')">
                                    เพิ่มสินค้า
                                </button>
                            </div>

                            <div class="card-body">
                                <div class="card-body table-responsive" v-if="products.length > 0">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>รูป</th>
                                                <th>ชื่อสินค้า</th>
                                                <th>ราคา</th> 
                                                <th>แก้ไข</th>
                                                <th>เพิ่มSN</th>
                                                <th>ดู</th>
                                                

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="product in products" :key="product.id">
                                                <td>
                                                    <img :src="product.Prd_Img" alt="Product Image" class="img-thumbnail" style="max-width: 50px;">
                                                </td>
                                                <td>{{ product.Prd_Name }}</td>
                                                <td>{{ currency(product.Prd_Price) }}</td>                                                
                                                <td>
                                                  <button class="btn btn-warning btn-sm" @click="$router.push(`/EditPrd/${product.id}`)">
                                                    แก้ไข
                                                  </button>

                                                </td>
                                                <td>
                                                  <button class="btn btn-success btn-sm" @click="$router.push(`/AddSn/${product.id}`)">
                                                    เพิ่มSN
                                                  </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" @click="showDetails(product)">
                                                        ดู
                                                    </button>
                                                </td>                                              
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-else>
                                    <p>ไม่มีสินค้าที่จะแสดง</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="row">
                            <div class="container-fluid">
                                <div class="card shadow-lg">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">รายละเอียดสินค้า</h3>
                                    </div>

                                    <div class="card-body">
                                        <div v-if="selectedProduct">
                                            <h5>{{ selectedProduct.Prd_Name }}</h5>
                                            <p><strong>สถานะสินค้า:</strong> {{ getProductStatus(selectedProduct.Prd_Status) }}</p>
                                            <p><strong>ราคา:</strong> {{ currency(selectedProduct.Prd_Price) }}</p>
                                            <p><strong>ราคาทุน:</strong> {{ currency(selectedProduct.Prd_CostPrice) }}</p>
                                            <p><strong>หมวดหมู่ </strong> {{ getCategoryName(selectedProduct.Cat_id)}}</p>
                                            <p><strong>รายละเอียด:</strong> {{ selectedProduct.Prd_details }}</p>                                           
                                            <img :src="selectedProduct.Prd_Img" alt="Product Image" class="img-fluid" style="max-width: 200px;">

                                            <div class="container-fluid">
                                              <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h4 class="card-title">จำนวนสินค้า</h4>
                                                </div>

                                                <div class="card-body">
                                                  
                                                    <p><strong>จำนวนสินค้าทั้งหมด : </strong>{{ countActive+countInActive }} รายการ</p>
                                                    <p><strong>จำนวนสินค้าที่ขายแล้ว : </strong>{{countInActive  }} รายการ</p>
                                                    <p><strong>จำนวนสินค้าในคลัง : </strong>{{countActive  }} รายการ</p>

                                             
                                                </div> 
                                              </div>
                                            </div>
   

                                        </div>
                                        <div v-else>
                                            <p>กรุณาเลือกสินค้าจากรายการด้านซ้ายเพื่อดูรายละเอียด</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                          <div class="container-fluid">
                            <div class="card shadow-lg">
                              <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">รายการสินค้าที่มี</h3>
                              </div>

                              <div class="card-body table-responsive">
                                <div v-if="serialNumbers.length > 0">
                                  <table class="table table-hover">
                                    <thead>
                                      <tr>
                                        <th>Serial Number</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr v-for="serial in serialNumbers" :key="serial.id">
                                        <td>{{ serial.Serial_SerialNumber }}</td>
                                        <td>
                                          <!-- วงกลมสีตามสถานะ พร้อมแสดงข้อความ -->
                                          <span 
                                            :class="serial.Serial_Status === 1 ? 'status-circle green' : 'status-circle red'"
                                            class="me-2"></span>
                                          {{ serial.Serial_Status === 1 ? 'อยู่ในคลัง' : 'ขายแล้ว' }}
                                        </td>
                                        <td>
                                          <button class="btn btn-warning btn-sm" @click="$router.push(`/EditSn/${serial.id}`)">
                                                    แก้ไข
                                          </button>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <div v-else>
                                  <p>ไม่มี serial numbers ที่จะแสดง</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                    </div>
                </div>
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
      products: [], // เก็บข้อมูลสินค้าที่ดึงมา
      selectedProduct: null, // เก็บข้อมูลสินค้าที่เลือกเพื่อแสดงรายละเอียด
      categories: [], // เก็บข้อมูลหมวดหมู่ที่ดึงมา
      errorMessage: '', // เก็บข้อความข้อผิดพลาด
      serialNumbers: [],
      countActive:'',
      countInActive:'',
    };
  },
  methods: {
    async fetchProducts() {
      const authStore = useAuthStore();
      const token = authStore.token;
      try {
        const response = await axios.get('http://localhost:8000/api/Product', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.products = response.data.data; 
        console.log(this.products)
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    },
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
    async fetchSerialNumbers(productId) {
      const authStore = useAuthStore();
      const token = authStore.token;
      try {
        const response = await axios.get(`http://localhost:8000/api/products/${productId}/serials`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.serialNumbers = response.data.data; 
        this.countActive = response.data.count_active; 
        this.countInActive = response.data.count_inactive; 


        // console.log(this.serialNumbers)
      } catch (error) {
        console.error('Error fetching serial numbers:', error);
      }
    },
    showDetails(product) {
      this.selectedProduct = product; // กำหนดสินค้าเป็นสินค้าเลือกเพื่อแสดงรายละเอียด
      this.fetchSerialNumbers(product.id); 
    },
    currency(value) {
      return new Intl.NumberFormat('th-TH', {
          style: 'currency',
          currency: 'THB',
      }).format(value);
    },
    getCategoryName(catId) {
      const category = this.categories.find(cat => cat.id === catId);
      return category ? category.Cat_Name : 'ไม่มีหมวดหมู่'; // หากไม่มีหมวดหมู่ให้แสดงข้อความ
    },
    getProductStatus(Prd_Status) {
      return Prd_Status === 1 ? 'พร้อมขาย' : 'ไม่พร้อมขาย';
    } ,
  },
  mounted() {
    this.fetchProducts(); // ดึงข้อมูลสินค้าเมื่อโหลดหน้า
    this.fetchCategories(); // ดึงข้อมูลหมวดหมู่เมื่อโหลดหน้า
  },
};
</script>

<style scoped>
.table {
  width: 100%;
  margin-bottom: 1rem;
  background-color: #fff;
}
.table-bordered {
  border: 1px solid #dee2e6;
}
.table-hover tbody tr:hover {
  background-color: #f5f5f5;
}
.img-thumbnail {
  max-width: 50px; /* กำหนดขนาดภาพให้ไม่ใหญ่เกินไป */
}
.img-fluid {
  max-width: 100%; /* กำหนดขนาดภาพให้ปรับขนาดได้ */
  height: auto; /* ให้ภาพคงสัดส่วน */
}

.status-circle {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.green {
  background-color: green;
}

.red {
  background-color: red;
}

.me-2 {
  margin-right: 8px;
}

</style>
