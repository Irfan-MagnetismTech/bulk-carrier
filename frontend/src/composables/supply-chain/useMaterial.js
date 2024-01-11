import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import useDropZone from '../../services/dropZone.js';

export default function useMaterial() {
    const BASE = 'scm' 
    const router = useRouter();
    const materials = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
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
            type: '',
            sample_photo: null
        });

    const filterParams = ref(null);
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'};
    const errors = ref('');
    const isLoading = ref(false);
    const isMaterialLoading = ref(false);

    async function getMaterials(filterOptions) {
        //NProgress.start();
        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show(LoaderConfig);
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
            const {data, status} = await Api.get(`/${BASE}/materials`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            materials.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
            console.log(status);
        } finally {
            if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            }
            else {
                isTableLoading.value = false;
                loader?.hide();
            }
            //NProgress.done();
        }
    }

    async function storeMaterial(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        const formData = processFormData(form);
        
        try {
            const { data, status } = await Api.post(`/${BASE}/materials`, formData);
            material.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterial(materialId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/materials/${materialId}`);
            material.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterial(form, materialId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        const formData = processFormData(form);
        formData.append('_method', 'PUT');
        try {
            const { data, status } = await Api.post(
                `/${BASE}/materials/${materialId}`,
                formData
            );
            material.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterial(materialId) {
        // const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/materials/${materialId}`);
            notification.showSuccess(status);
            await getMaterials(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterial(searchParam, loading = false) {

        // const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`${BASE}/search-materials`, {params: { searchParam: searchParam }});
            materials.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialWithCategory(searchParam,materialCategoryId, loading) {

        // const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`${BASE}/search-materials`, {params: { searchParam: searchParam ,materialCategoryId: materialCategoryId}});
            materials.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            loading(false)
        }
    }

    function processFormData(form){
        let formData = new FormData();
        formData.append('sample_photo', form.sample_photo);
        formData.append('description', form.description);
        formData.append('store_category', form.store_category);
        formData.append('minimum_stock', form.minimum_stock);
        formData.append('hs_code', form.hs_code);
        formData.append('unit', form.unit);
        formData.append('scm_material_category_id', form.scm_material_category_id);
        formData.append('material_code', form.material_code);
        formData.append('name', form.name);
        
            
        return formData;
    }

    async function getBunkerList() {

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`${BASE}/search-materials`, {params: { materialCategoryId: 1}});
            materials.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            // loading(false)
        }
    }

    return {
        materials,
        material,
        getMaterials,
        searchMaterial,
        storeMaterial,
        showMaterial,
        updateMaterial,
        deleteMaterial,
        isTableLoading,
        searchMaterialWithCategory,
        getBunkerList,
        isLoading,
        isMaterialLoading,
        errors
    };
}
