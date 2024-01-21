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

export default function useWcsSupplierSelection() {
    const BASE = 'scm' 
    const router = useRouter();
    const workCsLists = ref([]);
    const filteredWorkCs = ref([]);
    const filteredWcsQuotations = ref([]);
    const wcsQuotations = ref([]);
    const WcsData = ref([]);
    const filteredWorkCsLines = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const wcsQuotation = ref({
        auditor_remarks_date: null,
        auditor_remarks: null,
        selection_grounds: null,
    });

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function create(wcsId) {
        try {
            isLoading.value = true;
            const response = await Api.get(`${BASE}/getWcsData/${wcsId}`);
            WcsData.value = response.data.value;
        } catch (error) {
            console.log(error);
        } finally {
            isLoading.value = false;
        }
    }

    async function store(form) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`${BASE}/wcs-selected-supplier`, formData);
            notification.showSuccess(status);
            router.push({ name: `${BASE}.work-cs.index` });
        } catch (error) {
            console.log(error);
        } finally {
            isLoading.value = false;
            loader.hide();
        }
    }

    // async function edit(CsId) {
    //     try {
    //         isLoading.value = true;
    //         const response = await Api.get(`${BASE}/quotation/${id}/edit`);
    //         if (response.status === 200) {
    //             quotation.value = response.data.value;
    //         }
    //     } catch (error) {
    //         notification.error(error.response.data.message);
    //     } finally {
    //         isLoading.value = false;
    //     }
    // }

    // async function update(id) {
    //     try {
    //         isLoading.value = true;
    //         const response = await Api.put(`${BASE}/quotation/${id}`, quotation.value);
    //         if (response.status === 200) {
    //             notification.success(response.data.message);
    //             router.push({ name: "QuotationList" });
    //         }
    //     } catch (error) {
    //         notification.error(error.response.data.message);
    //     } finally {
    //         isLoading.value = false;
    //     }
    // }


    return {
        wcsQuotation,
        wcsQuotations,
        errors,
        isLoading,
        create,
        store,
        // edit,
        // update,
        WcsData,
        workCsLists,
        filteredWorkCs,
        filteredWcsQuotations,
        filteredWorkCsLines,
        isTableLoading,
        filterParams,
    };
}
