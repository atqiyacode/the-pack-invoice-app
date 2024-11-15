<script setup>
import { onMounted, ref } from 'vue';
import { useInvoiceStore } from '../store/useInvoiceStore';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';

const InvoiceStore = useInvoiceStore();

const { detailInvoice } = storeToRefs(InvoiceStore);

const { getById, downloadPdf, formatCurrency } = InvoiceStore;

const route = useRoute();
const gstTax = 9;

onMounted(() => {
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
            <template #end>
                <Button label="PDF Download" icon="pi pi-download" severity="danger" class="mr-2" @click="downloadPdf(detailInvoice)" />
            </template>
        </Toolbar>

        <Panel class="mb-3" header="Invoice">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-auto">
                    <p>
                        No. Invoice : <span class="font-bold">{{ detailInvoice.invoice_number }}</span>
                    </p>
                </div>
                <div class="flex-auto">
                    <p>
                        Date Invoice : <span class="font-bold">{{ detailInvoice.invoice_date_formatted }}</span>
                    </p>
                </div>
            </div>
        </Panel>
        <Panel class="mb-3" header="Client">
            <div class="flex flex-wrap">
                <p>
                    Name : <span class="font-bold">{{ detailInvoice.client_name }}</span>
                </p>
            </div>
            <div class="flex flex-wrap">
                <p>
                    Address : <span class="font-bold">{{ detailInvoice.client_address }}</span>
                </p>
            </div>
            <div class="flex flex-wrap">
                <p>
                    remarks : <span class="font-bold">{{ detailInvoice.remarks || '-' }}</span>
                </p>
            </div>
        </Panel>
        <Panel class="mb-3" header="List Items" v-if="detailInvoice.items">
            <DataTable :value="detailInvoice?.items">
                <template #empty>
                    <div class="flex flex-column md:flex-row md:justify-content-center md:align-items-center mb-3">
                        <h5 class="m-0 text-red-600">Item Not Found</h5>
                    </div>
                </template>
                <Column field="item_name" class="text-center" header="Name"></Column>
                <Column field="item_quantity" class="text-center" header="Qty"></Column>
                <Column field="item_price" class="text-center" header="Price">
                    <template #body="slotProps">
                        {{ formatCurrency(slotProps.data.item_price) }}
                    </template>
                </Column>
                <Column field="item_amount" class="text-center" header="Amount">
                    <template #body="slotProps">
                        {{ formatCurrency(slotProps.data.item_quantity * slotProps.data.item_price) }}
                    </template>
                </Column>
                <ColumnGroup type="footer">
                    <Row>
                        <Column footer="Sub-Total:" :colspan="3" footerStyle="text-align:right" />
                        <Column :footer="`${formatCurrency(detailInvoice.subtotal)}`" />
                    </Row>
                    <Row>
                        <Column :footer="`Discount (${detailInvoice.discount_amount}%):`" :colspan="3" footerStyle="text-align:right" />
                        <Column :footer="`${formatCurrency((detailInvoice.discount_amount / 100) * detailInvoice.subtotal)}`" />
                    </Row>
                    <Row>
                        <Column :footer="`GST Amount (${gstTax}%):`" :colspan="3" footerStyle="text-align:right" />
                        <Column :footer="`${formatCurrency(detailInvoice.gst_amount)}`" />
                    </Row>
                    <Row>
                        <Column footer="Grand Total:" :colspan="3" footerStyle="text-align:right" />
                        <Column :footer="`${formatCurrency(detailInvoice.grand_total)}`" />
                    </Row>
                </ColumnGroup>
            </DataTable>
        </Panel>
    </div>
</template>
