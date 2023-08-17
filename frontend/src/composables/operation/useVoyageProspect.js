import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useVoyageProspect() {
    const router = useRouter();
    const prospects = ref([]);
    const notification = useNotification();
    const prospect = ref( {
        voyage_id: '',
        prospects: [],
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getProspects(voyageId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/operation/voyage-prospects/' + voyageId);
            prospect.value.prospects = data?.value;
            //notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            //notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function storeProspect(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/operation/voyage-prospects', form);
            prospect.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "operation.voyage.prospects.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function showService(serviceId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/commercial/services/${serviceId}`);
            service.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    function serviceGroupById(serviceId) {
        NProgress.start();
        isLoading.value = true;

        Api.get(`commercial/service/groupby/${serviceId}`)
            .then((response) => {
                service.value = response.data.value;
                console.log("Service retrived.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    function serviceGroupByCode(serviceCode) {
        NProgress.start();
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
                isLoading.value = false;
                NProgress.done();
            });
    }

    function serviceSectorsByCode(serviceCode) {
        NProgress.start();
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
                isLoading.value = false;
                NProgress.done();
            });
    }

    function getServiceUniquePorts(serviceId) {
        NProgress.start();
        isLoading.value = true;

        Api.get(`common/servicePorts/${serviceId}`)
            .then((response) => {
                uniqueServicePorts.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    async function updateService(form, serviceId) {
        NProgress.start();
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
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function deleteVoyageProspects(voyageId) {
        if (!confirm('Are you sure you want to delete this voyage prospects?')) {
            return;
        }
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/operation/voyage-prospects/${voyageId}`);
            notification.showSuccess(status);
            await getProspects();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        prospects,
        prospect,
        storeProspect,
        getProspects,
        deleteVoyageProspects,
        isLoading,
        errors,
    };
}
