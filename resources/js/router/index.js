import AppLayout from '@/layout/AppLayout.vue';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: AppLayout,
            children: [
                {
                    path: '/',
                    name: 'dashboard',
                    component: () => import('@/views/pages/DashboardPage.vue')
                },
                {
                    path: '/pages/invoice',
                    name: 'invoice',
                    component: () => import('@/views/pages/invoice/InvoicePage.vue')
                }
            ]
        },

        {
            path: '/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        }
    ]
});

export default router;
