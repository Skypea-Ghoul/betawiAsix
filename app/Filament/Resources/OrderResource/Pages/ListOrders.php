<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Tentukan listener Livewire untuk event broadcast.
     * Livewire akan secara otomatis mendengarkan event yang di-broadcast
     * jika format string key-nya benar: 'echo-{nama_channel}:{nama_event_broadcastAs}'.
     *
     * @return array
     */
    public function getListeners(): array
    {
        // Pastikan nama channel ('orders') dan nama event ('OrderCreated', 'OrderUpdated')
        // sama persis dengan yang Anda definisikan di kelas event PHP.
        return [
            'echo-orders:OrderCreated' => 'refreshTable',
            'echo-orders:OrderUpdated' => 'refreshTable',
        ];
    }

    /**
     * Metode untuk me-refresh tabel Filament.
     * Ini akan memicu panggilan AJAX Livewire untuk mengambil data tabel terbaru.
     */
    public function refreshTable(): void
    {
        // Ini adalah event internal Livewire yang didengarkan oleh tabel Filament
        // untuk memuat ulang datanya.
        $this->dispatch('filament-tables::refresh');
    }
}
