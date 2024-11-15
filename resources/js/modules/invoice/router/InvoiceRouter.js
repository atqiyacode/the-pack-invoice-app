const InvoiceRouter = [
    {
        path: '/invoice',
        name: 'invoice',
        component: () => import('../pages/InvoicePage.vue'),
        meta: {
            title: 'invoice',
            requiresAuth: true
        }
    },
    {
        path: '/invoice/create',
        name: 'invoice-create',
        component: () => import('../pages/InvoiceFormPage.vue'),
        meta: {
            title: 'invoice-create',
            requiresAuth: true
        }
    },
    {
        path: '/invoice/detail/:id',
        name: 'invoice-detail',
        component: () => import('../pages/InvoiceDetailPage.vue'),
        meta: {
            title: 'invoice-detail',
            requiresAuth: true
        }
    },
    {
        path: '/invoice/edit/:id',
        name: 'invoice-edit',
        component: () => import('../pages/InvoiceFormPage.vue'),
        meta: {
            title: 'invoice-edit',
            requiresAuth: true
        }
    }
];

export { InvoiceRouter };
