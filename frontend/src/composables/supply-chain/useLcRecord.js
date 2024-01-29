import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import { merge } from 'lodash';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';

export default function useLcRecord() {
    const BASE = 'scm' 
    const router = useRouter();
    const lcRecords = ref([]);
    const filteredLcRecords = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    // const LoaderConfig = { 'can-cancel': false, 'loader': 'dots', 'color': 'purple' };
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
        issue_bank_name: null,
        issueBank: null,
        advising_bank_id: null, 
        advising_bank_name: null, 
        advisingBank: null,
        discounting_bank_id: null,
        discounting_bank_name: null,
        discountingBank: null,
        beneficiary_bank_id: null,
        beneficiary_bank_name: null,
        beneficiaryBank: null,
        scm_vendor_id: null,
        scmVendor: null,
        scmWarehouse: null,
        scm_warehouse_id: null,
        attachment: null,
        type: null,
        acc_bank_id: null,
        bank_name: null,
        accBank: null,
        cfr_value: 0.0,
        lc_margin: 0.0,
        total_cost: 0.0,
        document_value: 0.0,
        exchange_rate: 0.0,
        market_rate: 0.0,
        business_unit: null,
        created_by: null,
        pi_ref_no: null,
        expected_shipment_date: null,
        shipment_date: null,
        scmLcRecordLines: [
                    ], 
    });
    
    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getLcRecords(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/lc-records`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            lcRecords.value = data.value;
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
    async function storeLcRecord(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('attachment', form.attachment);

        try {
            const { data, status } = await Api.post(`/${BASE}/lc-records`, formData);
            lcRecord.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.lc-records.index` });
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
            const { data, status } = await Api.get(`/${BASE}/lc-records/${lcRecordId}`);
            lcRecord.value = data.value;
            console.log('lcdata', lcRecord.value);
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
        formData.append('attachment', form.attachment);
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/lc-records/${lcRecordId}`, formData);
            lcRecord.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.lc-records.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteLcRecord(lcRecordId) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/lc-records/${lcRecordId}`);
            notification.showSuccess(status);
            await getLcRecords(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }

    async function searchLcRecord(searchParam, business_unit, scm_po_id, loading = false) {
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/search-lc-record`,{params: {searchParam: searchParam, business_unit: business_unit, scm_po_id: scm_po_id}});
            filteredLcRecords.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loading(false)
            isLoading.value = false;
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
        isTableLoading,
        isLoading,
        errors,
    };
}
