@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Upload Bukti Pembayaran</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <form action="{{ route('payment-proof.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order_id" value="{{ $orderId }}">
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Jumlah Pembayaran</label>
            <input type="number" name="amount" class="w-full border rounded p-2" required min="1">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Upload Bukti (jpg/png, max 2MB)</label>
            <input type="file" name="proof_file" accept="image/*" class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-amber-600 text-white px-6 py-2 rounded hover:bg-amber-700">Kirim Bukti</button>
    </form>
</div>
@endsection