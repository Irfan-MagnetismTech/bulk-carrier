import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useCostCenter() {
    const router = useRouter();
    const $loading = useLoading();
    const notification = useNotification();
    const costCenters = ref([]); 
    const costCenter = ref( {
        name : '',
        short_name : ''
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getCostCenters(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/cost-centers',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            costCenters.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeCostCenter(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/accounts/cost-centers', form);
            costCenter.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.cost-centers.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCostCenter(costCenterId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/accounts/cost-centers/${costCenterId}`);
            costCenter.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCostCenter(form, costCenterId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/accounts/cost-centers/${costCenterId}`, form);
            costCenter.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.cost-centers.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCostCenter(costCenterId) {

        if (!confirm('Are you sure you want to delete?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/accounts/cost-centers/${costCenterId}`);
            notification.showSuccess(status);
            await getCostCenters();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        costCenter,
        costCenters,
        getCostCenters,
        storeCostCenter,
        showCostCenter,
        updateCostCenter,
        deleteCostCenter,
        isLoading,
        errors,
    };
}
