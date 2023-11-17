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
    const itemWiseJobLines = ref([]);
    const itemWiseAddedJobLines = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const job = ref( {
        ops_vessel_id: '',
        ops_vessel_name: '',
        mnt_ship_department_id: '',
        mnt_ship_department_name: '',
        mnt_item_group_id: '',
        mnt_item_group_name: '',
        mnt_item_name: '',
        mnt_item_id: '',
        present_run_hour: 0,
        mntJobLines: [{ job_description: '', cycle_unit: '', cycle: '', min_limit: '', last_done: '', remarks: '' }],
        mnt_item_groups: [],
        mnt_items: [],
        business_unit: '',
        form_type: 'create'
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getJobs(filterOptions) {
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;

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
            const {data, status} = await Api.get('/mnt/jobs',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            jobs.value = data.value;
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

    async function storeJob(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/jobs', form);
            job.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.jobs.index" });
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
            await router.push({ name: "mnt.jobs.index" });
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
            await getJobs(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getJobsForRequisition(businessUnit, opsVesselId, mntItemId){
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-jobs-for-requisition`,{
                params: {
                    business_unit: businessUnit,
                    ops_vessel_id: opsVesselId,
                    mnt_item_id: mntItemId,
                    return_field: 'mntJobLines',
                }
            });
            itemWiseJobLines.value = data.value;
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




    

    
    return {
        jobs,
        job,
        itemWiseJobLines,
        getJobs,
        storeJob,
        showJob,
        updateJob,
        deleteJob,
        getJobsForRequisition,
        isLoading,
        isTableLoading,
        errors,
    };
}
