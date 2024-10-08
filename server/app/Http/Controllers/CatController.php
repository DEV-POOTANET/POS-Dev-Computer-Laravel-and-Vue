<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use Illuminate\Http\Request;

class CatController extends Controller
{
 
    public function index()
    {
        $cats = Cat::all();

        return response()->json([
            'message' => 'Categories retrieved successfully',
            'data' => $cats,
        ], 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Cat_Name' => 'required|string|max:255',
            'Cat_Status' => 'nullable|boolean',
        ]);

        $cat = Cat::create($validatedData);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $cat,
        ], 201);
    }

   
    public function show($id)
    {
        $cat = Cat::find($id);

        return response()->json([
            'message' => 'Find Category successfully',
            'data' => $cat,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $cat = Cat::find($id);
        if (!$cat) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        $validatedData = $request->validate([
            'Cat_Name' => 'required|string|max:255',
            'Cat_Status' => 'nullable|boolean',
        ]);

        $cat->update($validatedData);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $cat,
        ], 200);
    }


    public function updateStatus(Request $request, $id)
    {
        $cat = Cat::find($id);

        if ($cat) {
            // สลับสถานะ
            $cat->Cat_Status = $cat->Cat_Status == 1 ? 0 : 1;

            // บันทึกการเปลี่ยนแปลง
            $cat->save();

            return response()->json(['message' => 'Category status updated successfully.']);
        }

        return response()->json(['message' => 'Category not found.'], 404);
    }
}
