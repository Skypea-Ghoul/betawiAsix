<?php

namespace App\Models;

use App\Events\OrderCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'promo_id',
        'grandtotal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

      public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

        public function paymentProofs()
    {
        return $this->hasMany(PaymentProofs::class);
    }

    protected static function booted()
    {
        // ---------------------------------------------------------------
        // 1) Setelah Order dibuat: hitung total_price berdasarkan items
        // ---------------------------------------------------------------
        static::created(function (Order $order) {
            $order->calculateTotalPrice();
            //   event(new OrderCreated($order));
        });

        // ---------------------------------------------------------------
        // 2) Saat Order di‐update: 
        //    - Jika status berubah jadi 'cancelled', kembalikan stok semua item
        //    - Jika status berubah dari 'cancelled' ke lain, kurangi stok kembali
        // ---------------------------------------------------------------
        static::updating(function (Order $order) {
            if ($order->isDirty('status')) {
                $original = $order->getOriginal('status');
                $new      = $order->status;

                // a) pending/paid/... → cancelled
                if ($new === 'canceled') {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->increaseStock($item->quantity);
                        }
                    }
                }

                // b) cancelled → pending/paid/...
                if ($original === 'cancelled' && $new !== 'canceled') {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            if (! $item->product->hasEnoughStock($item->quantity)) {
                                throw new \Exception(
                                    "Stok tidak cukup untuk memulihkan order. " .
                                    "Produk: “{$item->product->name}”. Stok tersedia: {$item->product->stock}"
                                );
                            }
                            $item->product->reduceStock($item->quantity);
                        }
                    }
                }
            }
        });

        // ---------------------------------------------------------------
        // 3) Saat Order di‐soft delete:
        //    a) Soft‑delete semua OrderItem
        //    b) Hanya kembalikan stok jika status ≠ 'cancelled'
        //    c) Jika forceDelete, hard delete OrderItem
        // ---------------------------------------------------------------
        static::deleting(function (Order $order) {
            // a) Soft delete semua OrderItem (jika bukan force delete)
            if (! $order->isForceDeleting()) {
                $order->items()->delete();
            }

            // b) Jika status saat ini bukan 'cancelled', kembalikan stok sekali
            if ($order->status !== 'canceled') {
                foreach ($order->items()->withTrashed()->get() as $item) {
                    if ($item->product) {
                        $item->product->increaseStock($item->quantity);
                    }
                }
            }

            // c) Jika forceDelete, hard delete semua OrderItem yang di‑soft delete
            if ($order->isForceDeleting()) {
                $order->items()->withTrashed()->forceDelete();
            }
        });

        // ---------------------------------------------------------------
        // 4) Saat Order direstore (dari soft delete):
        //    a) Restore semua OrderItem
        //    b) Jika status bukan 'cancelled', kurangi kembali stok
        // ---------------------------------------------------------------
        static::restoring(function (Order $order) {
            // a) Restore semua OrderItem
            $order->items()->withTrashed()->restore();

            // b) Jika status saat ini bukan 'cancelled', kurangi stok kembali
            if ($order->status !== 'canceled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        if (! $product->hasEnoughStock($item->quantity)) {
                            throw new \Exception(
                                "Stok tidak cukup untuk memulihkan order. " .
                                "Produk: “{$product->name}”. Stok tersedia: {$product->stock}"
                            );
                        }
                        $product->reduceStock($item->quantity);
                    }
                }
            }
        });
    }

    /**
     * Hitung ulang dan simpan total_price berdasarkan semua OrderItem.
     */
    public function calculateTotalPrice(): float
    {
        $total = $this->items()
                      ->get()
                      ->sum(fn($item) => $item->price * $item->quantity);

        if ((float)$this->total_price !== (float)$total) {
            $this->forceFill(['total_price' => $total])->saveQuietly();
        }

        return $total;
    }
}
