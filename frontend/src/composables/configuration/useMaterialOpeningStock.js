import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterialOpeningStock() {
    const router = useRouter();
    const materialOpeningStocks = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const materialOpeningStock = ref( {
        warehouse: '',
        warehouse_id: '',
        material: '',
        material_id: '',
        cost_center: '',
        cost_center_id: '',
        unit: '',
        quantity: '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getMaterialOpeningStocks() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/configuration/material-opening-stocks');
            materialOpeningStocks.value = data.value;
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

    async function storeMaterialOpeningStock(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/configuration/material-opening-stocks', form);
            materialOpeningStock.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.material-opening-stocks.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialOpeningStock(materialOpeningStockId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/configuration/material-opening-stocks/${materialOpeningStockId}`);
            materialOpeningStock.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialOpeningStock(form, materialOpeningStockId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/configuration/material-opening-stocks/${materialOpeningStockId}`,
                form
            );
            materialOpeningStock.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.material-opening-stocks.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialOpeningStock(materialOpeningStockId) {

        if (!confirm('Are you sure you want to delete?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/configuration/material-opening-stocks/${materialOpeningStockId}`);
            notification.showSuccess(status);
            await getMaterialOpeningStocks();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        materialOpeningStocks,
        materialOpeningStock,
        getMaterialOpeningStocks,
        storeMaterialOpeningStock,
        showMaterialOpeningStock,
        updateMaterialOpeningStock,
        deleteMaterialOpeningStock,
        isLoading,
        errors,
    };
}
