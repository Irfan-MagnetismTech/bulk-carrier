import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useAdvanceAdjustment() {
    const router = useRouter();
    const advanceAdjustments = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const advanceAdjustment = ref( {
        acc_cost_center_name : '',
        acc_cost_center_id : '',
        acc_cash_requisition_id : '',
        adjustment_date : '',
        adjustment_amount : '',
        business_unit : '',
        accAdvanceAdjustmentLines: [
            {
                acc_advance_adjustment_id: '',
                particular: '',
                amount: '',
                attachment: null,
                remarks: '',
                isParticularDuplicate: false
            }
        ]
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getAdvanceAdjustments(filterOptions) {
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
            const {data, status} = await Api.get('/acc/acc-advance-adjustments',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                }
            });
            advanceAdjustments.value = data.value;
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

    async function storeAdvanceAdjustment(form) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.post('/acc/acc-advance-adjustments', form);
                advanceAdjustment.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "acc.advance-adjustments.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function showAdvanceAdjustment(advanceAdjustmentId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-advance-adjustments/${advanceAdjustmentId}`);
            advanceAdjustment.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateAdvanceAdjustment(form, advanceAdjustmentId) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.put(`/acc/acc-advance-adjustments/${advanceAdjustmentId}`, form);
                advanceAdjustment.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "acc.advance-adjustments.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function deleteAdvanceAdjustment(advanceAdjustmentId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-advance-adjustments/${advanceAdjustmentId}`);
            notification.showSuccess(status);
            await getAdvanceAdjustments(filterParams.value);
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
        const hasDuplicates = form.accAdvanceAdjustmentLines.some((item,index) => {
            if (itemNamesSet.has(item.particular)) {
                let data = `Duplicate Particular [line no: ${index + 1}]`;
                messages.value.push(data);
                form.accAdvanceAdjustmentLines[index].isParticularDuplicate = true;
            } else {
                form.accAdvanceAdjustmentLines[index].isParticularDuplicate = false;
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
        advanceAdjustments,
        advanceAdjustment,
        getAdvanceAdjustments,
        storeAdvanceAdjustment,
        showAdvanceAdjustment,
        updateAdvanceAdjustment,
        deleteAdvanceAdjustment,
        checkUniqueArray,
        isLoading,
        isTableLoading,
        errors,
    };
}
