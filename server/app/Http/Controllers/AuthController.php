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
    
}
