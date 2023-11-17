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
    const isTableLoading = ref(false);
    const notification = useNotification();
    const permission = ref( {
        name: '',
    });
    const errors = ref(null);
    const isLoading = ref(false);
    const filterParams = ref(null);
    

    // async function getPermissions(page, isPaginate = true) {
    async function getPermissions(filterOptions) {
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
            // const { data, status } = await Api.get('/administration/permissions', {
            //     params: {
            //         page: page || 1,
            //         isPaginate: isPaginate,
            //     },
            // });
            const { data, status } = await Api.get('/administration/permissions', {
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                 }
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
            // await getPermissions();
            await getPermissions(filterParams.value);
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
        isTableLoading,
        updatePermission,
        deletePermission,
        getPermissionWithoutPaginate,
        isLoading,
        errors,
    };
}
