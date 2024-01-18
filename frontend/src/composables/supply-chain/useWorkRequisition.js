import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from './../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import useHelper from '../useHelper';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';
import Swal from 'sweetalert2';


export default function useWorkRequisition() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const workRequisitions = ref([]);
    const filteredWorkRequisitions = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const workRequisition = ref( {
        ref_no: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        acc_cost_center_id: '',
        raised_date: '',
        approved_date: '',
        purchase_center: '',
        entry_type: "0",
        attachment: null,
        remarks: '',
        business_unit: '',
        scmWrLines: [
            {
                scmService: '',
                scm_service_id: '',
                quantity: 0.0,
                required_date: '',
                description: '',
                remarks: '',
                business_unit: '',
            }
        ],

        // is_critical: 0.0,
        // purchase_center: '',
        // excel: null,
        // scmPrLines: [
        //     {
        //         scmMaterial: '',
        //         scm_material_id: '',
        //         unit: '',
        //         brand: '',
        //         model: '',
        //         specification: '',
        //         origin: '',
        //         country_name: '',
        //         drawing_no: '',
        //         part_no: '',
        //         rob: 0,
        //         quantity: 0.0,
        //         required_date: ''
        //     }
        // ],
    });
    // const materialObject = {
    //     scmMaterial: '',
    //     scm_material_id: '',
    //     unit: '',
    //     brand: '',
    //     model: '',
    //     specification: '',
    //     origin: '',
    //     country_name: '',
    //     drawing_no: '',
    //     part_no: '',
    //     rob: '',
    //     quantity: 0.0,
    //     required_date: ''
    // }
    // const excelExportData = ref( {
    //     store_category_name: ''
    // });

    const workObject = {
        scmService: '',
        scm_service_id: '',
        quantity: 0.0,
        required_date: '',
        description: '',
        remarks: '',
        business_unit: '',
    };

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getWorkRequisitions(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/work-requisitions`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            workRequisitions.value = data.value;
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
    async function storeWorkRequisition(form) {

        if (!checkUniqueArray(form)) return;

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }
        // if(form.entry_type == '1'){
        //     formData.append('excel', form.excel);
        // }
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/work-requisitions`, formData);
            workRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-requisitions.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showWorkRequisition(workRequisitionId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/work-requisitions/${workRequisitionId}`);
            workRequisition.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateWorkRequisition(form, workRequisitionId) {

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
            const { data, status } = await Api.post(`/${BASE}/work-requisitions/${workRequisitionId}`, formData);
            workRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-requisitions.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteWorkRequisition(workRequisitionId) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/work-requisitions/${workRequisitionId}`);
            notification.showSuccess(status);
            await getWorkRequisitions(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }

    async function searchWorkRequisition(business_unit, warehouse_id = null,purchase_center = null, cs_id = null ,searchParam = null) {
        //NProgress.start();
        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/${BASE}/search-work-requisitions`,{
                params: {
                    business_unit: business_unit,
                    searchParam: searchParam,
                    scm_warehouse_id: warehouse_id,
                    purchase_center: purchase_center,
                    scm_wcs_id: cs_id,
                },
            });
            filteredWorkRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            //NProgress.done();
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

    // async function searchPr(business_unit, cost_center_id = null, searchParam = '') {
    //     isLoading.value = true;

    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/search-pr`,{
    //             params: {
    //                 business_unit: business_unit,
    //                 searchParam: searchParam,
    //                 cost_center_id: cost_center_id,
    //             },
    //         });
    //         filteredPurchaseRequisitions.value = data.value;
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         loader.hide();
    //         isLoading.value = false;
    //     }
    // }


    // async function searchWarehouseWisePurchaseRequisition(scm_warehouse_id,searchParam, loading) {
    //     isLoading.value = true;

    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/search-purchase-requisitions`,scm_warehouse_id,searchParam);
    //         filteredPurchaseRequisitions.value = data.value;
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         loading(false)
    //         isLoading.value = false;
    //     }
    // }

    // async function getStoreCategoryWiseExcel() {
    
    //     try {
    //         const { data, status,headers} = await Api.get(`/${BASE}/export-materials`, {
    //             params: {
    //                 store_category: excelExportData.value.store_category_name,
    //             },
    //             responseType: 'blob'
    //         });
    //         downloadFile(data, 'materials',headers);

    //     } catch (error) {
    //         if (error.response) {
    //             const { data, status ,messege } = error.response;
    //             console.log(data,error.response);
    //             notification.showError(status);
    //         } else {
    //             notification.showError("An error occurred. Please check your internet connection.");
    //         }
    //     }finally {
    //         // isLoading.value = false;
    //     }

    // }

    async function closeWr(id,closing_remarks) {
        try {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('closing_remarks', closing_remarks);

            const { data, status } = await Api.post(`/${BASE}/close-wr`, formData);
            notification.showSuccess(status);
            await getWorkRequisitions(filterParams.value);
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

    async function closeWrLines(parent_id,id,closing_remarks) {
        try {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('parent_id', parent_id);
            formData.append('closing_remarks', closing_remarks);

            const { data, status } = await Api.post(`/${BASE}/close-wrline`, formData);
            notification.showSuccess(status);
            await showWorkRequisition(parent_id);
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

    function checkUniqueArray(form) {
        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.scmWrLines.some((scmWrLine, index) => {
            if (form.scmWrLines.filter(val => val.scm_service_id === scmWrLine.scm_service_id)?.length > 1) {
                let data = `Duplicate Service [Line no: ${index + 1}]`;
                messages.value.push(data);
                scmWrLine.isServiceDuplicate = true;
            } else {
                scmWrLine.isServiceDuplicate = false;
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

    return {
        workRequisitions,
        workRequisition,
        // filteredPurchaseRequisitions,
        // searchPurchaseRequisition,
        filteredWorkRequisitions,
        getWorkRequisitions,
        storeWorkRequisition,
        showWorkRequisition,
        updateWorkRequisition,
        deleteWorkRequisition,
        closeWr,
        closeWrLines,
        searchWorkRequisition,
        // getStoreCategoryWiseExcel,
        // searchWarehouseWisePurchaseRequisition,
        // materialObject,
        // searchPr,
        // excelExportData,
        workObject,
        isTableLoading,
        isLoading,
        errors,
    };
}
