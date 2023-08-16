import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function usePurchaseRate() {
    const router = useRouter();
    const purchaseRates = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const history = ref([]);
    const materials = ref();
    const purchaseRate = ref( {
        vendor: "",
        vendor_id: "",
        material: "",
        material_id: "",
        rate: "",

    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getPurchaseRates(page,isPaginate = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/revenue/purchase-rates',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || null,
                },
            });
            purchaseRates.value = data;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function storePurchaseRate(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/revenue/purchase-rates', form);
            purchaseRate.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "revenue.purchase-rate.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showPurchaseRate(purchaseRateId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/purchase-rates/${purchaseRateId}`);
            purchaseRate.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePurchaseRate(form, purchaseRateId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/revenue/purchase-rates/${purchaseRateId}`,
                form
            );
            purchaseRate.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "revenue.purchase-rate.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deletePurchaseRate(purchaseRateId) {

        if (!confirm('Are you sure you want to delete this purchaseRate?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/revenue/purchase-rates/${purchaseRateId}`);
            notification.showSuccess(status);
            await getPurchaseRates();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }


    async function purchaseRateHistory(vendorId, materialId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/purchase-rate-history`, {params: { vendor_id: vendorId, material_id: materialId }});
            history.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialWithVendorRate(vendorId, searchParam, loading) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/search-material-with-vendor-rate`, {params: { vendor_id: vendorId, searchParam: searchParam }});
            materials.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            loading(false)
        }
    }

    return {
        purchaseRates,
        purchaseRate,
        history,
        materials,
        searchMaterialWithVendorRate,
        purchaseRateHistory,
        getPurchaseRates,
        storePurchaseRate,
        showPurchaseRate,
        updatePurchaseRate,
        deletePurchaseRate,
        isLoading,
        errors,
    };
}
