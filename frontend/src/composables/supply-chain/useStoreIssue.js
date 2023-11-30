import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from './../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import useHelper from '../useHelper';

import { merge } from 'lodash';


export default function useStoreIssue() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const storeIssues = ref([]);
    const filteredStoreIssues = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const storeIssue = ref( {
        ref_no: '',
        date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        scmDepartment: '',
        scm_department_id: '',
        scmSr: '',
        scm_sr_id: '',
        sr_no: '',
        acc_cost_center_id: '',
        remarks: '',
        business_unit: '',
        scmSrLines: [
            {
                scmMaterial: '',
                scm_material_id: '',
                unit: '',
                quantity: 0.0,
                sr_quantity: 0.0,
                current_stock: 0.0,
                sr_composite_key: '',
            }
        ],
    });
    const materialObject = {
            scmMaterial: '',
            scm_material_id: '',
            unit: '',
            quantity: 0.0,
            sr_quantity: 0.0,
            current_stock: 0.0,
            sr_composite_key: '',
    }

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getStoreIssues(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/store-issues`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            storeIssues.value = data.value;
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
    async function storeStoreIssue(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/store-issues`, formData);
            storeIssue.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.store-issues.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showStoreIssue(storeIssueId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/store-issues/${storeIssueId}`);
            storeIssue.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateStoreIssue(form, storeIssueId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/store-issues/${storeIssueId}`, formData);
            storeIssue.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.store-issues.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteStoreIssue(storeIssueId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/store-issues/${storeIssueId}`);
            notification.showSuccess(status);
            await getStoreIssues(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchStoreIssue(searchParam, /* loading,*/ business_unit) {
        

        try {
            const { data, status } = await Api.get(`/${BASE}/search-store-issue`, {
                params: {
                    searchParam: searchParam,
                    business_unit: business_unit,
                },
            }
            );
            console.log('tag', data)
            filteredStoreIssues.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //     loading(false)
        }
    }
    async function getSrWiseSi(srId) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-sr-wise-data`,{
                params: {
                    sr_id: srId,
                },
            });
            storeIssue.value = merge(storeIssue.value, data.value);
            console.log('tag', data)
        } catch (error) {
            console.log('tag', error)
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

 

    return {
        storeIssues,
        storeIssue,
        filteredStoreIssues,
        searchStoreIssue,
        getStoreIssues,
        storeStoreIssue,
        showStoreIssue,
        updateStoreIssue,
        deleteStoreIssue,
        materialObject,
        getSrWiseSi,
        isTableLoading,
        isLoading,
        errors,
    };
}
