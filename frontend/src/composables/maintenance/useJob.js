import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useItemGroup() {
    const router = useRouter();
    const jobs = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const job = ref( {
        ops_vessel_id: '',
        mnt_ship_department_id: '',
        item_name: '',
        mnt_item_id: '',
        mntJobLines: [{ job_description: '', cycle_unit: '', cycle: '', min_limit: '', remarks: '' }],
        dept_wise_items: [],
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getJobs(page) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/mnt/jobs',{
                params: {
                    page: page || 1,
                },
            });
            jobs.value = data.value;
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

    async function storeJob(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/jobs', form);
            job.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "maintenance.job.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showJob(jobId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/jobs/${jobId}`);
            job.value = data.value;
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

    async function updateJob(form, jobId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/jobs/${jobId}`,
                form
            );
            job.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "maintenance.job.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteJob(jobId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/jobs/${jobId}`);
            notification.showSuccess(status);
            await getJobs();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    

    
    return {
        jobs,
        job,
        getJobs,
        storeJob,
        showJob,
        updateJob,
        deleteJob,
        isLoading,
        errors,
    };
}
