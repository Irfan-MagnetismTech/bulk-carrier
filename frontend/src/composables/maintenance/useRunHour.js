import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useRunHour() {
    const router = useRouter();
    const runHours = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const presentRunHour = ref('');
    const runHour = ref( {
        ops_vessel_id: '',
        ops_vessel_name: '',
        mnt_ship_department_id: '',
        mnt_ship_department_name: '',
        mnt_item_group_id: '',
        mnt_item_group_name: '',
        mnt_item_id: [],
        mnt_item_name: '',
        previous_run_hour: '',
        running_hour: '',
        present_run_hour: '',
        updated_on: '',
        itemGroupWiseItems: [],
        business_unit: '',
        form_type: 'create'
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isRunHourLoading = ref(false);
    const isTableLoading = ref(false);

    async function getRunHours(filterOptions) {
        //NProgress.start();
        let loader = null;
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
        if (!filterOptions.isFilter) {
            loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
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
            const {data, status} = await Api.get('/mnt/run-hours',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            runHours.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            //NProgress.done();
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

    async function storeRunHour(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/run-hours', form);
            runHour.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.run-hours.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showRunHour(runHourId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/run-hours/${runHourId}`);
            runHour.value = data.value;
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

    async function updateRunHour(form, runHourId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/run-hours/${runHourId}`,
                form
            );
            runHour.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.run-hours.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteRunHour(runHourId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/run-hours/${runHourId}`);
            notification.showSuccess(status);
            await getRunHours(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getItemPresentRunHour(opsVesselId, mntItemId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        isRunHourLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-item-present-run-hour/${opsVesselId}/${mntItemId}`);
            presentRunHour.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            isRunHourLoading.value = false;
            //NProgress.done();
        }
    }

    
    return {
        runHours,
        runHour,
        presentRunHour,
        getRunHours,
        storeRunHour,
        showRunHour,
        updateRunHour,
        deleteRunHour,
        getItemPresentRunHour,
        isLoading,
        isTableLoading,
        isRunHourLoading,
        errors,
    };
}
