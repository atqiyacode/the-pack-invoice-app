<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import { useUserStore } from '../../../shared/store/useUserStore';
import { useInvoiceStore } from '../store/useInvoiceStore';
import { storeToRefs } from 'pinia';

const InvoiceStore = useInvoiceStore();

const { meta, invoices, detailInvoice, formDialog, deleteDialog, form, keyword } = storeToRefs(InvoiceStore);

const { loadData, onEdit, onDelete, hideDialog } = InvoiceStore;
</script>

<template>
    <div className="card">
        <Toolbar class="mb-6">
            <template #start>
                <Button label="Back" icon="pi pi-arrow-left" severity="secondary" class="mr-2" @click="$router.push({ name: 'invoice' })" />
            </template>
            <template #center>
                <h5 class="m-0 capitalize">New Invoice</h5>
            </template>
        </Toolbar>
    </div>
</template>
