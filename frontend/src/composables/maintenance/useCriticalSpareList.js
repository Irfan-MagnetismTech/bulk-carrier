import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useCriticalSpareList() {
    const router = useRouter();
    const criticalSparelists = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const criticalSpareList = ref( {
        ops_vessel_id: '',
        ops_vessel_name: '',
        reference_no: '',
        record_date: '',
        business_unit: '',
        mntCriticalSpListLine: [],
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getCriticalSpareLists(page, businessUnit) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/mnt/critical-spare-lists',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            criticalSpareLists.value = data.value;
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

    async function storeCriticalSpareList(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/critical-spare-lists', form);
            criticalSpareList.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "mnt.critical-spare-lists.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCriticalSpareList(criticalSpareListId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/critical-spare-lists/${criticalSpareListId}`);
            criticalSpareList.value = data.value;
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

    async function updateCriticalSpareList(form, criticalSpareListId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/critical-spare-lists/${criticalSpareListId}`,
                form
            );
            criticalSpareList.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "mnt.critical-spare-lists.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteCriticalSpareList(criticalSparelistId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/critical-spare-lists/${criticalSparelistId}`);
            notification.showSuccess(status);
            await getCriticalSpareListItems(indexPage.value, indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    
    

    
    return {
        criticalSparelists,
        criticalSpareList,
        getCriticalSpareLists,
        storeCriticalSpareList,
        showCriticalSpareList,
        updateCriticalSpareList,
        deleteCriticalSpareList,
        isLoading,
        errors,
    };
}
