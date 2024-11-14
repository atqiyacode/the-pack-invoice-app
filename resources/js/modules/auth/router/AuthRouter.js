const AuthRouter = [
    {
        path: '/login',
        name: 'login',
        component: () => import('../pages/LoginPage.vue'),
        meta: {
            title: 'login',
            requiresAuth: false
        }
    }
];

export { AuthRouter };
