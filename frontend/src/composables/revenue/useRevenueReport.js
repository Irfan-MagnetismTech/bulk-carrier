import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useRevenueReport() {
    const router = useRouter();
    const $loading = useLoading();
    const agingSchedules = ref({});
    const notification = useNotification();
    const errors = ref('');
    const isLoading = ref(false);
    const salesData = ref();
    const shiftSummary = ref();

    async function getSaleReport(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/get-sales-report`, {params: form});
            salesData.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getShiftSummary(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {

            let data, status;

            if(form.sale_type == 'cash') {
                const response = await Api.get(`/revenue/daily-shift-cash-sale-report`, {params: form});
                data = response.data;
                status = response.status;
            } else if(form.sale_type == 'credit') {
                const response = await Api.get(`/revenue/credit-shifting-data`, {params: form});
                data = response.data;
                status = response.status;
            }
            shiftSummary.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getCustomerReport(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/all-customer-report`, {params: form});
            salesData.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getAgingSchedule(page, form = null) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.get('/revenue/get-aging-schedule', {
                params: {
                    page: page || 1,
                    bill_no: form.bill_no || null,
                    customer_id: form.customer_id || null,
                    aging: form.aging || null,
                },
            });

            agingSchedules.value = await data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    
    return {
        getSaleReport,
        getCustomerReport,
        getShiftSummary,
        agingSchedules,
        getAgingSchedule,
        salesData,
        shiftSummary
    }
}