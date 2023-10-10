import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useUnit() {
    const router = useRouter();
    const units = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const unit = ref( {
        name: '',
        short_code: '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getUnits() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/units');
            units.value = data.value;
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

    async function storeUnit(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/units', form);
            unit.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "supply-chain.unit.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showUnit(unitId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/units/${unitId}`);
            unit.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateUnit(form, unitId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/units/${unitId}`,
                form
            );
            unit.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "supply-chain.unit.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteUnit(unitId) {

        if (!confirm('Are you sure you want to delete this unit?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/units/${unitId}`);
            notification.showSuccess(status);
            await getunits();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchUnit(searchParam, loading) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`scm/search-unit`, {params: { searchParam: searchParam }});
            units.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            loading(false)
        }
    }

    return {
        units,
        unit,
        getUnits,
        searchUnit,
        storeUnit,
        showUnit,
        updateUnit,
        deleteUnit,
        isLoading,
        errors,
    };
}
