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
        raised_date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        remarks: '',
        attachment: null,
        is_critical: 0.0,
        purchase_center: '',
        approved_date: '',
        ref_no: '',
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

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePurchaseRequisition(form, purchaseRequisitionId) {
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
            const {data, status} = await Api.get(`/${BASE}/search-purchase-requisitions`,searchParam);
            filteredPurchaseRequisitions.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    async function getStoreCategoryWiseExcel() {
        
       
        // const { data, status } = await Api.get(`/${BASE}/store-category-wise-excel`);
        // api.get request with responseType blob
        // axios.get('http://localhost:8000/api/scm/export-materials', {
        //     responseType: 'blob'
        //   })
        //   .then(response => {

        //     //Create a Blob from the PDF Stream
        //     const file = new Blob(
        //       [response.data],
        //       {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}
        //     );
        //     //Build a URL from the file
        //     const fileURL = URL.createObjectURL(file);
        //     //Open the URL on new Window

        //download the file
        //     const link = document.createElement('a');
        //     link.href = fileURL;
        //     link.setAttribute('download', 'file.xlsx'); //or any other extension
        //     document.body.appendChild(link);
        //     link.click();
        //   })
        //   .catch(error => {
        
        axios({
            url: `/${BASE}/export-materials?store_category=${excelExportData.value.store_category_name}`,
            method: 'GET',
            responseType: 'blob', // important

            // send excel file to backend start
            // data: {
            //     store_category: excelExportData.value.store_category_name
            // },
            // send excel file to backend end

        }).then((response) => {
            // let dateTime = new Date();

            // //stream pdf file to new tab start
            // let fileURL = URL.createObjectURL(response.data);
            // let a = document.createElement('a');
            // a.href = fileURL;
            // a.target = '_blank';
            // a.click();
            // file name
            //stream pdf file to new tab end

            // download exel file star
            // const url = window.URL.createObjectURL(new Blob([response.data]));
            // const link = document.createElement('a');
            // download file nam

            let fileURL = URL.createObjectURL(response.data);
            let a = document.createElement('a');
            a.href = fileURL;
            a.target = '_blank';
            a.download = 'materials.xlsx';
            a.click();
            // other way to download file

        }).catch((error) => {
            if (error.response.status === 422) {
                const reader = new FileReader();
                reader.onload = function() {
                    const data = JSON.parse(reader.result);
                    const message = data.message;
                    console.log("Response message: " + message);
                    notification.showError(error.response.status, '', message);
                }
                reader.readAsText(error.response.data);
            } else {
                notification.showError(error.response.status, '', error.response.statusText);
            }
        }).finally(() => {
            // NProgress.done();
            isLoading.value = false;
        });




        // try {
        //     const { data, status } = await Api.get(`/${BASE}/export-materials`, {
        //         params: {
        //             store_category: excelExportData.value.store_category_name
        //         }
        //     });

        //     console.log(status);
        // } catch (error) {
        //     if (error.response) {
        //         const { data, status } = error.response;
        //         console.log(data,error.response);
        //         notification.showError(status);
        //     } else {
        //         // Handle network or other errors here
        //         notification.showError("An error occurred. Please check your internet connection.");
        //     }
        // } finally {
        //     // loading(false)
        // }
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
