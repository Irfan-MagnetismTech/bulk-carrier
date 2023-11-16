import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useUnit() {
    const BASE = 'scm' 
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
    const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

      

    async function getUnits(filterOptions) {
        //NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        indexPage.value = filterOptions.page;
        try {

            const filter_options = {
                ...filterOptions.filter_options
            }

            const {data, status} = await Api.get(`/${BASE}/units`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
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

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(`/${BASE}/units`, form);
            unit.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.units.index` });
        } catch (error) { 
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showUnit(unitId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/units/${unitId}`);
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

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/${BASE}/units/${unitId}`,
                form
            );
            unit.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.units.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteUnit(unitId) {
        const loader = $loading.show();
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/units/${unitId}`);
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

        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const { data, status } = await Api.get(`${BASE}/search-unit`, {params: { searchParam: searchParam }});
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
