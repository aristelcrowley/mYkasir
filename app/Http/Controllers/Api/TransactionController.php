<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product; // Import the Product model
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $transactions = Transaction::with('product')->get(); // Eager load product details
            return response()->json([
                'status' => 'success',
                'data' => $transactions,
            ], 200);
        } catch (Exception $e) {
             return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve transactions: ' . $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity');

            $product = Product::findOrFail($productId); // Use findOrFail

            if ($product->stock < $quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient stock.',
                ], 400);
            }
            $totalPrice = $product->price * $quantity;

            $transaction = Transaction::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
            ]);

            //update stock
            $product->stock -= $quantity;
            $product->save();

            $transaction = Transaction::with('product')->find($transaction->id);

            return response()->json([
                'status' => 'success',
                'data' => $transaction,
                'message' => 'Transaction created successfully.',
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
         try {
            $transaction = Transaction::with('product')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $transaction,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found: ' . $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $transaction = Transaction::findOrFail($id);

            $productId = $request->input('product_id');
            $quantity = $request->input('quantity');

            $product = Product::findOrFail($productId);

            // Get the difference in quantity.
            $quantityDifference = $quantity - $transaction->quantity;

            if ($product->stock < $quantityDifference) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient stock.',
                ], 400);
            }
            $totalPrice = $product->price * $quantity;

            $transaction->update([
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
            ]);

            $product->stock -= $quantityDifference;
            $product->save();
            $transaction = Transaction::with('product')->find($transaction->id);
            return response()->json([
                'status' => 'success',
                'data' => $transaction,
                'message' => 'Transaction updated successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product/Transaction not found.',
            ], 404);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $transaction = Transaction::findOrFail($id);

            $product = Product::findOrFail($transaction->product_id);
            $product->stock += $transaction->quantity;
            $product->save();

            $transaction->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Transaction deleted successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getProducts(): JsonResponse
    {
        try{
            $products = Product::all();
             return response()->json([
                'status' => 'success',
                'data' => $products,
                'message' => 'Products retrieved successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get products: ' . $e->getMessage(),
            ], 500);
        }

    }
}
