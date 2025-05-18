<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            // Authentication successful
            $token = $user->createToken('auth_token')->plainTextToken;
            // Set the token as a cookie
            cookie('auth_token', $token, 60 * 24); // 24 hours
            return response()->json(['token' => $token, 'user_id' => $user->id], 200);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    /**
     * Log the user out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $tokenValue = $request->cookie('auth_token');

        if ($tokenValue) {
            $accessToken = PersonalAccessToken::findToken($tokenValue);
            if ($accessToken) {
                $accessToken->delete();
            }
        }
        // Clear the cookie by returning a response that removes it
        return response()->json(['message' => 'Logged out'])->withoutCookie('auth_token');
    }
}