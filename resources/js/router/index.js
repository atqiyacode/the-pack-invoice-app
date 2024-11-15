import AppLayout from '@/layout/AppLayout.vue';
import { createRouter, createWebHistory } from 'vue-router';
import { AuthRouter } from '../modules/auth/router/AuthRouter';
import { DashboardRouter } from '../modules/dashboard/router/DashboardRouter';
import { InvoiceRouter } from '../modules/invoice/router/InvoiceRouter';
import { useUserStore } from '../shared/store/useUserStore';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: AppLayout,
            children: [...DashboardRouter, ...InvoiceRouter]
        },

        ...AuthRouter
    ]
});

router.beforeEach((to, from, next) => {
    const UserStore = useUserStore();
    const nearestWithTitle = to.matched
        .slice()
        .reverse()
        .find((r) => r.meta && r.meta.title);

    if (nearestWithTitle) document.title = nearestWithTitle.meta.title;

    if (!UserStore.isLoggedIn && to.matched.some((record) => record.meta.requiresAuth)) {
        next({ name: 'login' });
    } else if (UserStore.isLoggedIn && to.name === 'login') {
        console.log('User already logged in, redirecting to home...');
        next({ name: 'dashboard' });
    } else {
        next();
    }
});

export default router;
