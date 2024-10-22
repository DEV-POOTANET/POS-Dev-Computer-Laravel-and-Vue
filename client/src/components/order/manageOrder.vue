<template>
<div class="container py-2">
    <div class="card shadow-lg">
    <div class="card-header">
        <h3 class="card-title">รายการขาย</h3>
    </div>

    <div class="card-body table-responsive">
        <div class="card shadow-lg mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">ตัวเลือก</h4>
         
        </div>

        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex justify-content-start gap-3">
                    <button
                        class="btn btn-outline-primary"
                        :class="{ active: activeFilter === 'all' }"
                        @click="filterSales('all')">ทั้งหมด
                    </button>
                    <button
                        class="btn btn-outline-primary"
                        :class="{ active: activeFilter === 'today' }"
                        @click="filterSales('today')">วันนี้
                    </button>
                    <button
                        class="btn btn-outline-primary"
                        :class="{ active: activeFilter === 'month' }"
                        @click="filterSales('month')">เดือนนี้
                    </button>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <input
                            v-model="searchQuery"
                            type="text"
                            class="form-control w-50"
                            placeholder="ค้นหาลูกค้า..."
                            />
                        </div>                        
                    </div>
                </div>

                <div class="col-md-4">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th>เงินสด</th>
                            <th>เงินโอน</th>
                            <th>รวมยอด</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ cashTotal }} บาท</td>   
                            <td>{{ transferTotal }} บาท</td>   
                            <td>{{ grandTotal }} บาท</td>                                                             
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        </div>

        <!-- ตารางแสดงรายการขาย -->
        <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
            <th>ชื่อลูกค้า</th>
            <th>พนักงานขาย</th>
            <th>วันที่</th>
            <th>เวลา</th>
            <th>ยอดรวม (บาท)</th>
            <th>สถานะ</th>
            <th>การชำระเงิน</th>
            <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(sale, index) in filteredSales" :key="sale.id">
            <!-- <td>{{ index + 1 }}</td> -->

            <td>{{ sale.customers.Cus_fullname }}</td>
            <td>{{ sale.users.fullname }}</td>
            <td>{{ sale.Ord_Date }}</td>
            <td>{{ sale.Ord_Time }}</td>
            <td>{{ sale.Ord_TotalAmount }}</td>
            <td>
                <span 
                    class="status-dot" 
                    :class="{
                    'bg-success': sale.Ord_Status === 1, 
                    'bg-danger': sale.Ord_Status === 0 
                    }">
                </span>
                {{ getStatusText(sale.Ord_Status) }}
            </td>

            <td>
                <span 
                    class="badge" 
                    :class="{
                    'bg-success': sale.Ord_Payment === 0, 
                    'bg-info': sale.Ord_Payment === 1
                    }">
                    {{ getPaymentText(sale.Ord_Payment) }}
                </span>
            </td>


            <td>
                <button class="btn btn-warning btn-sm mx-2" @click="openModal(sale)">
                  ดูรายละเอียด
                </button>
            </td>
            </tr>
        </tbody>
        </table>
    </div>
    </div>
</div>

<CModal
  alignment="center"
  :visible="visibleViewModal"
  @close="closeModal"
  size="lg"
>
  <CModalHeader>
    <CModalTitle>รายการ Serial และสินค้า</CModalTitle>
  </CModalHeader>
  <CModalBody>
    <div v-if="filteredSerials.length === 0" class="text-center">
      ไม่มีข้อมูล
    </div>
    <ul v-else class="list-group">
      <li v-for="serial in filteredSerials" :key="serial.Serial_id" class="list-group-item">
        <strong>Serial:</strong> {{ serial.serials.Serial_SerialNumber }} <br />
        <strong>สินค้า:</strong> {{ serial.serials.products.Prd_Name }} <br />
        <strong>ราคา:</strong> {{ serial.OrdDtl_Price }} บาท
      </li>
    </ul>
  </CModalBody>
  <CModalFooter>
    <CButton color="secondary" @click="closeModal">ปิด</CButton>
  </CModalFooter>
</CModal>

</template>

<script>
import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import { CModal, CModalHeader, CModalTitle, CModalBody, CModalFooter, CButton } from '@coreui/vue';
export default {
data() {
    return {
    searchQuery: "",
    activeFilter: "all",
    sales: [],
    filteredSerials: [],
    visibleViewModal:false
    };
},
computed: {
    filteredSales() {
    if (!this.sales) return []; // ตรวจสอบว่ามีข้อมูลหรือไม่

    return this.sales.filter((sale) => {
        const matchesSearch = sale.customers.Cus_fullname
        .toLowerCase()
        .includes(this.searchQuery.toLowerCase());

        const saleDate = new Date(sale.Ord_Date);
        const today = new Date();

        if (this.activeFilter === "all") return matchesSearch;

        if (this.activeFilter === "today") {
            return matchesSearch && saleDate.toDateString() === today.toDateString();
        }else if (this.activeFilter === "month") {
            return (
                matchesSearch &&
                saleDate.getMonth() === today.getMonth() &&
                saleDate.getFullYear() === today.getFullYear()
            );
        }
    });
    },
     // คำนวณยอดรวมเงินสด
    cashTotal() {
        return this.filteredSales
        .filter((filteredSales) => filteredSales.Ord_Payment === 0) 
        .reduce((sum, filteredSales) => sum + Number(filteredSales.Ord_TotalAmount), 0);
    },
    // คำนวณยอดรวมเงินโอน
    transferTotal() {
        return this.filteredSales
        .filter((filteredSales) => filteredSales.Ord_Payment === 1) 
        .reduce((sum, filteredSales) => sum + Number(filteredSales.Ord_TotalAmount), 0); 
    },
    // ยอดรวมทั้งหมด
    grandTotal() {
        return this.cashTotal + this.transferTotal;
    },
},
methods: {
    filterSales(type) {
        this.activeFilter = type; 
    },
    getStatusText(status) {
        return status === 1 ? "ชำระแล้ว" : "ยังไม่ชำระ";
    },
    getPaymentText(pay) {
        return pay === 1 ? "เงินโอน" : "เงินสด";
    },

    async fetchSales() {
        const authStore = useAuthStore();
        const token = authStore.token;

        try {
            const response = await axios.get("http://localhost:8000/api/order", {
            headers: {
                Authorization: `Bearer ${token}`,
            },
            });
            this.sales = response.data;
        } catch (error) {
            console.error("Error fetching sales data:", error);
        }
    },
    
    async openModal(sale) {
        const authStore = useAuthStore();
        const token = authStore.token;

        try {
            const response = await axios.get(`http://localhost:8000/api/order-details/${sale.id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            this.filteredSerials = response.data;
            this.visibleViewModal = true; 
        } catch (error) {
            console.error("Error fetching order details:", error);
        }
    },
    closeModal() {
        console.log("Closing modal...");
        this.visibleViewModal = false;
    }

},
mounted() {
    this.fetchSales();

},
};
</script>

<style scoped>
.active {
background-color: #0056b3;
color: white;
}
.container {
max-width: auto;
}

.status-dot {
  display: inline-block;
  width: 10px; 
  height: 10px; 
  border-radius: 50%;
  margin-right: 5px; 
}

.bg-success {
  background-color: #28a745; 
}

.bg-danger {
  background-color: #dc3545; 
}

</style>
