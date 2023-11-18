import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Store from './../../store/index.js';

export default function useOpeningStock() {
    const BASE = 'scm' 
    const router = useRouter();
    const openingStocks = ref([]);
     const isTableLoading = ref(false);
    const filteredOpeningStocks = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);


    const openingStock = ref( {
        date: '',
        scmWarehouse: '',
        scm_warehouse_id: '',
        scm_cost_center_id: '',
        business_unit: '',
        scmOpeningStockLines: [
            {
                scmMaterial: '',
                scm_material_id: '',
                unit: '',
                quantity: 0.0,
                rate: 0.0,
            }
        ],
    });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        quantity: 0.0,
        rate: 0.0,
      };
    
   
    const filterParams = ref(null);
    const errors = ref('');
    const isLoading = ref(false);
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'};

    async function getOpeningStocks(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/opening-stocks`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            openingStocks.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            }
            else {
                isTableLoading.value = false;
                loader?.hide();
            }
        }
    }
    async function storeOpeningStock(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const { data, status } = await Api.post(`/${BASE}/opening-stocks`, form);
            openingStock.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.opening-stock.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showOpeningStock(openingStockId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/opening-stocks/${openingStockId}`);
            openingStock.value = data.value;
            console.log(data.value);
            // openingStock.value.materials = data.value.stockable;
            // openingStock.value.materials.forEach((material) => {
            //     material.material_name_with_code = material?.name;
            // });

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

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            // const { data, status } = await Api.put('/${BASE}/opening-stocks', form);
            const { data, status } = await Api.put(
				`/${BASE}/opening-stocks/${openingStockId}`,
				form
			);
            openingStock.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.opening-stock.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteOpeningStock(openingStockId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/opening-stocks/${openingStockId}`);
            notification.showSuccess(status);
            await getOpeningStocks(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }



    return {
        openingStocks,
        openingStock,
        getOpeningStocks,
        storeOpeningStock,
        showOpeningStock,
        updateOpeningStock,
        deleteOpeningStock,
        materialObject,
        isTableLoading,
        isLoading,
        errors,
    };
}
