<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Serial;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['users', 'customers'])
        ->orderBy('Ord_Date', 'desc') 
        ->orderBy('Ord_Time', 'desc')
        ->get();
        return response()->json($orders);
    }

    public function getOrderDetails($orderId)
    {
        $orderDetails = OrderDetail::with(['serials.products'])
            ->where('Ord_id', $orderId)
            ->get(['Serial_id', 'OrdDtl_Price']); 

        return response()->json($orderDetails);
    }

    public function getSalesByMonth()
    {
        // กำหนดปีปัจจุบัน
        $currentYear = date('Y');

        // ดึงยอดขายจากฐานข้อมูล
        $salesData = Order::selectRaw('MONTH(Ord_Date) as month, SUM(Ord_TotalAmount) as total_sales')
            ->whereYear('Ord_Date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // เตรียมข้อมูลยอดขายสำหรับแต่ละเดือน
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[$i] = 0; // กำหนดยอดขายเริ่มต้นเป็น 0
        }

        // นำยอดขายที่ได้มาจัดเก็บใน $monthlySales
        foreach ($salesData as $sale) {
            $monthlySales[$sale->month] = $sale->total_sales;
        }

        return response()->json($monthlySales);
    }

    public function getOrderSummary()
    {
        $today = now()->startOfDay();
        $currentMonth = now()->startOfMonth();
        $currentYear = now()->startOfYear();

        // จำนวนคำสั่งซื้อ
        $ordersToday = Order::whereDate('Ord_Date', $today)->count();
        $ordersThisMonth = Order::whereBetween('Ord_Date', [$currentMonth, now()])->count();
        $ordersThisYear = Order::whereBetween('Ord_Date', [$currentYear, now()])->count();
        $totalOrders = Order::count();

        // ยอดขายรวม
        $salesToday = Order::whereDate('Ord_Date', $today)->sum('Ord_TotalAmount');
        $salesThisMonth = Order::whereBetween('Ord_Date', [$currentMonth, now()])->sum('Ord_TotalAmount');
        $salesThisYear = Order::whereBetween('Ord_Date', [$currentYear, now()])->sum('Ord_TotalAmount');
        $totalSales = Order::sum('Ord_TotalAmount');

        return response()->json([
            'orders' => [
                'today' => $ordersToday,
                'this_month' => $ordersThisMonth,
                'this_year' => $ordersThisYear,
                'total' => $totalOrders,
            ],
            'sales' => [
                'today' => $salesToday,
                'this_month' => $salesThisMonth,
                'this_year' => $salesThisYear,
                'total' => $totalSales,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Cus_id' => 'required|integer',
            'Emp_id' => 'required|integer',
            'Ord_Date' => 'required|date',
            'Ord_Time' => 'required|date_format:H:i',
            'Ord_TotalAmount' => 'required|numeric',
            'Ord_Payment' => 'required|integer',
            'Ord_Status' => 'required|integer',
            'order_details' => 'required|array', 
            'order_details.*.Serial_id' => 'required|integer',
            'order_details.*.OrdDtl_Price' => 'required|numeric',
        ]);


        $order = Order::create([
            'Cus_id' => $request->Cus_id,
            'Emp_id' => $request->Emp_id,
            'Ord_Date' => $request->Ord_Date,
            'Ord_Time' => $request->Ord_Time,
            'Ord_TotalAmount' => $request->Ord_TotalAmount,
            'Ord_Payment' => $request->Ord_Payment,
            'Ord_Status' => $request->Ord_Status,
        ]);

       
        foreach ($request->order_details as $detail) {
            OrderDetail::create([
                'Ord_id' => $order->id, 
                'Serial_id' => $detail['Serial_id'],
                'OrdDtl_Price' => $detail['OrdDtl_Price'],
            ]);

            $serial = Serial::find($detail['Serial_id']);
            if ($serial) {
                $serial->Serial_Status = 0; 
                $serial->save();
            }
        }

        return response()->json([
            'message' => 'Order created successfully!',
            'order' => $order,
        ], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
