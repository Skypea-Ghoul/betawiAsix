<?php

namespace App\Filament\Resources\YesResource\Widgets;

use Illuminate\Support\Facades\DB;
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
    $randomNumbers4 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
    $randomNumbers5 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));

    $startOfDay = Carbon::now()->startOfDay();
    $endOfDay = Carbon::now()->endOfDay();

    $paidOrdersToday = Order::where('status', 'paid')
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->with('items.product')
        ->get();

    $sotoOnlyRevenueToday = 0;
    $sotoNasiRevenueToday = 0;
    $birPletokRevenueToday = 0;

    $totalOverallRevenueToday = 0;
    $totalRevenueWithDiscountToday = 0;

    foreach ($paidOrdersToday as $order) {
        $totalOverallRevenueToday += $order->total_price;
        $totalRevenueWithDiscountToday += $order->grandtotal ?? $order->total_price;

        foreach ($order->items as $item) {
            if (!$item->product) continue;

            $productName = $item->product->name;
            $lineTotal = $item->quantity * $item->product->price;

            if ($productName === 'Soto') {
                $sotoOnlyRevenueToday += $lineTotal;
            } elseif ($productName === 'Soto + Nasi') {
                $sotoNasiRevenueToday += $lineTotal;
            } elseif ($productName === 'Bir Pletok') {
                $birPletokRevenueToday += $lineTotal;
            }
        }
    }

    $totalDiscountToday = $totalOverallRevenueToday - $totalRevenueWithDiscountToday;

    // ✅ Tambahkan total dari pesanan pending
    $pendingTotal = Order::where('status', 'pending')
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->sum(DB::raw('COALESCE(grandtotal, total_price)'));

    return [
        Stat::make('Soto', 'Rp ' . number_format($sotoOnlyRevenueToday, 0, ',', '.'))
            ->description('Pendapatan hari ini')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('success')
            ->chart($randomNumbers),

        Stat::make('Soto + Nasi', 'Rp ' . number_format($sotoNasiRevenueToday, 0, ',', '.'))
            ->description('Pendapatan hari ini')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('success')
            ->chart($randomNumbers1),

        Stat::make('Bir Pletok', 'Rp ' . number_format($birPletokRevenueToday, 0, ',', '.'))
            ->description('Pendapatan hari ini')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('success')
            ->chart($randomNumbers2),

        Stat::make('Total Keseluruhan', 'Rp ' . number_format($totalOverallRevenueToday, 0, ',', '.'))
            ->description('Semua total harga asli hari ini')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('primary')
            ->chart($randomNumbers3),

        Stat::make('Total Dengan Diskon', 'Rp ' . number_format($totalRevenueWithDiscountToday, 0, ',', '.'))
            ->description('Total setelah diskon')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('violet')
            ->chart($randomNumbers4),

        Stat::make('Total Pending', 'Rp ' . number_format($pendingTotal, 0, ',', '.'))
            ->description('Total pending hari ini')
            ->descriptionIcon('heroicon-s-clock')
            ->color('warning')
            ->chart($randomNumbers5),
    ];
}
}
