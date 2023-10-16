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
    const runHour = ref( {
        mnt_item_id: [],
        mnt_item_group_id: '',
        mnt_ship_department_id: '',
        department_name: '',
        ops_vessel_id: '',
        previous_run_hour: '',
        present_run_hour: '',
        updated_on: '',
        itemGroupWiseItems: [],
        form_type: 'create'
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getRunHours(page) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/mnt/run-hours',{
                params: {
                    page: page || 1,
                },
            });
            runHours.value = data.value;
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

    async function storeRunHour(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/run-hours', form);
            runHour.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "maintenance.run-hours.index" });
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
            router.push({ name: "maintenance.run-hours.index" });
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
            await getRunHours();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    
    return {
        runHours,
        runHour,
        getRunHours,
        storeRunHour,
        showRunHour,
        updateRunHour,
        deleteRunHour,
        isLoading,
        errors,
    };
}