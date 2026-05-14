import './bootstrap';
import '../css/app.css';
import monkeyBrainUrl from '../images/monkey-brain.png.webp';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
void monkeyBrainUrl;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const VueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy);

        // config global property after createApp and before mount
        //   VueApp.config.globalProperties.revealed = false;

        VueApp.mount(el);

        return VueApp;
    },
    progress: {
        color: '#4B5563'
    }
});
