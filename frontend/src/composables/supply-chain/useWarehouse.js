import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useWarehouse() {
    const BASE = 'scm' 
    const router = useRouter();
    const warehouses = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const warehouse = ref( {
        cost_center_id: '',
        cost_center_name: '',
        name: '',
        address: '',
        short_code: '',
        business_unit: '',
        scmWarehouseContactPersons: [{
            name: '',
            designation: '',
            phone: '',
            email: '',
        }],
    });

    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};
    const filterParams = ref(null);
    const errors = ref('');
    const isLoading = ref(false);

    async function getWarehouses(filterOptions) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get(`/${BASE}/warehouses`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
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

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(`/${BASE}/warehouses`, form);
            warehouse.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.warehouse.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showWarehouse(warehouseId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/warehouses/${warehouseId}`);
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

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/${BASE}/warehouses/${warehouseId}`,
                form
            );
            warehouse.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.warehouse.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteWarehouse(warehouseId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/warehouses/${warehouseId}`);
            notification.showSuccess(status);
            await getWarehouses(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchWarehouse(searchParam, loading, business_unit) {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`${BASE}/search-warehouse`, {params: { searchParam: searchParam,business_unit: business_unit }});
            warehouses.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            loading(false)
        }
    }

    return {
        warehouses,
        warehouse,
        getWarehouses,
        searchWarehouse,
        storeWarehouse,
        showWarehouse,
        updateWarehouse,
        deleteWarehouse,
        isLoading,
        errors,
    };
}
