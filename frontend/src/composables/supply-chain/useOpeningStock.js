import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useOpeningStock() {
    const router = useRouter();
    const openingStocks = ref([]);
    const filteredOpeningStocks = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const openingStock = ref( {
        date: '',
        scm_warehouse_name: '',
        scm_warehouse_id: '',
        business_unit: '',
        materials: [
            {
                material_id: '',
                material_name_with_code: '',
                code: '',
                unit: '',
                quantity: 0.0,
                rate: 0.0,
            }
        ],
    });

    
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);
    const errors = ref('');
    const isLoading = ref(false);

    async function getOpeningStocks(page, businessUnit, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/scm/opening-stocks',{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    business_unit: businessUnit,
                },
            });
            openingStocks.value = data.value;
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
    async function storeOpeningStock(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.post('/scm/opening-stocks', formData);
            openingStock.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.opening-stocks.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showOpeningStock(openingStockId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/opening-stocks/${openingStockId}`);
            openingStock.value = data.value;
            openingStock.value.materials = data.value.stockable;
            openingStock.value.materials.forEach((material) => {
                material.material_name_with_code = material?.name;
            });

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateOpeningStock(form, openingStockId) {
        console.log(form);

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.post('/scm/opening-stocks', formData);
            openingStock.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.opening-stocks.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteOpeningStock(openingStockId) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/opening-stocks/${openingStockId}`);
            notification.showSuccess(status);
            await getOpeningStocks(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchOpeningStock(searchParam, loading) {
        

        try {
            const {data, status} = await Api.get('/common/search-opening-stock',searchParam);
            filteredOpeningStocks.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    async function getAllPendingOpeningStocks() {

        try {
            const {data, status} = await Api.get('/common/get-all-opening-stock');
            filteredOpeningStocks.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loading(false)
        }
    }

    return {
        openingStocks,
        openingStock,
        filteredOpeningStocks,
        searchOpeningStock,
        getAllPendingOpeningStocks,
        getOpeningStocks,
        storeOpeningStock,
        showOpeningStock,
        updateOpeningStock,
        deleteOpeningStock,
        isLoading,
        errors,
    };
}
