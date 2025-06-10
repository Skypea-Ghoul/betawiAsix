<section id="menu" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Menu Spesial Kami</h2>
            <div class="w-20 h-1 bg-amber-500 mx-auto"></div>
        </div>

        <div id="menu-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($products as $product)
                {{-- Tambahkan kelas opacity dan cursor-not-allowed jika stok 0 --}}
                <div class="food-card bg-white rounded-xl overflow-hidden shadow-md transition duration-300
                    @if($product->stock === 0) opacity-60 cursor-not-allowed @endif">
                    {{-- Periksa apakah gambar ada, jika tidak, gunakan placeholder --}}
                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/500x300?text=' . urlencode($product->name) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-amber-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            {{-- LOGIKA KONDISIONAL UNTUK STOK --}}
                            @if($product->stock > 0)
                                <button class="add-to-cart bg-amber-500 hover:bg-amber-600 text-white py-2 px-4 rounded-full transition duration-300"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}"
                                        data-max="{{ $product->stock }}">
                                    + Tambahkan
                                </button>
                            @else
                                {{-- Jika stok 0, tampilkan tombol "Stock Habis" dan nonaktifkan --}}
                                <button class="bg-gray-400 text-white py-2 px-4 rounded-full cursor-not-allowed" disabled>
                                    Stock Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">Tidak ada menu yang tersedia.</p>
            @endforelse
        </div>
    </div>
</section>