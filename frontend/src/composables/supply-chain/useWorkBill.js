import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';
import { merge } from 'lodash';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';
import Swal from 'sweetalert2';

export default function useWorkBill() {
    const BASE = 'scm' 
    const router = useRouter();
    const workBills = ref([]);
    // const wcsWiseVendorList = ref([]);
    // const filteredWorkCsLines = ref([]);
    const $loading = useLoading();
    // const wrServiceList = ref([]);
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const workBill = ref({
        date: '',
        scm_wo_id: '',
        scmWo: '',
        scm_warehouse_id: '',
        scmWarehouse: '',
        scm_vendor_id: '',
        scmVendor: '',
        currency: '',
        
    });

    


    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getWorkBills(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/work-bills`,{
                params: {
                    data: JSON.stringify(filterOptions)
                }
            });
            workBills.value = data.value;
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
    async function storeWorkBill(form) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/work-bills`, formData);
            workBill.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-bills.index` });
        } catch (error) {
            console.log(error);
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showWorkBill(workBillId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/work-bills/${workBillId}`);
            workBill.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateWorkBill(form, workBillId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/work-bills/${workBillId}`, formData);
            workBill.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-bills.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteWorkBill(workBillId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/work-bills/${workBillId}`);
            notification.showSuccess(status);
            await getWorkBills(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        workBill,
        workBills,
        getWorkBills,
        storeWorkBill,
        showWorkBill,
        updateWorkBill,
        deleteWorkBill,
        isTableLoading,
        isLoading,
        errors,
    };
}
