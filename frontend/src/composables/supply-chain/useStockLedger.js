import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import useDropZone from '../../services/dropZone.js';

export default function useStockLedger() {
    const BASE = 'scm' 
    const router = useRouter();
    const materials = ref([]);
    const CurrentStock = ref(0);
    const isTableLoading = ref(false);
    const stockData = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
        const material = ref( {
            name: '',
            material_code: '',
            scm_material_category_id: '',
            scm_material_category_name: '',
            unit: '',
            hs_code: '',
            minimum_stock: 0,
            store_category: '',
            description: '',
            sample_photo: null
        });

    const indexPage = ref(null);
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'};
    const errors = ref('');
    const isLoading = ref(false);


    async function getMaterialWiseCurrentStock(materialId,warehouseId) {
            //NProgress.start();
            const loader = $loading.show(LoaderConfig);
            isLoading.value = true;
    
            try {
                const {data, status} = await Api.get(`/${BASE}/current-stock-by-material`, {
        			params: {
        				scm_material_id: materialId,
        				scm_warehouse_id: warehouseId,
        			},
        		});
                CurrentStock.value = data.value;
                notification.showSuccess(status);
            } catch (error) {
                const { data, status } = error.response;
                notification.showError(status);
                console.log(status);
            } finally {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            }
        }
    
    async function getFromAndToWarehouseWiseCurrentStock(fromWarehouseId,toWarehouseId,materialId,index = null) {
        const loader = $loading.show(LoaderConfig);
        try {
            const {data, status} = await Api.get(`/${BASE}/get-current-stock-by-warehouse`, {
    			params: {
    				from_warehouse_id: fromWarehouseId,
    				to_warehouse_id: toWarehouseId,
    				scm_material_id: materialId,
    			},
            });
            if (index != null) {
                data.value.index = index;
            }
            stockData.value = data.value;
            console.log(stockData.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
            console.log(status);
        } finally {
            loader.hide();
            //NProgress.done();
        }
    }

    // async function getMaterials(page,columns = null, searchKey = null, table = null) {
    //     //NProgress.start();
    //     const loader = $loading.show(LoaderConfig);
    //     isLoading.value = true;

    //     indexPage.value = page;

    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/materials`, {
	// 			params: {
	// 				page: page || 1,
	// 				columns: columns || null,
	// 				searchKey: searchKey || null,
	// 				table: table || null,
	// 			},
	// 		});
    //         materials.value = data.value;
    //         notification.showSuccess(status);
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //         console.log(status);
    //     } finally {
    //         loader.hide();
    //         isLoading.value = false;
    //         //NProgress.done();
    //     }
    // }

    // async function storeMaterial(form) {

    //     const loader = $loading.show(LoaderConfig);
    //     isLoading.value = true;
    //     const formData = processFormData(form);
        
    //     try {
    //         const { data, status } = await Api.post(`/${BASE}/materials`, formData);
    //         material.value = data.value;
    //         notification.showSuccess(status);
    //         router.push({ name: `${BASE}.material.index` });
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         errors.value = notification.showError(status, data);
    //     } finally {
    //         loader.hide();
    //         isLoading.value = false;
    //     }
    // }

    // async function showMaterial(materialId) {

    //     const loader = $loading.show(LoaderConfig);
    //     isLoading.value = true;

    //     try {
    //         const { data, status } = await Api.get(`/${BASE}/materials/${materialId}`);
    //         material.value = data.value;
    //         notification.showSuccess(status);
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         loader.hide();
    //         isLoading.value = false;
    //     }
    // }

    // async function updateMaterial(form, materialId) {
    //     const loader = $loading.show(LoaderConfig);
    //     isLoading.value = true;
    //     const formData = processFormData(form);
    //     formData.append('_method', 'PUT');
        
    //     console.log(formData,form);
    //     try {
    //         const { data, status } = await Api.post(
    //             `/${BASE}/materials/${materialId}`,
    //             formData
    //         );
    //         material.value = data.value;
    //         notification.showSuccess(status);
    //         router.push({ name: `${BASE}.material.index` });
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         errors.value = notification.showError(status, data);
    //     } finally {
    //         loader.hide();
    //         isLoading.value = false;
    //     }
    // }

    // async function deleteMaterial(materialId) {
    //     const loader = $loading.show(LoaderConfig);
    //     isLoading.value = true;

    //     try {
    //         const { data, status } = await Api.delete( `/${BASE}/materials/${materialId}`);
    //         notification.showSuccess(status);
    //         await getMaterials(indexPage.value);
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         loader.hide();
    //         isLoading.value = false;
    //     }
    // }

    // async function searchMaterial(searchParam, loading) {

    //     // const loader = $loading.show(LoaderConfig);
    //     // isLoading.value = true;

    //     try {
    //         const { data, status } = await Api.get(`${BASE}/search-materials`, {params: { searchParam: searchParam }});
    //         materials.value = data.value;
    //         notification.showSuccess(status);
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         // loader.hide();
    //         // isLoading.value = false;
    //         loading(false)
    //     }
    // }

    // async function searchMaterialWithCategory(searchParam,materialCategoryId, loading) {

    //     // const loader = $loading.show(LoaderConfig);
    //     // isLoading.value = true;

    //     try {
    //         const { data, status } = await Api.get(`${BASE}/search-materials`, {params: { searchParam: searchParam ,materialCategoryId: materialCategoryId}});
    //         materials.value = data.value;
    //         notification.showSuccess(status);
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         // loader.hide();
    //         // isLoading.value = false;
    //         loading(false)
    //     }
    // }

    // function processFormData(form){
    //     let formData = new FormData();
    //     formData.append('sample_photo', form.sample_photo);
    //     formData.append('description', form.description);
    //     formData.append('store_category', form.store_category);
    //     formData.append('minimum_stock', form.minimum_stock);
    //     formData.append('hs_code', form.hs_code);
    //     formData.append('unit', form.unit);
    //     formData.append('scm_material_category_id', form.scm_material_category_id);
    //     formData.append('material_code', form.material_code);
    //     formData.append('name', form.name);
        
            
    //     return formData;
    // }

    return {
        materials,
        material,
        stockData,
        // getMaterials,
        // searchMaterial,
        // storeMaterial,
        // showMaterial,
        // updateMaterial,
        // deleteMaterial,
        // searchMaterialWithCategory,
        getFromAndToWarehouseWiseCurrentStock,
        CurrentStock,
        getMaterialWiseCurrentStock,
        isLoading,
        errors
    };
}
