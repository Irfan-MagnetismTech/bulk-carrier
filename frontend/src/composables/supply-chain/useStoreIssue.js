import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from './../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import useHelper from '../useHelper';


export default function useStoreIssue() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const storeIssues = ref([]);
    const filteredStoreIssues = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const storeIssue = ref( {
        ref_no: '',
        date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
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
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    async function getStoreIssues(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        
        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get(`/${BASE}/store-issues`,{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
                },
            });
            storeIssues.value = data.value;
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
            await getStoreIssues(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchStoreIssue(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-store-issues`,searchParam);
            filteredStoreIssues.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
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
        isLoading,
        errors,
    };
}
