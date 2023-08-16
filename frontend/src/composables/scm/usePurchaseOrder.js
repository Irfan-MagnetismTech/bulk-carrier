import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function usePurchaseOrder() {
    const router = useRouter();
    const purchaseOrders = ref([]);
    const filteredPurchaseOrders = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const defaultVendor = ref([]);
    const poReceivedMaterials = ref([]);
    const materialObject = {
        stockable_type: "",
        stockable_id: "",
        item_type: "",
        item_key: "",
        material: "",
        material_id: "",
        size: "",
        quantity: "",
        unit: "Liter",
        unit_price: "",
        amount: "",
        // warehouse_id: "",
        // warehouse: "",
        // tank_id: "",
        // tank: "",
        remarks: "",
        previous_stock: "",
        present_stock: "",

    };

    

    const payOrderObject = {
        no: "",
        date: "",
        amount: "",
        bank_id: "",
        bank: "",
        bank_branch_id: "",
        bank_branch: "",
    };

    const purchaseOrder = ref( {
        pay_order_id: "",
        date: "",
        sequence: "",
        mpr_id: "",
        vendor: "",
        vendor_id: "",
        material_requisition: null,
        material_requisition_id: "",
        entry_by: "",
        cs_id: "",
        carrying_charge: "",
        labour_charge: "",
        sub_total: "",
        round_off: "",
        discount: "",
        grand_total: "",
        is_src_tax_applicable: "",
        is_src_vat_applicable: "",
        terms: "",
        remarks: "",
        pay_orders: [
            { ... payOrderObject }
        ],
        materials: [
            {
                ...materialObject
            }
        ]
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getPurchaseOrders(page,isPaginate = false) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/purchase-orders',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || false,
                },
            });
            purchaseOrders.value = data.value;
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/purchase-orders', form);
            purchaseOrder.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.purchase-order.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showPurchaseOrder(purchaseOrderId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/purchase-orders/${purchaseOrderId}`);
            purchaseOrder.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePurchaseOrder(form, purchaseOrderId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/purchase-orders/${purchaseOrderId}`,
                form
            );
            purchaseOrder.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.purchase-order.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deletePurchaseOrder(purchaseOrderId) {

        if (!confirm('Are you sure you want to delete this purchaseOrder?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/purchase-orders/${purchaseOrderId}`);
            notification.showSuccess(status);
            await getPurchaseOrders();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getDefaultVendor(purchaseOrderId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/default-vendor`);
            defaultVendor.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchPurchaseOrder(searchParam, loading) {
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const {data, status} = await Api.get('/common/search-purchase-order',{params: { searchParam: searchParam }});
            filteredPurchaseOrders.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            loading(false)

        }
    }

    async function getAllPendingPurchaseOrders(searchParam, loading) {
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const {data, status} = await Api.get('/common/get-all-purchase-orders');
            filteredPurchaseOrders.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            // loading(false)

        }
    }

    async function getPurchaseOrderReceivedMaterial(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/purchase-order/received-materials', form);
            poReceivedMaterials.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        purchaseOrders,
        searchPurchaseOrder,
        getAllPendingPurchaseOrders,
        filteredPurchaseOrders,
        defaultVendor,
        getDefaultVendor,
        materialObject,
        payOrderObject,
        purchaseOrder,
        getPurchaseOrders,
        storePurchaseOrder,
        showPurchaseOrder,
        updatePurchaseOrder,
        getPurchaseOrderReceivedMaterial,
        poReceivedMaterials,
        deletePurchaseOrder,
        isLoading,
        errors,
    };
}
