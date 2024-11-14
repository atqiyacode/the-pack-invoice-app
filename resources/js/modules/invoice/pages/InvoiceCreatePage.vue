<script setup>
import { useInvoiceStore } from '../store/useInvoiceStore';
import { storeToRefs } from 'pinia';

const InvoiceStore = useInvoiceStore();

const { form } = storeToRefs(InvoiceStore);

const { store } = InvoiceStore;
</script>

<template>
    <div className="card">
        <Toolbar class="mb-6">
            <template #start>
                <Button label="Back" icon="pi pi-arrow-left" severity="secondary" class="mr-2" @click="$router.push({ name: 'invoice' })" />
            </template>
            <template #center>
                <h5 class="mr-2 capitalize">New Invoice</h5>
            </template>
            <template #end>
                <Button label="Submit" icon="pi pi-save" severity="success" class="mr-2" @click="store" />
            </template>
        </Toolbar>

        <Fluid>
            <div class="flex">
                <div class="card flex flex-col gap-4 w-full">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-auto">
                            <label class="font-bold block mb-2" for="invoice_number">invoice_number</label>
                            <InputText v-model="form.invoice_number" id="invoice_number" type="text" />
                        </div>
                        <div class="flex-auto">
                            <label class="font-bold block mb-2" for="invoice_date"> invoice_date </label>
                            <DatePicker v-model="form.invoice_date" showIcon fluid :showOnFocus="true" inputId="buttondisplay" />
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

                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex flex-wrap gap-2 w-full">
                            <label class="font-bold block mb-2" for="discount_amount">discount_amount</label>
                            <InputNumber v-model="form.discount_amount" inputId="currency-us" mode="currency" currency="USD" locale="en-US" fluid />
                        </div>
                        <div class="flex flex-wrap gap-2 w-full">
                            <label class="font-bold block mb-2" for="subtotal">subtotal</label>
                            <InputNumber v-model="form.subtotal" inputId="currency-us" mode="currency" currency="USD" locale="en-US" fluid />
                        </div>
                        <div class="flex flex-wrap gap-2 w-full">
                            <label class="font-bold block mb-2" for="gst_amount">gst_amount</label>
                            <InputNumber v-model="form.gst_amount" inputId="currency-us" mode="currency" currency="USD" locale="en-US" fluid />
                        </div>
                        <div class="flex flex-wrap gap-2 w-full">
                            <label class="font-bold block mb-2" for="grand_total">grand_total</label>
                            <InputNumber v-model="form.grand_total" inputId="currency-us" mode="currency" currency="USD" locale="en-US" fluid />
                        </div>
                    </div>
                </div>
            </div>
        </Fluid>
    </div>
</template>
