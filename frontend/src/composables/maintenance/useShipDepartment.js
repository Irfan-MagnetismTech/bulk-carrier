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
        short_code: '',
        business_unit: '',
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getShipDepartments(filterOptions) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        // indexPage.value = filterOptions.page;
        // indexBusinessUnit.value = filterOptions.business_unit;
        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/mnt/ship-departments',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
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
            const { data, status } = await Api.post('/mnt/ship-departments', form);
            shipDepartment.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.ship-departments.index" });
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
            const { data, status } = await Api.get(`/mnt/ship-departments/${shipDepartmentId}`);
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
                `/mnt/ship-departments/${shipDepartmentId}`,
                form
            );
            shipDepartment.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.ship-departments.index" });
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
            const { data, status } = await Api.delete( `/mnt/ship-departments/${shipDepartmentId}`);
            notification.showSuccess(status);
            await getShipDepartments(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getShipDepartmentsWithoutPagination(businessUnit) {
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/mnt/get-mnt-ship-departments',{
                params: {
                    business_unit: businessUnit,
                },
            });
            shipDepartments.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            //NProgress.done();
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
        getShipDepartmentsWithoutPagination,
        isLoading,
        errors,
    };
}
