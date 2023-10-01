import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useShipDepartment() {
    const router = useRouter();
    const shipDepartments = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const shipDepartment = ref( {
        name: '',
        short_name: '',
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getShipDepartments(page) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/maintenance/ship-departments',{
                params: {
                    page: page || 1,
                },
            });
            shipDepartments.value = data.value;
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

    async function storeShipDepartment(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/maintenance/ship-departments', form);
            shipDepartment.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "maintenance.ship-department.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showShipDepartment(shipDepartmentId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/maintenance/ship-departments/${shipDepartmentId}`);
            shipDepartment.value = data.value;
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

    async function updateShipDepartment(form, shipDepartmentId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/maintenance/ship-departments/${shipDepartmentId}`,
                form
            );
            shipDepartment.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "maintenance.ship-department.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteShipDepartment(shipDepartmentId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/maintenance/ship-departments/${shipDepartmentId}`);
            notification.showSuccess(status);
            await getShipDepartments();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    

    return {
        shipDepartments,
        shipDepartment,
        getShipDepartments,
        storeShipDepartment,
        showShipDepartment,
        updateShipDepartment,
        deleteShipDepartment,
        isLoading,
        errors,
    };
}
