import '../css/app.css'; // Import your global CSS
import './bootstrap'; // Import Bootstrap (if needed)
import '@fontsource/poppins';

import { createApp, h } from 'vue'; // Vue 3
import { createInertiaApp } from '@inertiajs/vue3'; // Inertia.js for Vue 3
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'; // Resolve page components
import { ZiggyVue } from '../../vendor/tightenco/ziggy'; // Ziggy for route helpers

// Set the app name (fallback to 'Laravel' if not defined in .env)
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Initialize Inertia.js
createInertiaApp({
  // Set the page title (e.g., "Home - My App")
  title: (title) => `${title} - ${appName}`,

  // Resolve page components dynamically
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`, // Path to the page component
      import.meta.glob('./Pages/**/*.vue') // Glob to find all Vue files in Pages folder
    ),

  // Set up the Vue app
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin) // Use Inertia.js plugin
      .use(ZiggyVue) // Use Ziggy for route helpers
      .mount(el); // Mount the app to the element with id="app"
  },

  // Configure the progress bar (optional)
  progress: {
    color: '#4B5563', // Progress bar color
  },
});