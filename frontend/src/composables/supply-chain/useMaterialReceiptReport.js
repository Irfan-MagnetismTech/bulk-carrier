import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';
import { merge } from 'lodash';


export default function useMaterialReceiptReport() {
    const BASE = 'scm' 
    const router = useRouter();
    const materialReceiptReports = ref([]);
    const filteredMaterialRreceiptReports = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const materialReceiptReport = ref( {
        ref_no: null,
        type: null,        
        date: null,        
        scm_po_id: null,        
        scmPo: null,
        scm_po_date: null,
        scm_pr_id: null,
        scmPr: null,
        scm_cs_id: null,
        scmCs: null,
        scm_lc_record_id: null,
        scmLcRecord: null,        
        scm_warehouse_id: null,
        acc_iou_id: null,
        accIou: null,
        scmWarehouse: null,
        acc_cost_center_id: null,
        scm_po_no: null,
        scm_cs_no: null,
        scm_pr_no: null,
        scm_iou_no: null,
        remarks: null,
        challan_no: null,
        qc_remarks: null,
        attachment: null,
        purchase_center: null,
        is_completed: 0,
        business_unit: null,
        scmMrrLines: [
            {
                scmMaterial: '',
                scm_material_id: '',
                unit: '',
                brand: '',
                model: '',
                quantity: 0.0,
                rate: 0.0,
                net_rate: 0.0,
                po_qty: 0.0,
                pr_qty: 0.0,
                current_stock: 0.0,
                po_composite_key: '',
                pr_composite_key: ''
            }
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        brand: '',
        model: '',
        quantity: 0.0,
        rate: 0.0,
        net_rate: 0.0,
        po_qty: 0.0,
        pr_qty: 0.0,
        current_stock: 0.0,
        po_composite_key: '',
        pr_composite_key: ''
    }

    const errors = ref('');
    const isLoading = ref(false);
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    async function getMaterialReceiptReports(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        
        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get(`/${BASE}/material-receipt-reports`,{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
                },
            });
            materialReceiptReports.value = data.value;
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
    async function storeMaterialReceiptReport(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        try {
            const { data, status } = await Api.post(`/${BASE}/material-receipt-reports`, formData);
            materialReceiptReport.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-receipt-reports.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialReceiptReport(materialReceiptReportId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const { data, status } = await Api.get(`/${BASE}/material-receipt-reports/${materialReceiptReportId}`);
            materialReceiptReport.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialReceiptReport(materialReceiptReportId, form) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/material-receipt-reports/${materialReceiptReportId}`, formData);
            materialReceiptReport.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-receipt-reports.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialReceiptReport(materialReceiptReportId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const { data, status } = await Api.delete( `/${BASE}/material-receipt-reports/${materialReceiptReportId}`);
            notification.showSuccess(status);
            await getMaterialReceiptReports(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    //get po and pr wise data
    async function getPrAndPoWiseMrrData(pr_id, po_id) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-po-or-pr-wise-mrr`,{
                params: {
                    pr_id: pr_id,
                    po_id: po_id,
                },
            });
            materialReceiptReport.value = merge(materialReceiptReport.value, data.value);
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
    async function searchMaterialReceiptReport(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-material-receipt-reports`,searchParam);
            filteredMaterialReceiptReports.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }


    return {
        materialReceiptReports,
        filteredMaterialRreceiptReports,
        materialReceiptReport,
        getMaterialReceiptReports,
        storeMaterialReceiptReport,
        showMaterialReceiptReport,
        updateMaterialReceiptReport,
        deleteMaterialReceiptReport,
        searchMaterialReceiptReport,
        getPrAndPoWiseMrrData,
        materialObject,
        isLoading,
        errors,
    };
    
}
