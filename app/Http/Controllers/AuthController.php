<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->json()->all() ?: $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:10',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'status' => true,
            'message' => 'User Registered Successfully!',
            'user' => auth('api')->user(),
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->json()->all() ?: $request->all();

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email or Password!'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login Successfull!',
            'user' => auth('api')->user(),
            'token' => $token
        ]);
    }

    public function me()
    {
        return response()->json([
            'status' => true,
            'user' => auth('api')->user()
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Loged out Successfully!'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => true,
            'user' => auth('api')->user(),
            'token' => auth('api')->refresh()
        ]);
    }

    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'message' => 'required|string'
        ]);

        if($validator->fails())
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ] , 422);
            }

            $contact = ContactUs::create([
                'address' => $request->address,
                'name' => $request->name,
                'phone' => $request->phone,
                'date' => $request->date ?: null,
                'message' => $request->message,

            ]);

            return response()->json([
                'status' => true,
                'message' => 'Your Response has been Collected!',
                'data' => $contact
            ] , 201);
    }

}
