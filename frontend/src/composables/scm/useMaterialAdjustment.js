import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterialAdjustment() {
    const router = useRouter();
    const materialAdjustments = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const materialAdjustment = ref( {
        date: '',
        remarks: '',
        adjustment_type: '',
        materials: [
            {
                warehouse_id: null,
                material_id: null,
                unit: '',
                quantity: ''
            }
        ],
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getMaterialAdjustment(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/material-adjustments',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            materialAdjustments.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function storeMaterialAdjustment(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/material-adjustments', form);
            materialAdjustment.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-adjustments.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialAdjustment(materialAdjustmentId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/material-adjustments/${materialAdjustmentId}`);
            materialAdjustment.value = data.value;
            materialAdjustment.value.stockable = data.value.stockable;
            materialAdjustment.value.page = 'show';

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialAdjustment(form, materialAdjustmentId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/material-adjustments/${materialAdjustmentId}`,
                form
            );
            materialAdjustment.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-adjustments.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialAdjustment(materialAdjustmentId) {

        if (!confirm('Are you sure you want to delete this data?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/material-adjustments/${materialAdjustmentId}`);
            notification.showSuccess(status);
            await getMaterialAdjustment();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialAdjustment(searchParam, loading) {
        try {
            const { data, status } = await Api.get(`/common/search-material-adjustments`, {params: { searchParam: searchParam }});
            materialAdjustments.value = data.value;
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
        materialAdjustments,
        searchMaterialAdjustment,
        materialAdjustment,
        getMaterialAdjustment,
        storeMaterialAdjustment,
        showMaterialAdjustment,
        updateMaterialAdjustment,
        deleteMaterialAdjustment,
        isLoading,
        errors,
    };
}
