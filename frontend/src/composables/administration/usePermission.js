import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function usePermission() {
    const router = useRouter();
    const $loading = useLoading();
    const permissions = ref([]);
    const notification = useNotification();
    const permission = ref( {
        name: '',
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getPermissions(page,isPaginate = true) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/administration/permissions', {
                params: {
                    page: page || 1,
                    isPaginate: isPaginate,
                },
            });
            permissions.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
        } finally {
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storePermission(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/permissions', form);
            permission.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "authorization.user.permission.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function showPermission(permissionId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/permissions/${permissionId}`);
            permission.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide()
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function updatePermission(form, permissionId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/permissions/${permissionId}`,
                form
            );
            permission.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "authorization.user.permission.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deletePermission(permissionId) {
        if (!confirm('Are you sure you want to delete this permission?')) {
            return;
        }
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/permissions/${permissionId}`);
            notification.showSuccess(status);
            await getPermissions();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function getPermissionWithoutPaginate() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/without-paginate/permissions');
            permissions.value = data.value;
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
        permissions,
        permission,
        getPermissions,
        storePermission,
        showPermission,
        updatePermission,
        deletePermission,
        getPermissionWithoutPaginate,
        isLoading,
        errors,
    };
}
