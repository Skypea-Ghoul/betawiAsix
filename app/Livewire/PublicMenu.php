<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth; // Use Auth facade
use Livewire\Attributes\On;

class PublicMenu extends Component
{
    public $products;

    public function mount()
    {
        $this->loadAllProducts();
    }

    #[On('productAddedToCart')] // Not strictly needed if addToCart is called directly
    public function updateProductsAfterCartAction()
    {
        $this->loadAllProducts();
    }

    // New method to handle adding to cart via Livewire
    public function addToCart($productId)
    {
        $user = Auth::user(); // Use Auth facade

        if (!$user) {
            // Dispatch a browser event for toast notification for unauthenticated users
            $this->dispatch('show-toast', type: 'error', message: 'Anda harus login terlebih dahulu.');
            // Or redirect, depending on desired UX:
            // return redirect()->route('login');
            return;
        }

        $product = Product::findOrFail($productId);
        $quantityToAdd = 1; // Assuming you add one product at a time from the menu page

        // Find the specific cart item for the current user and product
        // Use firstOrNew for efficiency here as well
        $cartItem = $user->cartItems()->where('product_id', $productId)->first();

        $currentQuantityInCart = $cartItem ? $cartItem->quantity : 0;
        $newQuantityInCart = $currentQuantityInCart + $quantityToAdd;

        // Check if adding one more product would exceed the product's available stock
        if ($newQuantityInCart > $product->stock) {
            $this->dispatch('show-toast', type: 'error', message: "Stok produk '{$product->name}' tidak mencukupi. Tersisa: {$product->stock}.");
            return;
        }

        // If cart item exists, update its quantity; otherwise, create a new one
        if ($cartItem) {
            $cartItem->quantity = $newQuantityInCart;
            $cartItem->save();
        } else {
            Auth::user()->cartItems()->create([
                'product_id' => $productId,
                'quantity' => $newQuantityInCart,
            ]);
        }

        // Re-load products to immediately reflect updated stock
        $this->loadAllProducts();

        // Dispatch a browser event to update the cart count in other parts of the application
        $cartCount = Auth::user()->cartItems()->sum('quantity');
        $this->dispatch('cart-updated', cartCount: $cartCount);
        $this->dispatch('show-toast', type: 'success', message: "Produk '{$product->name}' berhasil ditambahkan ke keranjang.");
    }

    // This is the key change for N+1 optimization
    private function loadAllProducts()
    {
        // Eager load `cartItems` relationship, but constrain it to the current user
        // This ensures only the current user's cart items are loaded with products,
        // preventing N+1 when checking for `quantityInCart` later.
        $this->products = Product::with(['users', 'orderItems']) // Keep other eager loads
            ->with(['cartItems' => function ($query) {
                // Only load cart items belonging to the authenticated user
                $query->where('user_id', Auth::id());
            }])
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.public-menu', [
            'products' => $this->products,
        ]);
    }
}