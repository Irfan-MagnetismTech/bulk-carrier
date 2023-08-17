import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useService() {
    const router = useRouter();
    const $loading = useLoading();
    const services = ref([]);
    const notification = useNotification();
    const bounds = ref({});
    const sectors = ref({})
    const service = ref( {
        code: '',
        name: '',
        route: '',
        sectors: [
            {
                bound: '',
                pol: '',
                pol_code_name: '',
                pod: '',
                pod_code_name: '',
            }
        ],
    });
    const uniqueServicePorts = ref([]);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getServices() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/commercial/services');
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
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/commercial/services', form);
            service.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "commercial.services.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function showService(serviceId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/commercial/services/${serviceId}`);
            service.value = data.value;
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

    function serviceGroupById(serviceId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.get(`commercial/service/groupby/${serviceId}`)
            .then((response) => {
                service.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    function serviceGroupByCode(serviceCode) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.get(`commercial/service/code/groupby/${serviceCode}`)
            .then((response) => {
                bounds.value = response.data.value;
                console.log("Service sector bound retrived.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    function serviceSectorsByCode(serviceCode) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.get(`commercial/service/sectors/groupby/${serviceCode}`)
            .then((response) => {
                sectors.value = response.data.value;
                console.log("Service sector bound retrived.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    function getServiceUniquePorts(serviceId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.get(`common/servicePorts/${serviceId}`)
            .then((response) => {
                uniqueServicePorts.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    async function updateService(form, serviceId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/commercial/services/${serviceId}`,
                form
            );
            service.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "commercial.services.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function deleteService(serviceId) {
        if (!confirm('Are you sure you want to delete this service?')) {
            return;
        }
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/commercial/services/${serviceId}`);
            notification.showSuccess(status);
            await getServices();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        services,
        service,
        bounds,
        sectors,
        uniqueServicePorts,
        getServiceUniquePorts,
        getServices,
        storeService,
        showService,
        updateService,
        serviceGroupById,
        serviceGroupByCode,
        serviceSectorsByCode,
        deleteService,
        isLoading,
        errors,
    };
}
