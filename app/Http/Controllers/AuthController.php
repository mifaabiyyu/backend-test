<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attribute = $request->validate([
                'email'     => 'required|email|unique:users,email',
                'phone'     => 'required|unique:users,phone',
                'name'      => 'required|string',
                'password'  => 'required|min:8|confirmed',
                'password_confirmation' => 'required'
            ]);

        $attribute['password']  = bcrypt($request->password);
        $attribute['status']     = false;

        $user = User::create($attribute);

        $user->assignRole('user');

        $response = [
            'message'   => 'User created successfully !',
            'user'      => $user,
        ];

        return response()->json($response, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response(['message' => 'Email number not Registered !'], 422);
        }

        $passwordCheck = Hash::check($request->password, $user->password);

        if (!$passwordCheck) {
            return response(['message' => 'Password Incorrect !'], 422);
        }

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'message'   => 'Login successfully !',
            'user'      => $user,
            'token'     => $token
        ];

        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $response = [
            'message' => "Successfully Logout"
        ];

        return response()->json($response, 200);
    }
}
