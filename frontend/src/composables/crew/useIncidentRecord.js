import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useIncidentRecord() {
    const router = useRouter();
    const incidentRecords = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const incidentRecord = ref( {
        business_unit: '',
        ops_vessel_id: '',
        date_time: '',
        type: '',
        location: '',
        reported_by: '',
        attachment: '',
        description: '',
        crwIncidentParticipants: [
            {
                crw_crew_id: '',
                injury_status: '',
                notes: '',
            }
        ]
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getIncidentRecords(page,businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/crw/crw-incidents',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            incidentRecords.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeIncidentRecord(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-incidents', form);
            incidentRecord.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.incidentRecords.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showIncidentRecord(incidentRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-incidents/${incidentRecordId}`);
            incidentRecord.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateIncidentRecord(form, incidentRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/crw-incidents/${incidentRecordId}`,
                form
            );
            incidentRecord.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.incidentRecords.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteIncidentRecord(incidentRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-incidents/${incidentRecordId}`);
            notification.showSuccess(status);
            await getIncidentRecords(indexPage.value, indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        incidentRecords,
        incidentRecord,
        getIncidentRecords,
        storeIncidentRecord,
        showIncidentRecord,
        updateIncidentRecord,
        deleteIncidentRecord,
        isLoading,
        errors,
    };
}
