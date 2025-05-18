<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the user's products.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
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
        if ($loggedInUser->id != $request->user()->id) {
        return response()->json(['message' => 'Forbidden - Not your product'], 403);
    }

        $products = Product::where('user_id', $request->user()->id)->get();
        return response()->json($products, 200);
    }

    /**
     * Store a newly created product for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
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

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id', // Expecting user_id in the request
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0', // Added stock validation here as well
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        if ($loggedInUser->id != $request->input('user_id')) {
            return response()->json(['message' => 'Forbidden - Cannot create products for other users'], 403);
        }

        $product = new Product();
        $product->user_id = $request->input('user_id');
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock', 0); // Default stock to 0 if not provided
        $product->save();

        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($productId)
    {
        $product = Product::findOrFail($productId);
        $tokenValue = request()->cookie('auth_token');
        if (!$tokenValue) {
            return response()->json(['message' => 'Unauthorized - Not logged in'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        if ($loggedInUser->id != $product->user_id) {
        return response()->json([
            'message' => "Forbidden - Not your product. Logged-in User ID: {$loggedInUser->id}, Product ID: {$product->id}, Product User ID: {$product->user_id}",
        ], 403);
    }
        return response()->json($product, 200);
    }

    /**
     * Update the specified product for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $productId)
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
        $product = Product::findOrFail($productId);

        if ($loggedInUser->id != $product->user_id) {
            return response()->json(['message' => 'Forbidden - Not your product'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'price' => 'numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $product->name = $request->input('name', $product->name);
        $product->price = $request->input('price', $product->price);
        $product->stock = $request->input('stock', $product->stock);
        $product->save();

        return response()->json($product, 200);
    }

    /**
     * Remove the specified product for the user.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($productId)
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
        $product = Product::findOrFail($productId);
        if ($loggedInUser->id != $product->user_id) {
            return response()->json(['message' => 'Forbidden - Not your product'], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}