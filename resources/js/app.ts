import '../css/app.css';
import 'leaflet/dist/leaflet.css'; // <-- Leaflet CSS

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

// ⬇️ Leaflet components
import { LMap, LTileLayer, LGeoJson } from '@vue-leaflet/vue-leaflet';

// ⬇️ Import Pinia
import { createPinia } from 'pinia';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        // ⬇️ Initialize Pinia before mounting the app
        vueApp.use(createPinia());

        vueApp.use(plugin).use(ZiggyVue);

        // ⬇️ Register Leaflet components globally
        vueApp.component('l-map', LMap);
        vueApp.component('l-tile-layer', LTileLayer);
        vueApp.component('l-geo-json', LGeoJson);

        vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// ⬇️ Set light / dark mode
initializeTheme();
