import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';


export default function useMaterialAdjustment() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const materialAdjustments = ref([]);
    const filteredMaterialAdjustments = ref([]);
    const filteredToWarehouses = ref([]);
    const filteredFromWarehouses = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const materialAdjustment = ref( {
        ref_no: '',
        date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        acc_cost_center_id: '',
        type: '',
        remarks: '',
        business_unit: '',
        scmAdjustmentLines: [
            {
                scmMaterial: '',
                scm_material_id: '',
                unit: '',
                quantity: 0.0,
                rate: 0.0,
            }
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        quantity: 0.0,
        rate: 0.0,
    }

 
    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getMaterialAdjustments(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/material-adjustments`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            materialAdjustments.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }
    async function storeMaterialAdjustment(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/material-adjustments`, formData);
            materialAdjustment.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-adjustments.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialAdjustment(materialAdjustmentId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/material-adjustments/${materialAdjustmentId}`);
            materialAdjustment.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialAdjustment(form, materialAdjustmentId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/material-adjustments/${materialAdjustmentId}`, formData);
            materialAdjustment.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-adjustments.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialAdjustment(materialAdjustmentId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/material-adjustments/${materialAdjustmentId}`);
            notification.showSuccess(status);
            await getMaterialAdjustments(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialAdjustment(searchParam, loading, business_unit) {
        try {
            const { data, status } = await Api.get(`/${BASE}/search-mmr`, {
                params: {
                    searchParam: searchParam,
                    business_unit: business_unit,
                },
            }
            );
            console.log('tag', data)
            filteredMaterialAdjustments.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
            console.log('tag', data)
        } finally {
            loading(false)
        }
    }

 

    return {
        materialAdjustments,
        materialAdjustment,
        filteredMaterialAdjustments,
        searchMaterialAdjustment,
        getMaterialAdjustments,
        storeMaterialAdjustment,
        showMaterialAdjustment,
        updateMaterialAdjustment,
        deleteMaterialAdjustment,
        isTableLoading,
        materialObject,
        isLoading,
        errors,
    };
}
