import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useCriticalVesselItem() {
    const router = useRouter();
    const criticalVesselItems = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const criticalVesselItem = ref({
        ops_vessel_id: '',
        ops_vessel: '',
        mnt_critical_function_id: '',
        mnt_critical_function: '',
        mnt_critical_item_cat_id: '',
        mnt_critical_item_cat: '',
        mnt_critical_item_id: '',
        mnt_critical_item: '',
        is_critical: 0,
        specification: '',
        notes: '',
        business_unit: '',
        
        mntCriticalItemSps: [],
        mntCriticalItemCategories: [],
        mntCriticalItems: [],
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getCriticalVesselItems(filterOptions) {
        //NProgress.start();
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
            const {data, status} = await Api.get('/mnt/critical-vessel-items',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            criticalVesselItems.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            //NProgress.done();
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

    async function storeCriticalVesselItem(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/critical-vessel-items', form);
            criticalVesselItem.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.critical-vessel-items.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCriticalVesselItem(criticalVesselItemId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/critical-vessel-items/${criticalVesselItemId}`);
            criticalVesselItem.value = data.value;
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

    async function updateCriticalVesselItem(form, criticalVesselItemId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/critical-vessel-items/${criticalVesselItemId}`,
                form
            );
            criticalVesselItem.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.critical-vessel-items.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteCriticalVesselItem(criticalVesselItemId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/critical-vessel-items/${criticalVesselItemId}`);
            notification.showSuccess(status);
            await getCriticalVesselItems(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            // notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getVesselWiseCriticalVesselItems(opsVesselId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/mnt/get-critical-vessel-items',{
                params: {
                    ops_vessel_id: opsVesselId,
                },
            });
            criticalVesselItems.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    

    
    return {
        criticalVesselItems,
        criticalVesselItem,
        getCriticalVesselItems,
        storeCriticalVesselItem,
        showCriticalVesselItem,
        updateCriticalVesselItem,
        deleteCriticalVesselItem,
        getVesselWiseCriticalVesselItems,
        isLoading,
        isTableLoading,
        errors,
    };
}
