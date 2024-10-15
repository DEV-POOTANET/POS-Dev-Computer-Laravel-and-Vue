<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Customer::all();

        return response()->json([
            'message' => 'Customer retrieved successfully',
            'data' => $customer,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Cus_fullname' => 'required|string|max:255',
            'Cus_tel' => 'required|string|max:20',
            'Cus_email' => 'required|string|email|unique:customers,Cus_email',
            'Cus_Status' => 'integer',
        ]);
    
        $customer = Customer::create($validated);
    
        return response()->json([
            'success' => true,
            'message' => 'Customer added successfully!',
            'data' => $customer,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = Customer::find($id);

        return response()->json([
            'message' => 'Customer retrieved successfully',
            'data' => $customer,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Cus_fullname' => 'required|string|max:255',
            'Cus_email' => 'required|string|email',
            'Cus_tel' => 'required|string|max:20',
            'Cus_Status' => 'required|integer|in:0,1', // ตรวจสอบสถานะเป็น 0 หรือ 1
        ]);
    
        $customer = Customer::find($id);
    
        if ($customer) {
            $customer->update($validated);
    
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully!',
                'data' => $customer,
            ], 200);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Customer not found.',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function suspendCus(Request $request, $id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            // สลับสถานะ
            $customer->Cus_Status = $customer->Cus_Status == 1 ? 0 : 1;

            // บันทึกการเปลี่ยนแปลง
            $customer->save();

            return response()->json(['message' => 'Customer status updated successfully.']);
        }

        return response()->json(['message' => 'Customer not found.'], 404);
    }
}
