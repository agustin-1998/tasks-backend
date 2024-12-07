<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login logic
     */
    public function login(LoginRequest $request)
    {           
        $credentials = $request->only('email', 'password');
    
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials. Please check your email and password.',
            ], 401);
        }
    
        $user = Auth::user();
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'status' => 200,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }
    

    /**
     * Register logic
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        return response()->json([
            'status' => 201,
            'message' => 'User registered successfully',
            'data' => $user,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    
        return response()->json([
            'status' => 200,
            'message' => 'Logged out successfully.'
        ], 200);
    }
}
