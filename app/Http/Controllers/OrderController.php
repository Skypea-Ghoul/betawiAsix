<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentProofs;
use App\Models\Product; // Pastikan model Product ada
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
   public function store(Request $request)
    {
        $validatedData = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            // Validasi file bukti pembayaran
            'proof_files' => 'required|array|min:1',
            'proof_files.*' => 'image|max:2048',
        ]);

        $orderItemsPayload = $validatedData['items'];
        $totalOrderPrice = 0;

        DB::beginTransaction();
        try {
            // 1. Simpan order
            $order = new Order();
            $order->user_id = Auth::id();
            $order->status = 'pending';
            $order->save();

            // 2. Simpan order item & update stok
            foreach ($orderItemsPayload as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak cukup untuk produk {$product->name}");
                }
                $price = $product->price;
                $subtotal = $price * $item['quantity'];
                $totalOrderPrice += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
                $product->decrement('stock', $item['quantity']);
            }

            // 3. Hapus cart user
            if (Auth::check()) {
                \App\Models\CartItem::where('user_id', Auth::id())->delete();
            }

            // 4. Hitung total dan promo
            $order->total_price = $totalOrderPrice;
            if ($request->promo_code) {
                $promo = \App\Models\Promo::where('kode', $request->promo_code)->where('is_active', true)->first();
                if ($promo) {
                    $order->promo_id = $promo->id;
                    $potongan = round($order->total_price * ($promo->diskon / 100));
                    $order->grandtotal = $order->total_price - $potongan;
                    $promo->is_active = false;
                    $promo->save();
                }
            }
            $order->save();

            // 5. Simpan bukti pembayaran
            $paths = [];
            if ($request->hasFile('proof_files')) {
                foreach ($request->file('proof_files') as $file) {
                    $paths[] = $file->store('payment_proofs', 'public');
                }
                PaymentProofs::create([
                    'order_id'   => $order->id,
                    'user_id'    => Auth::id(),
                    'amount'     => $order->grandtotal ?? $order->total_price,
                    'proof_path' => json_encode($paths),
                    'status'     => 'pending',
                ]);
            }

            
            DB::commit();
            
            event(new OrderCreated($order));
            return response()->json([
                'success' => true,
                'message' => 'Pesanan & bukti pembayaran berhasil dibuat!',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}