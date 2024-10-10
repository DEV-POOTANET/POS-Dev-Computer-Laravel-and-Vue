<?php

namespace App\Http\Controllers;

use App\Models\Serial;
use Illuminate\Http\Request;

class SerialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId)
    {
        // นับ serial ที่มี Serial_Status = 1
        $countActive = Serial::where('Prd_id', $productId)
                                ->where('Serial_Status', 1)
                                ->count();

        // นับ serial ที่มี Serial_Status = 0
        $countInactive = Serial::where('Prd_id', $productId)
                                ->where('Serial_Status', 0)
                                ->count();

        // ดึงข้อมูล serial ทั้งหมดสำหรับสินค้า
        $serials = Serial::where('Prd_id', $productId)->get();

        return response()->json([
        'success' => true,
        'message' => 'Serials retrieved successfully',
        'data' => $serials,
        'count_active' => $countActive,
        'count_inactive' => $countInactive,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'Prd_id' => 'required|exists:products,id',
        //     'Serial_SerialNumber' => 'required|string|unique:serials,Serial_SerialNumber',
        //     'Serial_Status' => 'required|boolean',
        // ]);

        // $serial = Serial::create($validatedData);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Serial added successfully!',
        //     'data' => $serial,
        // ], 201);

         // Validate the input data
        $validatedData = $request->validate([
            'serials' => 'required|array', // รับข้อมูลเป็นอาเรย์
            'serials.*.Prd_id' => 'required|exists:products,id',
            'serials.*.Serial_SerialNumber' => 'required|string|unique:serials,Serial_SerialNumber',
            'serials.*.Serial_Status' => 'required|boolean',
        ]);

        // เก็บผลลัพธ์ของ serial ที่เพิ่มเข้าไปในฐานข้อมูล
        $createdSerials = [];

        foreach ($validatedData['serials'] as $serialData) {
            $serial = Serial::create($serialData);
            $createdSerials[] = $serial; 
        }

        return response()->json([
            'success' => true,
            'message' => 'Serials added successfully!',
            'data' => $createdSerials,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $serial = Serial::find($id);

        if (!$serial) {
            return response()->json([
                'success' => false,
                'message' => 'Serial not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Serial retrieved successfully',
            'data' => $serial,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       
        $serial = Serial::find($id);

        if (!$serial) {
            return response()->json([
                'success' => false,
                'message' => 'Serial not found',
            ], 404);
        }
      
        $validatedData = $request->validate([
            'Serial_SerialNumber' => 'required|string|unique:serials,Serial_SerialNumber,' . $serial->id,
            'Serial_Status' => 'required|boolean',
        ]);

 
        $serial->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Serial updated successfully!',
            'data' => $serial,
        ], 200);
    }

 
    public function destroy(Serial $serial)
    {
        
    }
}
