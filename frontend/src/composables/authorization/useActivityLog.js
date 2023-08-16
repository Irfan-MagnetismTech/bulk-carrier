import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useActivityLog() {
    const router = useRouter();
    const activities = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const activity = ref( {
        subject_id: '',
        subject_type: '',
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getActivityLog(subject_type, subject_id) {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        const form = {
            subject_type: subject_type,
            subject_id: subject_id,
        };

        try {
            const { data, status } = await Api.post('/activity-log', form);
            activities.value = data.value;
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

    // async function storePermission(form) {
    //     NProgress.start();
    //     isLoading.value = true;
    //
    //     try {
    //         const { data, status } = await Api.post('/permissions', form);
    //         permission.value = data.value;
    //         notification.showSuccess(status);
    //         router.push({ name: "authorization.user.permission.index" });
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         errors.value = notification.showError(status, data);
    //     } finally {
    //         isLoading.value = false;
    //         NProgress.done();
    //     }
    // }
    //
    // async function showPermission(permissionId) {
    //     NProgress.start();
    //     isLoading.value = true;
    //
    //     try {
    //         const { data, status } = await Api.get(`/permissions/${permissionId}`);
    //         permission.value = data.value;
    //         notification.showSuccess(status);
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         isLoading.value = false;
    //         NProgress.done();
    //     }
    // }
    //
    // async function updatePermission(form, permissionId) {
    //     NProgress.start();
    //     isLoading.value = true;
    //
    //     try {
    //         const { data, status } = await Api.put(
    //             `/permissions/${permissionId}`,
    //             form
    //         );
    //         permission.value = data.value;
    //         notification.showSuccess(status);
    //         router.push({ name: "authorization.user.permission.index" });
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         errors.value = notification.showError(status, data);
    //     } finally {
    //         isLoading.value = false;
    //         NProgress.done();
    //     }
    // }
    //
    // async function deletePermission(permissionId) {
    //     if (!confirm('Are you sure you want to delete this permission?')) {
    //         return;
    //     }
    //     NProgress.start();
    //     isLoading.value = true;
    //
    //     try {
    //         const { data, status } = await Api.delete( `/permissions/${permissionId}`);
    //         notification.showSuccess(status);
    //         await getPermissions();
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         isLoading.value = false;
    //         NProgress.done();
    //     }
    // }

    return {
        activities,
        activity,
        getActivityLog,
        isLoading,
        errors,
    };
}
