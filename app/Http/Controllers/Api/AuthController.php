<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    
   public function register(Request $request)
{
    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}


public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password'
        ], 401);
    }

    // Old tokens delete (optional)
    $user->tokens()->delete();

    // New token create
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'message' => 'Login successful',
        'user' => $user,
        'token' => $token
    ], 200);
}

public function getProfile(Request $request)
{
    return response()->json([
        'status' => 'success',
        'user' => $request->user()
    ], 200);
}

public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'sometimes|string|max:255',
        'phone' => 'sometimes|string|max:20',
        'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
        'password' => 'sometimes|string|min:6|confirmed',
        'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        'date_of_birth' => 'sometimes|string',
        'city' => 'sometimes|string',
        'address' => 'sometimes|string',
        'pincode' => 'sometimes|string',
        'state' => 'sometimes|string',
        'country' => 'sometimes|string',
        'gender' => 'sometimes|string|in:male,female,other',
    ]);

    $user = $request->user();

    // --- Individual fields update ---
    if ($request->has('name')) {
        $user->name = $request->name;
    }
    if ($request->has('phone')) {
        $user->phone = $request->phone;
    }
    if ($request->has('email')) {
        $user->email = $request->email;
    }
    if ($request->has('date_of_birth')) {
        $user->date_of_birth = $request->date_of_birth;
    }
    if ($request->has('city')) {
        $user->city = $request->city;
    }
    if ($request->has('address')) {
        $user->address = $request->address;
    }
    if ($request->has('pincode')) {
        $user->pincode = $request->pincode;
    }
    if ($request->has('state')) {
        $user->state = $request->state;
    }
    if ($request->has('country')) {
        $user->country = $request->country;
    }
    if ($request->has('gender')) {
        $user->gender = $request->gender;
    }

    // --- Password update ---
    if ($request->has('password')) {
        $user->password = Hash::make($request->password);
    }

    // --- Image update ---
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/profile'), $filename);
        $user->image = 'uploads/profile/'.$filename;
    }

    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Profile updated successfully',
        'user' => $user
    ], 200);
}

public function testing(Request $request)
{
    dd("testing");
    
    
}


}
