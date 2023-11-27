import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from './../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';
import useHelper from '../useHelper';



export default function useStoreRequisition() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const storeRequisitions = ref([]);
    const filteredStoreRequisitions = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const storeRequisition = ref( {
        ref_no: '',
        date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        department_id: '',
        acc_cost_center_id: '',
        remarks: '',
        business_unit: '',
        scmSrLines: [
            {
                scmMaterial: '',
                scm_material_id: '',
                unit: '',
                specification: '',
                quantity: 0.0
            }
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        specification: '',
        quantity: 0.0,
    }

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getStoreRequisitions(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/store-requisitions`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            storeRequisitions.value = data.value;
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
    async function storeStoreRequisition(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/store-requisitions`, formData);
            storeRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.store-requisitions.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showStoreRequisition(storeRequisitionId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/store-requisitions/${storeRequisitionId}`);
            storeRequisition.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateStoreRequisition(form, storeRequisitionId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/store-requisitions/${storeRequisitionId}`, formData);
            storeRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.store-requisitions.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteStoreRequisition(storeRequisitionId) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/store-requisitions/${storeRequisitionId}`);
            notification.showSuccess(status);
            await getStoreRequisitions(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }

    async function searchStoreRequisition(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-store-requisitions`,searchParam);
            filteredStoreRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

 

    return {
        storeRequisitions,
        storeRequisition,
        filteredStoreRequisitions,
        searchStoreRequisition,
        getStoreRequisitions,
        storeStoreRequisition,
        showStoreRequisition,
        updateStoreRequisition,
        deleteStoreRequisition,
        materialObject,
        isTableLoading,
        isLoading,
        errors,
    };
}
