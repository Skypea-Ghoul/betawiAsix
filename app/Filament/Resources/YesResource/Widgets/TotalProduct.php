<?php

namespace App\Filament\Resources\YesResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Order;    // Import model Order Anda
use App\Models\Product;  // Import model Product Anda
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalProduct extends BaseWidget
{
    // protected static bool $isLazy = false;
    protected static ?string $pollingInterval = '10s';
   protected static ?int $sort = 2;

protected function getStats(): array
{
    $randomNumbers = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
    $randomNumbers1 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
    $randomNumbers2 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
    $randomNumbers3 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));

    $startOfDay = Carbon::now()->startOfDay();
    $endOfDay = Carbon::now()->endOfDay();

    $paidOrdersToday = Order::where('status', 'paid')
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->with('items.product')
        ->get();

    $sotoRevenueToday = 0;
    $sotoProductNames = ['Soto', 'Soto + Nasi'];

    $birPletokRevenueToday = 0;
    $birPletokProductName = 'Bir Pletok';

    $totalOverallRevenueToday = 0; // Semua total_price
    $totalRevenueWithDiscountToday = 0; // Semua grandtotal jika ada, jika tidak pakai total_price

    foreach ($paidOrdersToday as $order) {
        $totalOverallRevenueToday += $order->total_price;
        $totalRevenueWithDiscountToday += $order->grandtotal !== null ? $order->grandtotal : $order->total_price;

        foreach ($order->items as $item) {
            if ($item->product && in_array($item->product->name, $sotoProductNames)) {
                $sotoRevenueToday += ($item->quantity * $item->product->price);
            }
            if ($item->product && $item->product->name === $birPletokProductName) {
                $birPletokRevenueToday += ($item->quantity * $item->product->price);
            }
        }
    }

    $totalDiscountToday = $totalOverallRevenueToday - $totalRevenueWithDiscountToday;

    $formattedSotoRevenueToday = 'Rp ' . number_format($sotoRevenueToday, 0, ',', '.');
    $formattedBirPletokRevenueToday = 'Rp ' . number_format($birPletokRevenueToday, 0, ',', '.');
    $formattedTotalOverallRevenueToday = 'Rp ' . number_format($totalOverallRevenueToday, 0, ',', '.');
    $formattedTotalRevenueWithDiscountToday = 'Rp ' . number_format($totalRevenueWithDiscountToday, 0, ',', '.');
    $formattedTotalDiscountToday = 'Rp ' . number_format($totalDiscountToday, 0, ',', '.');

    return [
        Stat::make('Soto & Soto + Nasi', $formattedSotoRevenueToday)
            ->description('Pendapatan hari ini')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('success')
            ->chart($randomNumbers),

        Stat::make('Bir Pletok', $formattedBirPletokRevenueToday)
            ->description('Pendapatan hari ini')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('success')
            ->chart($randomNumbers1),

        Stat::make('Total Keseluruhan', $formattedTotalOverallRevenueToday)
            ->description('Semua total harga asli hari ini')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('primary')
            ->chart($randomNumbers2),

        Stat::make('Total Keseluruhan Dengan Diskon', $formattedTotalRevenueWithDiscountToday)
            ->description('Total setelah diskon')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('violet')
            ->chart($randomNumbers3),

    ];
}
}
