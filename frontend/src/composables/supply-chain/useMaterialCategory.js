import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterialCategory() {
    const BASE = 'scm' 
    const router = useRouter();
    const materialCategories = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    const materialCategory = ref( {
        name: '',
        short_code: '',
        parent_category_name: '',
        parent_id: '',
    });

    const filterParams = ref(null);
    const errors = ref('');
    const isLoading = ref(false);
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'};

    async function getMaterialCategories(filterOptions) {
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
            const {data, status} = await Api.get(`/${BASE}/material-categories`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            materialCategories.value = data.value;
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
            //NProgress.done();
        }
    }

    async function storeMaterialCategory(form) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(`/${BASE}/material-categories`, form);
            materialCategory.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-category.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialCategory(materialCategoryId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/material-categories/${materialCategoryId}`);
            materialCategory.value = data.value;
            console.log(data.value);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialCategory(form, materialCategoryId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/${BASE}/material-categories/${materialCategoryId}`,
                form
            );
            materialCategory.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-category.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialCategory(materialCategoryId) {
        // const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const { data, status } = await Api.delete( `/${BASE}/material-categories/${materialCategoryId}`);
            notification.showSuccess(status);
            await getMaterialCategories(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialCategory(searchParam, loading) {

        try {
            const { data, status } = await Api.get(`${BASE}/search-material-category`, {params: { searchParam: searchParam }});
            materialCategories.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false)
        }
    }

    return {
        materialCategories,
        materialCategory,
        getMaterialCategories,
        searchMaterialCategory,
        storeMaterialCategory,
        showMaterialCategory,
        updateMaterialCategory,
        deleteMaterialCategory,
        isTableLoading,
        isLoading,
        errors,
    };
}
