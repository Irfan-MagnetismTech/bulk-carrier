import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useShiftPerformance() {
    const router = useRouter();
    const shiftPerformances = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const shiftPerformance = ref( {
        sale_type: '',
        closing_time: '',
        shift: '',
        total_cash_sale: '',
        summary: {
            cash_sales_ltr: {
                octane: '',
                diesel: '',
                petrol: '',
                mobile: ''
            },
            credit_sales_ltr: {
                octane: '',
                diesel: '',
                petrol: '',
                mobile: ''
            },
            cash_sales_tk: {
                octane: '',
                diesel: '',
                petrol: '',
                mobile: ''
            },
            misc_cash_sales: {
                octane: '',
                diesel: '',
                petrol: '',
                mobile: ''
            }
        }
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getShiftPerformances() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/revenue/shift-performances');
            shiftPerformances.value = data.value;
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

    async function storeShiftPerformance(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/revenue/shift-performances', form);
            // shiftPerformance.value = data.value;
            notification.showSuccess(status);
            router.go({ name: "revenue.shift-performances.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showShiftPerformance(shiftPerformanceId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/shift-performances/${shiftPerformanceId}`);
            shiftPerformance.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function editShiftPerformance(shiftPerformanceId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/shift-performances/${shiftPerformanceId}/edit`);
            shiftPerformance.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }
    async function updateShiftPerformance(form, shiftPerformanceId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/revenue/shift-performances/${shiftPerformanceId}`,
                form
            );
            shiftPerformance.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "revenue.shift-performances.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteShiftPerformance(shiftPerformanceId) {

        if (!confirm('Are you sure you want to delete this shiftPerformance?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/revenue/shift-performances/${shiftPerformanceId}`);
            notification.showSuccess(status);
            await getShiftPerformances();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchShiftPerformance(searchParam) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/search-shift-performances`, {params: { searchParam: searchParam }});
            shiftPerformances.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getUserShiftInfoOfThisDay(shift) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        console.log("shift", shift)
        try {
            // const { data, status } = await Api.get(`/revenue/get-users-shift-of-this-day`, {params: { shift: shift }});
            const { data, status } = await Api.get(`/revenue/cash-shifting-data`, {params: { shift: shift }});

            shiftPerformance.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getLastShiftPerformance(shift) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        console.log("shift", shift)
        try {
            // const { data, status } = await Api.get(`/revenue/get-users-shift-of-this-day`, {params: { shift: shift }});
            const { data, status } = await Api.get(`/revenue/latest-shift-performance`, {params: { shift: shift }});

            shiftPerformance.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        
        shiftPerformances,
        shiftPerformance,
        getShiftPerformances,
        getLastShiftPerformance,
        searchShiftPerformance,
        storeShiftPerformance,
        showShiftPerformance,
        editShiftPerformance,
        updateShiftPerformance,
        deleteShiftPerformance,
        getUserShiftInfoOfThisDay,
        isLoading,
        errors,
    };
}
