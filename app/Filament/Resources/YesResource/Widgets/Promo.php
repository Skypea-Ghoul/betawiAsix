<?php

namespace App\Filament\Resources\YesResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Promo extends BaseWidget
{

   protected static ?int $sort = 3;
//     protected int | string | array $columnSpan = 'full';
// protected static bool $isLazy = false;
    protected static ?string $pollingInterval = '10s';
    // protected static int $columns = 2;
    protected int | string | array $columnSpan = 'full';


    protected function getStats(): array
    {
        $randomNumbers = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
        $randomNumbers1 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));

        // Jumlah promo yang sudah tidak aktif
        $inactivePromoCount = \App\Models\Promo::where('is_active', false)->count();

        // Ambil semua order yang menggunakan promo
        $ordersWithPromo = \App\Models\Order::whereNotNull('promo_id')->get();

        // Hitung total diskon dari semua order yang menggunakan promo
        $totalDiscount = 0;
        foreach ($ordersWithPromo as $order) {
            if ($order->total_price !== null && $order->grandtotal !== null) {
                $totalDiscount += ($order->total_price - $order->grandtotal);
            }
        }

        $formattedTotalDiscount = 'Rp ' . number_format($totalDiscount, 0, ',', '.');

        return [
            Stat::make('Promo Tidak Aktif', $inactivePromoCount)
                ->description('Jumlah promo yang sudah dipakai')
                ->descriptionIcon('heroicon-s-x-circle')
                ->color('rose')
                  ->chart($randomNumbers),

            Stat::make('Total Diskon dari Promo', $formattedTotalDiscount)
                ->description('Akumulasi diskon dari semua order yang memakai promo')
                ->descriptionIcon('heroicon-s-ticket')
                ->color('fuchsia')
                  ->chart($randomNumbers1),
        ];
    }

    

}