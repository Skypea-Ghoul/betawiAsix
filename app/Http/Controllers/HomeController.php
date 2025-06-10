<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Promo;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartData = [];
        $totalPrice = 0;

        if ($user) {
            $cartItems = CartItem::with('product')
                ->where('user_id', $user->id)
                ->get();

            $cartData = $cartItems->map(function ($ci) {
                return [
                    'id'       => $ci->product->id,
                    'name'     => $ci->product->name,
                    'price'    => $ci->product->price,
                    'quantity' => $ci->quantity,
                    'image'    => $ci->product->image_url,
                    'stock'    => $ci->product->stock,
                    'subtotal' => $ci->product->price * $ci->quantity,
                ];
            })->toArray();

            $totalPrice = array_reduce($cartData, function ($carry, $item) {
                return $carry + $item['subtotal'];
            }, 0);
        }

        $hasActivePromo = Promo::where('is_active', true)->exists();

        return view('home', [
            'cartItems'      => $cartData,
            'totalPrice'     => $totalPrice,
            'hasActivePromo' => $hasActivePromo,
        ]);
    }
}
