import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterialReceive() {
    const router = useRouter();
    const materialReceives = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const materialReceive = ref( {
        date: '',
        shift: '',
        purchase_order_id: null,
        supplier_id: null,
        warehouse_id: null,
        challan_no: '',
        remarks: '',
        stockable: [],
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getMaterialReceive(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/material-receives',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            materialReceives.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function storeMaterialReceive(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/material-receives', form);
            materialReceive.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-receives.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialReceive(materialReceiveId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/material-receives/${materialReceiveId}`);
            materialReceive.value = data.value;
            materialReceive.value.stockable = data.value.stockable;
            materialReceive.value.page = 'show';

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialReceive(form, materialReceiveId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/material-receives/${materialReceiveId}`,
                form
            );
            materialReceive.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-receives.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialReceive(materialReceiveId) {

        if (!confirm('Are you sure you want to delete this data?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/material-receives/${materialReceiveId}`);
            notification.showSuccess(status);
            await getMaterialReceive();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialReceive(searchParam, loading) {
        try {
            const { data, status } = await Api.get(`/common/search-material-receives`, {params: { searchParam: searchParam }});
            materialReceives.value = data.value;
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
        materialReceives,
        searchMaterialReceive,
        materialReceive,
        getMaterialReceive,
        storeMaterialReceive,
        showMaterialReceive,
        updateMaterialReceive,
        deleteMaterialReceive,
        isLoading,
        errors,
    };
}
