<template>
<div class="container">
    <div class="card shadow-lg">
    <div class="card-header">
        <h3 class="card-title">Dashboard</h3>
    </div>
    <div class="card-body">
        <div class="row">
        <CCol :sm="3">
            <CWidgetStatsF class="mb-3"color="danger" title="จำนวนรายการสั่งซื้อวันนี้" :value="`${ordersToday} รายการ`">
            <template #icon>
                <CIcon icon="cil-notes" size="xl" />
            </template>
            <template #footer>
                <CLink
                class="font-weight-bold font-xs text-body-secondary"
                href="http://localhost:3000/#/order"
                rel="noopener noreferrer"
                target="_blank"
                >
                View more
                <CIcon icon="cil-arrow-right" class="ms-auto" width="16" />
                </CLink>
            </template>
            </CWidgetStatsF>
        </CCol>
        <CCol :sm="3">
            <CWidgetStatsF class="mb-3"color="info" title="จำนวนรายการสั่งซื้อเดือนนี้" :value="`${ordersMonth} รายการ`">
            <template #icon>
                <CIcon icon="cil-notes" size="xl" />
            </template>
            <template #footer>
                <CLink
                class="font-weight-bold font-xs text-body-secondary"
                href="http://localhost:3000/#/order"
                rel="noopener noreferrer"
                target="_blank"
                >
                View more
                <CIcon icon="cil-arrow-right" class="ms-auto" width="16" />
                </CLink>
            </template>
            </CWidgetStatsF>
        </CCol>
        <CCol :sm="3">
            <CWidgetStatsF class="mb-3"color="warning" title="จำนวนรายการสั่งซื้อปีนี้" :value="`${ordersYear} รายการ`">
            <template #icon>
                <CIcon icon="cil-notes" size="xl" />
            </template>
            <template #footer>
                <CLink
                class="font-weight-bold font-xs text-body-secondary"
                href="http://localhost:3000/#/order"
                rel="noopener noreferrer"
                target="_blank"
                >
                View more
                <CIcon icon="cil-arrow-right" class="ms-auto" width="16" />
                </CLink>
            </template>
            </CWidgetStatsF>
        </CCol>
        <CCol :sm="3">
            <CWidgetStatsF class="mb-3"color="primary" title="จำนวนรายการสั่งซื้อทั้งหมด" :value="`${ordersTotal} รายการ`">
            <template #icon>
                <CIcon icon="cil-notes" size="xl" />
            </template>
            <template #footer>
                <CLink
                class="font-weight-bold font-xs text-body-secondary"
                href="http://localhost:3000/#/order"
                rel="noopener noreferrer"
                target="_blank"
                >
                View more
                <CIcon icon="cil-arrow-right" class="ms-auto" width="16" />
                </CLink>
            </template>
            </CWidgetStatsF>
        </CCol>
        </div>

        <div class="row">
        <CCol :sm="3">
        <CWidgetStatsC
            class="mb-3"
            inverse
            color="danger"
            :value="`${salesToday} บาท`"
            :progress="{ value: 75 }"
            title="ยอดขายวันนี้"
        >
            <template #icon>
            <CIcon icon="cilCalculator" height="36" />
            </template>
        </CWidgetStatsC>
        </CCol>

        <CCol :sm="3">
        <CWidgetStatsC
            class="mb-3"
            inverse
            color="info"
            :value="`${salesMonth} บาท`"
            :progress="{ value: 75 }"
            title="ยอดขายเดือนนี้"
        >
            <template #icon>
            <CIcon icon="cilCalculator" height="36" />
            </template>
        </CWidgetStatsC>
        </CCol>

        <CCol :sm="3">
        <CWidgetStatsC
            class="mb-3"
            inverse
            color="warning"
            :value="`${salesYear} บาท`"
            :progress="{ value: 75 }"
            title="ยอดขายปีนี้"
        >
            <template #icon>
            <CIcon icon="cilCalendar" height="36" />
            </template>
        </CWidgetStatsC>
        </CCol>

        <CCol :sm="3">
        <CWidgetStatsC
            class="mb-3"
            inverse
            color="primary"
            :value="`${salesTotal} บาท`"
            :progress="{ value: 75 }"
            title="ยอดรวมทั้งหมด"
        >
            <template #icon>
            <CIcon icon="cilCalendar" height="36" />
            </template>
        </CWidgetStatsC>
        </CCol>
    </div>

        <div class="row mt-3">
        <canvas id="salesChart"></canvas>
        </div>
    </div>
    </div>
</div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { Chart } from 'chart.js/auto';
import { useAuthStore } from '@/stores/auth';

export default {
setup() {
    const salesData = ref({});
    const chart = ref(null);

    const ordersToday = ref(0);
    const ordersTotal= ref(0);
    const ordersMonth = ref(0);
    const ordersYear= ref(0);
    const salesTotal= ref(0);
    const salesToday= ref(0);
    const salesMonth= ref(0);
    const salesYear= ref(0);

    const fetchSalesData = async () => {
    const authStore = useAuthStore();
    const token = authStore.token;
    try {
        const response = await axios.get('http://localhost:8000/api/sales/monthly', {
        headers: { Authorization: `Bearer ${token}` },
        });
        salesData.value = response.data;
    
    } catch (error) {
        console.error('Error fetching sales data:', error);
    }
    };

    const fetchOrderStats = async () => {
    const authStore = useAuthStore();
    const token = authStore.token;
    try {
        const response = await axios.get('http://localhost:8000/api/orders/summary', {
        headers: { Authorization: `Bearer ${token}` },
        });
        ordersToday.value = response.data.orders.today;
        ordersTotal.value = response.data.orders.total;
        ordersMonth.value = response.data.orders.this_month;
        ordersYear.value = response.data.orders.this_year;
        salesTotal.value = response.data.sales.total
        salesToday.value = response.data.sales.today
        salesMonth.value = response.data.sales.this_month
        salesYear.value = response.data.sales.this_year
    } catch (error) {
        console.error('Error fetching order stats:', error);
    }
    };

    const createChart = () => {
    if (!salesData.value) return;
    if (chart.value) chart.value.destroy();

    const monthNames = [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December'
    ];

    const labels = monthNames;
    const data = monthNames.map((_, index) =>
        parseFloat(salesData.value[index + 1] || 0)
    );

    chart.value = new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
        labels,
        datasets: [
            {
            label: 'รายได้ต่อเดือน',
            data,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            borderRadius: 10,
            hoverBackgroundColor: 'rgba(75, 192, 192, 0.8)',
            hoverBorderColor: 'rgba(75, 192, 192, 1)',
            hoverBorderWidth: 3,
            },
        ],
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
            display: true,
            position: 'top',
            labels: { font: { size: 14, weight: 'bold' } },
            },
            tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: '#ddd',
            borderWidth: 1,
            },
        },
        scales: {
            x: {
            title: {
                display: true,
                text: 'เดือน',
                font: { size: 16, weight: 'bold' },
            },
            grid: { color: 'rgba(0, 0, 0, 0.1)', lineWidth: 1 },
            },
            y: {
            title: {
                display: true,
                text: 'รายได้ (บาท)',
                font: { size: 16, weight: 'bold' },
            },
            ticks: { callback: (value) => value.toFixed(2) },
            grid: { color: 'rgba(0, 0, 0, 0.1)', lineWidth: 1 },
            },
        },
        },
    });
    };

    onMounted(() => {
    fetchSalesData();
    fetchOrderStats();
    });

    watch(salesData, createChart);

    return { salesData, chart ,
        ordersToday , 
        ordersTotal,
        salesTotal,
        salesToday,
        salesMonth,
        salesYear,
        ordersMonth,
        ordersYear
    };
},
};
</script>

<style scoped>
.container {
max-width: 1600px;
margin: auto;
}

canvas {
height: 400px;
}

.card {
margin-top: 20px;
}
</style>
