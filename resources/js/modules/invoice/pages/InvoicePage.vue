<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import { useInvoiceStore } from '../store/useInvoiceStore';
import { storeToRefs } from 'pinia';

const InvoiceStore = useInvoiceStore();

const { meta, invoices, detailInvoice, deleteDialog, keyword, rowsPerPageOptions } = storeToRefs(InvoiceStore);

const { loadData, onDelete, hideDialog, onChangePage, destroy } = InvoiceStore;

const label = 'Invoice';

onBeforeMount(() => {
    keyword.value = '';
    hideDialog();
});

onMounted(() => {
    loadData();
});
const showTemplate = () => {
    toast.add({ severity: 'success', summary: 'Can you send me the report?', group: 'bc' });
};
</script>

<template>
    <div className="card">
        <Toolbar class="mb-6">
            <template #start>
                <Button label="New" icon="pi pi-plus" severity="secondary" class="mr-2" @click="$router.push({ name: 'invoice-create' })" />
            </template>
            <template #center>
                <div class="font-semibold text-xl mb-4">Invoice Page</div>
            </template>
            <template #end>
                <div class="flex justify-end">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="keyword" placeholder="Keyword Search" />
                    </IconField>
                </div>
            </template>
        </Toolbar>

        <DataTable showGridlines stripedRows :value="invoices">
            <template #empty>
                <div class="flex flex-column md:flex-row md:justify-content-center md:align-items-center mb-3">
                    <h5 class="m-0 text-red-600">Data Not Found</h5>
                </div>
            </template>

            <Column field="invoice_number" header="invoice_number"></Column>
            <Column field="invoice_date_formatted" header="invoice_date_formatted"></Column>
            <Column field="client_name" header="client_name"></Column>
            <Column field="items_count" header="items_count"></Column>
            <Column field="subtotal" header="subtotal"></Column>
            <Column field="grand_total" header="grand_total"></Column>

            <Column :exportable="false" header="Action">
                <template #body="slotProps">
                    <Button icon="pi pi-eye" outlined rounded severity="info" class="mr-2" @click="$router.push({ name: 'invoice-detail', params: { id: slotProps.data.id } })" />
                    <Button icon="pi pi-pencil" outlined rounded severity="warn" class="mr-2" @click="$router.push({ name: 'invoice-edit', params: { id: slotProps.data.id } })" />
                    <Button icon="pi pi-trash" outlined rounded severity="danger" @click="onDelete(slotProps.data)" />
                </template>
            </Column>
        </DataTable>

        <Paginator v-if="meta?.total > 1" :first="meta.to - 1" :rows="parseInt(meta.per_page)" :totalRecords="meta.total" :rowsPerPageOptions="rowsPerPageOptions" @page="onChangePage"></Paginator>
    </div>

    <Dialog v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span v-if="detailInvoice">
                Are you sure you want to delete
                <b>Invoice: {{ detailInvoice.invoice_number }}</b>
                ?
            </span>
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="hideDialog" />
            <Button label="Yes" icon="pi pi-check" @click="destroy(detailInvoice.id)" />
        </template>
    </Dialog>
</template>
