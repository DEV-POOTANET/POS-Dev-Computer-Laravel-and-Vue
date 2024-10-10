<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prd = Product::all();

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => $prd,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่รับเข้ามา
        $validatedData = $request->validate([
            'Prd_Name' => 'required|string|max:255',
            'Prd_details' => 'nullable|string',
            'Cat_id' => 'required|exists:cats,id',
            'Prd_Price' => 'required|numeric',
            'Prd_CostPrice' => 'required|numeric',
            'Prd_Img' => 'nullable|string',
            'Prd_Status' => 'required|boolean',
        ]);

        // สร้างสินค้าใหม่โดยใช้ข้อมูลที่ผ่านการ validate แล้ว
        $product = Product::create($validatedData);

        // ส่งข้อมูลสินค้าที่สร้างสำเร็จกลับไป
        return response()->json([
            'success' => true,
            'message' => 'Product added successfully!',
            'data' => $product,
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        return response()->json([
            'message' => 'Find Product successfully',
            'data' => $product,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         // ตรวจสอบข้อมูลที่รับเข้ามา
        $validatedData = $request->validate([
            'Prd_Name' => 'required|string|max:255',
            'Prd_details' => 'nullable|string',
            'Cat_id' => 'required|exists:cats,id',
            'Prd_Price' => 'required|numeric',
            'Prd_CostPrice' => 'required|numeric',
            'Prd_Img' => 'nullable|string',
            'Prd_Status' => 'required|boolean',
        ]);

        // หาสินค้าตาม id ที่ส่งมา
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        // อัปเดตข้อมูลสินค้า
        $product->update($validatedData);

        // ส่งข้อมูลสินค้าที่อัปเดตสำเร็จกลับไป
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'data' => $product,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
