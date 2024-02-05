import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import { merge } from 'lodash';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';
import Swal from 'sweetalert2';

export default function usePurchaseOrder() {
    const BASE = 'scm' 
    const router = useRouter();
    const purchaseOrders = ref([]);
    const poMaterials = ref([]);
    const filteredPurchaseOrders = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    // const LoaderConfig = { 'can-cancel': false, 'loader': 'dots', 'color': 'purple' };
    // use lodash

    const purchaseOrder = ref( {
        ref_no: null,
        scmWarehouse: null,
        scm_warehouse_id: null,
        acc_cost_center_id: null,
        date: null,
        date: null,
        cs_no: null,
        purchase_center: null,
        scm_cs_id: null,
        scmCs: null,
        scmVendor: null,
        scm_vendor_id: null,
        vendor_name: null,
        currency: null,
        usd_to_bdt: 0,
        foreign_to_usd: 0,
        remarks: null,
        sub_total: 0.0,
        discount: 0.0,
        total_amount: 0.0,
        vat: 0.0,
        net_amount: 0.0,
        business_unit: null,
        scmPoLines: [
            {
                scmPr: null,
                scm_pr_id: null,
                scmPoItems: [
                    {
                        scmMaterial: null,
                        scm_material_id: null,
                        unit: null,
                        brand: null,
                        model: null,
                        required_date: null,
                        tolerence_level: 0.0,
                        pr_composite_key: null,
                        cs_composite_key: null,
                        pr_quantity: 0.0,
                        remaining_quantity: 0.0,
                        max_quantity: 0.0,
                        quantity: 0.0,
                        isAspectDuplicate: false,
                        rate: 0.0,
                        total_price: 0.0,
                    }
                ]
            }
        ],
        scmPoTerms: [
                        {
                            description: null
                        }
                    ],  
    });
    
    const prMaterialList = ref([]);
    const materialObject = {
                scmMaterial: null,
                scm_material_id: null,
                unit: null,
                brand: null,
                model: null,
                required_date: null,
                tolerence_level: 0.0,
                pr_composite_key: null,
                cs_composite_key: null,
                pr_quantity: 0.0,
                isAspectDuplicate: false,
                remaining_quantity: 0.0,
                max_quantity: 0.0,
                quantity: 0.0,
                rate: 0.0,
                total_price: 0.0,
             }

    const poLineObject = 
        {
            scmPr: null,
            scm_pr_id: null,
            scmPoItems: [
                {
                    scmMaterial: null,
                    scm_material_id: null,
                    unit: null,
                    brand: null,
                    model: null,
                    required_date: null,
                    tolerence: 0.0,
                    pr_composite_key: null,
                    cs_composite_key: null,
                    pr_quantity: 0.0,
                    remaining_quantity: 0.0,
                    max_quantity: 0.0,
                    quantity: 0.0,
                    rate: 0.0,
                    total_price: 0.0,
                }
            ]
    }
    
    const materialList = ref([[],]);

    const termsObject =  {
        description: null
    }

    const errors = ref(null);
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getPurchaseOrders(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/purchase-orders`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            purchaseOrders.value = data.value;
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
    async function storePurchaseOrder(form) {
        if (!checkUniqueArray(form)) return;
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
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
            console.log('podata', purchaseOrder.value);

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePurchaseOrder(form, purchaseOrderId) {
        if (!checkUniqueArray(form)) return;
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
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

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/purchase-orders/${purchaseOrderId}`);
            notification.showSuccess(status);
            await getPurchaseOrders(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }

    async function searchPurchaseOrder(searchParam, business_unit) {
        isLoading.value = true;
        try {
        const { data, status } = await Api.get(`${BASE}/search-po`, {params: {searchParam: searchParam, business_unit: business_unit}});
            filteredPurchaseOrders.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loading(false)
            isLoading.value = false;
        }
    }

    async function searchPurchaseOrderForLc(searchParam, business_unit) {
        isLoading.value = true;
            try {
            const { data, status } = await Api.get(`${BASE}/search-po-for-lc`, {params: {searchParam: searchParam, business_unit: business_unit}});
                filteredPurchaseOrders.value = data.value;
            } catch (error) {
                const { data, status } = error.response;
                notification.showError(status);
            } finally {
                // loading(false)
                isLoading.value = false;
            }
    }
    
    
    async function getPrAndCsWisePurchaseOrder(prId, csId) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-pr-cs-wise-po-data`,{
                params: {
                    pr_id: prId,
                    cs_id: csId,
                },
            });
            purchaseOrder.value = merge(purchaseOrder.value, data.value);
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

    
    async function getMaterialList(prId,csId=null,poId = null) {
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/search-pr-wise-material`,{
                params: {
                    pr_id: prId,
                    po_id: poId,
                    cs_id: csId,
                },
            });
            prMaterialList.value = data.value;
            return data.value;
            console.log('prMaterialList', prMaterialList.value);    
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
        }
    }

    async function getLineData(prId, csId=null,poId = null) { 
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/get-po-line-datas`,{
                params: {
                    pr_id: prId,
                    cs_id: csId,
                    po_id: poId,
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

    async function closePo(id,closing_remarks) {
        try {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('closing_remarks', closing_remarks);

            const { data, status } = await Api.post(`/${BASE}/close-po`, formData);
            notification.showSuccess(status);
            await getPurchaseOrders(filterParams.value);
        }
        catch (error) {
            if (error.response) {
                const { data, status ,messege } = error.response;
                console.log(data,error.response);
                notification.showError(status);
            } else {
                notification.showError("An error occurred. Please check your internet connection.");
            }

        } finally {
            // isLoading.value = false;
        }

    }

    async function closePoLines(parent_id,id,closing_remarks) {
        try {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('parent_id', parent_id);
            formData.append('closing_remarks', closing_remarks);

            const { data, status } = await Api.post(`/${BASE}/close-poline`, formData);
            notification.showSuccess(status);
            await showPurchaseOrder(parent_id);
        }
        catch (error) {
            if (error.response) {
                const { data, status ,messege } = error.response;
                console.log(data,error.response);
                notification.showError(status);
            } else {
                notification.showError("An error occurred. Please check your internet connection.");
            }

        } finally {
            // isLoading.value = false;
        }
    }
    async function getPoMaterials(poId) {
        // const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-po-material-by-po-id`,{
                params: {
                    scm_po_id: poId
                },
            });
            poMaterials.value =data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }
    function checkUniqueArray(form) {
        let isHasError = false;
        const messages = ref([]);
        let materialArray = [];
        form.scmPoLines.map((scmPoLine, scmPoLineIndex) => {
             
            scmPoLine.scmPoItems.map((scmPoitem, scmPoitemIndex) => {
            let material_key = scmPoitem.pr_composite_key;
            if (materialArray.indexOf(material_key) === -1) {
                materialArray.push(material_key);
                form.scmPoLines[scmPoLineIndex].scmPoItems[scmPoitemIndex].isAspectDuplicate = false;
              } else {
                let data = `Duplicate Material Name Having Purchase Requisition ${scmPoLine.scmPr.ref_no} in ${scmPoLineIndex} Block Row: ${scmPoitemIndex + 1}`;
                messages.value.push(data);
                form.scmPoLines[scmPoLineIndex].scmPoItems[scmPoitemIndex].isAspectDuplicate = true;
              }
            });
        });
        if (messages.value.length > 0) {
            let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
            if (Object.keys(messages.value).length) {
                for (const property in messages.value) {
                    rawHtml += `<li> ${messages.value[property]} </li>`
                }
                rawHtml += `</ul>`;

                Swal.fire({
                    icon: "",
                    title: "Correct Please!",
                    html: `
                ${rawHtml}
                        `,
                    customClass: "swal-width",
                });
                return false;
            }
        } else {
            return true;
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
        getPrAndCsWisePurchaseOrder,
        searchPurchaseOrderForLc,
        getMaterialList,
        prMaterialList,
        getLineData,
        getPoMaterials,
        poMaterials,
        materialObject,
        poLineObject,
        termsObject,
        isTableLoading,
        closePo,
        closePoLines,
        materialList,
        isLoading,
        errors,
    };
}