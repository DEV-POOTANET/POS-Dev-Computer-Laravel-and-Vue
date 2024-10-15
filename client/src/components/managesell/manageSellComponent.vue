<template>
<div class="container py-2">
<div class="card shadow-lg">
<div class="card-header">
    <h3 class="card-title">ขายสินค้า</h3>
</div>
<div class="card-body">
    <div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title">หมวดหมู่สินค้า</h5>
    </div>
    <div class="card-body">
        <div v-if="loadingCategories">กำลังโหลดหมวดหมู่...</div>
        <div class="d-flex justify-content-start gap-3" v-else>
        <button
            v-for="category in categories"
            :key="category.id"
            class="btn btn-outline-primary"
            @click="selectedCategory = category.Cat_Name"
            :class="{ active: selectedCategory === category.Cat_Name }"
        >
            {{ category.Cat_Name }}
        </button>
        </div>
    </div>
    </div>

    <div class="row">
    <div class="col-md-8">
        <input
        v-model="searchQuery"
        type="text"
        class="form-control w-50 mb-4"
        placeholder="ค้นหาสินค้า..."
        />

        <div v-if="loadingProducts">กำลังโหลดสินค้า...</div>
        <div v-else class="row row-cols-1 row-cols-md-4 g-4">
        <div v-for="(product, index) in filteredProducts" :key="product.id" class="col">
            <div class="card">
            <img :src="product.Prd_Img" class="card-img-top" alt="Product Image" />
            <div class="card-body">
                <h5 class="card-title">{{ product.Prd_Name.length > 30 ? product.Prd_Name.slice(0, 30) + '...' : product.Prd_Name }}</h5>
                <div class="mb-5">
                <h4 class="text">{{ product.Prd_Price }}฿</h4>
                <button class="btn btn-primary w-100" @click="openModal(product)">Add</button>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">ตะกร้าสินค้า</h4>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <button class="btn btn-primary btn-sm me-2">เพิ่มลูกค้า</button>
                <strong>ลูกค้า :</strong> Pootanet Rampuey
            </div>
            <ul class="list-group mt-2">
                <li
                    v-for="(item, index) in cart"
                    :key="index"
                    class="list-group-item"
                >
                    <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1">
                        <strong>สินค้า: </strong>{{ item.Prd_Name }}
                        </p>
                        <p class="mb-1">
                        <strong>SN: </strong>{{ item.serial_number }}
                        </p>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        {{ item.Prd_Price }}฿
                    </span>
                    </div>
                </li>
            </ul>

            <div v-if="cart.length === 0" class="mt-3">ยังไม่มีสินค้าในตะกร้า</div>

            <div v-else class="mt-3">
            
            <h4>รวมยอด: {{ formattedTotalPrice }}</h4>
            <div class="mt-3">
                    <label for="payment-method" class="form-label">เลือกวิธีการชำระเงิน:</label>
                    <select id="payment-method" class="form-select" v-model="paymentMethod" @change="handlePaymentChange">
                        <option value="0">จ่ายเงินสด</option>
                        <option value="1">เงินโอน</option>
                    </select>
            </div>
            <div v-if="paymentMethod == '0'" class="mt-3">
                <label for="amount-received" class="form-label">รับเงินมา:</label>
                <input type="number" id="amount-received" v-model="amountReceived" class="form-control" placeholder="กรุณากรอกจำนวนเงิน" @input="calculateChange" />
                
                <label for="change" class="form-label mt-3">เงินทอน:</label>
                <input type="text" id="change" class="form-control" :value="changeAmount" readonly />
            </div>
            <div class="mt-3">
                <button class="btn btn-primary" @click="processPayment">ยืนยันการชำระเงิน</button>
                <button class="btn btn-danger ms-2" @click="clearCart">เคลียร์ตะกร้า</button>
            </div>
                
            </div>
        </div>
        </div>
    </div>

    </div>
</div>
</div>

<CModal
alignment="center"
:visible="visibleViewModal"
@close="closeModal"
aria-labelledby="VerticallyCenteredExample"
size="lg"
>
<CModalHeader>
    <CModalTitle id="VerticallyCenteredExample">เลือกสินค้า</CModalTitle>
</CModalHeader>
<CModalBody>
    <div v-if="loadingSerials">กำลังโหลดหมายเลขประจำตัว...</div>
    <ul v-else class="list-group">
    <li
        v-for="serial in filteredSerials"
        :key="serial.id"
        class="list-group-item"
    >
        {{ serial.Serial_SerialNumber }}
        <button class="btn btn-success btn-sm float-end" @click="addSerialToCart(serial)">เลือก</button>
    </li>
    </ul>
    <div v-if="filteredSerials.length === 0" class="mt-3 text-center">
    ไม่มีหมายเลขประจำตัวที่พร้อมใช้งาน
    </div>
</CModalBody>
<CModalFooter>
    <CButton color="secondary" @click="closeModal">Close</CButton>
</CModalFooter>
</CModal>

<CToaster class="p-3" placement="top-end">
<CToast 
    v-if="successMessage" 
    visible 
    autohide 
    :delay="2000"
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
    :delay="2000"  
    class="bg-danger text-white">
    <CToastHeader closeButton class="bg-danger text-white">
    <span class="me-auto fw-bold">ข้อผิดพลาด</span>
    </CToastHeader>
    <CToastBody>{{ errorMessage }}</CToastBody>
</CToast>
</CToaster>


</div>
</template>

<script setup>


import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import { CModal, CModalHeader, CModalTitle, CModalBody, CModalFooter, CButton } from '@coreui/vue';

const authStore = useAuthStore();
const token = authStore.token;

const selectedCategory = ref('All');
const cart = ref([]);
const searchQuery = ref('');
const categories = ref([]);
const allProducts = ref([]);
const loadingCategories = ref(false);
const loadingProducts = ref(false);
const serials = ref([]);
const loadingSerials = ref(false);
const selectedProduct = ref(null);
const visibleViewModal = ref(false);
const successMessage = ref(''); 
const errorMessage = ref(''); 

const paymentMethod = ref('0');
const amountReceived = ref(0);
const changeAmount = ref();

const calculateChange = () => {
  const total = totalPrice.value;
  changeAmount.value = amountReceived.value - total;
};


const handlePaymentChange = () => {
  if (paymentMethod.value === '0') {
    amountReceived.value = 0; 
    changeAmount.value = ''; 
  }
};

// ฟังก์ชันเปิด modal และดึงหมายเลขประจำตัว
const openModal = async (product) => {
    selectedProduct.value = product; // เก็บข้อมูลสินค้าที่เลือก
    await fetchSerials(product.id); // ดึงหมายเลขประจำตัวสำหรับสินค้านี้
    visibleViewModal.value = true; // แสดง modal
};

// ฟังก์ชันปิด modal
const closeModal = () => {
    visibleViewModal.value = false; // ซ่อน modal
};

const showMessage = (type, message) => {
if (type === 'success') {
    successMessage.value = message;
} else if (type === 'error') {
    errorMessage.value = message;
}

setTimeout(() => {
    successMessage.value = '';
    errorMessage.value = '';
}, 2000);
};

const filteredSerials = computed(() => 
    serials.value.filter(serial => serial.Serial_Status === 1)
);
// ฟังก์ชันดึงหมายเลขประจำตัวจาก API
const fetchSerials = async (productId) => {
    loadingSerials.value = true;
    try {
    const response = await axios.get(`http://localhost:8000/api/products/${productId}/serials`, {
    headers: {
        Authorization: `Bearer ${token}`,
    },
    });
    serials.value = response.data.data; // เก็บหมายเลขประจำตัว
    } catch (error) {
    console.error('Error fetching serials:', error);
    } finally {
    loadingSerials.value = false;
    }
};

// ฟังก์ชันเพิ่มหมายเลขประจำตัวลงในตะกร้า
const addSerialToCart = (serial) => {
    const exists = cart.value.some(
    (item) => item.serial_number === serial.Serial_SerialNumber
    );

    if (exists) {
    showMessage('error', 'หมายเลขนี้ถูกเพิ่มในตะกร้าแล้ว!');
    return; // หยุดการทำงานหากมี Serial นี้อยู่แล้ว
    }

    const item = {
    ...selectedProduct.value,
    serial_number: serial.Serial_SerialNumber,
    serial_id: serial.id,
    };

    cart.value.push(item); // เพิ่มสินค้าลงในตะกร้า
    showMessage('success', 'เพิ่มสินค้าในตะกร้าสำเร็จ!');
    console.log(cart)
    closeModal(); // ปิด modal หลังจากเพิ่มสินค้า
};


// ฟังก์ชันเคลียร์ตะกร้า
const clearCart = () => {
    cart.value = [];
};

// ฟังก์ชันดึงหมวดหมู่สินค้า
const fetchCategories = async () => {
    loadingCategories.value = true;
    try {
    const response = await axios.get('http://localhost:8000/api/cat', {
    headers: {
        Authorization: `Bearer ${token}`,
    },
    });
    categories.value = [{ id: 0, Cat_Name: 'All' }, ...response.data.data];
    } catch (error) {
    console.error('Error fetching categories:', error);
    } finally {
    loadingCategories.value = false;
    }
};

// ฟังก์ชันดึงสินค้าทั้งหมด
const fetchProducts = async () => {
    loadingProducts.value = true;
    try {
    const response = await axios.get('http://localhost:8000/api/Product', {
    headers: {
        Authorization: `Bearer ${token}`,
    },
    });
    allProducts.value = response.data.data;
    } catch (error) {
    console.error('Error fetching products:', error);
    } finally {
    loadingProducts.value = false;
    }
};

// ฟังก์ชันสำหรับฟิลเตอร์สินค้า
const filteredProducts = computed(() => {
    let products = selectedCategory.value === 'All'
    ? allProducts.value
    : allProducts.value.filter(product => product.Cat_id === categories.value.find(cat => cat.Cat_Name === selectedCategory.value)?.id);

    if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    products = products.filter(product => product.Prd_Name.toLowerCase().includes(query));
    }

    return products;
});

// ฟังก์ชันคำนวณยอดรวม
const totalPrice = computed(() =>
    cart.value.reduce((total, item) => total + parseFloat(item.Prd_Price), 0)
);

const formattedTotalPrice = computed(() => {
    const price = totalPrice.value;
    return `${price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}฿`;
});

onMounted(() => {
    fetchCategories();
    fetchProducts();
});


</script>

<style scoped>
.active {
background-color: #0d6efd;
color: white;
}
</style>
