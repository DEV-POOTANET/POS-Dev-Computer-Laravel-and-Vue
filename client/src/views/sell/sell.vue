<script setup>
import { ref, computed } from 'vue'

// อิมพอร์ตรูปภาพ
import vueImage from '../../assets/images/vue.jpg'
import reactImage from '../../assets/images/react.jpg'
import angularImage from '../../assets/images/angular.jpg'

// ตัวอย่างข้อมูลหมวดหมู่สินค้า
const categories = ref(['All', 'Cakes', 'Cookies', 'Pastries'])

// ตัวอย่างข้อมูลสินค้า
const allProducts = [
  { title: 'TIRAMISU CAKE', category: 'Cakes', price: 190, imgSrc: vueImage },
  { title: 'CHOCOLATE CHIP COOKIE', category: 'Cookies', price: 50, imgSrc: reactImage },
  { title: 'BLUEBERRY MUFFIN', category: 'Pastries', price: 80, imgSrc: angularImage },
  { title: 'STRAWBERRY CAKE', category: 'Cakes', price: 200, imgSrc: vueImage },
  { title: 'OATMEAL COOKIE', category: 'Cookies', price: 45, imgSrc: reactImage },
  { title: 'CROISSANT', category: 'Pastries', price: 60, imgSrc: angularImage },
]

// ตัวแปรสำหรับหมวดหมู่ที่เลือก
const selectedCategory = ref('All')

// ตะกร้าสินค้า
const cart = ref([])

// ฟิลเตอร์สินค้าตามหมวดหมู่
const filteredProducts = computed(() => {
  if (selectedCategory.value === 'All') {
    return allProducts
  }
  return allProducts.filter((product) => product.category === selectedCategory.value)
})

// ฟังก์ชันรวมยอดเงินทั้งหมด
const totalPrice = computed(() => {
  return cart.value.reduce((total, item) => total + item.price, 0)
})

// ฟังก์ชันเคลียร์ตะกร้า
const clearCart = () => {
  cart.value = []
}
// เพิ่มสินค้าไปยังตะกร้า
const addToCart = (product) => {
  cart.value.push(product)
}

</script>

<template>
  <div class="container py-2">
    <!-- Card ใหญ่ครอบทั้งส่วนสินค้าและตะกร้า -->
    <div class="card shadow-lg">
      <div class="card-header">
        <h3 class="card-title">ขายสินค้า</h3>        
      </div>
      <div class="card-body">
        
        <!-- Card สำหรับเลือกหมวดหมู่ -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title">หมวดหมู่สินค้า</h5>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-start gap-3">
              <button
                v-for="category in categories"
                :key="category"
                class="btn btn-outline-primary"
                @click="selectedCategory = category"
                :class="{ active: selectedCategory === category }"
              >
                {{ category }}
              </button>
            </div>
          </div>
        </div>
 
        <div class="row">
          <!-- สินค้า (ซ้าย) -->

          <div class="col-md-8">
            <input
            v-model="searchQuery"
            type="text"
            class="form-control w-50"
            placeholder="ค้นหาสินค้า..."
            />
            <br>
            <div class="row row-cols-1 row-cols-md-3 g-4">
              <div v-for="(product, index) in filteredProducts" :key="index" class="col">
                <div class="card h-100">
                  <img :src="product.imgSrc" class="card-img-top" alt="Product Image" />
                  <div class="card-body">
                    <h5 class="card-title">{{ product.title }}</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet.</p>
                  </div>
                  <div class="mb-5 d-flex justify-content-around">
                    <h3>{{ product.price }}$</h3>
                    <button class="btn btn-primary" @click="addToCart(product)">Buy Now</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ตะกร้าสินค้า (ขวา) -->
          <!-- <div class="col-md-4">
            <h3>ตะกร้าสินค้า</h3>          
            <ul class="list-group">
              <li v-for="(item, index) in cart" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
                {{ item.title }}
                <span class="badge bg-primary rounded-pill">{{ item.price }}$</span>
              </li>
            </ul>
            <div v-if="cart.length === 0" class="mt-3">ยังไม่มีสินค้าในตะกร้า</div>
          </div> -->

          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-header">
               
                <h4 class="card-title"> <CIcon icon="cil-basket" height="36" />   ตะกร้าสินค้า</h4>
              </div>
              <div class="card-body">
                <!-- รายการสินค้าในตะกร้า -->
                <ul class="list-group">
                  <li
                    v-for="(item, index) in cart"
                    :key="index"
                    class="list-group-item d-flex justify-content-between align-items-center"
                  >
                    {{ item.title }}
                    <span class="badge bg-primary rounded-pill">{{ item.price }}$</span>
                  </li>
                </ul>

                <!-- แสดงข้อความหากยังไม่มีสินค้าในตะกร้า -->
                <div v-if="cart.length === 0" class="mt-3">ยังไม่มีสินค้าในตะกร้า</div>

                <!-- รวมยอดเงินทั้งหมด -->
                <div v-else class="mt-3">
                  <h4>รวมยอด: {{ totalPrice }}฿</h4>

                  <!-- ปุ่มเคลียร์ตะกร้า -->
                  <button class="btn btn-danger mt-2" @click="clearCart">เคลียร์ตะกร้า</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.active {
  background-color: #0d6efd;
  color: white;
}
</style>



<!-- <div class="row">          
  <div class="col-md-6">
    
  </div>
  <div class="col-md-6">
  </div>
</div> -->