import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterialCategory() {
    const router = useRouter();
    const materialCategories = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const materialCategory = ref( {
        name: '',
        short_code: '',
        parent_category_name: '',
        parent_id: '',
    });

    const indexPage = ref(null);
    const path = 'scm'
    const errors = ref('');
    const isLoading = ref(false);

    async function getMaterialCategories(page,columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        indexPage.value = page;

        try {
            const {data, status} = await Api.get('/scm/material-categories', {
				params: {
					page: page || 1,
					columns: columns || null,
					searchKey: searchKey || null,
					table: table || null,
				},
			});
            materialCategories.value = data.value;
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

    async function storeMaterialCategory(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/material-categories', form);
            materialCategory.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${path}.material-category.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialCategory(materialCategoryId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/material-categories/${materialCategoryId}`);
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/material-categories/${materialCategoryId}`,
                form
            );
            materialCategory.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${path}.material-category.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialCategory(materialCategoryId) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.delete( `/scm/material-categories/${materialCategoryId}`);
            notification.showSuccess(status);
            await getMaterialCategories(indexPage.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterialCategory(searchParam, loading) {

        try {
            const { data, status } = await Api.get(`scm/search-material-category`, {params: { searchParam: searchParam }});
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
        isLoading,
        errors,
    };
}
