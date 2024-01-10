import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Swal from 'sweetalert2';
import cloneDeep from 'lodash/cloneDeep';


export default function useAppraisalRecord() {
    const router = useRouter();
    const appraisalRecords = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const appraisalRecord = ref({
        business_unit: '',
        crw_crew_id: '',
        crw_crew_profile: '',
        age: 0,
        // completed_job_id: '',
        // completed_job: '',
        appraisal_form_id: '',
        appraisal_form: '', 
        crw_crew_assignment: '',
        crw_crew_assignment_id: '',
        total_marks: '',
        obtained_marks: '',
        appraisal_date: '',
        appraisalRecordLines: [{
            appraisalFormLine: {},
            appraisalRecordLineItems: [],
        }],
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getAppraisalRecords(filterOptions) {
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
            const {data, status} = await Api.get('/crw/appraisal-records',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            appraisalRecords.value = data.value;
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

    async function storeAppraisalRecord(form) {

        // if (!checkUniqueArray(form)) return;

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/appraisal-records', form);
            appraisalRecord.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.appraisal-records.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showAppraisalRecord(appraisalRecordId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/appraisal-records/${appraisalRecordId}`);
            appraisalRecord.value = data.value;
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

    async function updateAppraisalRecord(form, appraisalRecordId) {
        // if (!checkUniqueArray(form)) return;

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/appraisal-records/${appraisalRecordId}`,
                form
            );
            appraisalRecord.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.appraisal-records.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteAppraisalRecord(appraisalRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/appraisal-records/${appraisalRecordId}`);
            notification.showSuccess(status);
            await getAppraisalRecords(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            // notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    } 

    // function checkUniqueArray(form) {
    //     let isHasError = false;
    //     const messages = ref([]);
    //     const hasDuplicates = form.appraisalFormLines.some((appraisalFormLine, index) => {
    //         if (form.appraisalFormLines.filter(val => val.section_name === appraisalFormLine.section_name)?.length > 1) {
    //             let data = `Duplicate Section Name [Section no: ${index + 1}]`;
    //             messages.value.push(data);
    //             form.appraisalFormLines[index].isSectionDuplicate = true;
    //         } else {
    //             form.appraisalFormLines[index].isSectionDuplicate = false;
    //         }

    //         appraisalFormLine.appraisalFormLineItems.some((appraisalFormLineItem, appraisalFormLineItemIndex) => {
    //             if (appraisalFormLine.appraisalFormLineItems.filter(val => val.aspect === appraisalFormLineItem.aspect)?.length > 1) {
    //                 let data = `Duplicate Aspect Name [Section no: ${index + 1} , Aspect no: ${appraisalFormLineItemIndex+1}]`;
    //                 messages.value.push(data);
    //                 appraisalFormLine.appraisalFormLineItems[appraisalFormLineItemIndex].isAspectDuplicate = true;
    //             } else {
    //                 appraisalFormLine.appraisalFormLineItems[appraisalFormLineItemIndex].isAspectDuplicate = false;
    //             }
    //         });
    //     });

    //     if (messages.value.length > 0) {
    //         let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
    //         if (Object.keys(messages.value).length) {
    //             for (const property in messages.value) {
    //                 rawHtml += `<li> ${messages.value[property]} </li>`
    //             }
    //             rawHtml += `</ul>`;

    //             Swal.fire({
    //                 icon: "",
    //                 title: "Correct Please!",
    //                 html: `
    //             ${rawHtml}
    //                     `,
    //                 customClass: "swal-width",
    //             });
    //             return false;
    //         }
    //     } else {
    //         return true;
    //     }
    // }
    

    return {
        appraisalRecords,
        appraisalRecord,
        getAppraisalRecords,
        storeAppraisalRecord,
        showAppraisalRecord,
        updateAppraisalRecord,
        deleteAppraisalRecord,
        isLoading,
        isTableLoading,
        errors,
    };
}
