import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useCriticalItem() {
    const router = useRouter();
    const criticalVesselItems = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const criticalVesselItem = ref({
        ops_vessel_id: '',
        ops_vessel_name: '',
        mnt_critical_function_id: '',
        mnt_critical_function_name: '',
        mnt_critical_item_cat_id: '',
        mnt_critical_item_cat_name: '',
        mnt_critical_item_id: '',
        mnt_critical_item_name: '',
        is_critical: false,
        notes: '',
        business_unit: '',
        
        mntCriticalItemSps: [{sp_name: '', unit: '', min_rob: ''}],
        mntCriticalItemCategories: [],
        mntCriticalItems: [],
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getCriticalVesselItems(page, businessUnit) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/mnt/critical-vessel-items',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            criticalVesselItems.value = data.value;
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

    async function storeCriticalVesselItem(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/critical-vessel-items', form);
            criticalVesselItem.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "mnt.critical-vessel-items.index" });
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
            router.push({ name: "mnt.critical-vessel-items.index" });
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
            await getCriticalVesselItems(indexPage.value, indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
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
        isLoading,
        errors,
    };
}
