import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useService() {
    const BASE = 'scm' 
    const router = useRouter();
    const services = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    const service = ref( {
        name: '',
        short_code: '',
        description: ''
    });

    const filterParams = ref(null);
    const errors = ref('');
    const isLoading = ref(false);
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    async function getServices(filterOptions) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get(`/${BASE}/services`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            services.value = data.value;
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

    async function storeService(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(`/${BASE}/services`, form);
            service.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.service.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showService(serviceId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/services/${serviceId}`);
            service.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateService(form, serviceId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/${BASE}/services/${serviceId}`,
                form
            );
            service.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.service.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteService(serviceId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/services/${serviceId}`);
            notification.showSuccess(status);
            await getServices(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchService(searchParam, loading) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`${BASE}/search-service`, {params: { searchParam: searchParam }});
            services.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            loading(false)
        }
    }

    return {
        services,
        service,
        getServices,
        searchService,
        storeService,
        showService,
        updateService,
        deleteService,
        isTableLoading,
        isLoading,
        errors,
    };
}
