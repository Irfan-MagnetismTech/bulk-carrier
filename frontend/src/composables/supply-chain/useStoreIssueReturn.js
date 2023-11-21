import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';
import { merge } from 'lodash';


export default function useStoreIssueReturn() {
    const BASE = 'scm' 
    const router = useRouter();
    const storeIssueReturns = ref([]);
    const filteredStoreIssueReturns = ref([]);
    const filteredStoreIssueReturnLines = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const storeIssueReturn = ref({
        ref_no: '',
        date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        scm_warehouse_name: '',
        acc_cost_center_id: '',
        scmDepartment: '',
        scm_department_id: '',
        scmSi: '',
        scm_si_id: '',
        si_no: '',
        remarks: '',
        business_unit: '',
        scmSirLines: [
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        quantity: 0.0,
        notes: '',
        sr_composite_key: '',
        si_composite_key: '',
    }

    const errors = ref('');
    const isLoading = ref(false);
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    async function getStoreIssueReturns(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        
        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get(`/${BASE}/store-issue-returns`,{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
                },
            });
            storeIssueReturns.value = data.value;
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
    async function storeStoreIssueReturn(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/store-issue-returns`, formData);
            storeIssueReturn.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.store-issue-returns.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showStoreIssueReturn(storeIssueReturnId) {
        console.log('tag', storeIssueReturnId);
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/store-issue-returns/${storeIssueReturnId}`);
            storeIssueReturn.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateStoreIssueReturn(form, storeIssueReturnId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/store-issue-returns/${storeIssueReturnId}`, formData);
            storeIssueReturn.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.store-issue-returns.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteStoreIssueReturn(storeIssueReturnId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/store-issue-returns/${storeIssueReturnId}`);
            notification.showSuccess(status);
            await getStoreIssueReturns(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchStoreIssueReturn(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-store-issue-returns`,searchParam);
            filteredStoreIssueReturns.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    async function getSiWiseSir(siId) {
        //NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/get-si-wise-data`,{
                params: {
                    si_id: siId,
                },
            });
            filteredStoreIssueReturnLines.value = data.value.scmSirLines;
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }

    // async function getSiWiseData(srId) {
    //     NProgress.start();
    //     const loader = $loading.show(LoaderConfig);
    //     isLoading.value = true;

    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/get-sr-wise-data`,{
    //             params: {
    //                 sr_id: srId,
    //             },
    //         });
    //         storeIssueReturn.value = merge(storeIssueReturn.value, data.value);
    //         console.log('tag', data)
    //     } catch (error) {
    //         console.log('tag', error)
    //     } finally {
    //         loader.hide();
    //         isLoading.value = false;
    //         NProgress.done();
    //     }
    // }

 

    return {
        storeIssueReturns,
        storeIssueReturn,
        filteredStoreIssueReturns,
        filteredStoreIssueReturnLines,
        searchStoreIssueReturn,
        getStoreIssueReturns,
        storeStoreIssueReturn,
        showStoreIssueReturn,
        updateStoreIssueReturn,
        deleteStoreIssueReturn,
        materialObject,
        getSiWiseSir,
        // getSiWiseData,
        isLoading,
        errors,
    };
}