<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin; // Use the Admin model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins', // unique in 'admins' table
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $admin->createToken('AdminAuthToken')->accessToken;

        return response()->json(['token' => $token, 'admin' => $admin], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Attempt login using the 'admins' guard
        if (Auth::guard('admins')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $admin = Auth::guard('admins')->user();
            // Ensure $admin is an instance of App\Models\Admin
            if ($admin instanceof \App\Models\Admin) {
                $token = $admin->createToken('AdminAuthToken')->accessToken;
                return response()->json(['token' => $token, 'admin' => $admin], 200);
            } else {
                 return response()->json(['error' => 'Login failed. Admin model mismatch.'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized. Invalid credentials.'], 401);
        }
    }
}
