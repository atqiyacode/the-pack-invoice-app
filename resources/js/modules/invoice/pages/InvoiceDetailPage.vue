<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import { useUserStore } from '../../../shared/store/useUserStore';
import { useInvoiceStore } from '../store/useInvoiceStore';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';

const InvoiceStore = useInvoiceStore();

const { meta, invoices, detailInvoice, formDialog, deleteDialog, form, keyword } = storeToRefs(InvoiceStore);

const { getById } = InvoiceStore;

const route = useRoute();

onMounted(() => {
    console.log(route.params.id);

    getById(route.params.id);
});
</script>

<template>
    <div className="card">
        <Toolbar class="mb-6">
            <template #start>
                <Button label="Back" icon="pi pi-arrow-left" severity="secondary" class="mr-2" @click="$router.push({ name: 'invoice' })" />
            </template>
            <template #center>
                <h5 class="m-0 capitalize">Detail: {{ detailInvoice.invoice_number }}</h5>
            </template>
        </Toolbar>
        <pre>
            {{ detailInvoice }}
        </pre>
    </div>
</template>
