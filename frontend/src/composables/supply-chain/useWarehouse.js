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

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);
    const errors = ref('');
    const isLoading = ref(false);

    async function getWarehouses(page,businessUnit,columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/scm/warehouses', {
				params: {
					page: page || 1,
					columns: columns || null,
					searchKey: searchKey || null,
					table: table || null,
                    business_unit: businessUnit,
				},
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/warehouses', form);
            warehouse.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.warehouse.index" });
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
            const { data, status } = await Api.get(`/scm/warehouses/${warehouseId}`);
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
                `/scm/warehouses/${warehouseId}`,
                form
            );
            warehouse.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.warehouse.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteWarehouse(warehouseId) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/warehouses/${warehouseId}`);
            notification.showSuccess(status);
            await getWarehouses(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchWarehouse(searchParam, loading, business_unit) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`scm/search-warehouse`, {params: { searchParam: searchParam,business_unit: business_unit }});
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
