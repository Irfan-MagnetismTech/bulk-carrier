import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from "sweetalert2";
import cloneDeep from "lodash/cloneDeep";

export default function usePayrollBatch() {
    const router = useRouter();
    const payrollBatches = ref([]);
    const monthlyAttendance = ref({});
    const isTableLoading = ref(false);
    const isAttendanceCrewAvailable = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const payrollBatch = ref( {
        business_unit: '',
        ops_vessel_id: '',
        ops_vessel_name: '',
        crw_attendance_id: '',
        compensation_type: 'salary',
        process_date: '',
        net_payment: 0,
        currency: 'BDT',
        working_days: 0,
        total_crew: 0,
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
            const {data, status} = await Api.get('/crw/crw-payroll-batches',{
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

        const isUnique = checkUniqueArray(form);

        if(isUnique){
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

            form.net_payment = form.crwPayrollBatchLines.reduce((sum, item) => sum + parseFloat(item.net_payable_amount || 0), 0);

            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.post('/crw/crw-payroll-batches', form);
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

    async function showPayrollBatch(crewPayrollBatchId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-payroll-batches/${crewPayrollBatchId}`);

            //prepare data
            data.value.crwPayrollBatchLines.forEach((batchLine,batchLineIndex) => {
                batchLine.crw_full_name = batchLine?.crwCrew?.full_name;
                batchLine.crw_contact_no = batchLine?.crwCrew?.pre_mobile_no;
                batchLine.net_salary = batchLine?.crwSalaryStructure?.net_amount;
                batchLine.present_days = batchLine?.crwAttendanceLine?.present_days;
                batchLine.absent_days = batchLine?.crwAttendanceLine?.absent_days;
                batchLine.payable_days = batchLine?.crwAttendanceLine?.payable_days;
            });

            let previousHeadLines = data.value.crwPayrollBatchHeadLines;

            data.value.crwPayrollBatchHeadLines = [];
            if(data.value.crwPayrollBatchHeads.filter(item => item.head_type === 'addition').length){
                data.value.crwPayrollBatchLines.forEach((batchLine,batchLineIndex) => {
                    let batchLineObj = {
                        crew_id: batchLine?.crw_crew_id,
                        crew_name: batchLine?.crw_full_name,
                        crw_contact_no: batchLine?.crw_contact_no,
                        amount: '',
                        head_type: 'addition',
                        crew_batch_heads: cloneDeep(data.value.crwPayrollBatchHeads.filter(item => item.head_type === 'addition'))
                    }
                    data.value.crwPayrollBatchHeadLines.push(batchLineObj);
                });
            }

            if(data.value.crwPayrollBatchHeads.filter(item => item.head_type === 'deduction').length){
                data.value.crwPayrollBatchLines.forEach((batchLine,batchLineIndex) => {
                    let batchLineObj = {
                        crew_id: batchLine?.crw_crew_id,
                        crew_name: batchLine?.crw_full_name,
                        crw_contact_no: batchLine?.crw_contact_no,
                        amount: '',
                        head_type: 'deduction',
                        crew_batch_heads: cloneDeep(data.value.crwPayrollBatchHeads.filter(item => item.head_type === 'deduction'))
                    }
                    data.value.crwPayrollBatchHeadLines.push(batchLineObj);
                });
            }

            let grouped = {};

            previousHeadLines.forEach(item => {
                const key = `${item.head_type}_${item.crw_crew_id}`;
                if (!grouped[key]) {
                    grouped[key] = [];
                }
                grouped[key].push(item);
            });

            grouped = Object.keys(grouped).map(key => ({
                items: grouped[key]
            }));

            data.value.crwPayrollBatchHeadLines.forEach((batchHeadLine,batchHeadLineIndex) => {
                batchHeadLine.crew_batch_heads.forEach((crwItem,crwItemIndex) => {
                    crwItem.amount = grouped[batchHeadLineIndex]['items'][crwItemIndex].amount;
                });
                batchHeadLine.amount = batchHeadLine.crew_batch_heads.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);
            });
            //prepare data

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

            form.net_payment = form.crwPayrollBatchLines.reduce((sum, item) => sum + parseFloat(item.net_payable_amount || 0), 0);

            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.put(
                    `/crw/crw-payroll-batches/${crewPayrollBatchId}`,
                    form
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
            const { data, status } = await Api.delete( `/crw/crw-payroll-batches/${crewPayrollBatchId}`);
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
        const hasDuplicates = form.crwPayrollBatchHeads.some((item,index) => {
            if (itemNamesSet.has(item.head_name)) {
                let data = `Duplicate Head Name [line no: ${index + 1}]`;
                messages.value.push(data);
                form.crwPayrollBatchHeads[index].isHeadNameDuplicate = true;
            } else {
                form.crwPayrollBatchHeads[index].isHeadNameDuplicate = false;
            }
            itemNamesSet.add(item.head_name);
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
            if(monthlyAttendance){
                isAttendanceCrewAvailable.value = true;
            }
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
        isAttendanceCrewAvailable,
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