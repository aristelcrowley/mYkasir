<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

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
}