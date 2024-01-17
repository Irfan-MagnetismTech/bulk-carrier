import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Swal from 'sweetalert2';
import cloneDeep from 'lodash/cloneDeep';

export default function useRestHourRecord() {
    const router = useRouter();
    const restHourRecords = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const restHourRecord = ref({
        business_unit: '',
        date: '',
        ops_vessel_id: '',
        ops_vessel_name: null,
        location_type: '',
        is_all_crew_checked: false,
        hourlyRecords: [],
        selectedCrews: [],
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getRestHourRecords(form) {

        console.log("Data:" , form);

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
        //
        // try {
        //     const { data, status } = await Api.post('/crw/crw-rest-hour-entries', form);
        //     restHourRecord.value = data.value;
        //     notification.showSuccess(status);
        //     await router.push({ name: "crw.rest-hour-records.index" });
        // } catch (error) {
        //     const { data, status } = error.response;
        //     errors.value = notification.showError(status, data);
        // } finally {
        //     loader.hide();
        //     isLoading.value = false;
        // }
    }

    async function storeRestHourRecord(form) {

        console.log("Data:" , form);

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-rest-hour-entries', form);
            restHourRecord.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.rest-hour-records.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showRestHourRecord(restHourRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/rest-hour-records/${restHourRecordId}`);
            restHourRecord.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateRestHourRecord(form, restHourRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/rest-hour-records/${restHourRecordId}`,
                form
            );
            restHourRecord.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.rest-hour-records.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteRestHourRecord(restHourRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/rest-hour-records/${restHourRecordId}`);
            notification.showSuccess(status);
            await getRestHourRecords(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    } 

    return {
        restHourRecords,
        restHourRecord,
        getRestHourRecords,
        storeRestHourRecord,
        showRestHourRecord,
        updateRestHourRecord,
        deleteRestHourRecord,
        isLoading,
        isTableLoading,
        errors,
    };
}
