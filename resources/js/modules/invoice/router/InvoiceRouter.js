const InvoiceRouter = [
    {
        path: '/invoice',
        name: 'invoice',
        component: () => import('../pages/InvoicePage.vue'),
        meta: {
            title: 'invoice',
            requiresAuth: true
        }
    }
];

export { InvoiceRouter };
