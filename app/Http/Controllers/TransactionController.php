<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

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
            return response()->json(['message' => 'Unauthorized - Not logged in'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        if ($loggedInUser->id != $userId) {
            return response()->json(['message' => 'Forbidden - Not your transactions'], 403);
        }

        $transactions = Transaction::where('user_id', $userId)->get();
        return response()->json($transactions, 200);
    }

    /**
     * Store a newly created transaction for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $userId)
    {
        $tokenValue = request()->cookie('auth_token');
        if (!$tokenValue) {
            return response()->json(['message' => 'Unauthorized - Not logged in'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        if ($loggedInUser->id != $userId) {
            return response()->json(['message' => 'Forbidden - Cannot create transactions for other users'], 403);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->product_id = $request->input('product_id');
        $transaction->quantity = $request->input('quantity');
        $transaction->total_amount = $request->input('total_amount');
        // Set other fields
        $transaction->save();

        return response()->json($transaction, 201);
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
            return response()->json(['message' => 'Unauthorized - Not logged in'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        if ($loggedInUser->id != $transaction->user_id) {
            return response()->json(['message' => 'Forbidden - Not your transaction'], 403);
        }
        return response()->json($transaction, 200);
    }

    /**
     * Update the specified transaction for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $userId, $id)
    {
        $tokenValue = request()->cookie('auth_token');
        if (!$tokenValue) {
            return response()->json(['message' => 'Unauthorized - Not logged in'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        $transaction = Transaction::findOrFail($id);
        if ($loggedInUser->id != $transaction->user_id) {
            return response()->json(['message' => 'Forbidden - Not your transaction'], 403);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'integer|exists:products,id',
            'quantity' => 'integer|min:1',
            'total_amount' => 'numeric|min:0',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }
        $transaction->product_id = $request->input('product_id', $transaction->product_id);
        $transaction->quantity = $request->input('quantity', $transaction->quantity);
        $transaction->total_amount = $request->input('total_amount', $transaction->total_amount);
        // Update other fields
        $transaction->save();

        return response()->json($transaction, 200);
    }

    /**
     * Remove the specified transaction for the user.
     *
     * @param  int  $userId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($userId, $id)
    {
        $tokenValue = request()->cookie('auth_token');
        if (!$tokenValue) {
            return response()->json(['message' => 'Unauthorized - Not logged in'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        $transaction = Transaction::findOrFail($id);
        if ($loggedInUser->id != $transaction->user_id) {
            return response()->json(['message' => 'Forbidden - Not your transaction'], 403);
        }

        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully'], 200);
    }
}