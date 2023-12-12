import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import {loaderSetting as LoaderConfig} from "../../config/setting";
import Swal from "sweetalert2";

export default function useFixedAsset() {
    const router = useRouter();
    const fixedAssets = ref([]);
    const filteredFixedAssets = ref([]);
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
        material_account_name: '',
        fixedAssetCosts: [
            {
                particular: '',
                amount: '',
                remarks: '',
                isParticularDuplicate: false
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

        const isUnique = checkUniqueArray(form);

        if(isUnique){
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

        const isUnique = checkUniqueArray(form);

        if(isUnique){
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

    async function searchFixedAsset(cost_center_id = null, business_unit=null) {

        //const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let form = {
            business_unit: business_unit,
            acc_cost_center_id: cost_center_id,
        };

        try {
            const { data, status } = await Api.post('/acc/get-fixed-assets', form);
            filteredFixedAssets.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }
    }

    function checkUniqueArray(form){
        const itemNamesSet = new Set();
        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.fixedAssetCosts.some((item,index) => {
            if (itemNamesSet.has(item.particular)) {
                let data = `Duplicate Particular [line no: ${index + 1}]`;
                messages.value.push(data);
                form.fixedAssetCosts[index].isParticularDuplicate = true;
            } else {
                form.fixedAssetCosts[index].isParticularDuplicate = false;
            }
            itemNamesSet.add(item.particular);
        });

        if (messages.value.length > 0) {
            let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
            if (Object.keys(messages.value).length) {
                for (const property in messages.value) {
                    rawHtml += `<li> ${messages.value[property]} </li>`
                }
                rawHtml += `</ul>`;

                Swal.fire({
                    icon: "",
                    title: "Correct Please!",
                    html: `
                ${rawHtml}
                        `,
                    customClass: "swal-width",
                });
                return false;
            }
        } else {
            return true;
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
        filteredFixedAssets,
        searchFixedAsset,
        checkUniqueArray,
        isLoading,
        isTableLoading,
        errors,
    };
}
