<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

protected function afterCreate(): void
{
    foreach ($this->record->items as $item) {
        $product = $item->product;
        if ($product && $product->stock >= $item->quantity) {
            $product->decrement('stock', $item->quantity);
        }
    }
}	

}
