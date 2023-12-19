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

export default function useMovementOut() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const movementOuts = ref([]);
    const filteredMovementOuts = ref([]);
    const filteredToWarehouses = ref([]);
    const filteredFromWarehouses = ref([]);
    const filteredMovementRequisitionLines = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const movementOut = ref( {
        ref_no: '',
        date: '',
        fromWarehouse: '',
        from_warehouse_id: '',
        from_warehouse_name: '',
        toWarehouse: '',
        to_warehouse_id: '',
        to_warehouse_name: '',
        from_cost_center_id: '',
        to_cost_center_id: '',
        scmMmr: '',
        scm_mmr_id: '',
        business_unit: '',
        scmMoLines: [
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        remarks: '',
        mmr_quantity: 0.00,
        mmr_composite_key: '',
        quantity: 0.00
    }

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getMovementOuts(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/movement-outs`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            movementOuts.value = data.value;
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
    async function storeMovementOut(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/movement-outs`, formData);
            movementOut.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.movement-outs.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMovementOut(movementOutId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/movement-outs/${movementOutId}`);
            movementOut.value = data.value;
console.log(movementOut.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMovementOut(form, movementOutId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/movement-outs/${movementOutId}`, formData);
            movementOut.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.movement-outs.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMovementOut(movementOutId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/movement-outs/${movementOutId}`);
            notification.showSuccess(status);
            await getMovementOuts(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMovementOut(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-store-requisitions`,searchParam);
            filteredMovementOuts.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }
    async function getMmrWiseMoData(mmrId) {
        try {
            const {data, status} = await Api.get(`/${BASE}/get-mmr-wise-data`,{
                params: {
                    mmr_id: mmrId,
                },
            });
            filteredMovementRequisitionLines.value = data.value.scmMmrLines;
            console.log(filteredMovementRequisitionLines.value);
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }

    async function getMmrWiseMo(business_unit,mmrId) {
        try {
            const {data, status} = await Api.get(`/${BASE}/search-mo`,{
                params: {
                    mmr_id: mmrId,
                    business_unit: business_unit,
                },
            });
            filteredMovementOuts.value = data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }

 

    return {
        movementOuts,
        movementOut,
        filteredMovementOuts,
        searchMovementOut,
        getMovementOuts,
        storeMovementOut,
        showMovementOut,
        updateMovementOut,
        deleteMovementOut,
        filteredMovementRequisitionLines,
        getMmrWiseMoData,
        getMmrWiseMo,
        materialObject,
        isTableLoading,
        isLoading,
        errors,
    };
}
