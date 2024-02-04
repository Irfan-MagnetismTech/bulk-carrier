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

export default function useSupplierSelection() {
    const BASE = 'scm' 
    const router = useRouter();
    const materialCsLists = ref([]);
    const filteredMaterialCs = ref([]);
    const filteredQuotations = ref([]);
    const quotations = ref([]);
    const CsData = ref([]);
    const filteredMaterialCsLines = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const quotation = ref({
        auditor_remarks_date: null,
        auditor_remarks: null,
        selection_grounds: null,
    });

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function create(csId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const response = await Api.get(`${BASE}/getCsData/${csId}`);
           CsData.value = response.data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function store(form) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`${BASE}/selected-supplier`, formData);
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-cs.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            loader.hide();
        }
    }

    async function edit(CsId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const response = await Api.get(`${BASE}/quotation/${id}/edit`);
            if (response.status === 200) {
                quotation.value = response.data.value;
            }
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            loader.hide();
        }
    }

    async function update(id) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const response = await Api.put(`${BASE}/quotation/${id}`, quotation.value);
            if (response.status === 200) {
                notification.success(response.data.message);
                router.push({ name: "QuotationList" });
            }
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            loader.hide();
        }
    }


    return {
        quotation,
        quotations,
        errors,
        isLoading,
        create,
        store,
        edit,
        update,
        CsData,
        materialCsLists,
        filteredMaterialCs,
        filteredQuotations,
        filteredMaterialCsLines,
        isTableLoading,
        filterParams,
    };
}
