const DashboardRouter = [
    {
        path: '/',
        name: 'dashboard',
        component: () => import('../pages/DashboardPage.vue'),
        meta: {
            title: 'dashboard',
            requiresAuth: true
        }
    }
];

export { DashboardRouter };
