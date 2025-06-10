<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        // -------------------------------------------------------------------
        // Saat OrderItem baru dibuat: kurangi stok
        // -------------------------------------------------------------------
        // static::creating(function (OrderItem $item) {
        //     if (! $item->price) {
        //         $item->price = $item->product->price;
        //     }
        //     $item->subtotal = $item->price * $item->quantity;

        //     if (! $item->product->hasEnoughStock($item->quantity)) {
        //         throw new \Exception("Stok tidak cukup untuk produk “{$item->product->name}”. Stok: {$item->product->stock}");
        //     }
        //     $item->product->reduceStock($item->quantity);
        // });

        // -------------------------------------------------------------------
        // Saat OrderItem diupdate: adjust stok sesuai perbedaan
        // -------------------------------------------------------------------
        static::updating(function (OrderItem $item) {
        $item->subtotal = $item->price * $item->quantity;
    });

        // -------------------------------------------------------------------
        // Saat OrderItem dihapus langsung (tidak lewat Order): kembalikan stok
        // -------------------------------------------------------------------
        static::deleting(function (OrderItem $item) {
            // Hanya jika memang delete langsung di OrderItem
            if (! $item->isForceDeleting()) {
                $item->product->increaseStock($item->quantity);
            }
        });

        // -------------------------------------------------------------------
        // Saat OrderItem direstore: kurangi kembali stok
        // -------------------------------------------------------------------
        static::restoring(function (OrderItem $item) {
            $product = $item->product;
            if ($product) {
                if (! $product->hasEnoughStock($item->quantity)) {
                    throw new \Exception("Stok tidak cukup untuk memulihkan item. Produk: “{$product->name}”. Stok: {$product->stock}");
                }
                $product->reduceStock($item->quantity);
            }
        });

        // Setelah pembuatan atau update, hitung ulang total di Order induk
        static::saved(function (OrderItem $item) {
            if ($item->order && ! $item->order->trashed()) {
                $item->order->calculateTotalPrice();
            }
        });

        // Setelah dihapus langsung, hitung ulang total di Order
        static::deleted(function (OrderItem $item) {
            if ($item->order && ! $item->order->trashed()) {
                $item->order->calculateTotalPrice();
            }
        });
    }
}
