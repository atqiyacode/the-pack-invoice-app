import AppLayout from '@/layout/AppLayout.vue';
import { createRouter, createWebHistory } from 'vue-router';
import { AuthRouter } from '../modules/auth/router/AuthRouter';
import { DashboardRouter } from '../modules/dashboard/router/DashboardRouter';
import { InvoiceRouter } from '../modules/invoice/router/InvoiceRouter';

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

export default router;
