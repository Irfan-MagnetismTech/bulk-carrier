import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Swal from 'sweetalert2';
import cloneDeep from 'lodash/cloneDeep';


export default function useAppraisalForm() {
    const router = useRouter();
    const appraisalForms = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const appraisalFormLineObject ={
                            section_name: '',
                            is_tabular: 1,
                            section_no: '',
                            appraisalFormLineItems: [
                                // {
                                //     aspect: '',
                                //     description: '',
                                //     answer_type: '',
                                // }
                            ],
                        };
    const appraisalForm = ref( {
        form_no: '',
        form_name: '',
        version: '',
        description: '',
        // appraisalFormLines: [{...appraisalFormLineObject}],
        appraisalFormLines: [cloneDeep(appraisalFormLineObject)],
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getAppraisalForms(filterOptions) {
        //NProgress.start();
        let loader = null;
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;

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
            const {data, status} = await Api.get('/crw/appraisal-forms',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            appraisalForms.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            //NProgress.done();
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

    async function storeAppraisalForm(form) {

        if (!checkUniqueArray(form)) return;

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/appraisal-forms', form);
            appraisalForm.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.appraisal-forms.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showAppraisalForm(appraisalFormId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/appraisal-forms/${appraisalFormId}`);
            appraisalForm.value = data.value;
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

    async function updateAppraisalForm(form, appraisalFormId) {
        if (!checkUniqueArray(form)) return;

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/appraisal-forms/${appraisalFormId}`,
                form
            );
            appraisalForm.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.appraisal-forms.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteAppraisalForm(appraisalFormId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/appraisal-forms/${appraisalFormId}`);
            notification.showSuccess(status);
            await getAppraisalForms(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            // notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    } 

    function checkUniqueArray(form) {
        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.appraisalFormLines.some((appraisalFormLine, index) => {
            if (form.appraisalFormLines.filter(val => val.section_name === appraisalFormLine.section_name)?.length > 1) {
                let data = `Duplicate Section Name [Section no: ${index + 1}]`;
                messages.value.push(data);
                form.appraisalFormLines[index].isSectionDuplicate = true;
            } else {
                form.appraisalFormLines[index].isSectionDuplicate = false;
            }

            appraisalFormLine.appraisalFormLineItems.some((appraisalFormLineItem, appraisalFormLineItemIndex) => {
                if (appraisalFormLine.appraisalFormLineItems.filter(val => val.aspect === appraisalFormLineItem.aspect)?.length > 1) {
                    let data = `Duplicate Aspect Name [Section no: ${index + 1} , Aspect no: ${appraisalFormLineItemIndex+1}]`;
                    messages.value.push(data);
                    appraisalFormLine.appraisalFormLineItems[appraisalFormLineItemIndex].isAspectDuplicate = true;
                } else {
                    appraisalFormLine.appraisalFormLineItems[appraisalFormLineItemIndex].isAspectDuplicate = false;
                }
            });
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

    async function getAppraisalFormsWithoutPaginate(businessUnit) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            'business_unit': businessUnit,
        }

        try {
            const { data, status } = await Api.post('/crw/get-appraisal-forms', form);
            appraisalForms.value = data.value;

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }
    

    return {
        appraisalForms,
        appraisalForm,
        appraisalFormLineObject,
        getAppraisalForms,
        storeAppraisalForm,
        showAppraisalForm,
        updateAppraisalForm,
        deleteAppraisalForm,
        getAppraisalFormsWithoutPaginate,
        isLoading,
        isTableLoading,
        errors,
    };
}
