import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterialMovementRequisition() {
    const router = useRouter();
    const materialMovementRequisitions = ref([]);
    const filteredMaterialMovementRequisitions = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const materialMovementRequisition = ref( {
        requisition_date: '',
        remarks: '',
        warehouse_id: '',
        materials: [
            {
                material_id: '',
                size: '',
                unit: '',
                quantity: null
            }
        ],
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getMaterialMovementRequisitions(page,isPaginate = null) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/material-movement-requisitions',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || null,
                },
            });
            materialMovementRequisitions.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function getPendingMaterialMovementRequisitions() {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;
        
        try {
            const {data, status} = await Api.get('/scm/material-movement-requisitions');
            materialMovementRequisitions.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeMaterialMovementRequisition(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/material-movement-requisitions', form);
            materialMovementRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-movement-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialMovementRequisition(materialMovementRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/material-movement-requisitions/${materialMovementRequisitionId}`);
            materialMovementRequisition.value = data.value;
            materialMovementRequisition.value.materials = data.value.stockable;
            materialMovementRequisition.value.from_warehouse_id = data.value.from_warehouse.id
            materialMovementRequisition.value.to_warehouse_id = data.value.to_warehouse_id

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialMovementRequisition(form, materialMovementRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put('/scm/material-movement-requisitions/'+materialMovementRequisitionId, form);
            materialMovementRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-movement-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialMovementRequisition(materialMovementRequisitionId) {

        if (!confirm('Are you sure you want to delete this data?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/material-movement-requisitions/${materialMovementRequisitionId}`);
            notification.showSuccess(status);
            await getMaterialMovementRequisitions();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialMovementRequisition(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get('/common/search-material-movement-requisition', {params: { searchParam: searchParam }});
            materialMovementRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    return {
        materialMovementRequisitions,
        materialMovementRequisition,
        filteredMaterialMovementRequisitions,
        searchMaterialMovementRequisition,
        getMaterialMovementRequisitions,
        getPendingMaterialMovementRequisitions,
        storeMaterialMovementRequisition,
        showMaterialMovementRequisition,
        updateMaterialMovementRequisition,
        deleteMaterialMovementRequisition,
        isLoading,
        errors,
    };
}
