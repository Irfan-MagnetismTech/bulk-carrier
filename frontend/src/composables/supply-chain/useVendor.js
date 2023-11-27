import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useVendor() {
    const BASE = 'scm' 
    const router = useRouter();
    const vendors = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const vendor = ref( {
        name: '',
        address: '',
        country_id: '',
        country_name: '',
        vendor_type: '',
        product_source_type: '',
        product_type: '',
        scmVendorContactPersons: [{
            name:'',
            designation:'',
            phone: '',
            email: ''
        }],
        
    });

    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'};
    const filterParams = ref(null);
    const errors = ref('');
    const isLoading = ref(false);

    async function getVendors(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/vendors`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            vendors.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { status } = error.response;
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

    async function storeVendor(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(`/${BASE}/vendors`, form);
            vendor.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.vendor.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showVendor(vendorId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/vendors/${vendorId}`);
            vendor.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateVendor(form, vendorId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/${BASE}/vendors/${vendorId}`,
                form
            );
            vendor.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.vendor.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteVendor(vendorId) {
        // const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/vendors/${vendorId}`);
            notification.showSuccess(status);
            await getVendors(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }

    async function searchVendor(searchParam, loading= false) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`${BASE}/search-vendor`, {params: { searchParam: searchParam }});
            vendors.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            // loading(false)
        }
    }

    return {
        vendors,
        vendor,
        getVendors,
        searchVendor,
        storeVendor,
        showVendor,
        updateVendor,
        deleteVendor,
        isTableLoading,
        isLoading,
        errors,
    };
}
