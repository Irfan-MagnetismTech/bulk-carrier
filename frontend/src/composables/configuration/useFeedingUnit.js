import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useFeedingUnit() {
    const router = useRouter();
    const feedingUnits = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const feedingUnit = ref( {
        name: '',
        quantity: '',
        dip_mm: '',
        status: '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getFeedingUnits() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/configuration/feeding-units');
            feedingUnits.value = data.value;
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

    async function storeFeedingUnit(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/configuration/feeding-units', form);
            feedingUnit.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.feeding-unit.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showFeedingUnit(feedingUnitId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/configuration/feeding-units/${feedingUnitId}`);
            feedingUnit.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateFeedingUnit(form, feedingUnitId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/configuration/feeding-units/${feedingUnitId}`,
                form
            );
            feedingUnit.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.feeding-unit.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteFeedingUnit(feedingUnitId) {

        if (!confirm('Are you sure you want to delete this feedingUnit?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/configuration/feeding-units/${feedingUnitId}`);
            notification.showSuccess(status);
            await getFeedingUnits();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        feedingUnits,
        feedingUnit,
        getFeedingUnits,
        storeFeedingUnit,
        showFeedingUnit,
        updateFeedingUnit,
        deleteFeedingUnit,
        isLoading,
        errors,
    };
}
