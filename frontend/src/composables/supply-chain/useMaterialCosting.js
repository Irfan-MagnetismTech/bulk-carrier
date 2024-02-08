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



export default function useMaterialCosting() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const materialCostings = ref([]);
    const filteredMaterialCostings = ref([]);
    const srWiseMaterials = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const materialCosting = ref( {
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

    async function getMaterialCostings(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/material-costings`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            materialCostings.value = data.value;
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
    async function storeMaterialCosting(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/material-costings`, formData);
            materialCosting.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-costings.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialCosting(materialCostingId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/material-costings/${materialCostingId}`);
            materialCosting.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialCosting(form, materialCostingId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/material-costings/${materialCostingId}`, formData);
            materialCosting.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-costings.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialCosting(materialCostingId) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/material-costings/${materialCostingId}`);
            notification.showSuccess(status);
            await getMaterialCostings(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }

    async function searchMaterialCosting(searchParam, loading) {
        
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/search-material-costings`,searchParam);
            filteredMaterialCostings.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
            isLoading.value = false;
        }
    }

    const fetchSrWiseMaterials = async (materialCostingId,storeIssueId = null) => {
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const { data, status } = await Api.get(`/${BASE}/search-sr-wise-material`, {
                params: {
                    sr_id: materialCostingId,
                    si_id: storeIssueId
                }
            });
            srWiseMaterials.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }
    

    return {
        materialCostings,
        materialCosting,
        filteredMaterialCostings,
        searchMaterialCosting,
        getMaterialCostings,
        storeMaterialCosting,
        showMaterialCosting,
        updateMaterialCosting,
        deleteMaterialCosting,
        fetchSrWiseMaterials,
        srWiseMaterials,
        materialObject,
        isTableLoading,
        isLoading,
        errors,
    };
}
