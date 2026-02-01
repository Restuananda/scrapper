import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// Global components
import Button from './Components/Button.vue';
import Input from './Components/Input.vue';
import Card from './Components/Card.vue';
import Badge from './Components/Badge.vue';
import Modal from './Components/Modal.vue';
import Dropdown from './Components/Dropdown.vue';
import Avatar from './Components/Avatar.vue';
import LoadingSpinner from './Components/LoadingSpinner.vue';

createInertiaApp({
    title: (title) => `${title} - Shopee Intelligence Platform`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Head', Head)
            .component('Link', Link)
            .component('Button', Button)
            .component('Input', Input)
            .component('Card', Card)
            .component('Badge', Badge)
            .component('Modal', Modal)
            .component('Dropdown', Dropdown)
            .component('Avatar', Avatar)
            .component('LoadingSpinner', LoadingSpinner);

        // Global properties
        app.config.globalProperties.$formatPrice = (price) => {
            if (!price) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(price);
        };

        app.config.globalProperties.$formatNumber = (num) => {
            if (!num) return '0';
            if (num >= 1000000) return (num / 1000000).toFixed(1) + 'jt';
            if (num >= 1000) return (num / 1000).toFixed(1) + 'rb';
            return num.toString();
        };

        app.config.globalProperties.$formatDate = (date) => {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
            });
        };

        app.mount(el);
    },
    progress: {
        color: '#2c91ff',
        showSpinner: true,
    },
});
