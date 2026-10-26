import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/situs.css', 'resources/js/situs.js'],
      // Komponen Livewire dan Blade situs publik; panel admin punya aset Filament sendiri.
      refresh: ['resources/views/**', 'app/Livewire/**'],
    }),
    tailwindcss(),
  ],
});
