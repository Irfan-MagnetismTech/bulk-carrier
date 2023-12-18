import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';


export default function useMovementRequisition() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const movementRequisitions = ref([]);
    const filteredMovementRequisitions = ref([]);
    const filteredToWarehouses = ref([]);
    const filteredFromWarehouses = ref([]);
    const mmrWiseMaterials = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const movementRequisition = ref( {
        ref_no: '',
        date: '',
        delivery_date: '',
        fromWarehouse: '',
        from_warehouse_id: '',
        toWarehouse: '',
        to_warehouse_id: '',
        scm_warehouse_id: '',
        from_cost_center_id: '',
        to_cost_center_id: '',
        requested_by: '',
        requested_for: '',
        remarks: '',
        business_unit: '',
        scmMmrLines: [
            {
                scmMaterial: '',
                scm_material_id: '',
                unit: '',
                specification: '',
                available_stock: '',
                present_stock: '',
                quantity: 0.0
            }
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        specification: '',
        available_stock: '',
        present_stock: '',
        quantity: 0.0
    }

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getMovementRequisitions(filterOptions) {
        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show(LoaderConfig);
            isLoading.value = true;
            isTableLoading.value = false;
        }
        else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }
        filterParams.value = filterOptions;
        try {
            const {data, status} = await Api.get(`/${BASE}/movement-requisitions`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            movementRequisitions.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            }
            else {
                isTableLoading.value = false;
                loader?.hide();
            }
        }
    }
    async function storeMovementRequisition(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/movement-requisitions`, formData);
            movementRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.movement-requisitions.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMovementRequisition(movementRequisitionId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/movement-requisitions/${movementRequisitionId}`);
            movementRequisition.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMovementRequisition(form, movementRequisitionId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/movement-requisitions/${movementRequisitionId}`, formData);
            movementRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.movement-requisitions.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMovementRequisition(movementRequisitionId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/movement-requisitions/${movementRequisitionId}`);
            notification.showSuccess(status);
            await getMovementRequisitions(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMovementRequisition(searchParam,business_unit,loading = false) {
        try {
            const { data, status } = await Api.get(`/${BASE}/search-mmr`, {
                params: {
                    searchParam: searchParam,
                    business_unit: business_unit,
                },
            }
            );
            console.log('tag', data)
            filteredMovementRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
            console.log('tag', data)
        } finally {
            // loading(false)
        }
    }

    async function getMmrWiseMaterials(mmrId,moId = null) {
        try {
            const { data, status } = await Api.get(`/${BASE}/get-mmr-wise-materials`,{
            params: {
                    mmr_id: mmrId,
                    mo_id: moId,
                },
            });
            mmrWiseMaterials.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        }
    }

 

    return {
        movementRequisitions,
        movementRequisition,
        filteredMovementRequisitions,
        searchMovementRequisition,
        getMovementRequisitions,
        storeMovementRequisition,
        showMovementRequisition,
        updateMovementRequisition,
        deleteMovementRequisition,
        getMmrWiseMaterials,
        mmrWiseMaterials,
        materialObject,
        isTableLoading,
        isLoading,
        errors,
    };
}
