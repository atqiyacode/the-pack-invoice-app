<script setup>
import { onMounted, ref, watch } from 'vue';
import { useInvoiceStore } from '../store/useInvoiceStore';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';
import { numeric, required } from '@vuelidate/validators';
import useVuelidate from '@vuelidate/core';
import { useGlobalStore } from '../../../shared/store/useGlobalStore';

const route = useRoute();

const GlobalStore = useGlobalStore();
const InvoiceStore = useInvoiceStore();

const { loading, errors } = storeToRefs(GlobalStore);
const { form, detailInvoice } = storeToRefs(InvoiceStore);

const { getById, store, update } = InvoiceStore;

const subtotal = ref(0);
const discountTotal = ref(0);
const gstTotal = ref(0);
const gstTax = ref(9);

const dialogItem = ref(false);
const formItem = ref({});

const nonEmptyArray = (value) => {
    return Array.isArray(value) && value.length > 0;
};

const rules = {
    invoice_date: { required },
    client_name: { required },
    client_address: { required },
    remarks: {},
    discount_amount: { numeric },
    subtotal: { required, numeric },
    gst_amount: { required, numeric },
    grand_total: { required, numeric },
    items: { required, $validators: { nonEmptyArray } }
};
const v$ = useVuelidate(rules, form);

onMounted(() => {
    if (route.params.id) {
        getById(route.params.id);
    } else {
        form.value = {
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
const deleteItem = (data) => {
    form.value.items = form.value.items.filter((item) => item.item_name !== data.item_name);
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
                <Button label="Submit" icon="pi pi-save" :severity="v$.$invalid ? 'warn' : 'success'" class="mr-2" @click="route.params.id ? update() : store()" :disabled="v$.$invalid" />
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
                            <DatePicker @blur="v$.invoice_date.$touch()" :invalid="errors.invoice_date || v$.invoice_date.$error" v-model="form.invoice_date" showIcon fluid :showOnFocus="true" dateFormat="DD, dd MM yy" />
                            <Message size="small" severity="error" variant="simple">{{ errors.invoice_date ? errors.invoice_date[0] : '' }}</Message>
                        </div>
                        <div class="flex-auto">
                            <label class="font-bold block mb-2" for="client_name">client_name</label>
                            <InputText @blur="v$.client_name.$touch()" :invalid="errors.client_name || v$.client_name.$error" v-model="form.client_name" id="client_name" type="text" />
                            <Message size="small" severity="error" variant="simple">{{ errors.client_name ? errors.client_name[0] : '' }}</Message>
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <label class="font-bold block mb-2" for="client_address">client_address</label>
                        <Textarea @blur="v$.client_address.$touch()" :invalid="errors.client_address || v$.client_address.$error" v-model="form.client_address" id="client_address" rows="4" />
                        <Message size="small" severity="error" variant="simple">{{ errors.client_address ? errors.client_address[0] : '' }}</Message>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-auto">
                            <label class="font-bold block mb-2" for="remarks">remarks</label>
                            <InputText @blur="v$.remarks.$touch()" :invalid="errors.remarks || v$.remarks.$error" v-model="form.remarks" id="remarks" type="text" />
                            <Message size="small" severity="error" variant="simple">{{ errors.remarks ? errors.remarks[0] : '' }}</Message>
                        </div>
                        <div class="flex-auto">
                            <label class="font-bold block mb-2" for="discount">discount</label>
                            <InputNumber suffix="%" @blur="v$.discount_amount.$touch()" :invalid="errors.discount_amount || v$.discount_amount.$error" v-model="form.discount_amount" :min="0" :max="100" fluid @keypress="updateDiscount" />
                            <Message size="small" severity="error" variant="simple">{{ errors.discount_amount ? errors.discount_amount[0] : '' }}</Message>
                        </div>
                    </div>
                </div>
            </div>
        </Fluid>
        <Toolbar class="mb-6">
            <template #start>
                <h2 class="font-bold">Items</h2>
            </template>
            <template #center>
                <Message size="large" severity="error" variant="simple">{{ errors.items ? errors.items[0] : '' }}</Message>
                <Message size="large" severity="error" variant="simple" v-if="form.items?.length < 1"> Please Add Item </Message>
            </template>
            <template #end>
                <Button label="Add Item" outlined icon="pi pi-plus" severity="success" @click="dialogItem = true" />
            </template>
        </Toolbar>

        <DataTable :lazy="true" :value="form.items">
            <template #empty>
                <div class="flex flex-column md:flex-row md:justify-content-center md:align-items-center mb-3">
                    <h5 class="m-0 text-red-600">Item Not Found</h5>
                </div>
            </template>
            <Column class="text-center" header="action">
                <template #body="slotProps">
                    <Button label="" icon="pi pi-trash" severity="danger" @click="deleteItem(slotProps.data)" />
                </template>
            </Column>
            <Column field="item_name" class="text-center" header="Name"></Column>
            <Column field="item_quantity" class="text-center" header="Qty"></Column>
            <Column field="item_price" class="text-center" header="Price">
                <template #body="slotProps">
                    {{ formatCurrency(slotProps.data.item_price) }}
                </template>
            </Column>
            <Column field="item_amount" class="text-center" header="Amount">
                <template #body="slotProps">
                    {{ formatCurrency(getTotal(slotProps.data)) }}
                </template>
            </Column>
            <ColumnGroup type="footer" v-if="form.items?.length > 0">
                <Row>
                    <Column footer="Sub-Total:" :colspan="4" footerStyle="text-align:right" />
                    <Column :footer="`${formatCurrency(getSubtotal(form.items))}`" />
                </Row>
                <Row>
                    <Column :footer="`Discount (${form.discount_amount}%):`" :colspan="4" footerStyle="text-align:right" />
                    <Column :footer="`${formatCurrency(getDiscountAmount(form.discount_amount))}`" />
                </Row>
                <Row>
                    <Column :footer="`GST Amount (${gstTax}%):`" :colspan="4" footerStyle="text-align:right" />
                    <Column :footer="`${formatCurrency(getGstAmount())}`" />
                </Row>
                <Row>
                    <Column footer="Grand Total:" :colspan="4" footerStyle="text-align:right" />
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
