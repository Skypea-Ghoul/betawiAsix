<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function check(Request $request)
{
    $code = $request->query('code');
    $promo = \App\Models\Promo::where('kode', $code)->where('is_active', true)->first();
    if ($promo) {
        return response()->json(['success' => true, 'promo' => $promo]);
    }
    return response()->json(['success' => false, 'message' => 'Kode promo tidak valid atau sudah dipakai.']);
    
}
}
