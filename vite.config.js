import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.js', // Main JS entry point
        'resources/css/app.css',
      ],
      refresh: true, // Enable hot reloading
    }),
    vue({
      template: {
        transformAssetUrls: {
          // Ensure Vue handles asset URLs correctly
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  resolve: {
    alias: {
      // Add aliases for easier imports
      '@': '/resources/js', // Use @ to refer to resources/js
    },
  },
  server: {
    // Configure the development server
    host: 'localhost', // Set the host
    port: 3000, // Set the port (optional)
    hmr: {
      host: 'localhost', // Enable Hot Module Replacement (HMR)
    },
  },
  build: {
    // Configure the production build
    outDir: 'public/build', // Output directory for production assets
    emptyOutDir: true, // Clear the output directory before building
    manifest: true, // Generate a manifest file for asset versioning
  },
});