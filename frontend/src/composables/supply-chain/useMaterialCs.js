import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';
import { merge } from 'lodash';


export default function useMaterialCs() {
    const BASE = 'scm' 
    const router = useRouter();
    const materialCsLists = ref([]);
    const filteredMaterialCs = ref([]);
    const filteredMaterialCsLines = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const materialCs = ref({
        ref_no: '',
        effective_date: '',
        expire_date: '',
        priority: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        scm_warehouse_name: '',
        acc_cost_center_id: '',
        scmPr: '',
        scm_pr_id: '',
        pr_no: '',
        special_instructions: '',
        purchase_center: '',
        business_unit: '',
        required_days: '',
    });


    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getMaterialCs(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/material-cs`,{
                params: {
                   data: JSON.stringify(filterOptions)
                }
            });
            materialCsLists.value = data.value;
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
    async function storeMaterialCs(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/material-cs`, formData);
            materialCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-cs.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialCs(materialCsId) {
        console.log('tag', materialCsId);
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/material-cs/${materialCsId}`);
            materialCs.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialCs(form, materialCsId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/material-cs/${materialCsId}`, formData);
            materialCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-cs.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialCs(materialCsId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/material-cs/${materialCsId}`);
            notification.showSuccess(status);
            await getMaterialCs(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialCs(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-material-cs`,searchParam);
            filteredMaterialCs.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

   


    async function getPrWiseCs(prId) {
        //NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/get-pr-wise-cs-data`,{
                params: {
                    pr_id: prId,
                },
            });
            materialCs.value =  merge(materialCs.value, data.value);
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }

 

    return {
        materialCs,
        materialCsLists,
        filteredMaterialCs,
        searchMaterialCs,
        getMaterialCs,
        storeMaterialCs,
        showMaterialCs,
        updateMaterialCs,
        deleteMaterialCs,
        getPrWiseCs,
        // getSiWiseData,
        isTableLoading,
        isLoading,
        errors,
    };
}
