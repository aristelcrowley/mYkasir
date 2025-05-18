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
            return response()->json(['message' => 'Forbidden - Not your products', 'redirect_url' => '/products/' . $loggedInUser->id], 403);
        }

        $products = Product::where('user_id', $userId)->get();
        return response()->json($products, 200);
    }

    /**
     * Store a newly created product for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $userId)
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
            return response()->json(['message' => 'Forbidden - Cannot create products for other users', 'redirect_url' => '/products/' . $loggedInUser->id], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->user_id = $userId;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->save();

        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $tokenValue = request()->cookie('auth_token');
        if (!$tokenValue) {
            return response()->json(['message' => 'Unauthorized - Not logged in', 'redirect_url' => '/login'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token', 'redirect_url' => '/login'], 401);
        }

        $loggedInUser = $accessToken->tokenable;
        if ($loggedInUser->id != $product->user_id) {
            return response()->json(['message' => 'Forbidden - Not your product', 'redirect_url' => '/products/' . $loggedInUser->id], 403);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified product for the user.
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
            return response()->json(['message' => 'Unauthorized - Not logged in', 'redirect_url' => '/login'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($tokenValue);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized - Invalid token', 'redirect_url' => '/login'], 401);
        }

        $loggedInUser = $accessToken->tokenable;


        $product = Product::findOrFail($id);

        if ($loggedInUser->id != $product->user_id) {
            return response()->json(['message' => 'Forbidden - Not your product', 'redirect_url' => '/products/' . $loggedInUser->id], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $product->name = $request->input('name', $product->name);
        $product->description = $request->input('description', $product->description);
        $product->price = $request->input('price', $product->price);
        $product->save();

        return response()->json($product, 200);
    }

    /**
     * Remove the specified product for the user.
     *
     * @param  int  $userId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($userId, $id)
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
        $product = Product::findOrFail($id);
        if ($loggedInUser->id != $product->user_id) {
            return response()->json(['message' => 'Forbidden - Not your product', 'redirect_url' => '/products/' . $loggedInUser->id], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}