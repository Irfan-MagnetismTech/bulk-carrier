import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';


export default function useMovementIn() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const movementIns = ref([]);
    const filteredMovementIns = ref([]);
    const filteredToWarehouses = ref([]);
    const filteredFromWarehouses = ref([]);
    const filteredMovementRequisitionLines = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const movementIn = ref( {
        ref_no: '',
        date: '',
        fromWarehouse: '',
        from_warehouse_id: '',
        from_warehouse_name: '',
        toWarehouse: '',
        to_warehouse_id: '',
        to_warehouse_name: '',
        from_cost_center_id: '',
        to_cost_center_id: '',
        scmMmr: '',
        scm_mmr_id: '',
        business_unit: '',
        scmMoLines: [
            {
                scmMaterial: '',
                scm_material_id: '',
                unit: '',
                remarks: '',
                mmr_quantity: 0.00,
                mo_quantity: 0.00,
                quantity: 0.00
            }
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        remarks: '',
        mmr_quantity: 0.00,
        mo_quantity: 0.00,
        quantity: 0.00
    }

    const errors = ref('');
    const isLoading = ref(false);
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    async function getMovementIns(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        
        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get(`/${BASE}/movement-ins`,{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
                },
            });
            movementIns.value = data.value;
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
    async function storeMovementIn(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/movement-ins`, formData);
            movementIn.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.movement-ins.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMovementIn(movementInId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/movement-ins/${movementInId}`);
            movementIn.value = data.value;
console.log(movementIn.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMovementIn(form, movementInId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/movement-ins/${movementInId}`, formData);
            movementIn.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.movement-ins.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMovementIn(movementInId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/movement-ins/${movementInId}`);
            notification.showSuccess(status);
            await getMovementIns(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMovementIn(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-store-requisitions`,searchParam);
            filteredMovementIns.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }
    async function getMmrWiseMo(mmrId) {
        try {
            const {data, status} = await Api.get(`/${BASE}/get-mmr-wise-data`,{
                params: {
                    mmr_id: mmrId,
                },
            });
            filteredMovementRequisitionLines.value = data.value.scmMmrLines;
            console.log(filteredMovementRequisitionLines.value);
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }

 

    return {
        movementIns,
        movementIn,
        filteredMovementIns,
        searchMovementIn,
        getMovementIns,
        storeMovementIn,
        showMovementIn,
        updateMovementIn,
        deleteMovementIn,
        filteredMovementRequisitionLines,
        getMmrWiseMo,
        materialObject,
        isLoading,
        errors,
    };
}
