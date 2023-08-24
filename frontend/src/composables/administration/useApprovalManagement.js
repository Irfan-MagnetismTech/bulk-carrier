import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useApprovalManagement() {
    const router = useRouter();
    const approvals = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const approvalSubjectUser = ref('');
    const approval = ref( {
        model: '',
        approval_bodies: [
            {
                approver_id: '',
                approval_order: '',
                approver_role: '',
            }
        ],
    });

    const errors = ref(null);
    const isLoading = ref(false);

    async function getApproval() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/approvables');
            approvals.value = data.value;
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

    async function getApprovalSubjectUser() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/approvableSubjects');
            approvalSubjectUser.value = data.value;
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

    async function storeApproval(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/approvables', form);
            approval.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "authorization.approval.management.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function showApproval(approvalId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/approvables/${approvalId}`);
            approval.value = data.value;
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

    async function updateApproval(form, approvalId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/approvables/${approvalId}`,
                form
            );
            approval.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "authorization.approval.management.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteApproval(approvalId) {
        if (!confirm('Are you sure you want to delete this approval?')) {
            return;
        }
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/approvables/${approvalId}`);
            notification.showSuccess(status);
            await getApproval();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function approved(form) {
        if (!confirm('Are you sure you want to approve this?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        //NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/approved', form);
            notification.showSuccess(status);
            router.go();
            //router.push({ name: "administration.approval.management.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        approvals,
        approval,
        approvalSubjectUser,
        getApproval,
        getApprovalSubjectUser,
        storeApproval,
        showApproval,
        updateApproval,
        deleteApproval,
        approved,
        isLoading,
        errors,
    };
}
