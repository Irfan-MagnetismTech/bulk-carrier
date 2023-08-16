import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import useHelper from "../useHelper";


export default function useCashSale() {
    const { printPopup } = useHelper();
    const router = useRouter();
    const cashSales = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const serviceObject = {
        service: "",
        service_id: "",
        unit_price: "",
        quantity: "",
        amount: "",
        service_rate_id: "",
    }

    const mobilObject = {
        material: "",
        material_id: "",
        material_category_id: "",
        in_stock: "",
        unit: "",
        unit_price: "",
        quantity: "",
        amount: "",
    }

    const fuelObject = {
        material: "",
        material_id: "",
        material_category_id: "",
        short_cut:  "",
        in_stock: "",
        unit: "",
        unit_price: "",
        quantity: "",
        amount: "",
        sale_rate_id: ""
    }

    const cashSale = ref( {
        entry_date: "",
        entry_time: "",
        shift: "",
        user: "",
        total_amount: "",
        sale_type: "cash",
        payment_method: "",
        services: [
            { ...serviceObject }
        ],
        fuel: [
            { ...fuelObject }
        ]

    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getCashSales(page, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/revenue/cash-sales',{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                },
            });
            cashSales.value = data.value;
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

    async function storeCashSale(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/revenue/sale-transactions', form);
            cashSale.value = data.value;
            notification.showSuccess(status);
            
            router.go({ name: "revenue.cash-sale.create" });

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCashSale(cashSaleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/sale-transactions/${cashSaleId}`);
            cashSale.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function editCashSale(cashSaleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/sale-transactions/${cashSaleId}/edit`);
            cashSale.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCashSale(form, cashSaleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/revenue/sale-transactions/${cashSaleId}`,
                form
            );
            cashSale.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "revenue.cash-sale.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCashSale(cashSaleId) {

        if (!confirm('Are you sure you want to delete this cashSale?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/revenue/sales/${cashSaleId}`);
            notification.showSuccess(status);
            await getCashSales();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchCashSale(searchParam) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/search-cash-sale`, {params: { searchParam: searchParam }});
            cashSales.value = data.value;
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
        cashSales,
        cashSale,
        getCashSales,
        serviceObject,
        mobilObject,
        searchCashSale,
        storeCashSale,
        showCashSale,
        editCashSale,
        updateCashSale,
        deleteCashSale,
        isLoading,
        errors,
    };
}
