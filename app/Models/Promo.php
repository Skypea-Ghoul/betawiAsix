<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'diskon',
        'is_active',
    ];

     public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}
