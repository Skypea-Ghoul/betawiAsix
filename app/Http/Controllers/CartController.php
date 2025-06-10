<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Promo;

class CartController extends Controller
{
public function checkout(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Anda harus login terlebih dahulu.'
        ], 401);
    }

    $cartData = $request->input('cart', []);
    if (empty($cartData) || !is_array($cartData)) {
        return response()->json([
            'success' => false,
            'message' => 'Keranjang kosong atau format tidak valid.'
        ], 422);
    }

    DB::beginTransaction();
    try {
        $totalPrice     = 0;
        $structuredItems = [];

        // 1) Ambil semua product dalam satu query
        $productIds = collect($cartData)->pluck('product_id')->all();
        $products   = Product::whereIn('id', $productIds)
                             ->get()
                             ->keyBy('id');

        // 2) Validasi stok & hitung total
        foreach ($cartData as $item) {
            $productId = intval($item['product_id']);
            $quantity  = intval($item['quantity']);

            $product = $products->get($productId);
            if (! $product) {
                throw new \Exception("Produk dengan ID {$productId} tidak ditemukan.");
            }

            if ($quantity < 1 || $quantity > $product->stock) {
                throw new \Exception(
                    "Stok produk “{$product->name}” tidak mencukupi. Stok saat ini: {$product->stock}."
                );
            }

            $priceAtOrder = $product->price;
            $subtotal     = $priceAtOrder * $quantity;
            $totalPrice  += $subtotal;

            $structuredItems[] = [
                'product_id' => $productId,
                'quantity'   => $quantity,
                'price'      => $priceAtOrder,
                'subtotal'   => $subtotal,
            ];
        }

        // 3) Buat order
        $order = Order::create([
            'user_id'     => $user->id,
            'status'      => 'pending',
            'total_price' => $totalPrice,
        ]);

        // 4) Simpan order items & kurangi stok
        foreach ($structuredItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);

            // Kurangi stok produk
            Product::where('id', $item['product_id'])
                   ->decrement('stock', $item['quantity']);
        }

        // 5) Bersihkan cart
        CartItem::where('user_id', $user->id)->delete();

        DB::commit();

        return response()->json([
            'success'  => true,
            'message'  => 'Pesanan berhasil dibuat.',
            'order_id' => $order->id,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    // Menampilkan halaman keranjang
    public function index()
    {
        // Hanya customer yang boleh akses
        // (Anda bisa pakai middleware 'auth' dan cek role 'customer')
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors('Silakan login terlebih dahulu.');
        }

        // Ambil semua record CartItem milik user ini, eager‐load product-nya
        $cartItems = CartItem::with('product')
                        ->where('user_id', $user->id)
                        ->get();

        // Build array untuk view: [ id, name, price, quantity, image, subtotal, max_stock ]
        $cartData = $cartItems->map(function ($ci) {
            return [
                'id'         => $ci->product->id,
                'name'       => $ci->product->name,
                'price'      => $ci->product->price,
                'quantity'   => $ci->quantity,
                'image'      => $ci->product->image_url,
                'stock'      => $ci->product->stock,
                'subtotal'   => $ci->product->price * $ci->quantity,
            ];
        })->toArray();

        // Hitung total harga
        $totalPrice = array_reduce($cartData, function ($carry, $item) {
            return $carry + $item['subtotal'];
        }, 0);

        // $hasActivePromo = Promo::where('is_active', true)->exists();
        return view('cart.index', [
            'cartItems'  => $cartData,
            'totalPrice' => $totalPrice,
            // 'hasActivePromo' => $hasActivePromo,
        ]);



    //     $cartItems = collect($cartItems)->map(function($item) {
    // $product = \App\Models\Product::find($item['id']);
    // $item['stock'] = $product ? $product->stock : 0;
    // return $item;
    //     });
    }

    // Menambah produk ke keranjang
  public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
    ]);

    $user = Auth::user();
    // (Pastikan Anda sudah memakai middleware auth di route)

    $productId = $request->product_id;
    $quantity  = $request->quantity;
    $product   = Product::findOrFail($productId);

    if ($quantity > $product->stock) {
        return response()->json([
            'success' => false,
            'message' => "Stok tidak mencukupi. Tersisa: {$product->stock}"
        ], 422);
    }

    // Jika sudah ada, update quantity
    $cartItem = CartItem::firstOrNew([
        'user_id'    => $user->id,
        'product_id' => $productId,
    ]);

    $newQty = $cartItem->exists 
              ? $cartItem->quantity + $quantity 
              : $quantity;

    if ($newQty > $product->stock) {
        return response()->json([
            'success' => false,
            'message' => "Maksimal stok hanya {$product->stock}"
        ], 422);
    }

    $cartItem->quantity = $newQty;
    $cartItem->save();

    // Hitung total item di keranjang untuk badge
    $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');

    return response()->json([
        'success'   => true,
        'message'   => 'Produk berhasil ditambahkan ke keranjang.',
        'cartCount' => $cartCount
    ]);
}

    // Update kuantitas (increase/decrease) via AJAX
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user      = Auth::user();
        $productId = $request->product_id;
        $newQty    = $request->quantity;
        $product   = Product::findOrFail($productId);

        if ($newQty > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak mencukupi. Tersisa: {$product->stock}"
            ], 422);
        }

        $cartItem = CartItem::where('user_id', $user->id)
                            ->where('product_id', $productId)
                            ->first();

        if (! $cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang.'
            ], 404);
        }

        $cartItem->quantity = $newQty;
        $cartItem->save();

        // Hitung ulang total harga keranjang
        $allItems = CartItem::with('product')
                      ->where('user_id', $user->id)
                      ->get();

        $newTotal = $allItems->sum(function ($ci) {
            return $ci->product->price * $ci->quantity;
        });

        return response()->json([
            'success'  => true,
            'newTotal' => $newTotal,
            'message'  => 'Kuantitas berhasil diupdate.'
        ]);
    }

    // Menghapus satu item dari keranjang
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user      = Auth::user();
        $productId = $request->product_id;

        $cartItem = CartItem::where('user_id', $user->id)
                            ->where('product_id', $productId)
                            ->first();

        if (! $cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang.'
            ], 404);
        }

        $cartItem->delete();

        // Hitung ulang total harga
        $allItems = CartItem::with('product')
                      ->where('user_id', $user->id)
                      ->get();

        $newTotal = $allItems->sum(function ($ci) {
            return $ci->product->price * $ci->quantity;
        });

        return response()->json([
            'success'  => true,
            'newTotal' => $newTotal,
            'message'  => 'Item berhasil dihapus dari keranjang.'
        ]);
        
    }
}
