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

export default function useMaterialReceiptReport() {
    const BASE = 'scm' 
    const router = useRouter();
    const materialReceiptReports = ref([]);
    const materialList = ref([]);
    const filteredMaterialReceiptReports = ref([]);
    const filteredCashRequisitions = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const materialReceiptReport = ref( {
        ref_no: null,
        type: null,        
        date: null,        
        scm_po_id: null,        
        scmPo: null,
        scm_po_no: null,
        scm_po_date: null,
        scmPr: null,
        scm_pr_id: null,
        scm_pr_no: null,
        scm_cs_id: null,
        scmCs: null,
        scm_cs_no: null,
        scm_lc_record_id: null,
        scmLcRecord: null,        
        scm_warehouse_id: null,
        scmWarehouse: null,
        accCashRequisition: null,
        acc_cash_requisition_id: null,
        acc_cost_center_id: null,
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
    const filterParams = ref(null);

    async function getMaterialReceiptReports(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/material-receipt-reports`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            materialReceiptReports.value = data.value;
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
            console.log(materialReceiptReport.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialReceiptReport(form, materialReceiptReportId) {
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

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const { data, status } = await Api.delete( `/${BASE}/material-receipt-reports/${materialReceiptReportId}`);
            notification.showSuccess(status);
            await getMaterialReceiptReports(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            // isLoading.value = false;
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
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/search-material-receipt-reports`,searchParam);
            filteredMaterialReceiptReports.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
            isLoading.value = false;
        }
    }

    async function getMaterialList(pr_id, po_id = null,warehouse_id,mrr_id = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-material-for-mrr`,{
                params: {
                    scm_pr_id: pr_id,
                    scm_po_id: po_id,
                    scm_warehouse_id: warehouse_id,
                    scm_mrr_id: mrr_id,
                },
            });
            materialList.value = data.value;
            console.log(materialList.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function searchMrr(business_unit, cost_center_id = null, searchParam = '') {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/search-mrr`,{
                params: {
                    business_unit: business_unit,
                    searchParam: searchParam,
                    cost_center_id: cost_center_id,
                },
            });
            filteredMaterialReceiptReports.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    //get Cash Requisition no list
    async function getCashRequisitionNoList(cost_center_id , business_unit = null) {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.post(`/acc/get-cash-requisitions`,{
                params: {
                    cost_center_id: cost_center_id,
                },
            });
            filteredCashRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        materialReceiptReports,
        filteredMaterialReceiptReports,
        materialReceiptReport,
        getMaterialReceiptReports,
        storeMaterialReceiptReport,
        showMaterialReceiptReport,
        updateMaterialReceiptReport,
        deleteMaterialReceiptReport,
        searchMaterialReceiptReport,
        getPrAndPoWiseMrrData,
        getMaterialList,
        materialList,
        materialObject,
        searchMrr,
        isTableLoading,
        getCashRequisitionNoList,
        filteredCashRequisitions,
        isLoading,
        errors,
    };
    
}
