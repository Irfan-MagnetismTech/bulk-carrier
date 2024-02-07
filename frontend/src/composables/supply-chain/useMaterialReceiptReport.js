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
import Swal from 'sweetalert2';

export default function useMaterialReceiptReport() {
    const BASE = 'scm' 
    const router = useRouter();
    const materialReceiptReports = ref([]);
    const materialList = ref([]);
    const filteredMaterialReceiptReports = ref([]);
    const filteredCashRequisitions = ref([]);
    const polist = ref([]);
    const prlist = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const materialReceiptReport = ref( {
        ref_no: null,      
        date: null,        
        scm_po_id: null,        
        scmPo: null,
        // scmPr: null,
        // scm_pr_id: null,
        // scm_pr_no: null,      
        scm_warehouse_id: null,
        scmWarehouse: null,
        remarks: null,
        challan_no: null,
        qc_remarks: null,
        attachment: null,
        purchase_center: null,
        business_unit: null,
        scmMrrLines: [
            {
                scm_pr_id: null,
                scmPr: null,
                scmMrrLineItems: [
                    {
                        scm_mrr_item_id: null,
                        scmMaterial: null,
                        scm_material_id: null,
                        unit: null,
                        brand: null,
                        model: null,
                        quantity: 0.0,
                        tolerence: 0.0,
                        tolerence_qty: 0.0,
                        rate: 0.0,
                        net_rate: 0.0,
                        po_qty: 0.0,
                        pr_qty: 0.0,
                        current_stock: 0.0,
                        po_composite_key: null,
                        pr_composite_key: null
                    }
                ],
            }
        ],
    });
    const mrrLineObject = {
        scm_pr_id: null,
        scmPr: null,
        scmMrrLineItems: [
            {
                scm_mrr_item_id: null,
                scmMaterial: null,
                scm_material_id: null,
                unit: null,
                brand: null,
                model: null,
                quantity: 0.0,
                tolerence: 0.0,
                tolerence_qty: 0.0,
                rate: 0.0,
                net_rate: 0.0,
                po_qty: 0.0,
                pr_qty: 0.0,
                current_stock: 0.0,
                po_composite_key: null,
                pr_composite_key: null,
                isAspectDuplicate: false,
            }
        ],
    }

    const materialObject = {
        scm_mrr_item_id: null,
        scmMaterial: null,
        scm_material_id: null,
        unit: null,
        brand: null,
        model: null,
        quantity: 0.0,
        tolerence: 0.0,
        tolerence_qty: 0.0,
        rate: 0.0,
        net_rate: 0.0,
        po_qty: 0.0,
        pr_qty: 0.0,
        current_stock: 0.0,
        po_composite_key: null,
        pr_composite_key: null,
        isAspectDuplicate: false,
    }

    const poMaterialList = ref([]);
    const errors = ref('');
    const isLoading = ref(false);
    const isPrLoading = ref(false);
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
        if (!checkUniqueArray(form)) return;
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
        if (!checkUniqueArray(form)) return;
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

    function checkUniqueArray(form) {
        let isHasError = false;
        const messages = ref([]);
        let materialArray = [];
        form.scmMrrLines.map((scmMrrLine, scmMrrLineIndex) => {
             
            scmMrrLine.scmMrrLineItems.map((scmMrrLineItem, scmMrrLineItemIndex) => {
            let material_key = scmMrrLineItem.po_composite_key;
            if (materialArray.indexOf(material_key) === -1) {
                materialArray.push(material_key);
                form.scmMrrLines[scmMrrLineIndex].scmMrrLineItems[scmMrrLineItemIndex].isAspectDuplicate = false;
              } else {
                let data = `Duplicate Material Name Having Purchase Requisition ${scmMrrLine.scmPr.ref_no} in ${scmMrrLineIndex} Block Row: ${scmMrrLineItemIndex + 1}`;
                messages.value.push(data);
                form.scmMrrLines[scmMrrLineIndex].scmMrrLineItems[scmMrrLineItemIndex].isAspectDuplicate = true;
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

    async function getPoList(business_unit ,purchase_center,scm_warehouse_id) {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        console.log(business_unit, purchase_center, scm_warehouse_id);

        try {
            const {data, status} = await Api.get(`/${BASE}/get-po-list`,{
                params: {
                    business_unit: business_unit,
                    purchase_center: purchase_center,
                    scm_warehouse_id: scm_warehouse_id,
                },
            });
            polist.value = data.value;
            console.log(polist.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function getPoWisePrList(po_id) {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isPrLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-po-wise-pr-list`,{
                params: {
                    po_id: po_id,
                },
            });
            prlist.value = data.value;
            console.log(prlist.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isPrLoading.value = false;
            //NProgress.done();
        }
    }

    async function getMrrLineData(po_id, pr_id, mrr_id = null) {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-mrr-line-data`,{
                params: {
                    scm_pr_id: pr_id,
                    scm_po_id: po_id,
                    scm_mrr_id: mrr_id,
                },
            });
            console.log(data.value);
            return data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function getPoMaterialList(po_id, pr_id, mrr_id = null) {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/get-po-material-list`,{
                params: {
                    scm_pr_id: pr_id,
                    scm_po_id: po_id,
                    scm_mrr_id: mrr_id,
                },
            });
            console.log(data.value);
            return data.value;
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
        getPoList,
        polist,
        getCashRequisitionNoList,
        filteredCashRequisitions,
        isLoading,
        isPrLoading,
        prlist,
        getPoWisePrList,
        getMrrLineData,
        getPoMaterialList,
        poMaterialList,
        mrrLineObject,
        errors,
    };
    
}
