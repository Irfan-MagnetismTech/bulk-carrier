import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from "sweetalert2";

export default function usePayrollBatch() {
    const router = useRouter();
    const payrollBatches = ref([]);
    const monthlyAttendance = ref({});
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const payrollBatch = ref( {
        business_unit: '',
        ops_vessel_id: '',
        ops_vessel_name: '',
        year_month: '',
        compensation_type: 'salary',
        process_date: '',
        net_payment: 0,
        currency: 'BDT',
        working_days: 0,
        crwPayrollBatchLines: [],
        crwPayrollBatchHeads: [],
        crwPayrollBatchHeadLines: [],
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getPayrollBatches(filterOptions) {

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
            const {data, status} = await Api.get('/crw/crw-incidents',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            payrollBatches.value = data.value;
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

    async function storePayrollBatch(form) {

        console.log("DATA: "  ,form);

        //for addition
        let additionBatchHeadLines = form.crwPayrollBatchHeadLines.filter(item => item.head_type === 'addition');

        if(additionBatchHeadLines){
            form.crwPayrollBatchHeads.filter(item => item.head_type === 'addition').forEach((batchHead,batchHeadIndex) => {
                batchHead.crwPayrollBatchHeadLines = [];
                additionBatchHeadLines.filter(item => item.head_type === 'addition').forEach((batchHeadLine,batchHeadLineIndex) => {

                    let obj = {
                        crew_id: batchHeadLine?.crew_id,
                        head_type: batchHeadLine?.head_type,
                        amount: batchHeadLine.crew_batch_heads[batchHeadIndex].amount,
                        particular: batchHead?.head_name,
                    };
                    batchHead.crwPayrollBatchHeadLines.push(obj);
                });
            });
        }

        //for deduction
        let deductionBatchHeadLines = form.crwPayrollBatchHeadLines.filter(item => item.head_type === 'deduction');

        if(deductionBatchHeadLines){
            form.crwPayrollBatchHeads.filter(item => item.head_type === 'deduction').forEach((batchHead,batchHeadIndex) => {
                batchHead.crwPayrollBatchHeadLines = [];
                deductionBatchHeadLines.filter(item => item.head_type === 'deduction').forEach((batchHeadLine,batchHeadLineIndex) => {
                    let obj = {
                        crew_id: batchHeadLine?.crew_id,
                        head_type: batchHeadLine?.head_type,
                        amount: batchHeadLine.crew_batch_heads[batchHeadIndex].amount,
                        particular: batchHead?.head_name,
                    };
                    batchHead.crwPayrollBatchHeadLines.push(obj);
                });
            });
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-payroll-batches', form);
            payrollBatch.value = data.value;
            notification.showSuccess(status);
            //await router.push({ name: "crw.crewPayrollBatches.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showPayrollBatch(crewPayrollBatchId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-incidents/${crewPayrollBatchId}`);
            payrollBatch.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePayrollBatch(form, crewPayrollBatchId) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            let formData = new FormData();
            formData.append('attachment', form.attachment);
            formData.append('data', JSON.stringify(form));
            formData.append('_method', 'PUT');

            try {
                const { data, status } = await Api.post(
                    `/crw/crw-incidents/${crewPayrollBatchId}`,
                    formData
                );
                payrollBatch.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.crewPayrollBatches.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function deletePayrollBatch(crewPayrollBatchId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-incidents/${crewPayrollBatchId}`);
            notification.showSuccess(status);
            await getPayrollBatches(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    function checkUniqueArray(form){
        const itemNamesSet = new Set();
        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.crwIncidentParticipants.some((item,index) => {
            if (itemNamesSet.has(item.crw_crew_name)) {
                let data = `Duplicate Crew Name [line no: ${index + 1}]`;
                messages.value.push(data);
                form.crwIncidentParticipants[index].isCrewNameDuplicate = true;
            } else {
                form.crwIncidentParticipants[index].isCrewNameDuplicate = false;
            }
            itemNamesSet.add(item.crw_crew_name);
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

    async function getMonthlyAttendance(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.post('/crw/get-crw-monthly-attendances', form);
            monthlyAttendance.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        payrollBatches,
        payrollBatch,
        getPayrollBatches,
        storePayrollBatch,
        showPayrollBatch,
        updatePayrollBatch,
        deletePayrollBatch,
        checkUniqueArray,
        getMonthlyAttendance,
        monthlyAttendance,
        isTableLoading,
        isLoading,
        errors,
    };
}
