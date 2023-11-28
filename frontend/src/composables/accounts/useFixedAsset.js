import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';

export default function useFixedAsset() {
    const router = useRouter();
    const fixedAssets = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const fixedAsset = ref( {
        acc_cost_center_name : null,
        acc_cost_center_id : '',
        scm_mrr_name : null,
        scm_mrr_id : '',
        scm_material_name : null,
        scm_material_id : '',
        brand : '',
        model : '',
        serial : '',
        acc_parent_account_name : null,
        acc_parent_account_id : '',
        acc_account_name : null,
        acc_account_id : 1,
        asset_tag : '',
        useful_life : '',
        depreciation_rate : '',
        acquisition_date : '',
        location : '',
        business_unit : '',
        acquisition_cost: '',
        fixedAssetCosts: [
            {
                particular: '',
                amount: '',
                remarks: '',
            }
        ]
    });

    // const indexPage = ref(null);
    // const indexBusinessUnit = ref(null);
    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getFixedAssets(filterOptions) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
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

        // indexPage.value = filterOptions.page;
        // indexBusinessUnit.value = filterOptions.business_unit;
        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/acc/acc-fixed-assets',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                }
            });
            fixedAssets.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
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

    async function storeFixedAsset(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-fixed-assets', form);
            fixedAsset.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.fixed-assets.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showFixedAsset(fixedAssetId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-fixed-assets/${fixedAssetId}`);
            fixedAsset.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateFixedAsset(form, fixedAssetId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/acc/acc-fixed-assets/${fixedAssetId}`, form);
            fixedAsset.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.fixed-assets.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteFixedAsset(fixedAssetId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-fixed-assets/${fixedAssetId}`);
            notification.showSuccess(status);
            await getFixedAssets(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        fixedAssets,
        fixedAsset,
        getFixedAssets,
        storeFixedAsset,
        showFixedAsset,
        updateFixedAsset,
        deleteFixedAsset,
        isLoading,
        isTableLoading,
        errors,
    };
}
