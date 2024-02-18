import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Swal from 'sweetalert2';
import cloneDeep from 'lodash/cloneDeep';
import useDebouncedRef from "../useDebouncedRef";

export default function useRestHourRecord() {
    const router = useRouter();
    const restHourRecords = ref([]);
    const restHourReport = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const restHourRecord = ref({
        business_unit: '',
        record_date: '',
        ops_vessel_id: '',
        ops_vessel_name: null,
        location_type: 'X',
        is_all_crew_checked: false,
        hourlyRecords: [],
        selectedCrews: [],
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getRestHourRecords(filterOptions) {

        let loader = null;

        if (!filterOptions.isFilter) {
            loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;
            isTableLoading.value = false;
        } else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }

        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/crw/crw-rest-hour-entries',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            restHourRecords.value = data.value;
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

    async function storeRestHourRecord(form) {

        const selectedHourlyRecords = form.hourlyRecords.filter(record => record.is_selected === true);
        const selectedCrews = form.selectedCrews.filter(record => record.is_checked === true);

        if(!selectedHourlyRecords.length){
            alert("Please select at least one hourly record.");
            return;
        }

        if(!selectedCrews.length){
            alert("Please select at least one crew.");
            return;
        }

        form.hourlyRecords = [];
        form.selectedCrews = [];
        form.hourlyRecords = selectedHourlyRecords;
        form.selectedCrews = selectedCrews;

        //console.log(form);

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
            const { data, status } = await Api.get(`/crw/crw-rest-hour-entries/${restHourRecordId}`);
            restHourRecord.value = data.value;

            restHourRecord.value.location_type = 'X';

            //customize hourly records
            restHourRecord.value.crwRestHourEntryLines.forEach((entryLine, entryIndex) => {
                let hourlyRecords = restHourRecord.value.crwRestHourEntryLines[entryIndex].hourly_records;
                restHourRecord.value.crwRestHourEntryLines[entryIndex].hourly_records = [];
                for (let index = 0; index < 48; index++) {
                    let existingRecord = hourlyRecords.find(record => record.hour === index);
                    console.log(existingRecord);
                    if(existingRecord){
                        restHourRecord.value.crwRestHourEntryLines[entryIndex].hourly_records.push(
                            {
                                'is_selected': existingRecord?.is_selected,
                                'hour'       : existingRecord?.hour,
                                'type'       : existingRecord?.type,
                            }
                        );
                    } else {
                        restHourRecord.value.crwRestHourEntryLines[entryIndex].hourly_records.push(
                            {
                                'is_selected': false,
                                'hour'       : '',
                                'type'       : '',
                            }
                        );
                    }
                }
            });
            //customize hourly records


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

        form.crwRestHourEntryLines.forEach((entryLine, entryIndex) => {

            const selectedHourlyRecords = form.crwRestHourEntryLines[entryIndex].hourly_records.filter(record => record.is_selected === true);
            form.crwRestHourEntryLines[entryIndex].hourly_records = [];
            form.crwRestHourEntryLines[entryIndex].hourly_records = selectedHourlyRecords;
        });

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/crw-rest-hour-entries/${restHourRecordId}`,
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
            const { data, status } = await Api.delete( `/crw/crw-rest-hour-entries/${restHourRecordId}`);
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

    async function getRestHourReport(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-rest-hour-report', form);
            restHourReport.value = data.value;
            //notification.showSuccess(status,data.value.message);
            //await router.push({ name: "crw.rest-hour-records.index" });
        } catch (error) {
            const { data, status } = error.response;
            if(status === 422){
                restHourReport.value = [];
            }
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
        getRestHourReport,
        restHourReport,
        isLoading,
        isTableLoading,
        errors,
    };
}
