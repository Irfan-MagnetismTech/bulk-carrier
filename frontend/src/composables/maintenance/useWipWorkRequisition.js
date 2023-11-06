import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useWipWorkRequisition() {
    const router = useRouter();
    const wipWorkRequisitions = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const wipWorkRequisition = ref( {
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
        responsible_person_name: '',
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
        status: '',
        mnt_item_groups: [],
        mnt_items: [],
        business_unit: '',
        form_type: 'create',
        added_job_lines: [],
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

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getWipWorkRequisitions(page, businessUnit) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/mnt/get-work-requisitions-wip',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            wipWorkRequisitions.value = data.value;
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

    async function storeWipWorkRequisition(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/work-requisitions', form);
            workRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "mnt.work-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showWipWorkRequisition(wipWorkRequisitionId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/work-requisitions/${wipWorkRequisitionId}`);
            wipWorkRequisition.value = data.value;
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

    async function updateWipWorkRequisition(form, wipWorkRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/update-work-requisition-wip/${wipWorkRequisitionId}`,
                form
            );
            wipWorkRequisition.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "mnt.wip-work-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteWipWorkRequisition(wipWorkRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/work-requisitions/${wipWorkRequisitionId}`);
            notification.showSuccess(status);
            await getWipWorkRequisitions(indexPage.value, indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }


    return {
        wipWorkRequisitions,
        wipWorkRequisition,
        getWipWorkRequisitions,
        storeWipWorkRequisition,
        showWipWorkRequisition,
        updateWipWorkRequisition,
        deleteWipWorkRequisition,
        isLoading,
        errors,
    };
}
