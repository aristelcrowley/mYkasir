<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        if (!$name || !$email || !$password) {
            return response()->json(['message' => 'All fields are required'], 400);
        }

        if (User::where('email', $email)->exists()) {
            return response()->json(['message' => 'Email already exists'], 409);
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        // Create a token for the new user
        $token = $user->createToken('auth_token')->plainTextToken;
            cookie('auth_token', $token, 60 * 24);
        return response()->json(['token' => $token, 'user_id' => $user->id], 201);
    }
}