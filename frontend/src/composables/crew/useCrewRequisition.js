import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useCrewRequisition() {
    const router = useRouter();
    const crewRequisitions = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const crewRequisition = ref( {
        ops_vessel_id: '',
        applied_date: '',
        total_required_manpower: '',
        remarks: '',
        crwCrewRequisitionLines: [
            {
                crw_rank_id: '',
                required_manpower: '',
                remarks: '',
            }
        ]
    });

    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewRequisitions(page) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/crw/crw-requisitions',{
                params: {
                    page: page || 1,
                },
            });
            crewRequisition.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeCrewRequisition(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-requisitions', form);
            crewRequisition.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewRequisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCrewRequisition(crewRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-requisitions/${crewRequisitionId}`);
            crewRequisition.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrewRequisition(form, crewRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/crw-requisitions/${crewRequisitionId}`,
                form
            );
            crewRequisition.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewRequisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrewRequisition(crewRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-requisitions/${crewRequisitionId}`);
            notification.showSuccess(status);
            await getCrewRequisitions();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        crewRequisitions,
        crewRequisition,
        getCrewRequisitions,
        storeCrewRequisition,
        showCrewRequisition,
        updateCrewRequisition,
        deleteCrewRequisition,
        isLoading,
        errors,
    };
}
