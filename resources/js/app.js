import './bootstrap';
import './script.js';
if (document.body.classList.contains('filament-body')) {
  import('./echo-filament')
    .then(() => console.log('Echo-Filament loaded'))
    .catch(e => console.error('Failed to load Echo-Filament', e));
}
