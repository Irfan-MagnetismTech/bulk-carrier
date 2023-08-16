import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useCreditSale() {
    const router = useRouter();
    const creditSales = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const saleStatusBySlip = ref();

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
        vehicle: "",
        customer_vehicle_id: "",
        sale_rate_id: "",
    }

    const creditSale = ref( {
        entry_date: "",
        entry_time: "",
        shift: "",
        user: "",
        total_amount: "",
        sale_type: "credit",
        payment_method: "",
        date: "",
        billing_address: "",
        billing_email: "",
        billing_party: "",
        slip_no: "",

        fuel: [
            { ...fuelObject }
        ]

    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getCreditSales(page, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/revenue/credit-sales',{
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                },
            });
            creditSales.value = data.value;
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

    async function storeCreditSale(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/revenue/sale-transactions', form);
            creditSale.value = data.value;
            notification.showSuccess(status);
            router.go({ name: "revenue.credit-sale.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCreditSale(creditSaleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/sale-transactions/${creditSaleId}`);
            creditSale.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function editCreditSale(creditSaleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/sale-transactions/${creditSaleId}/edit`);
            creditSale.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }
    async function updateCreditSale(form, creditSaleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/revenue/sale-transactions/${creditSaleId}`,
                form
            );
            creditSale.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "revenue.credit-sale.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCreditSale(creditSaleId) {

        if (!confirm('Are you sure you want to delete this creditSale?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/revenue/sales/${creditSaleId}`);
            notification.showSuccess(status);
            await getCreditSales();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchCreditSale(searchParam) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/search-credit-sale`, {params: { searchParam: searchParam }});
            creditSales.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function checkSlipValidity(customerId, slipNo) {
        try {
            const { data, status } = await Api.get(`/revenue/check-slip-validity`, {params: { customer_id: customerId, slip_no: slipNo }});
            saleStatusBySlip.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }

    return {
        creditSales,
        creditSale,
        getCreditSales,
        mobilObject,
        searchCreditSale,
        storeCreditSale,
        showCreditSale,
        editCreditSale,
        updateCreditSale,
        deleteCreditSale,
        checkSlipValidity,
        saleStatusBySlip,
        isLoading,
        errors,
    };
}
