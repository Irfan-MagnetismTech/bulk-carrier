import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';

export default function useDepreciation() {
    const router = useRouter();
    const depreciations = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const depreciation = ref( {
        month_year : null,
        applied_date : null,
        acc_cost_center_name : null,
        acc_cost_center_id : '',
        business_unit : '',
        depreciationLine: [
            {
                acc_fixed_asset_id: '',
                amount: '',
            }
        ]
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getDepreciations(filterOptions) {

        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;
            isTableLoading.value = false;
        }
        else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }

        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/acc/acc-depreciations',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                }
            });
            depreciations.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            }
            else {
                isTableLoading.value = false;
                loader?.hide();
            }
        }
    }

    async function storeDepreciation(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-depreciations', form);
            depreciation.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.depreciations.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showDepreciation(depreciationId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-depreciations/${depreciationId}`);
            depreciation.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateDepreciation(form, depreciationId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/acc/acc-depreciations/${depreciationId}`, form);
            depreciation.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.depreciations.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteDepreciation(depreciationId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-depreciations/${depreciationId}`);
            notification.showSuccess(status);
            await getDepreciations(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        depreciations,
        depreciation,
        getDepreciations,
        storeDepreciation,
        showDepreciation,
        updateDepreciation,
        deleteDepreciation,
        isLoading,
        isTableLoading,
        errors,
    };
}
