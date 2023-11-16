import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useCostCenter() {
    const router = useRouter();
    const salaryHeads = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const salaryHead = ref( {
        name: '',
        business_unit: '',
    });
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getSalaryHeads(filterOptions) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = filterOptions.page;
        indexBusinessUnit.value = filterOptions.business_unit;

        try {
            const {data, status} = await Api.get('/acc/acc-cost-centers',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            costCenters.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeCostCenter(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-cost-centers', form);
            costCenter.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.cost-centers.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCostCenter(costCenterId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-cost-centers/${costCenterId}`);
            costCenter.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCostCenter(form, costCenterId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/acc/acc-cost-centers/${costCenterId}`,
                form
            );
            costCenter.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.cost-centers.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCostCenter(costCenterId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-cost-centers/${costCenterId}`);
            notification.showSuccess(status);
            await getCostCenters(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        costCenters,
        costCenter,
        getCostCenters,
        storeCostCenter,
        showCostCenter,
        updateCostCenter,
        deleteCostCenter,
        isLoading,
        errors,
    };
}
