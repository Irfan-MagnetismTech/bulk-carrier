import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useSchedule() {
    const router = useRouter();
    const services = ref([]);
    const notification = useNotification();
    const schedule = ref( {
        service_id: '',
        vessel_id: '',
        voyage_no: '',
        status: '',
        schedule_ports: [
            {
                bound: '',
                voyage: '',
                port_id: '',
                port_code: '',
                etb_date: '',
                etd_date: '',
            }
        ],
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getSchedule() {
        NProgress.start();
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/commercial/schedules');
            services.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function storeSchedule(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/commercial/schedules', form);
            services.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "commercial.schedule.website.layout" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function showSchedule(scheduleId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/commercial/schedules/${scheduleId}`);
            schedule.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function updateSchedule(form, scheduleId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/commercial/schedules/${scheduleId}`,
                form
            );
            schedule.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "commercial.schedule.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function deleteSchedule(scheduleId) {
        if (!confirm('Are you sure you want to delete this schedule?')) {
            return;
        }
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/commercial/schedules/${scheduleId}`);
            notification.showSuccess(status);
            await getSchedule();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getScheduleForWebsite() {
        NProgress.start();
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/commercial/get-schedule-layout-for-website');
            services.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        services,
        schedule,
        storeSchedule,
        getSchedule,
        showSchedule,
        updateSchedule,
        getScheduleForWebsite,
        deleteSchedule,
        isLoading,
        errors,
    };
}
