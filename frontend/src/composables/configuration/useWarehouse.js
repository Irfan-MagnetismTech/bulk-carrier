import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useWarehouse() {
    const router = useRouter();
    const warehouses = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const warehouse = ref( {
        name: '',
        code: '',
        phone: '',
        address: '',
        status: '',
        responsible_person: '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getWarehouses(warehouseType) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            // const {data, status} = await Api.get('/configuration/warehouses'+((warehouseType) ? '?type='+warehouseType : ''));
            const {data, status} = await Api.get('/configuration/warehouses');
            warehouses.value = data.value;
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

    async function storeWarehouse(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/configuration/warehouses', form);
            warehouse.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.warehouse.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showWarehouse(warehouseId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/configuration/warehouses/${warehouseId}`);
            warehouse.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateWarehouse(form, warehouseId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/configuration/warehouses/${warehouseId}`,
                form
            );
            warehouse.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.warehouse.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteWarehouse(warehouseId) {

        if (!confirm('Are you sure you want to delete this warehouse?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/configuration/warehouses/${warehouseId}`);
            notification.showSuccess(status);
            await getWarehouses();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchWarehouse(searchParam, loading) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/search-warehouse?type=`, {params: { searchParam: searchParam }});
            warehouses.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    return {
        warehouses,
        warehouse,
        searchWarehouse,
        getWarehouses,
        storeWarehouse,
        showWarehouse,
        updateWarehouse,
        deleteWarehouse,
        isLoading,
        errors,
    };
}
