import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'
import path from 'path'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/filament/admin/theme.css', // Tetap sertakan jika ini file CSS asli
      ],
      refresh: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/views/**/*.blade.php', // Pindahkan ke sini untuk auto-refresh
        'app/Filament/**', // Tambahkan ini agar Filament v4.3 merespons perubahan kode
      ],
    }),
    tailwindcss(),
  ],
  server: {
    fs: {
      allow: [
        path.resolve(__dirname),
        path.resolve(__dirname, 'vendor'),
      ],
    },
  },
})