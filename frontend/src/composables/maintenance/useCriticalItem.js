import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useCriticalItem() {
    const router = useRouter();
    const criticalItems = ref([]);
    const criticalItemCategoryWiseItems = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const criticalItem = ref( {
        mnt_critical_function_id: '',
        mnt_critical_function_name: '',
        mnt_critical_item_cat_id: '',
        mnt_critical_item_cat_name: '',
        item_name: '',
        specification: '',
        notes: '',
        mntItemCategories: [],
        // business_unit: '',
    });

    // const indexPage = ref(null);
    // const indexBusinessUnit = ref(null);
    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isCriticalItemLoading = ref(false);
    const isTableLoading = ref(false);

    async function getCriticalItems(filterOptions) {
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
            const {data, status} = await Api.get('/mnt/critical-items',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            criticalItems.value = data.value;
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

    async function storeCriticalItem(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/critical-items', form);
            criticalItem.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.critical-items.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCriticalItem(criticalItemId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/critical-items/${criticalItemId}`);
            criticalItem.value = data.value;
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

    async function updateCriticalItem(form, criticalItemId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/critical-items/${criticalItemId}`,
                form
            );
            criticalItem.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.critical-items.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteCriticalItem(criticalItemId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/critical-items/${criticalItemId}`);
            notification.showSuccess(status);
            await getCriticalItems(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            // notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getCriticalItemCategoryWiseItems(mntCriticalItemCatId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        isCriticalItemLoading.value = true;
        
        try {
            const {data, status} = await Api.get('/mnt/get-critical-items',{
                params: {
                    mnt_critical_item_cat_id: mntCriticalItemCatId,
                },
            });
            criticalItemCategoryWiseItems.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            isCriticalItemLoading.value = false;
            //NProgress.done();
        }
    }

    

    
    return {
        criticalItems,
        criticalItem,
        criticalItemCategoryWiseItems,
        getCriticalItems,
        storeCriticalItem,
        showCriticalItem,
        updateCriticalItem,
        deleteCriticalItem,
        getCriticalItemCategoryWiseItems,
        isLoading,
        isTableLoading,
        isCriticalItemLoading,
        errors,
    };
}
