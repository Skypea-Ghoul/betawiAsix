<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];

    /**
     * Relasi many-to-many ke staff/user yang menangani produk ini.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'product_user', 'product_id', 'user_id');
    }

      public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Relasi one-to-many ke OrderItem.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Kurangi stok produk.
     */
    public function reduceStock(int $quantity): bool
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Tambah stok produk.
     */
    public function increaseStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    /**
     * Cek apakah stok mencukupi untuk kuantitas tertentu.
     */
    public function hasEnoughStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }
}
