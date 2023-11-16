import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useRole() {
    const router = useRouter();
    const $loading = useLoading();
    const roles = ref([]);
    const notification = useNotification();
    const role = ref( {
        name: '',
        current_permissions: [],
    });
    const errors = ref(null);
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getRoles(filterOptions) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/administration/roles',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                 }
            });
            roles.value = data.value;
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

    async function storeRole(form) {

        form.current_permissions = [];
        Object.entries(form.permissions).forEach(([permissionMenuIndex, permissionMenuData]) => {
            Object.entries(permissionMenuData).forEach(([permissionSubjectIndex, permissionSubjectData]) => {
                Object.entries(permissionSubjectData).forEach(([permissionIndex, permissionData]) => {
                    if(permissionData.is_checked){
                        form.current_permissions.push(permissionData.id);
                    }
                });
            });
        });

        if(!form.current_permissions.length){
            alert('Please select at least one permission');
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/administration/roles', form);
            role.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "administration.user.roles.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showRole(roleId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/administration/roles/${roleId}`);
            role.value = data.value;
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

    async function updateRole(form, roleId) {

        form.current_permissions = [];
        Object.entries(form.permissions).forEach(([permissionMenuIndex, permissionMenuData]) => {
            Object.entries(permissionMenuData).forEach(([permissionSubjectIndex, permissionSubjectData]) => {
                Object.entries(permissionSubjectData).forEach(([permissionIndex, permissionData]) => {
                    if(permissionData.is_checked){
                        form.current_permissions.push(permissionData.id);
                    }
                });
            });
        });

        if(!form.current_permissions.length){
            alert('Please select at least one permission');
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/administration/roles/${roleId}`,
                form
            );
            role.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "administration.user.roles.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteRole(roleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/administration/roles/${roleId}`);
            notification.showSuccess(status);
            await getRoles(filterOptions.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        roles,
        role,
        getRoles,
        storeRole,
        showRole,
        updateRole,
        deleteRole,
        isLoading,
        errors,
    };
}
