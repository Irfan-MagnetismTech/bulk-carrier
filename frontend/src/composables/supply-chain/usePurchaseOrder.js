import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';

export default function usePurchaseOrder() {
    const BASE = 'scm' 
    const router = useRouter();
    const purchaseOrders = ref([]);
    const filteredPurchaseOrders = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const purchaseOrder = ref( {
        raised_date: '',
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
        scmPrLines: [
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

    async function getPurchaseOrders(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        
        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get(`/${BASE}/purchase-orders`,{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
                },
            });
            purchaseOrders.value = data.value;
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
    async function storePurchaseOrder(form) {

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
            const { data, status } = await Api.post(`/${BASE}/purchase-orders`, formData);
            purchaseOrder.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.purchase-orders.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showPurchaseOrder(purchaseOrderId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/purchase-orders/${purchaseOrderId}`);
            purchaseOrder.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePurchaseOrder(form, purchaseOrderId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }

        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/purchase-orders/${purchaseOrderId}`, formData);
            purchaseOrder.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.purchase-orders.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deletePurchaseOrder(purchaseOrderId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/purchase-orders/${purchaseOrderId}`);
            notification.showSuccess(status);
            await getPurchaseOrders(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchPurchaseOrder(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-purchase-orders`,searchParam);
            filteredPurchaseOrders.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }



    return {
        purchaseOrders,
        purchaseOrder,
        filteredPurchaseOrders,
        searchPurchaseOrder,
        getPurchaseOrders,
        storePurchaseOrder,
        showPurchaseOrder,
        updatePurchaseOrder,
        deletePurchaseOrder,
        materialObject,
        isLoading,
        errors,
    };
}
