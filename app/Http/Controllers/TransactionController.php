<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product; // Don't forget to use the Product model
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // For database transactions

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

        $transactions = Transaction::where('user_id', $userId)->with('product')->get(); // Eager load product
        return response()->json(['status' => 'success', 'data' => $transactions], 200);
    }

    /**
     * Store a newly created transaction for the user and update product stock.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
     public function store(Request $request)
    {
        $tokenValue = $request->cookie('auth_token');
        if (!$tokenValue) {
            return response()->json(['message' => 'Unauthorized - Not logged in'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
        }

        $loggedInUser = $accessToken->tokenable;

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            return response()->json(['message' => 'Insufficient stock for product: ' . $product->name], 400);
        }

        // Calculate total price
        $totalPrice = $product->price * $quantity;

        // Use a database transaction
        return DB::transaction(function () use ($loggedInUser, $productId, $quantity, $totalPrice, $product) {
            $transaction = new Transaction();
            $transaction->user_id = $loggedInUser->id;
            $transaction->product_id = $productId;
            $transaction->quantity = $quantity;
            $transaction->total_price = $totalPrice;
            $transaction->save();

            $product->decrement('stock', $quantity);
            $product->save();

            return response()->json(['status' => 'success', 'data' => $transaction, 'message' => 'Transaction created successfully and stock updated.'], 201);
        });
    }

    /**
     * Display the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $transaction = Transaction::with('product')->findOrFail($id);
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
        return response()->json(['status' => 'success', 'data' => $transaction], 200);
    }

    /**
     * Update the specified transaction for the user (limited functionality in this example).
     * Note: Updating transactions that affect stock can be complex and might require adjustments.
     * This example only allows updating the quantity and recalculates the total price.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $transactionId)
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
        $transaction = Transaction::findOrFail($transactionId);
        if ($loggedInUser->id != $transaction->user_id) {
            return response()->json(['message' => 'Forbidden - Not your transaction'], 403);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'integer|exists:products,id', // Add validation for product_id
            'quantity' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        // Handle product ID update
        if ($request->filled('product_id') && $request->input('product_id') != $transaction->product_id) {
            $newProductId = $request->input('product_id');
            $oldProductId = $transaction->product_id;
            $quantity = $transaction->quantity;

            // Fetch both old and new products
            $newProduct = Product::findOrFail($newProductId);
            $oldProduct = Product::findOrFail($oldProductId);

            // Check if there's enough stock for the new product
            if ($newProduct->stock < $quantity) {
                return response()->json(['message' => 'Insufficient stock for the new product: ' . $newProduct->name], 400);
            }

            // Update stock levels (revert stock for old product, decrement for new product)
            $oldProduct->increment('stock', $quantity);
            $newProduct->decrement('stock', $quantity);
            $oldProduct->save();
            $newProduct->save();

            // Update transaction's product ID and total price
            $transaction->product_id = $newProductId;
            $transaction->total_price = $newProduct->price * $quantity;
        }

        // Handle quantity update (as before)
        if ($request->filled('quantity')) {
            $oldQuantity = $transaction->quantity;
            $newQuantity = $request->input('quantity');

            if ($newQuantity !== $oldQuantity) {
                $product = Product::findOrFail($transaction->product_id); // Use the transaction's current product

                if ($newQuantity > $oldQuantity) {
                    if ($product->stock < ($newQuantity - $oldQuantity)) {
                        return response()->json(['message' => 'Insufficient stock for the updated quantity.'], 400);
                    }
                    $product->decrement('stock', ($newQuantity - $oldQuantity));
                } else {
                    $product->increment('stock', ($oldQuantity - $newQuantity));
                }
                $product->save();
                $transaction->quantity = $newQuantity;
                $transaction->total_price = $product->price * $newQuantity;
            }
        }

        $transaction->save();

        return response()->json(['status' => 'success', 'data' => $transaction, 'message' => 'Transaction updated successfully.'], 200);
    }

    /**
     * Remove the specified transaction for the user and revert product stock.
     *
     * @param  int  $userId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($transactionId)
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
        $transaction = Transaction::findOrFail($transactionId);
        if ($loggedInUser->id != $transaction->user_id) {
            return response()->json(['message' => 'Forbidden - Not your transaction'], 403);
        }

        return DB::transaction(function () use ($transaction) {
            $product = Product::findOrFail($transaction->product_id);
            $product->increment('stock', $transaction->quantity);
            $product->save();
            $transaction->delete();

            return response()->json(['status' => 'success', 'message' => 'Transaction deleted successfully and stock reverted.'], 200);
        });
    }


    /**
     * Get a list of all products for dropdowns.
     * This assumes you have a similar method in your ProductController or a dedicated route.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductList()
    {
        $products = Product::all(['id', 'name', 'price', 'stock']); // Fetch necessary product details
        return response()->json(['status' => 'success', 'data' => $products], 200);
    }
}