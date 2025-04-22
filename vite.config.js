import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.js',
        'resources/css/app.css',
      ],
      refresh: [
        // Add all directories that need hot reload
        'resources/views/**',
        'app/**',
        'routes/**',
        'lang/**'
      ],
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
  server: {
    host: 'localhost',
    port: 3000,
    hmr: {
      host: 'localhost',
    },
    fs: {
      // More permissive but still secure settings
      strict: false, // Changed to false for Laravel compatibility
      deny: [
        '.env',
        '.env.*',
        '*.key',
      ],
      allow: [
        // Allow all project files
        process.cwd()
      ]
    }
  },
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        app: 'resources/js/app.js',
      },
    },
  },
});