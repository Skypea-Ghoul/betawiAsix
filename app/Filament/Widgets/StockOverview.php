<?php

namespace App\Filament\Widgets; // Pastikan namespace ini benar, biasanya di App\Filament\Widgets

use App\Models\Order;   // Import model Order
use App\Models\Product; // Import model Product
use Carbon\Carbon;      // Import Carbon untuk manipulasi tanggal
use Filament\Widgets\StatsOverviewWidget as BaseWidget; // Menggunakan BaseWidget (StatsOverviewWidget)
use Filament\Widgets\StatsOverviewWidget\Stat; // Menggunakan Stat

class StockOverview extends BaseWidget
{
    // protected static ?string $heading = 'Ringkasan Stok Produk'; // Judul ini akan muncul di atas kumpulan stat
// protected static bool $isLazy = false;
    protected static ?string $pollingInterval = '10s';
   protected static ?int $sort = 1;


 protected function getStats(): array
    {
                $randomNumbers = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
        $randomNumbers1 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
        $randomNumbers2 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
        $randomNumbers3 = array_map(fn() => rand(1, 20), array_fill(0, 9, null));
        // Total stok semua produk
        $currentStock = Product::sum('stock');
        $formattedCurrentStock = number_format($currentStock, 0, ',', '.') . ' Unit';

        // Produk habis
        $outOfStockCount = Product::where('stock', 0)->count();

        // Hari ini
        $startOfDay = Carbon::today();
        $endOfDay = Carbon::today()->endOfDay();

        // Ambil semua order paid hari ini beserta itemnya
        $paidOrdersToday = Order::where('status', 'paid')
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with('items')
            ->get();

        // Hitung Soto & Soto + Nasi terjual
        $sotoIds = Product::whereIn('name', ['Soto', 'Soto + Nasi'])->pluck('id')->toArray();
        $sotoNasiSoldToday = 0;
        $birPletokSoldToday = 0;
        $birPletokId = Product::where('name', 'Bir Pletok')->value('id');

        foreach ($paidOrdersToday as $order) {
            foreach ($order->items as $item) {
                if (in_array($item->product_id, $sotoIds)) {
                    $sotoNasiSoldToday += $item->quantity;
                }
                if ($birPletokId && $item->product_id == $birPletokId) {
                    $birPletokSoldToday += $item->quantity;
                }
            }
        }

        return [
            Stat::make('Total Stok Tersedia', $formattedCurrentStock)
                ->description('Total dari semua produk')
                ->descriptionIcon('heroicon-s-cube')
                ->color('neutral')
                ->chart($randomNumbers),

            Stat::make('Soto & Soto + Nasi Terjual Hari Ini', number_format($sotoNasiSoldToday, 0, ',', '.') . ' Unit')
                ->description('Dari pesanan yang sudah dibayar hari ini')
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('sky')
                ->chart($randomNumbers1),

            Stat::make('Bir Pletok Terjual Hari Ini', number_format($birPletokSoldToday, 0, ',', '.') . ' Unit')
                ->description('Dari pesanan yang sudah dibayar hari ini')
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('sky')
                    ->chart($randomNumbers2),

            Stat::make('Produk Habis', $outOfStockCount . ' Produk')
                ->description('Produk dengan stok kosong')
                ->descriptionIcon('heroicon-s-exclamation-triangle')
                ->color('danger')
                ->chart($randomNumbers3),
        ];
    }
}