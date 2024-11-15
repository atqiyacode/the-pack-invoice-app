<script setup>
import { onMounted, ref, watch } from 'vue';
import { useInvoiceStore } from '../store/useInvoiceStore';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';

const route = useRoute();

const InvoiceStore = useInvoiceStore();

const { form, detailInvoice } = storeToRefs(InvoiceStore);

const { getById, store, update } = InvoiceStore;

const subtotal = ref(0);
const discountTotal = ref(0);
const gstTotal = ref(0);
const gstTax = ref(9);

const dialogItem = ref(false);
const formItem = ref({});

onMounted(() => {
    if (route.params.id) {
        getById(route.params.id);
    } else {
        form.value = {
            invoice_number: null,
            invoice_date: null,
            client_name: null,
            client_address: null,
            remarks: null,
            discount_amount: 0,
            subtotal: 0,
            gst_amount: 0,
            grand_total: 0,
            items: []
        };
    }
});

watch(discountTotal, (newDiscountTotal) => {
    if (newDiscountTotal) {
        getGstAmount();
    }
});

const getTotal = (item) => item.item_price * item.item_quantity;

const getSubtotal = (items) => {
    subtotal.value = items.reduce((acc, item) => acc + (item.item_amount || 0), 0);
    form.value.subtotal = subtotal.value;
    return subtotal.value;
};

const getDiscountAmount = (discount) => {
    discountTotal.value = (discount / 100) * subtotal.value;
    return discountTotal.value;
};

const getGstAmount = () => {
    gstTotal.value = (gstTax.value / 100) * (subtotal.value - discountTotal.value);
    form.value.gst_amount = gstTotal.value;
    return gstTotal.value;
};

const getGrandTotal = () => {
    const grandTotal = subtotal.value - discountTotal.value + gstTotal.value;
    form.value.grand_total = grandTotal;
    return grandTotal;
};

const updateDiscount = () => {
    discountTotal.value = (form.value.discount_amount / 100) * subtotal.value;
    getGstAmount();
};

const formatCurrency = (value) => {
    return `${new Intl.NumberFormat('us-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 2
    }).format(value)}`;
};

const addItem = () => {
    const item = formItem.value;
    const existingItem = form.value.items.find((i) => i.item_name === item.item_name);
    if (existingItem) {
        existingItem.item_quantity += parseInt(item.item_quantity);
        existingItem.item_amount = existingItem.item_price * existingItem.item_quantity;
    } else {
        item.item_amount = item.item_price * item.item_quantity;
        form.value.items.push(item);
    }

    dialogItem.value = false;
    formItem.value = {};
};
</script>

<template>
    <div className="card">
        <Toolbar class="mb-6">
            <template #start>
                <Button label="Back" icon="pi pi-arrow-left" severity="secondary" class="mr-2" @click="$router.push({ name: 'invoice' })" />
            </template>
            <template #center>
                <h5 class="mr-2 font-bold">
                    {{ form.id ? `Edit Invoice: ${detailInvoice.invoice_number}` : 'New Invoice' }}
                </h5>
            </template>
            <template #end>
                <Button label="Submit" icon="pi pi-save" severity="success" class="mr-2" @click="route.params.id ? update() : store()" />
            </template>
        </Toolbar>

        <Fluid>
            <div class="flex">
                <div class="card flex flex-col gap-4 w-full">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-auto">
                            <label class="font-bold block mb-2" for="invoice_number">invoice_number</label>
                            <InputText :disabled="true" :value="form.id ? detailInvoice.invoice_number : 'Automatically'" id="invoice_number" type="text" />
                        </div>

                        <div class="flex-auto">
                            <label class="font-bold block mb-2" for="invoice_date"> invoice_date</label>
                            <DatePicker v-model="form.invoice_date" showIcon fluid :showOnFocus="true" dateFormat="DD, dd MM yy" />
                        </div>
                        <div class="flex-auto">
                            <label class="font-bold block mb-2" for="client_name">client_name</label>
                            <InputText v-model="form.client_name" id="client_name" type="text" />
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <label class="font-bold block mb-2" for="client_address">client_address</label>
                        <Textarea v-model="form.client_address" id="client_address" rows="4" />
                    </div>
                    <div class="flex flex-wrap">
                        <label class="font-bold block mb-2" for="remarks">remarks</label>
                        <InputText v-model="form.remarks" id="remarks" type="text" />
                    </div>
                </div>
            </div>
        </Fluid>
    </div>

    <div className="card">
        <Toolbar class="mb-6">
            <template #start>
                <InputNumber suffix="%" v-model="form.discount_amount" :min="0" :max="100" fluid @keyup="updateDiscount" />
            </template>
            <template #center>
                <h5 class="mr-2 font-bold">Items</h5>
            </template>
            <template #end>
                <Button label="Add Item" icon="pi pi-plus" severity="success" class="mr-2" @click="dialogItem = true" />
            </template>
        </Toolbar>

        <DataTable :lazy="true" :value="form.items">
            <Column field="item_name" class="text-center" header="item_name"></Column>
            <Column field="item_quantity" class="text-center" header="item_quantity"></Column>
            <Column field="item_price" class="text-center" header="item_price"></Column>
            <Column field="item_amount" class="text-center" header="item_amount">
                <template #body="slotProps">
                    {{ formatCurrency(getTotal(slotProps.data)) }}
                </template>
            </Column>
            <ColumnGroup type="footer" v-if="form.items">
                <Row>
                    <Column footer="Sub-Total:" :colspan="3" footerStyle="text-align:right" />
                    <Column :footer="`${formatCurrency(getSubtotal(form.items))}`" />
                </Row>
                <Row>
                    <Column :footer="`Discount (${form.discount_amount}%):`" :colspan="3" footerStyle="text-align:right" />
                    <Column :footer="`${formatCurrency(getDiscountAmount(form.discount_amount))}`" />
                </Row>
                <Row>
                    <Column :footer="`GST Amount (${gstTax}%):`" :colspan="3" footerStyle="text-align:right" />
                    <Column :footer="`${formatCurrency(getGstAmount())}`" />
                </Row>
                <Row>
                    <Column footer="Grand Total:" :colspan="3" footerStyle="text-align:right" />
                    <Column :footer="`${formatCurrency(getGrandTotal())}`" />
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>

    <Dialog v-model:visible="dialogItem" :style="{ width: '450px' }" header="Confirm" :modal="true">
        <div class="flex flex-col gap-6">
            <div>
                <label for="name" class="block font-bold mb-3">Name</label>
                <InputText id="name" v-model.trim="formItem.item_name" required="true" fluid />
            </div>

            <div>
                <label for="quantity" class="block font-bold mb-3">quantity</label>
                <InputNumber id="quantity" v-model.trim="formItem.item_quantity" required="true" fluid />
            </div>

            <div>
                <label for="price" class="block font-bold mb-3">price</label>
                <InputNumber id="price" v-model.trim="formItem.item_price" required="true" fluid />
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" icon="pi pi-times" text @click="dialogItem = false" />
            <Button label="Add" icon="pi pi-check" @click="addItem" />
        </template>
    </Dialog>
</template>
