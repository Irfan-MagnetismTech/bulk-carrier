import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterialMovement() {
    const router = useRouter();
    const materialMovements = ref([]);
    const filteredMaterialMovements = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const materialMovement = ref( {
        movement_date: '',
        remarks: '',
        movement_type: '',
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

    async function getMaterialMovements(page,isPaginate = null, movementType) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/material-movements',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || null,
                    movement_type: movementType
                },
            });
            materialMovements.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function storeMaterialMovement(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/material-movements', form);
            materialMovement.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-movements.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialMovement(materialMovementId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/material-movements/${materialMovementId}`);
            materialMovement.value = data.value;
            materialMovement.value.materials = data.value.stockable;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialMovement(form, materialMovementId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put('/scm/material-movements/'+materialMovementId, form);
            materialMovement.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material-movements.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialMovement(materialMovementId) {

        if (!confirm('Are you sure you want to delete this data?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/material-movements/${materialMovementId}`);
            notification.showSuccess(status);
            await getMaterialMovements();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialMovement(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get('/common/search-material-movement',searchParam);
            filteredMaterialMovements.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    return {
        materialMovements,
        materialMovement,
        filteredMaterialMovements,
        searchMaterialMovement,
        getMaterialMovements,
        storeMaterialMovement,
        showMaterialMovement,
        updateMaterialMovement,
        deleteMaterialMovement,
        isLoading,
        errors,
    };
}
