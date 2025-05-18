<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TransactionController extends Controller
{
    /**
     * Display a listing of the user's transactions.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($userId)
    {
        $tokenValue = request()->cookie('auth_token');
        if (!$tokenValue) {
            return response()->json(['message' => 'Unauthorized - Not logged in', 'redirect_url' => '/login'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token', 'redirect_url' => '/login'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        if ($loggedInUser->id != $userId) {
            return response()->json(['message' => 'Forbidden - Not your transactions', 'redirect_url' => '/transactions/' . $loggedInUser->id], 403);
        }

        $transactions = Transaction::where('user_id', $userId)->get();
        return response()->json($transactions, 200);
    }
    
    /**
     * Display the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        $tokenValue = request()->cookie('auth_token');
        if (!$tokenValue) {
            return response()->json(['message' => 'Unauthorized - Not logged in', 'redirect_url' => '/login'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token', 'redirect_url' => '/login'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        if ($loggedInUser->id != $transaction->user_id) {
            return response()->json(['message' => 'Forbidden - Not your transaction', 'redirect_url' => '/transactions/' . $loggedInUser->id], 403);
        }

        return response()->json($transaction, 200);
    }
}