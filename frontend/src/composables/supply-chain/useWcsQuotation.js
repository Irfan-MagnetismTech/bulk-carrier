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

export default function useWcsQuotation() {
    const BASE = 'scm' 
    const router = useRouter();
    const workCsLists = ref([]);
    const filteredWorkCs = ref([]);
    const filteredWcsQuotations = ref([]);
    const wcsQuotations = ref([]);
    const filteredWorkCsLines = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const wcsQuotation = ref({
        scmWcs: null,
        scm_wcs_id: null,

        scm_vendor_id: null,
        scmVendor: null,

        quotation_ref_no: null,
        quotation_date: null,
        quotation_validity: null,

        payment_mode: "",
        credit_term: null,
        vat: '',//local
        ait: '',//local
        currency: '',
        security_money: 0,
        adjustment_policy: null,
        attachment: null,
        terms_and_condition: null,
        remarks: null,


        // vendor_type: null,
        // sourcing: null,
        // date_of_rfq: null,
        // quotations_received_date: null,
        // currency: null,
        // carring_cost_bear_by: null,//local
        // unloading_cost_bear_by: null,//local
        // credit_term: null,//local
        // quotation_shipment_date: null,
        // quotation_received_date: null,
        // estimated_shipment: null,//foreign
        // port_of_loading: null,//foreign
        // port_of_discharge: null,//foreign
        // port_of_shipment: null,//foreign
        // mode_of_shipment: null,//foreign
        // delivery_term: null,
        // stock_type: null,
        // manufaturing_days: null,
        // warranty: null,
        scmWcsVendorServices: [
            {
                scmWr: null,
                scm_wr_id: null,
                scmService: null,
                scm_service_id: null,
                quantity: null,
                rate: 0,  

                // brand: null,
                // model: null,
                // origin: null,
                // stock_type: null,
                // manufaturing_days: null,
                // unit: null,
                // offered_price: null,
                // negotiated_price: null,
            },
        ],
    });

    const wcsQuotationLines = {
        scmWr: null,
        scm_wr_id: null,
        scmService: null,
        scm_service_id: null,
        quantity: null,
        rate: 0,  
    } 

    
    // const localQuotationLines = {
    //     scmMaterial: null,
    //     scm_material_id: null,
    //     brand: null,
    //     model: null,
    //     unit: null,
    //     negotiated_price: null,
    // } 



    // const foreignQuotationLines = {
    //     scmMaterial: null,
    //     scm_material_id: null,
    //     brand: null,
    //     model: null,
    //     origin: null,
    //     stock_type: null,
    //     manufaturing_days: null,
    //     unit: null,
    //     offered_price: null,
    //     negotiated_price: null,
    // } 
    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getWcsQuotations(wcsId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/wcs-quotations`,{
                params: {
                    scm_wcs_id: wcsId,
                },
            });
            wcsQuotations.value = data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            isLoading.value = false;
            loader.hide();
        }
    }
    
    async function storeWcsQuotations(form,wcsId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/wcs-quotations`, formData);
            // materialCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.wcs-quotations.index` , params: { wcsId: wcsId }});
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showWcsQuotation(wcsId,wcsQuotationId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/wcs-quotations/${wcsQuotationId}`);
            wcsQuotation.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateWcsQuotations(form, wcsId,wcsQuotationId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            // console.log("object");
            formData.append('attachment', form.attachment);
        }
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/wcs-quotations/${wcsQuotationId}`, formData);
            wcsQuotation.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.wcs-quotations.index` , params: { wcsId: wcsId }});
        } catch (error) {
            console.log('tag', error)
            // const { data, status } = error.response;
            // errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteWcsQuotations(wcsId,wcsQuotationId) {
        // console.log('tag', quotationId);
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/wcs-quotations/${wcsQuotationId}`);
            notification.showSuccess(status);
            await getWcsQuotations(wcsId);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    // async function searchMaterialCs(searchParam, loading) {
        

    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/search-material-cs`,searchParam);
    //         filteredMaterialCs.value = data.value;
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         loading(false)
    //     }
    // }

   


    // async function getPrWiseCs(prId) {
    //     //NProgress.start();
    //     // const loader = $loading.show(LoaderConfig);
    //     // isLoading.value = true;
    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/get-pr-wise-cs-data`,{
    //             params: {
    //                 pr_id: prId,
    //             },
    //         });
    //         materialCs.value =  merge(materialCs.value, data.value);
    //     } catch (error) {
    //         console.log('tag', error)
    //     } finally {
    //         //NProgress.done();
    //     }
    // }


    // async function getQuotations(csId) {
    //     // NProgress.start();
    //     const loader = $loading.show(LoaderConfig);
    //     isLoading.value = true;
    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/get-quotations`,{
    //             params: {
    //                 cs_id: csId,
    //             },
    //         });
    //         quotations.value = data.value;
    //     } catch (error) {
    //         console.log('tag', error)
    //     } finally {
    //         isLoading.value = false;
    //         loader.hide();
    //     }
    // }
 

    return {
        workCsLists,
        filteredWorkCs,
        storeWcsQuotations,
        showWcsQuotation,
        updateWcsQuotations,
        deleteWcsQuotations,
        filteredWcsQuotations,
        getWcsQuotations,
        wcsQuotations,
        wcsQuotationLines,
        // localQuotationLines,
        // foreignQuotationLines,
        // getSiWiseData,
        isTableLoading,
        wcsQuotation,
        isLoading,
        errors,
    };
}
