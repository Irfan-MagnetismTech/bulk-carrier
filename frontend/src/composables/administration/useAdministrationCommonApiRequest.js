import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useAdministrationCommonApiRequest() {
    const router = useRouter();
    const permissions = ref([]);
    const roles = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const errors = ref(null);
    const isLoading = ref(false);

    async function getPermissionList(name=null, businessUnit, loading=null) {

        isLoading.value = true;
        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {

            const { data,status } = await Api.post('/administration/get-administration-permissions', form);
            permissions.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
        }
    }

    async function getRoleList(name=null, businessUnit, loading=null) {

        isLoading.value = true;
        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {

            const { data,status } = await Api.post('/administration/get-administration-roles', form);
            roles.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
        }
    }

    return {
        permissions,
        roles,
        getPermissionList,
        getRoleList,
        isLoading,
        errors,
    };
}
