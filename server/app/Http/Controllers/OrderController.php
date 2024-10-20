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
        //
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
            'order_details' => 'required|array', // Assuming order details are passed as an array
            'order_details.*.Serial_id' => 'required|integer',
            'order_details.*.OrdDtl_Price' => 'required|numeric',
        ]);

        // Create a new order
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
