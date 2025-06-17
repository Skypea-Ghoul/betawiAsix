{{-- @php
    $cartItems = $cartItems ?? [];
    $totalPrice = $totalPrice ?? 0;
@endphp --}}

<div id="cart-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="cart-modal-title">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[80vh] overflow-hidden flex flex-col">
        <div class="flex justify-between items-center border-b p-4">
            <h3 id="cart-modal-title" class="text-xl font-bold text-gray-800">Keranjang Belanja</h3>
            <button id="close-cart" class="text-gray-500 hover:text-gray-700" aria-label="Tutup keranjang">
                <i class="fas fa-times"></i>
            </button>
        </div>

      <div id="cart-view-container">
    <div class="p-4 overflow-y-auto max-h-[60vh]">
        <div id="cart-items" class="space-y-4">
            @if(count($cartItems) > 0)
              @foreach($cartItems as $item)
    <div class="flex justify-between items-start border-b pb-4 summary-item-row"
        data-product-id="{{ $item['id'] }}"
        data-quantity="{{ $item['quantity'] }}"
        data-price="{{ $item['price'] }}"
        data-max="{{ $item['stock']}}">
        <div class="flex items-start">
            <img src="{{ $item['image'] ?? '/image/default-product.jpg' }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded-md mr-4">
           <div>
    <h4 class="font-bold text-gray-800">{{ $item['name'] }}</h4>
    <p class="text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
    <p class="text-gray-600">Jumlah: {{ $item['quantity'] }}</p>
    <p class="text-xs text-amber-600 font-semibold">
           Stok: {{ $item['stock'] ?? $item['max'] ?? 0 }}
    </p>
</div>
        </div>
        <div class="flex flex-col items-end">
            <p class="text-gray-800 font-semibold">
                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
            </p>
        </div>
    </div>
@endforeach
            @endif
        </div>
        
        <div id="empty-cart-message" class="text-center py-8 text-gray-500" style="display: {{ count($cartItems) > 0 ? 'none' : 'block' }}">
            <i class="fas fa-shopping-cart text-4xl mb-3"></i>
            <p>Keranjang belanja Anda kosong</p>
            <a href="#menu" class="mt-4 inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                Lihat Menu
            </a>
        </div>
    </div>
    
   <div class="border-t p-4">
    <div class="flex justify-between mb-4">
        <span class="font-bold text-gray-700">Subtotal:</span>
      <span id="cart-total" class="font-bold text-amber-600">
    Rp {{ number_format($totalPrice, 0, ',', '.') }}
</span>
    </div>
    <button
        id="proceed-to-payment-step-btn"
        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-full transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
        {{ count($cartItems) > 0 ? '' : 'disabled' }}
    >
        Lanjutkan ke Pembayaran
    </button>
</div>

</div>

        <div id="payment-view-container" class="hidden flex-grow overflow-y-auto">
            <div class="p-4">
                <h4 class="text-lg font-bold mb-4 text-gray-800">Ringkasan Pesanan Anda</h4>

                <div id="order-details-summary" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h5 class="font-semibold text-gray-700 mb-3">Item Pesanan:</h5>
                    <div id="summary-order-items-list" class="space-y-2">
                         <div class="summary-item-row" data-product-id="1" data-quantity="8" data-price="15000">
        <p>Soto</p>
        <p>Jumlah: 8</p>
    </div>
                    </div>
                  @if($hasActivePromo ?? false)
                    <div id="promo-section" class="mb-4">
                        <label for="promo-code" class="block text-gray-700 font-semibold mb-2">Kode Promo</label>
                            <div class="flex gap-2">
                            <input type="text" id="promo-code" class="border rounded px-3 py-2 w-full" placeholder="Masukkan kode promo">
                            <button id="apply-promo-btn" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 rounded">Pakai</button>
                            </div>
                        <p id="promo-message" class="text-sm mt-2"></p>
                    </div>
                @endif

                    <div class="border-t pt-2 mt-4">
                        <p class="text-right text-lg font-bold text-gray-800">
                            Total Harga: <span id="summary-total-price">Rp 0</span>
                        </p>
                        <p id="grandtotal-summary" class="text-right text-lg font-bold text-amber-600 mt-2"></p>
                    </div>
                </div>

                <div class="mb-4" id="qris-section">
                    
                </div>
   <form id="order-form"
      action="{{ route('order.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="items" id="order-items-json">
    <input type="text" name="promo_code" id="promo-code-input" hidden>
    <div class="mb-2">
        <label class="block text-gray-700 font-semibold mb-1">Upload Bukti Pembayaran (bisa lebih dari 1 gambar, jpg/png, max 2MB per file)</label>
        <input type="file" name="proof_files[]" accept="image/*" class="w-full border rounded p-2" multiple required>
    </div>
    {{-- <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mt-2">
        Konfirmasi Pesanan & Upload Bukti
    </button> --}}
</form>
<small class="text-gray-500">
    Upload <b>1 bukti pembayaran untuk setiap QRIS</b> yang muncul. Jika ada 2 QRIS, upload 2 foto.
</small>
@if(session('payment_proof_path'))
    <div class="mt-4">
        <h4 class="font-semibold mb-2">Bukti Pembayaran:</h4>
        @foreach(session('payment_proof_path') as $img)
            <img src="{{ asset('storage/' . $img) }}" alt="Bukti Pembayaran" class="w-40 rounded shadow mb-2">
        @endforeach
    </div>
@endif
            </div>
            
            <div class="border-t p-4 flex justify-between">
                <button id="back-to-cart-btn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-full transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Keranjang
                </button>
                <button id="place-order-btn" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
	  <span class="spinner" style="visibility: hidden;"></span>
  <span class="btn-text">Konfirmasi Pesanan</span>              
  </button>
            </div>
        </div>
    </div>
</div>

<div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="success-modal-title">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 text-center">
        <div class="text-green-500 text-6xl mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 id="success-modal-title" class="text-2xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h3>
        <p class="text-gray-600 mb-6">Terima kasih atas pesanan Anda. Kami akan segera memprosesnya.</p>
        <button id="close-success" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-6 rounded-full transition duration-300" aria-label="Tutup notifikasi sukses">
            Tutup
        </button>
    </div>
</div>

<div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-96 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Login</h2>
            <button id="close-login" class="text-gray-500 hover:text-gray-800">&times;</button>
        </div>
        <p class="text-center text-gray-700 mb-4">
            Silakan login menggunakan akun Google Anda.
        </p>
        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded transition mt-2">
            <i class="fab fa-google"></i> Login dengan Google
        </a>
        <p class="mt-3 text-sm text-gray-600 text-center">
            Belum punya akun?
            <a href="#" id="login-to-register-link" class="text-amber-500 hover:text-amber-600 font-bold">Daftar di sini</a>
        </p>
    </div>
</div>

<div id="register-modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4 hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm relative shadow-xl">
        <button id="close-register" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Daftar Akun Baru</h2>
        <p class="text-center text-gray-700 mb-4">
            Untuk mendaftar, silakan gunakan akun Google Anda.
        </p>
        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded transition mt-2">
            <i class="fab fa-google"></i> Daftar & Login dengan Google
        </a>
        <p class="text-center text-gray-600 text-sm mt-4">
            Sudah punya akun? <a href="#" id="register-to-login-link" class="text-amber-500 hover:text-amber-600 font-bold">Login di sini</a>
        </p>
    </div>
</div>
