<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'address' => 'nullable|string',
            'tel' => 'required|string',
            'role' => 'required|integer'
        ]);
    
        $user = User::create([
            'fullname' => $fields['fullname'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'address' => $fields['address'] ?? null,
            'tel' => $fields['tel'],
            'img' => "user.png", 
            'status' => true, 
            'role' => $fields['role']
        ]);
    
        $response = [
            'status' => true,
            'message' => "User registered successfully",
            'user' => $user,
        ];
    
        return response($response, 201);
    }

    public function login(Request $request) {
        // Validate field
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
    
        // Check email
        $user = User::where('email', $fields['email'])->first();
        
        
        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'status' => false,
                'message' => 'Login failed'
            ], 401);

        
        }else if ($user->status != 1) {
            return response([
                'status' => false,
                'message' => 'Account is inactive'
            ], 403); // 403 Forbidden
        }
        else 
        {
            // ลบ token เก่าก่อนแล้วค่อยสร้างใหม่
            $user->tokens()->delete();
            // Create token
            $token = $user->createToken($request->userAgent(), ["{$user->role}"])->plainTextToken;
    
            $response = [
                'status' => true,
                'message' => 'Login successfully',
                'user' => $user,
                'token' => $token
            ];
    
            return response($response, 201);
        }
    }
    
    public function logout(Request $request) 
    {
        $user = $request->user();
        $user ->tokens()->delete();
        return [
            'status' => true,
            'message' => 'Logged out'
        ];
    }

    public function getAllUser()
    {
        $userall = User::all();

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $userall,
        ], 200);
    }

    public function getUser($id)
    {
        $user = User::find($id);

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $user,
        ], 200);
    }

    public function updateUser(Request $request, $id)
    {
        // ค้นหาผู้ใช้โดย ID
        $user = User::find($id);

        // ตรวจสอบว่าผู้ใช้มีอยู่หรือไม่
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // ตรวจสอบข้อมูลที่ส่งมา
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'address' => 'nullable|string',
            'tel' => 'required|string|unique:users,Tel,' . $id,
            'role' => 'required|integer',
        ]);

        // อัปเดตข้อมูลผู้ใช้
        $user->update($validatedData);

        // ส่งข้อมูลกลับไปยังผู้ใช้
        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    public function suspendUser(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            // สลับสถานะ
            $user->status = $user->status == 1 ? 0 : 1;

            // บันทึกการเปลี่ยนแปลง
            $user->save();

            return response()->json(['message' => 'User status updated successfully.']);
        }

        return response()->json(['message' => 'User not found.'], 404);
    }


    
}
