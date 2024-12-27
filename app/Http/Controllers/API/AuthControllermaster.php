<?php

// namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
// use Tymon\JWTAuth\Facades\JWTAuth;

// class AuthController extends Controller
// {
//     public function register(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:6|confirmed',
//         ]);
 
//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 422);
//         }

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);

//         $token = JWTAuth::fromUser($user);

//         return response()->json(compact('user', 'token'), 201);
//     }

//     public function login(Request $request)
//     {
//         $credentials = $request->only('email', 'password');

//         if (!$token = Auth::guard('api')->attempt($credentials)) {
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }

//         return response()->json(compact('token'));
//     }

//     public function logout()
//     {
//         Auth::guard('api')->logout();

//         return response()->json(['message' => 'Successfully logged out']);
//     }

//     public function me()
//     {
//         return response()->json(Auth::guard('api')->user());
//     }
// }
