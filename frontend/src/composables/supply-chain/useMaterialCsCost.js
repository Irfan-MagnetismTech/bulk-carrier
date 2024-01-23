import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Store from '../../store/index.js';
import NProgress from 'nprogress';
import useHelper from '../useHelper.js';
import { merge } from 'lodash';
import { loaderSetting as LoaderConfig} from '../../config/setting.js';
import Swal from 'sweetalert2';

export default function useMaterialCsCost() {
    const BASE = 'scm' 
    const router = useRouter();
    const materialCsLists = ref([]);
    const filteredMaterialCs = ref([]);
    const quotations = ref([]);
    const csWiseVendorList = ref([]);
    const filteredMaterialCsLines = ref([]);
    const $loading = useLoading();
    const prMaterialList = ref([]);
    const isTableLoading = ref(false);
    const notification = useNotification();
    // const LoaderConfig = {'can-cancel': false, 'loader': 'dots', 'color': 'purple'};

    const materialCs = ref({
        scmCs: null,
        scm_cs_id: null,
        scmVendor: null,
        scm_vendor_id: null,
        scmCsVendor: null,
        scm_cs_vendor_id: null,
        business_unit: null,
        type: null,
        status: null,
        total_cost: 0.0,
        market_rate: 0.0,
        name_of_bank: null,
        cfr_value:0.00,
        lc_margin:0.00,
        bank_commission:0.00,
        vat:0.00,
        others:0.00,
        insurence_premium:0.00,
        document_value:0.00,
        exchange_rate:0.00,
        insurence_company: null,
        business_unit: null,
    });

    const materialObj = {
                scmPr: null,
                scm_pr_id: null,
                scm_material_id: null,
                scmMaterial: null,
                max_quantity: null,
                pr_composite_key: null,
                unit : null,
                quantity : null,
    }

    const materialList = ref([]);


    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

    async function getMaterialCs(filterOptions) {
        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show(LoaderConfig);
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
            const {data, status} = await Api.get(`/${BASE}/material-cs`,{
                params: {
                    data: JSON.stringify(filterOptions)
                }
            });
            materialCsLists.value = data.value;
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
    async function storeMaterialCs(form) {
        if (!checkUniqueArray(form)) return;

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(`/${BASE}/material-cs`, formData);
            materialCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-cs.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterialCs(materialCsId) {
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/${BASE}/material-cs/${materialCsId}`);
            materialCs.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterialCs(form, materialCsId) {
        if (!checkUniqueArray(form)) return;

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        let formData = new FormData();
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(`/${BASE}/material-cs/${materialCsId}`, formData);
            materialCs.value = data.value;
            notification.showSuccess(status);
            router.push({ name: `${BASE}.material-cs.index` });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterialCs(materialCsId) {

        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/${BASE}/material-cs/${materialCsId}`);
            notification.showSuccess(status);
            await getMaterialCs(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    // async function searchMaterialCs(searchParam, loading) {
        

    //     try {
    //         const {data, status} = await Api.get(`/${BASE}/search-material-cs`,searchParam);
    //         filteredMaterialCs.value = data.value;
    //     } catch (error) {
    //         const { data, status } = error.response;
    //         notification.showError(status);
    //     } finally {
    //         loading(false)
    //     }
    // }


    async function getCsData(id) {
        //NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/get-cs-data/${id}`);
            materialCs.value = data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }
   


    async function getPrWiseCs(prId) {
        //NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/get-pr-wise-cs-data`,{
                params: {
                    pr_id: prId,
                },
            });
            materialCs.value =  merge(materialCs.value, data.value);
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }


    async function getQuotations(csId) {
        // NProgress.start();
        const loader = $loading.show(LoaderConfig);
        isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/get-quotations`,{
                params: {
                    cs_id: csId,
                },
            });
            quotations.value = data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            isLoading.value = false;
            loader.hide();
        }
    }

    function checkUniqueArray(form) {
        let isHasError = false;
        const messages = ref([]);
        let materialArray = [];
        form.scmCsMaterials.map((scmCsMaterial, scmCsMaterialIndex) => {
            let material_key = (scmCsMaterial?.scm_material_id ?? '') + "-" + (scmCsMaterial?.scm_pr_id ?? '');
            console.log(material_key,scmCsMaterial);
            if (materialArray.indexOf(material_key) === -1) {
                materialArray.push(material_key);
                form.scmCsMaterials[scmCsMaterialIndex].isAspectDuplicate = false;
              } else {
                let data = `Duplicate Material Name In Row ${scmCsMaterialIndex + 1}`;
                messages.value.push(data);
                form.scmCsMaterials[scmCsMaterialIndex].isAspectDuplicate = true;
              }
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


    async function getPrWiseMaterialList(prId) {
        //NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/search-pr-wise-material-for-cs`,{
                params: {
                    pr_id: prId,
                },
            });
            prMaterialList.value = data.value;
            return data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }
 

    async function searchCs(business_unit, scm_warehouse_id = null, purchase_center = null, searchParam = '') { 
        //NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/search-material-cs`,{
                params: {
                    business_unit: business_unit,
                    scm_warehouse_id: scm_warehouse_id,
                    purchase_center: purchase_center,
                    searchParam: searchParam,
                },
            });
            filteredMaterialCs.value = data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            //NProgress.done();
        }
    }

    async function getCsWiseVendorList(csId) {
        // NProgress.start();
        // const loader = $loading.show(LoaderConfig);
        // isLoading.value = true;
        try {
            const {data, status} = await Api.get(`/${BASE}/cs-wise-vendor-list`,{
                params: {
                    cs_id: csId,
                },
            });
            csWiseVendorList.value = data.value;
            return data.value;
        } catch (error) {
            console.log('tag', error)
        } finally {
            // isLoading.value = false;
            // loader.hide();
        }
    }



    return {
        materialCs,
        materialCsLists,
        filteredMaterialCs,
        searchCs,
        getMaterialCs,
        storeMaterialCs,
        showMaterialCs,
        updateMaterialCs,
        deleteMaterialCs,
        getPrWiseCs,
        getQuotations,
        quotations,
        materialObj,
        prMaterialList,
        materialList,
        getPrWiseMaterialList,
        getCsData,
        getCsWiseVendorList,
        csWiseVendorList,
        // getSiWiseData,
        isTableLoading,
        isLoading,
        errors,
    };
}
