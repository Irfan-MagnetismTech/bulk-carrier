import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useDoneWorkRequisition() {
    const router = useRouter();
    const doneWorkRequisitions = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const doneWorkRequisition = ref( {
        ops_vessel_id: null,
        ops_vessel_name: '',
        mnt_ship_department_id: null,
        mnt_ship_department_name: '',
        mnt_item_group_id: null,
        mnt_item_group_name: '',
        mnt_item_id: '',
        mnt_item_name: '',
        assigned_to: '',
        responsible_person: '',
        // responsible_person_name: '',
        scm_vendor_id: '',
        scm_vendor_name: '',
        maintenance_type: '',
        reference_no: '',
        requisition_date: '',
        priority: '',
        present_run_hour:'',
        est_start_date: '',
        est_completion_date: '',
        act_start_date: '',
        act_completion_date: '',
        status: 0,
        mnt_item_groups: [],
        mnt_items: [],
        business_unit: '',
        form_type: 'create',
        added_job_lines: [],
        mntWorkRequisitionLines: [],
        // workRequisition
        mntWorkRequisitionMaterials: [
            {
                material_name_and_code: '',
                specification: '',
                unit: '',
                quantity: 0,
                remarks: '',
            }
        ]
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isDoneWorkRequisitionLoading = ref(false);
    const isTableLoading = ref(false);

    async function getDoneWorkRequisitions(filterOptions) {
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
        let loader = null;
        
        if (!filterOptions.isFilter) {
            loader = $loading.show({ 'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2' });
            isLoading.value = true;
            isTableLoading.value = false;
        }
        else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }

        // indexPage.value = page;
        // indexBusinessUnit.value = businessUnit;
        filterParams.value = filterOptions;
        console.log("filterOptions", filterOptions);

        try {
            const {data, status} = await Api.get('/mnt/get-work-requisitions-wip',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions),
                    status: 2,
                },
            });
            doneWorkRequisitions.value = data.value;
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
            //NProgress.done();
        }
    }

    async function storeDoneWorkRequisition(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/work-requisitions', form);
            doneWorkRequisition.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.work-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showDoneWorkRequisition(doneWorkRequisitionId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/work-requisitions/${doneWorkRequisitionId}`);
            doneWorkRequisition.value = data.value;
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

    async function updateDoneWorkRequisition(form, doneWorkRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/update-work-requisition-done/${doneWorkRequisitionId}`,
                form
            );
            doneWorkRequisition.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.done-work-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    
    async function updateDoneWorkRequisitionLine(workRequisitionLine, workRequisitionLineId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/update-work-requisition-line-done/${workRequisitionLineId}`,
                workRequisitionLine
            );
            workRequisitionLine.status = data?.value?.status ?? 0;
            notification.showSuccess(status);
            // router.push({ name: "mnt.done-work-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }



    async function deleteDoneWorkRequisition(doneWorkRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/work-requisitions/${doneWorkRequisitionId}`);
            notification.showSuccess(status);
            await getDoneWorkRequisitions(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }


    return {
        doneWorkRequisitions,
        doneWorkRequisition,
        getDoneWorkRequisitions,
        storeDoneWorkRequisition,
        showDoneWorkRequisition,
        updateDoneWorkRequisition,
        updateDoneWorkRequisitionLine,
        deleteDoneWorkRequisition,
        isLoading,
        isTableLoading,
        isDoneWorkRequisitionLoading,
        errors,
    };
}
