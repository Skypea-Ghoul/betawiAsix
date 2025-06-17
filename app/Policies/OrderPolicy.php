<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
     public function before(User $user, $ability)
    {
        // If the user is an admin, allow all actions
        if ($user->role === 'admin') {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'staff';
        // return false; 
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->role === 'staff') {
            // Cek apakah salah satu orderItem produk di assign ke staff
            return $order->items()
                         ->whereHas('product.users', function ($q) use ($user) {
                             $q->where('user_id', $user->id);
                         })
                         ->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
          return $user->role === 'staff';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
            if ($user->role === 'staff') {
            return $order->items()
                         ->whereHas('product.users', function ($q) use ($user) {
                             $q->where('user_id', $user->id);
                         })
                         ->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        if ($user->role === 'staff') {
            return $order->items()
                         ->whereHas('product.users', function ($q) use ($user) {
                             $q->where('user_id', $user->id);
                         })
                         ->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}
