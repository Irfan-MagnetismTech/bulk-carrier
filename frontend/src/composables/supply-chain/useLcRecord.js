import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import { merge } from 'lodash';


export default function useLcRecord() {
    const BASE = 'scm' 
    const router = useRouter();
    const lcRecords = ref([]);
    const filteredLcRecords = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    const LoaderConfig = { 'can-cancel': false, 'loader': 'dots', 'color': 'purple' };
    // use lodash

    const lcRecord = ref({
        lc_no: null,
        lc_date: null,
        expire_date: null,
        weight: null,
        no_of_packet: 0,
        scm_po_id: null,
        scmPo: null,
        invoice_value: 0,
        assessment_value: 0,
        issue_bank_id: null,
        issueBank: null,
        advising_bank_id: null,
        advisingBank: null,
        discounting_bank_id: null,
        discountingBank: null,
        beneficiary_bank_id: null,
        beneficiaryBank: null,
        type: null,
        acc_bank_id: null,
        accBank: null,
        cfr_value: 0.0,
        lc_margin: 0.0,
        insurance_payment: 0.0,
        total_cost: 0.0,
        cfr_margin: 0.0,
        exchange_rate: 0.0,
        market_rate: 0.0,
        business_unit: null,
        attachment: null,
        created_by: null,
        scm_vendor_id: null,
        scmVendor: null,
        scmLcRecordLines: [
                        {
                            particular: '',
                            amount: 0,
                        }
                    ], 
        });
    const errors = ref('');
    const isLoading = ref(false);
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    async function getLcRecords(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        
        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get(`/${BASE}/purchase-orders`,{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
                },
            });
            lcRecords.value = data.value;
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
    async function storeLcRecord(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/purchase-orders`, formData);
            lcRecord.value = data.value;
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

    async function showLcRecord(lcRecordId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/purchase-orders/${lcRecordId}`);
            lcRecord.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateLcRecord(form, lcRecordId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/purchase-orders/${lcRecordId}`, formData);
            lcRecord.value = data.value;
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

    async function deleteLcRecord(lcRecordId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/purchase-orders/${lcRecordId}`);
            notification.showSuccess(status);
            await getLcRecords(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchLcRecord(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get(`/${BASE}/search-purchase-orders`,searchParam);
            filteredLcRecords.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }
    



    return {
        lcRecords,
        lcRecord,
        filteredLcRecords,
        searchLcRecord,
        getLcRecords,
        storeLcRecord,
        showLcRecord,
        updateLcRecord,
        deleteLcRecord,
        isLoading,
        errors,
    };
}
