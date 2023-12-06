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
        scmCs: '',
        scm_cs_id: '',
        scm_vendor_id: '',
        scmVendor: '',
        vendor_type: '',
        sourcing: '',
        date_of_rfq: '',
        quotations_received_date: '',
        quotation_ref: '',
        quotation_date: '',
        quotation_validity: '',
        payment_method: '',
        currency: '',
        carring_cost_bear_by: '',//local
        unloading_cost_bear_by: '',//local
        vat: '',//local
        ait: '',//local
        credit_term: '',//local
        quotation_shipment_date: '',
        estimated_shipment: '',//foreign
        port_of_loading: '',//foreign
        port_of_discharge: '',//foreign
        port_of_shipment: '',//foreign
        mode_of_shipment: '',//foreign
        delivery_term: '',
        terms_and_condition: '',
        remarks: '',
        attachment: '',
        scmCsVendorMaterial: [
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
    async function storeQuotations(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/quotations`, formData);
            materialCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.quotations.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showQuotations(materialCsId) {
        console.log('tag', materialCsId);
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/quotations/${materialCsId}`);
            materialCs.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateQuotations(form, materialCsId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/quotations/${materialCsId}`, formData);
            materialCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.quotations.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteQuotations(materialCsId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/quotations/${materialCsId}`);
            notification.showSuccess(status);
            await getMaterialCs(filterParams.value);
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
        showQuotations,
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
