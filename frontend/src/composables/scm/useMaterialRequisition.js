import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterialRequisition() {
    const router = useRouter();
    const materialRequisitions = ref([]);
    const filteredMaterialRequisitions = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const materialRequisition = ref( {
        date: '',
        note: '',
        remarks: '',
        attachment: null,
        total_amount: 0.0,
        materials: [
            {
                material_id: '',
                material_category_id: '',
                material_category_name: '',
                size: '',
                unit: '',
                quantity: 0.0,
                unit_price: 0.0,
                amount: 0.0,
            }
        ],
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getMaterialRequisitions(page,isPaginate = null) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/material-requisitions',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || null,
                },
            });
            materialRequisitions.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function storeMaterialRequisition(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('/scm/material-requisitions', formData);
            materialRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialRequisition(materialRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/material-requisitions/${materialRequisitionId}`);
            materialRequisition.value = data.value;
            materialRequisition.value.materials = data.value.stockable;
            materialRequisition.value.materials.forEach((material) => {
                material.material_category_name = material?.material_category?.name;
            });

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialRequisition(form, materialRequisitionId) {
        console.log(form);

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('/scm/material-requisitions', formData);
            materialRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialRequisition(materialRequisitionId) {

        if (!confirm('Are you sure you want to delete this data?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/material-requisitions/${materialRequisitionId}`);
            notification.showSuccess(status);
            await getMaterialRequisitions();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialRequisition(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get('/common/search-material-requisition',searchParam);
            filteredMaterialRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    async function getAllPendingMaterialRequisitions() {

        try {
            const {data, status} = await Api.get('/common/get-all-material-requisitions');
            filteredMaterialRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loading(false)
        }
    }

    return {
        materialRequisitions,
        materialRequisition,
        filteredMaterialRequisitions,
        searchMaterialRequisition,
        getAllPendingMaterialRequisitions,
        getMaterialRequisitions,
        storeMaterialRequisition,
        showMaterialRequisition,
        updateMaterialRequisition,
        deleteMaterialRequisition,
        isLoading,
        errors,
    };
}
