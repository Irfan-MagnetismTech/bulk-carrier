import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useService() {
    const router = useRouter();
    const services = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const service = ref( {
        name: '',
        short_code: '',
        description: ''
    });

    const indexPage = ref(null);
    const errors = ref('');
    const isLoading = ref(false);

    async function getServices(page,columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        indexPage.value = page;

        try {
            const {data, status} = await Api.get('/scm/Service', {
				params: {
					page: page || 1,
					columns: columns || null,
					searchKey: searchKey || null,
					table: table || null,
				},
			});
            services.value = data.value;
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

    async function storeService(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/services', form);
            service.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "supply-chain.service.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showService(serviceId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/services/${vendorId}`);
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/services/${serviceId}`,
                form
            );
            service.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "supply-chain.service.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteService(serviceId) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/services/${serviceId}`);
            notification.showSuccess(status);
            await getServices(indexPage.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchService(searchParam, loading) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`scm/search-service`, {params: { searchParam: searchParam }});
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
        isLoading,
        errors,
    };
}
