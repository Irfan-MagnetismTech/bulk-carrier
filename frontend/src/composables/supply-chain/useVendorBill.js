import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from './../../store/index.js';
// import useFileDownload from 'vue-composable/dist/vue-composable.esm';
import NProgress from 'nprogress';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';
import useHelper from '../useHelper';



export default function useVendorBill() {
    const BASE = 'scm' 
    const { downloadFile } = useHelper();
    const router = useRouter();
    const vendorBills = ref([]);
    const filteredVendorBills = ref([]);
    const srWiseMaterials = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const vendorBill = ref( {
        date: '',
        scmVendor: '',
        scm_vendor_id: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        bill_no: '',
        currency: '',
        exchange_rate_bdt: null,
        exchange_rate_usd: null,
        business_unit: '',
        attachment: '',
        scmVendorBillLines: [],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        specification: '',
        quantity: 0.0,
    }

    const errors = ref();
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getVendorBills(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/vendor-bills`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            vendorBills.value = data.value;
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
    async function storeVendorBill(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/vendor-bills`, formData);
            vendorBill.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.vendor-bills.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showVendorBill(vendorBillId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/vendor-bills/${vendorBillId}`);
            vendorBill.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateVendorBill(form, vendorBillId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/vendor-bills/${vendorBillId}`, formData);
            vendorBill.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.vendor-bills.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteVendorBill(vendorBillId) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/vendor-bills/${vendorBillId}`);
            notification.showSuccess(status);
            await getVendorBills(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }

    async function searchVendorBill(searchParam, loading) {
        
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/search-vendor-bills`,searchParam);
            filteredVendorBills.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
            isLoading.value = false;
        }
    }

    const fetchSrWiseMaterials = async (vendorBillId = null) => {
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const { data, status } = await Api.get(`/${BASE}/search-sr-wise-material`, {
                params: {
                    sr_id: vendorBillId,
                    si_id: vendorBillId
                }
            });
            srWiseMaterials.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }
    

    return {
        vendorBills,
        vendorBill,
        filteredVendorBills,
        searchVendorBill,
        getVendorBills,
        storeVendorBill,
        showVendorBill,
        updateVendorBill,
        deleteVendorBill,
        fetchSrWiseMaterials,
        srWiseMaterials,
        materialObject,
        isTableLoading,
        isLoading,
        errors,
    };
}
