import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { $notify } from "@/config/notify.js";
import { $server } from "@/config/axios.js";
import { errors } from "@/config/helpers.js";
import { setupI18n, __ } from "@/config/locale.js";
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

setupI18n();
window.__ = __;
window.$server = $server;
window.$errors = errors;
window.$notify = $notify

createInertiaApp({
  nonce: document.querySelector('meta[name="nonce"]')?.getAttribute('content'),
  http: $server,
  resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue')),
  setup({ el, App, props, plugin }) {

    const app = createApp({ render: () => h(App, props) });

    app.config.globalProperties.$server = $server;
    app.config.globalProperties.$notify = $notify;
    app.config.globalProperties.$errors = $errors;
    app.config.globalProperties.__ = __;

    app.use(plugin);
    app.mount(el);
  },
});
