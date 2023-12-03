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
        date: '',
        expire_date: '',
        priority: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        scm_warehouse_name: '',
        acc_cost_center_id: '',
        scmPr: '',
        scm_pr_id: '',
        pr_no: '',
        special_instruction: '',
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
            const {data, status} = await Api.get(`/${BASE}/store-issue-returns`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
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
            const { data, status } = await Api.post(`/${BASE}/store-issue-returns`, formData);
            materialCsLists.value = data.value;
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

    async function showMaterialCs(materialCsId) {
        console.log('tag', materialCsId);
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/store-issue-returns/${materialCsId}`);
            materialCsLists.value = data.value;

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
            const { data, status } = await Api.post(`/${BASE}/store-issue-returns/${materialCsId}`, formData);
            materialCsLists.value = data.value;
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

    async function deleteMaterialCs(materialCsId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/store-issue-returns/${materialCsId}`);
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
            const {data, status} = await Api.get(`/${BASE}/search-store-issue-returns`,searchParam);
            filteredMaterialCs.value = data.value;
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
            filteredMaterialCsLines.value = data.value.scmSirLines;
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
    //         materialCs.value = merge(materialCs.value, data.value);
    //         console.log('tag', data)
    //     } catch (error) {
    //         console.log('tag', error)
    //     } finally {
    //         loader.hide();
    //         isLoading.value = false;
    //         NProgress.done();
    //     }
    // }

    async function getPrWiseCs(prId) {
        //NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/get-pr-wise-data`,{
                params: {
                    pr_id: prId,
                },
            });
            filteredMaterialCsLines.value = data.value.scmPrLines;
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
        filteredMaterialCsLines,
        searchMaterialCs,
        getMaterialCs,
        storeMaterialCs,
        showMaterialCs,
        updateMaterialCs,
        deleteMaterialCs,
        getSiWiseSir,
        getPrWiseCs,
        // getSiWiseData,
        isTableLoading,
        isLoading,
        errors,
    };
}
