<?php

namespace App\Http\Controllers;

use App\Models\PaymentProofs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentProofController extends Controller
{
    public function create($orderId)
    {
        return view('payment_proofs.create', ['orderId' => $orderId]);
    }

public function store(Request $request)
{
    $request->validate([
        'order_id'    => 'required|exists:orders,id',
        'proof_files' => 'required|array|min:1',
        'proof_files.*' => 'image|max:2048',
    ]);

    $paths = [];
    foreach ($request->file('proof_files') as $file) {
        $paths[] = $file->store('payment_proofs', 'public');
    }

    \App\Models\PaymentProofs::create([
        'order_id'   => $request->order_id,
        'user_id'    => auth()->id(),
        'amount'     => 0, // Atur sesuai kebutuhan
        'proof_path' => json_encode($paths), // Simpan array path sebagai JSON
        'status'     => 'pending',
    ]);

    return back()->with('payment_proof_path', $paths)->with('success', 'Bukti pembayaran berhasil diupload!');
}
}
