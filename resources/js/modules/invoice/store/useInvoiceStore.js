import { defineStore } from 'pinia';
import { ref, watch } from 'vue';
import { ApiService } from '../../../infrastructure/api/ApiService';
import { useToast } from 'primevue';
import { useRouter } from 'vue-router';
export const useInvoiceStore = defineStore(
    'Invoice',
    () => {
        const router = useRouter();
        const toast = useToast();

        const invoices = ref([]);
        const detailInvoice = ref({});

        const meta = ref({
            current_page: 1,
            from: 0,
            last_page: 0,
            links: [],
            path: '',
            per_page: 10,
            to: 0,
            total: 0
        });

        const page = ref(1);
        const per_page = ref(10);

        const rowsPerPageOptions = [5, 10, 20, 50];

        const keyword = ref('');
        const form = ref({});

        const formDialog = ref(false);
        const deleteDialog = ref(false);

        watch(keyword, (value) => {
            let length = value.length;
            if (length >= 3 || length == 0) {
                const params = {};
                keyword.value = value;
                keyword.value ? (params.search = keyword.value) : null;
                loadData(params);
            }
        });

        function hideDialog() {
            form.value = {};
            formDialog.value = false;
            deleteDialog.value = false;
        }

        function onDelete(data) {
            form.value = {
                id: data.id,
                invoice_number: data.invoice_number
            };
            detailInvoice.value = data;
            deleteDialog.value = true;
        }

        const loadData = (params) => {
            return new Promise((resolve, reject) => {
                ApiService.get(`api/invoices`, {
                    params: params
                })
                    .then(async (res) => {
                        invoices.value = res.data.data;
                        meta.value = res.data.meta;
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        };
        const getById = (id) => {
            return new Promise((resolve, reject) => {
                ApiService.get(`api/invoices/${id}`)
                    .then(async (res) => {
                        detailInvoice.value = res.data;
                        form.value = res.data;

                        const date = new Date(form.value.invoice_date);
                        form.value.invoice_date = date;

                        resolve(res);
                    })
                    .catch((err) => {
                        form.value = {};
                        reject(err);
                    });
            });
        };

        const store = () => {
            return new Promise((resolve, reject) => {
                ApiService.post(`api/invoices`, form.value)
                    .then(async (res) => {
                        toast.add({ severity: 'success', summary: 'Success', detail: 'Invoice Created', life: 5000 });
                        loadData({
                            page: page.value,
                            per_page: per_page.value
                        });
                        hideDialog();
                        router.push({ name: 'invoice' });
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        };

        const update = () => {
            return new Promise((resolve, reject) => {
                ApiService.put(`api/invoices/${form.value.id}`, form.value)
                    .then(async (res) => {
                        toast.add({ severity: 'success', summary: 'Success', detail: 'Invoice Updated', life: 5000 });
                        loadData({
                            search: keyword.value,
                            page: page.value,
                            per_page: per_page.value
                        });
                        hideDialog();
                        router.push({ name: 'invoice' });
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        };

        const destroy = (id) => {
            return new Promise((resolve, reject) => {
                ApiService.delete(`api/invoices/${id}`)
                    .then(async (res) => {
                        toast.add({ severity: 'success', summary: 'Success', detail: 'Invoice Deleted', life: 5000 });
                        hideDialog();
                        loadData({
                            search: keyword.value,
                            page: page.value,
                            per_page: per_page.value
                        });
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        };

        const downloadPdf = (data) => {
            return new Promise((resolve, reject) => {
                ApiService.get(`api/download-invoice/${data.id}`, { responseType: 'blob' })
                    .then(async (res) => {
                        const url = window.URL.createObjectURL(new Blob([res.data], { type: 'application/pdf' }));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', `${data.invoice_number}.pdf`);
                        document.body.appendChild(link);
                        link.click();
                        link.remove();

                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        };

        const onChangePage = (val) => {
            page.value = val.page + 1;
            per_page.value = val.rows;
            loadData({
                page: page.value,
                per_page: per_page.value
            });
        };

        return {
            keyword,
            invoices,
            detailInvoice,
            meta,
            rowsPerPageOptions,
            //
            form,
            formDialog,
            deleteDialog,

            page,
            per_page,

            // function
            loadData,
            getById,
            store,
            update,
            destroy,
            downloadPdf,
            //
            onDelete,
            hideDialog,
            onChangePage
        };
    },
    {
        persist: false
    }
);
