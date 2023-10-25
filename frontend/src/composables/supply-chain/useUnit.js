import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useUnit() {
    const router = useRouter();
    const units = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const unit = ref( {
        name: '',
        short_code: '',
    });

    const indexPage = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

      

    async function getUnits(page,columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;

        try {
            const {data, status} = await Api.get('/scm/units', {
				params: {
					page: page || 1,
					columns: columns || null,
					searchKey: searchKey || null,
					table: table || null,
				},
			});
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
            router.push({ name: "scm.units.index" });
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
            router.push({ name: "scm.units.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteUnit(unitId) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/units/${unitId}`);
            console.log(status);
            notification.showSuccess(status);
            await getUnits(indexPage.value);
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
            console.log('tag', data.value);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            console.log('tag', status);
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
