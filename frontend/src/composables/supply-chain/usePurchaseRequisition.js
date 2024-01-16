import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from './../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import useHelper from '../useHelper';
import Swal from 'sweetalert2';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';


export default function usePurchaseRequisition() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const purchaseRequisitions = ref([]);
    const filteredPurchaseRequisitions = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const purchaseRequisition = ref( {
        raised_date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        acc_cost_center_id: '',
        remarks: '',
        attachment: null,
        is_critical: 0.0,
        purchase_center: '',
        approved_date: '',
        ref_no: '',
        excel: null,
        entry_type: "0",
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
                country_name: '',
                drawing_no: '',
                part_no: '',
                rob: 0,
                quantity: 0.0,
                required_date: ''
            }
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        isAspectDuplicate: false,
        unit: '',
        brand: '',
        model: '',
        specification: '',
        origin: '',
        country_name: '',
        drawing_no: '',
        part_no: '',
        rob: '',
        quantity: 0.0,
        required_date: ''
    }
    const excelExportData = ref( {
        store_category_name: ''
    });

    const closingData = ref({
        closing_date: null,
        closing_remarks: null,
        scm_pr_id: null,
    });

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getPurchaseRequisitions(filterOptions) {
        //NProgress.start();
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
            const {data, status} = await Api.get(`/${BASE}/purchase-requisitions`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            purchaseRequisitions.value = data.value;
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
    async function storePurchaseRequisition(form) {
        if (!checkUniqueArray(form)) return;

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

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePurchaseRequisition(form, purchaseRequisitionId) {
        if (!checkUniqueArray(form)) return;

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }

        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/purchase-requisitions/${purchaseRequisitionId}`, formData);
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

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/purchase-requisitions/${purchaseRequisitionId}`);
            notification.showSuccess(status);
            await getPurchaseRequisitions(filterParams.value);
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
        form.scmPrLines.map((scmPrLine, scmPrLineIndex) => {
            let material_key = scmPrLine.scm_material_id + "-" + scmPrLine?.brand ?? + "-" + scmPrLine?.model ?? '';
            if (materialArray.indexOf(material_key) === -1) {
                materialArray.push(material_key);
                form.scmPrLines[scmPrLineIndex].isAspectDuplicate = false;
              } else {
                let data = `Duplicate Material Name [Material Name: ${scmPrLineIndex + 1}]`;
                messages.value.push(data);
                form.scmPrLines[scmPrLineIndex].isAspectDuplicate = true;
              }
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


    // async function searchPurchaseRequisition(searchParam, loading) {
    //     isLoading.value = true;

    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/search-purchase-requisitions`,searchParam);
    //         filteredPurchaseRequisitions.value = data.value;
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         loading(false)
    //         isLoading.value = false;
    //     }
    // }

    async function searchPr(business_unit, cost_center_id = null, searchParam = '') {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/search-pr`,{
                params: {
                    business_unit: business_unit,
                    searchParam: searchParam,
                    cost_center_id: cost_center_id,
                },
            });
            filteredPurchaseRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }


    async function searchPurchaseRequisition(business_unit, warehouse_id = null,purchase_center = null, cs_id = null ,searchParam = null) {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/search-purchase-requisitions`,{
                params: {
                    business_unit: business_unit,
                    searchParam: searchParam,
                    scm_warehouse_id: warehouse_id,
                    purchase_center: purchase_center,
                    cs_id: cs_id,
                },
            });
            filteredPurchaseRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }


    async function searchWarehouseWisePurchaseRequisition(scm_warehouse_id,searchParam, loading) {
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/search-purchase-requisitions`,scm_warehouse_id,searchParam);
            filteredPurchaseRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
            isLoading.value = false;
        }
    }

    async function getStoreCategoryWiseExcel() {
    
        try {
            const { data, status,headers} = await Api.get(`/${BASE}/export-materials`, {
                params: {
                    store_category: excelExportData.value.store_category_name,
                },
                responseType: 'blob'
            });
            downloadFile(data, 'materials',headers);

        } catch (error) {
            if (error.response) {
                const { data, status ,messege } = error.response;
                console.log(data,error.response);
                notification.showError(status);
            } else {
                notification.showError("An error occurred. Please check your internet connection.");
            }
        }finally {
            // isLoading.value = false;
        }

    }

    async function closePr(id,closing_remarks) {
        try {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('closing_remarks', closing_remarks);

            const { data, status } = await Api.post(`/${BASE}/close-pr`, formData);
            notification.showSuccess(status);
            await getPurchaseRequisitions(filterParams.value);
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

    async function closePrLines(parent_id,id,closing_remarks) {
        try {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('parent_id', parent_id);
            formData.append('closing_remarks', closing_remarks);

            const { data, status } = await Api.post(`/${BASE}/close-prline`, formData);
            notification.showSuccess(status);
            await showPurchaseRequisition(parent_id);
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
        searchWarehouseWisePurchaseRequisition,
        materialObject,
        searchPr,
        closePr,
        closePrLines,
        closingData,
        excelExportData,
        isTableLoading,
        isLoading,
        errors,
    };
}
