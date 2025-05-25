<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('UserAuthToken')->accessToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
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

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('web')->user();
            // Ensure $user is an instance of App\Models\User before calling createToken
            if ($user instanceof \App\Models\User) {
                $token = $user->createToken('UserAuthToken')->accessToken;
                return response()->json(['token' => $token, 'user' => $user], 200);
            } else {
                // This case should ideally not happen if guard and provider are set up correctly
                return response()->json(['error' => 'Login failed. User model mismatch.'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized. Invalid credentials.'], 401);
        }
    }
}
