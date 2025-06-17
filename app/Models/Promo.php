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

	protected $casts = [

    'is_active' => 'boolean', // <-- Pastikan ini jika memang diperlukan casting eksplisit

	];

     public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}
