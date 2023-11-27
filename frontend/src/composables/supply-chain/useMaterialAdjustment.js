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
    const $loading = useLoading();
    const notification = useNotification();
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const materialAdjustment = ref( {
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
        required_for: '',
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
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    async function getMaterialAdjustments(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        
        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get(`/${BASE}/material-adjustments`,{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
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
            await getMaterialAdjustments(indexPage.value,indexBusinessUnit.value);
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
        materialObject,
        isLoading,
        errors,
    };
}
