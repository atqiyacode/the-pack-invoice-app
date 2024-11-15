import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

import Aura from '@primevue/themes/aura';
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';

import '@/assets/styles.scss';
import '@/assets/tailwind.css';

import { createPinia } from 'pinia';
import piniaPluginPersistedstate, { createPersistedState } from 'pinia-plugin-persistedstate';
import { useUserStore } from './shared/store/useUserStore';

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

(async () => {
    const app = createApp(App);

    app.use(router);
    app.use(PrimeVue, {
        theme: {
            preset: Aura,
            options: {
                darkModeSelector: '.app-dark'
            }
        }
    });
    app.use(ToastService);
    app.use(ConfirmationService);

    app.use(pinia);

    const UserStore = useUserStore();
    await UserStore.getSession();

    app.mount('#app');
})();
