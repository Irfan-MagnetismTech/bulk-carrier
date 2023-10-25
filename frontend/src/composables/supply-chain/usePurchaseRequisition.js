import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from './../../store/index.js';

export default function usePurchaseRequisition() {
    const BASE = 'scm' 
    const router = useRouter();
    const purchaseRequisitions = ref([]);
    const filteredPurchaseRequisitions = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const purchaseRequisition = ref( {
        date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        remarks: '',
        attachment: null,
        is_critical: 0.0,
        purchase_center: '',
        approved_date: '',
        pr_ref: '',
        excel: null,
        entry_type: 0,
        business_unit: '',
        ScmPrLines: [
            {
                scmMaterial: '',
                scm_material_id: '',
                unit: '',
                brand: '',
                model: '',
                specification: '',
                origin: '',
                drawing_no: '',
                part_no: '',
                rob: '',
                quantity: 0.0,
                required_date: ''
            }
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        brand: '',
        model: '',
        specification: '',
        origin: '',
        drawing_no: '',
        part_no: '',
        rob: '',
        quantity: 0.0,
        required_date: ''
    }
    const excelExportData = ref( {
        store_category_name: ''
    });

    const errors = ref('');
    const isLoading = ref(false);
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    async function getPurchaseRequisitions(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        
        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get(`/${BASE}/purchase-requisitions`,{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
                },
            });
            purchaseRequisitions.value = data.value;
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
    async function storePurchaseRequisition(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }
        if(form.entry_type == '1'){
            formData.append('excel', form.excel);
        }
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/purchase-requisitions`, formData);
            purchaseRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.purchase-requisitions.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showPurchaseRequisition(purchaseRequisitionId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/purchase-requisitions/${purchaseRequisitionId}`);
            purchaseRequisition.value = data.value;
            purchaseRequisition.value.purchases = data.value.stockable;
            purchaseRequisition.value.purchases.forEach((purchase) => {
                purchase.purchase_category_name = purchase?.purchase_category?.name;
            });

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePurchaseRequisition(form, purchaseRequisitionId) {
        console.log(form);

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/purchase-requisitions`, formData);
            purchaseRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.purchase-requisitions.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deletePurchaseRequisition(purchaseRequisitionId) {

        if (!confirm('Are you sure you want to delete this data?')) {
            return;
        }

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/purchase-requisitions/${purchaseRequisitionId}`);
            notification.showSuccess(status);
            await getPurchaseRequisitions(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchPurchaseRequisition(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-purchase-requisition`,searchParam);
            filteredPurchaseRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    async function getStoreCategoryWiseExcel() {
        try {
            const { data, status } = await Api.get(`/${BASE}/store-category-wise-excel`, {
                params: {
                    store_category_name: excelExportData.value.store_category_name
                }
            });

            console.log(status);
        } catch (error) {
            if (error.response) {
                const { data, status } = error.response;
                console.log(data,error.response);
                notification.showError(status);
            } else {
                // Handle network or other errors here
                notification.showError("An error occurred. Please check your internet connection.");
            }
        } finally {
            // loading(false)
        }
    }

    return {
        purchaseRequisitions,
        purchaseRequisition,
        filteredPurchaseRequisitions,
        searchPurchaseRequisition,
        getPurchaseRequisitions,
        storePurchaseRequisition,
        showPurchaseRequisition,
        updatePurchaseRequisition,
        deletePurchaseRequisition,
        getStoreCategoryWiseExcel,
        materialObject,
        excelExportData,
        isLoading,
        errors,
    };
}
