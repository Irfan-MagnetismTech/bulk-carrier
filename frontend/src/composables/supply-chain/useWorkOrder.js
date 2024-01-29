import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import { merge } from 'lodash';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';

export default function useWorkOrder() {
    const BASE = 'scm' 
    const router = useRouter();
    const workOrders = ref([]);
    const filteredWorkOrders = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    // const LoaderConfig = { 'can-cancel': false, 'loader': 'dots', 'color': 'purple' };
    // use lodash

    const workOrder = ref( {
        scmWcs: null,
        scm_wcs_id: null,
        ref_no: '',
        scm_warehouse_id: '',
        scmWarehouse: '',
        acc_cost_center_id: null,
        scmVendor: null,
        scm_vendor_id: null,
        currency: '',
        foreign_to_bdt: 0,
        foreign_to_usd: 0,
        remarks: '',
        business_unit: '',
        date: '',
        wr_no: null,
        scm_wr_id: null,
        scmWr: null,
        date: '',
        wcs_no: null,
        purchase_center: null,
        vendor_name: null,
        sub_total: 0.0,
        discount: 0.0,
        total_amount: 0.0,
        vat: 0.0,
        net_amount: 0.0,
        scmWoLines: [
            {
                scmWr: '',
                scm_wr_id: '',
                scmWoItems: [
                    {
                        scmService: '',
                        scm_service_id: '',
                        // unit: '',
                        // brand: '',
                        // model: '',
                        required_date: null,
                        tolarence_level: 0.0,
                        wr_composite_key: '',
                        wcs_composite_key: '',
                        wr_quantity: 0.0,
                        remaining_quantity: 0.0,
                        max_quantity: 0.0,
                        quantity: 0.0,
                        rate: 0.0,
                        total_price: 0.0,
                    }
                ]
            }
        ],
        scmWoTerms: [
                        {
                            description: ''
                        }
                    ],  
    });
    
    const wrServiceList = ref([]);
    const serviceObject = {
                scmService: '',
                scm_service_id: '',
                // unit: '',
                // brand: '',
                // model: '',
                required_date: null,
                tolerence: 0.0,
                wr_composite_key: '',
                wcs_composite_key: '',
                wr_quantity: 0.0,
                remaining_quantity: 0.0,
                max_quantity: 0.0,
                quantity: 0.0,
                rate: 0.0,
                total_price: 0.0,
             }

    const woLineObject = 
        {
            scmWr: '',
            scm_wr_id: '',
            scmWoItems: [
                {
                    scmService: '',
                    scm_service_id: '',
                    unit: '',
                    brand: '',
                    model: '',
                    required_date: null,
                    tolerence: 0.0,
                    pr_composite_key: '',
                    cs_composite_key: '',
                    pr_quantity: 0.0,
                    remaining_quantity: 0.0,
                    max_quantity: 0.0,
                    quantity: 0.0,
                    rate: 0.0,
                    total_price: 0.0,
                }
            ]
    }
    
    const serviceList = ref([
        [],
    ]);

    const termsObject =  {
        description: ''
    }

    const errors = ref(null);
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getWorkOrders(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/work-orders`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            workOrders.value = data.value;
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
    async function storeWorkOrder(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/work-orders`, formData);
            workOrder.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-orders.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showWorkOrder(workOrderId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/work-orders/${workOrderId}`);
            workOrder.value = data.value;
            console.log('wodata', workOrder.value);

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateWorkOrder(form, workOrderId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/work-orders/${workOrderId}`, formData);
            workOrder.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-orders.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteWorkOrder(workOrderId) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/work-orders/${workOrderId}`);
            notification.showSuccess(status);
            await getWorkOrders(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }

    async function searchWorkOrder(searchParam, business_unit) {
        isLoading.value = true;
        try {
        const { data, status } = await Api.get(`${BASE}/search-wo`, {params: {searchParam: searchParam, business_unit: business_unit}});
            filteredWorkOrders.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
        }
    }

    // async function searchPurchaseOrderForLc(searchParam, business_unit) {
    //     isLoading.value = true;
    //         try {
    //         const { data, status } = await Api.get(`${BASE}/search-po-for-lc`, {params: {searchParam: searchParam, business_unit: business_unit}});
    //             filteredPurchaseOrders.value = data.value;
    //         } catch (error) {
    //             const { data, status } = error.response;
    //             notification.showError(status);
    //         } finally {
    //             isLoading.value = false;
    //         }
    // }
    
    
    async function getWrAndWcsWiseWorkOrder(wrId, wcsId) {
        // const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-wr-wcs-wise-wo-data`,{
                params: {
                    scm_wr_id: wrId,
                    scm_wcs_id: wcsId,
                },
            });
            workOrder.value = merge(workOrder.value, data.value);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }

    
    async function getServiceList(wrId, wcsId=null, woId = null) {
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/search-wr-wise-service`,{
                params: {
                    scm_wr_id: wrId,
                    scm_wcs_id: wcsId,
                    scm_wo_id: woId,
                },
            });
            wrServiceList.value = data.value;
            return data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
        }
    }

    async function getLineData(wrId, wcsId = null, woId = null) { 
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/get-wo-line-datas`,{
                params: {
                    scm_wr_id: wrId,
                    scm_wcs_id: wcsId,
                    scm_wo_id: woId,
                },
            });
            return data.value;  
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
        }
    }
        

    return {
        workOrders,
        workOrder,
        filteredWorkOrders,
        // searchPurchaseOrder,
        searchWorkOrder,
        getWorkOrders,
        storeWorkOrder,
        showWorkOrder,
        updateWorkOrder,
        deleteWorkOrder,
        getWrAndWcsWiseWorkOrder,
        // searchPurchaseOrderForLc,
        // getMaterialList,
        // prMaterialList,
        getServiceList,
        wrServiceList,
        getLineData,
        serviceObject,
        woLineObject,
        termsObject,
        isTableLoading,
        serviceList,
        isLoading,
        errors,
    };
}