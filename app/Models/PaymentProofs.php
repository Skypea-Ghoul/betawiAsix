<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProofs extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'proof_path',
        'status',
        'verified_by',
        'verified_at',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier()
{
    return $this->belongsTo(\App\Models\User::class, 'verified_by');
}
}
