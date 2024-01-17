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

export default function useWorkCs() {
    const BASE = 'scm' 
    const router = useRouter();
    const workCsLists = ref([]);
    const filteredWorkCs = ref([]);
    const quotations = ref([]);
    const csWiseVendorList = ref([]);
    const filteredWorkCsLines = ref([]);
    const $loading = useLoading();
    const wrServiceList = ref([]);
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const workCs = ref({
        ref_no: null,
        scmWarehouse: null,
        scm_warehouse_id: null,
        effective_date: null,
        expire_date: null,
        // requirment_type: "",
        required_days: null,
        purchase_center: '',
        business_unit: null,
        special_instructions: null,
        
        // priority: null,
        // scm_warehouse_name: null,
        // acc_cost_center_id: null,
        // scmPr: null,
        // scm_pr_id: null,
        // pr_no: null,
        // purchase_center: null,
        scmWcsServices: [
            {
                scmWr: null,
                scm_wr_id: null,
                scm_service_id: null,
                scmService: null,
                quantity : null,

                // max_quantity: null,
                // pr_composite_key: null,
                // unit : null,
            }
        ]
    });

    const serviceObj = {
                scmWr: null,
                scm_wr_id: null,
                scm_service_id: null,
                scmService: null,
                quantity : null,
                // max_quantity: null,
                // pr_composite_key: null,
                // unit : null,
    }

    const serviceList = ref([]);


    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getWorkCs(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/work-cs`,{
                params: {
                    data: JSON.stringify(filterOptions)
                }
            });
            workCsLists.value = data.value;
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
    async function storeWorkCs(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/work-cs`, formData);
            workCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-cs.index` });
        } catch (error) {
            console.log(error);
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showWorkCs(workCsId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/work-cs/${workCsId}`);
            workCs.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateWorkCs(form, workCsId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/work-cs/${workCsId}`, formData);
            workCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-cs.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteWorkCs(workCsId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/work-cs/${workCsId}`);
            notification.showSuccess(status);
            await getWorkCs(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getWrWiseServiceList(wrId) {
        //NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/search-wr-wise-service-for-wcs`,{
                params: {
                    scm_wr_id: wrId,
                },
            });
            wrServiceList.value = data.value;
            return data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }

    


    return {
        workCs,
        workCsLists,
        filteredWorkCs,
        getWorkCs,
        storeWorkCs,
        showWorkCs,
        updateWorkCs,
        deleteWorkCs,
        getWrWiseServiceList,
        serviceObj,
        serviceList,
        wrServiceList,
        // getSiWiseData,
        isTableLoading,
        isLoading,
        errors,
    };
}
