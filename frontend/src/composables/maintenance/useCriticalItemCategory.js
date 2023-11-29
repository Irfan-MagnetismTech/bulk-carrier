import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useCriticalItemCategory() {
    const router = useRouter();
    const criticalItemCategories = ref([]);
    const criticalFunctionWiseItemCategories = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const criticalItemCategory = ref( {
        mnt_critical_function_id: '',
        mnt_critical_function_name: '',
        category_name: '',
        notes: '',
        // business_unit: '',
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isCriticalItemCategoryLoading = ref(false);
    const isTableLoading = ref(false);

    async function getCriticalItemCategories(filterOptions) {
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
        let loader = null;
        
        if (!filterOptions.isFilter) {
            loader = $loading.show({ 'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2' });
            isLoading.value = true;
            isTableLoading.value = false;
        }
        else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }

        // indexPage.value = page;
        // indexBusinessUnit.value = businessUnit;
        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/mnt/critical-item-cats',{
                params: {
                    // page: page || 1,
                    // business_unit: businessUnit,
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            criticalItemCategories.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
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

    async function storeCriticalItemCategory(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/critical-item-cats', form);
            criticalItemCategory.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.critical-item-categories.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCriticalItemCategory(criticalItemCategoryId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/critical-item-cats/${criticalItemCategoryId}`);
            criticalItemCategory.value = data.value;
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

    async function updateCriticalItemCategory(form, criticalItemCategoryId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/critical-item-cats/${criticalItemCategoryId}`,
                form
            );
            criticalItemCategory.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.critical-item-categories.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteCriticalItemCategory(criticalItemCategoryId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/critical-item-cats/${criticalItemCategoryId}`);
            notification.showSuccess(status);
            await getCriticalItemCategories(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            // notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    //get-critical-function-wise-item-cats
    async function getCriticalFunctionWiseItemCategories(mntCriticalFunctionId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        isCriticalItemCategoryLoading.value = true;

        try {
            const {data, status} = await Api.get('/mnt/get-critical-item-cats',{
                params: {
                    mnt_critical_function_id: mntCriticalFunctionId,
                },
            });
            criticalFunctionWiseItemCategories.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            isCriticalItemCategoryLoading.value = false;
            //NProgress.done();
        }
    }

    

    
    return {
        criticalItemCategories,
        criticalItemCategory,
        criticalFunctionWiseItemCategories,
        getCriticalItemCategories,
        storeCriticalItemCategory,
        showCriticalItemCategory,
        updateCriticalItemCategory,
        deleteCriticalItemCategory,
        getCriticalFunctionWiseItemCategories,
        isLoading,
        isTableLoading,
        isCriticalItemCategoryLoading,
        errors,
    };
}
