import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Pastikan Pusher tersedia secara global di window
window.Pusher = Pusher;

// Inisialisasi Laravel Echo dengan konfigurasi Pusher Anda
window.Echo = new Echo({
    broadcaster: 'pusher',
    // Menggunakan variabel lingkungan dari Vite (VITE_PUSHER_APP_KEY, VITE_PUSHER_APP_CLUSTER)
    // Pastikan ini terdefinisi dengan benar di file .env Anda dan dipublikasikan melalui Vite.
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true // Gunakan HTTPS untuk koneksi WebSocket
});

window.Echo.channel('orders')
    .listen('.OrderCreated', (e) => {
        console.log('OrderCreated received:', e);
        // Refresh all Filament tables on the page
        // This sends a Livewire event to trigger table re-render
        if (window.Filament && window.Filament.tables) {
            window.Filament.tables.forEach(table => {
                table.refresh();
            });
        } else {
            // Fallback for development/testing if Filament tables object isn't immediately available
            console.warn('Filament tables object not found for OrderCreated. Reloading page as fallback.');
            location.reload();
        }
    })
    .listen('.OrderUpdated', (e) => {
        console.log('OrderUpdated received:', e);
        // Refresh all Filament tables on the page
        if (window.Filament && window.Filament.tables) {
            window.Filament.tables.forEach(table => {
                table.refresh();
            });
        }
    });

// Make sure this is loaded if the body has 'filament-body' class
if (document.body.classList.contains('filament-body')) {
    import('./echo-filament')
        .then(() => console.log('Echo-Filament loaded'))
        .catch(e => console.error('Failed to load Echo-Filament', e));
}
