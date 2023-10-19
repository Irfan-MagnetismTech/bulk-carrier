import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useCrewAssign() {
    const router = useRouter();
    const crewAssigns = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const crewAssign = ref( {
        business_unit: '',
        ops_vessel_id: '',
        ops_vessel_name: '',
        ops_vessel_flag: '',
        crw_crew_id: '',
        crw_crew_name: '',
        position_onboard: '',
        date_of_joining: '',
        port_of_joining: '',
        port_of_joining_code: '',
        approx_duration: '',
        remarks: '',
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewAssigns(page,businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/crw/crw-crew-assignments',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            crewAssigns.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeCrewAssign(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-crew-assignments', form);
            crewAssign.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewAssigns.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCrewAssign(crewAssignId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-crew-assignments/${crewAssignId}`);
            crewAssign.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrewAssign(form, crewAssignId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/crw-crew-assignments/${crewAssignId}`,
                form
            );
            crewAssign.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewAssigns.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrewAssign(crewAssignId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-crew-assignments/${crewAssignId}`);
            notification.showSuccess(status);
            await getCrewAssigns(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        crewAssigns,
        crewAssign,
        getCrewAssigns,
        storeCrewAssign,
        showCrewAssign,
        updateCrewAssign,
        deleteCrewAssign,
        isLoading,
        errors,
    };
}
