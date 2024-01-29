import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';
import { merge } from 'lodash';


export default function useQuotation() {
    const BASE = 'scm' 
    const router = useRouter();
    const materialCsLists = ref([]);
    const filteredMaterialCs = ref([]);
    const filteredQuotations = ref([]);
    const quotations = ref([]);
    const filteredMaterialCsLines = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const quotation = ref({
        scmCs: null,
        scm_cs_id: null,
        scm_vendor_id: null,
        scmVendor: null,
        vendor_type: null,
        sourcing: null,
        date_of_rfq: null,
        quotations_received_date: null,
        quotation_ref: null,
        quotation_date: null,
        quotation_validity: null,
        payment_method: null,
        currency: null,
        carring_cost_bear_by: null,//local
        unloading_cost_bear_by: null,//local
        vat: null,//local
        ait: null,//local
        credit_term: null,//local
        quotation_shipment_date: null,
        estimated_shipment: null,//foreign
        port_of_loading: null,//foreign
        port_of_discharge: null,//foreign
        port_of_shipment: null,//foreign
        mode_of_shipment: null,//foreign
        delivery_term: null,
        terms_and_condition: null,
        remarks: null,
        attachment: null,
        scmCsMaterialVendors: [
            {
                scmMaterial: null,
                scm_material_id: null,
                brand: null,
                model: null,
                origin: null,
                stock_type: null,
                manufaturing_days: null,
                unit: null,
                offered_price: null,
                negotiated_price: null,
            },
        ],
    });

    const localQuotationLines = {
        scmMaterial: null,
        scm_material_id: null,
        brand: null,
        model: null,
        unit: null,
        negotiated_price: null,
    } 

    const foreignQuotationLines = {
        scmMaterial: null,
        scm_material_id: null,
        brand: null,
        model: null,
        origin: null,
        stock_type: null,
        manufaturing_days: null,
        unit: null,
        offered_price: null,
        negotiated_price: null,
    } 
    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getQuotations(csId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/quotations`,{
                params: {
                    cs_id: csId,
                },
            });
            quotations.value = data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            isLoading.value = false;
            loader.hide();
        }
    }
    
    async function storeQuotations(form,csId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/quotations`, formData);
            // materialCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.quotations.index` , params: { csId: csId }});
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showQuotation(csId,quotationId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/quotations/${quotationId}`);
            quotation.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateQuotations(form, csId,quotationId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/quotations/${quotationId}`, formData);
            quotation.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.quotations.index` , params: { csId: csId }});
        } catch (error) {
            console.log('tag', error)
            // const { data, status } = error.response;
            // errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteQuotations(csId,quotationId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/quotations/${quotationId}`);
            notification.showSuccess(status);
            await getQuotations(csId);
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
        materialCsLists,
        filteredMaterialCs,
        storeQuotations,
        showQuotation,
        updateQuotations,
        deleteQuotations,
        filteredQuotations,
        getQuotations,
        quotations,
        localQuotationLines,
        foreignQuotationLines,
        // getSiWiseData,
        isTableLoading,
        quotation,
        isLoading,
        errors,
    };
}
