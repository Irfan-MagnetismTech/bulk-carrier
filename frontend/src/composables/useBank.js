import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../apis/Api";
import Error from "../services/error";
import useNotification from '../composables/useNotification.js';


export default function useBank() {
    const router = useRouter();
    const $loading = useLoading();
    const banks = ref([]);
    const bank = ref({});
    const notification = useNotification();
    const errors = ref([]);
    const isLoading = ref(false);

    async function getBanks(page) {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/banks', {
                params: {
                    page: page || 1,
                },
            });
            banks.value = data.value.data;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeBank(form) {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/banks', form);
            bank.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "banks.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function showBank(bankId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/banks/${bankId}`);
            bank.value = data.value;
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

    async function updateBank(form, bankId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/banks/${bankId}`,
                form
            );
            bank.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "banks.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteBank(bankId) {
        if (!confirm('Are you sure you want to delete this bank?')) {
            return;
        }
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/banks/${bankId}`);
            notification.showSuccess(status);
            await getBanks();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    function getBankWithoutPaginate() {
        NProgress.start();
        isLoading.value = true;

        Api.get("banks/without/paginate")
            .then((response) => {
                banks.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    return {
        banks,
        bank,
        getBanks,
        getBankWithoutPaginate,
        storeBank,
        showBank,
        updateBank,
        deleteBank,
        isLoading,
        errors,
    };
}
