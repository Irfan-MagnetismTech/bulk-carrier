import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import NProgress from "nprogress";

export default function useSupplierBill() {
    const router = useRouter();
    const supplierBills = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const supplierBill = ref( {
        date: '',
        vendor_id: null,
        bill_no: null,
        discount: null,
        sub_total: null,
        total: null,
        final_total: null,
        mrrs: [
            {
                material_receive_id: null,
                amount: null,
                purchase_order_id: null,
                vendor_id: null,
                po_no: null,
            }
        ],
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getSupplierBill(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/supplier-bills',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            supplierBills.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function storeSupplierBill(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/supplier-bills', form);
            supplierBill.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.supplier-bills.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showSupplierBill(supplierBillId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/supplier-bills/${supplierBillId}`);
            supplierBill.value = data.value;
            supplierBill.value.page = 'show';

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateSupplierBill(form, supplierBillId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/supplier-bills/${supplierBillId}`,
                form
            );
            notification.showSuccess(status);
            router.push({ name: "scm.supplier-bills.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteSupplierBill(supplierBillId) {

        if (!confirm('Are you sure you want to delete this data?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/supplier-bills/${supplierBillId}`);
            notification.showSuccess(status);
            await getSupplierBill();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function downloadBill(billId) {
        NProgress.start();
        isLoading.value = true;

        axios({
            url: '/scm/download-supplier-bill/' + billId,
            data: billId,
            method: 'GET',
            responseType: 'blob', // important
        }).then((response) => {
            let dateTime = new Date();

            //stream pdf file to new tab start
            let fileURL = URL.createObjectURL(response.data);
            let a = document.createElement('a');
            a.href = fileURL;
            a.target = '_blank';
            a.click();
            //stream pdf file to new tab end
        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    return {
        supplierBills,
        supplierBill,
        downloadBill,
        getSupplierBill,
        storeSupplierBill,
        showSupplierBill,
        updateSupplierBill,
        deleteSupplierBill,
        isLoading,
        errors,
    };
}
