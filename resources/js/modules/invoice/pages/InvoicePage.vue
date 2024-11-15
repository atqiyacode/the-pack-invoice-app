<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import { useInvoiceStore } from '../store/useInvoiceStore';
import { storeToRefs } from 'pinia';
import { useGlobalStore } from '../../../shared/store/useGlobalStore';

const GlobalStore = useGlobalStore();
const InvoiceStore = useInvoiceStore();

const { loading } = storeToRefs(GlobalStore);

const { meta, invoices, detailInvoice, deleteDialog, keyword, rowsPerPageOptions } = storeToRefs(InvoiceStore);

const { loadData, onDelete, hideDialog, onChangePage, destroy, formatCurrency } = InvoiceStore;

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

        <DataTable resizableColumns scrollable columnResizeMode="fit" size="small" stripedRows :value="invoices">
            <template #empty>
                <h1 class="text-center font-bold text-red-500">Data Not Found</h1>
            </template>

            <Column field="invoice_number" header="No. Invoice"></Column>
            <Column field="invoice_date_formatted" header="Date"></Column>
            <Column field="client_name" header="Client Name"></Column>
            <Column field="discount" header="Disc. Perc">
                <template #body="slotProps"> {{ slotProps.data.discount }}% </template>
            </Column>
            <Column field="discount_amount" header="Disc. Amount">
                <template #body="slotProps">
                    {{ formatCurrency(slotProps.data.discount_amount) }}
                </template>
            </Column>
            <Column field="items_count" header="Items"></Column>
            <Column field="subtotal" header="Subtotal">
                <template #body="slotProps">
                    {{ formatCurrency(slotProps.data.subtotal) }}
                </template>
            </Column>
            <Column field="grand_total" header="Grand Total">
                <template #body="slotProps">
                    {{ formatCurrency(slotProps.data.grand_total) }}
                </template>
            </Column>

            <Column alignFrozen="right" :frozen="true">
                <template #header>
                    <p class="text-center">Action</p>
                </template>
                <template #body="slotProps">
                    <Button size="small" icon="pi pi-eye" outlined rounded severity="info" class="mr-1" @click="$router.push({ name: 'invoice-detail', params: { id: slotProps.data.id } })" />
                    <Button size="small" icon="pi pi-pencil" outlined rounded severity="warn" class="mr-1" @click="$router.push({ name: 'invoice-edit', params: { id: slotProps.data.id } })" />
                    <Button size="small" icon="pi pi-trash" outlined rounded severity="danger" @click="onDelete(slotProps.data)" />
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
