import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from "sweetalert2";

export default function useTransaction() {
    const router = useRouter();
    const transactions = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const bgColor = ref('');
    const notification = useNotification();
    const transaction = ref( {
        acc_cost_center_id: '',
        acc_cost_center_name: '',
        voucher_type: '',
        transactionable_id: '',
        transactionable_type: '',
        transaction_date: '',
        bill_no: '',
        mr_no: '',
        narration: '',
        instrument_type: '',
        instrument_no: '',
        instrument_date: '',
        business_unit: '',
        total_debit_amount: 0.0,
        total_credit_amount: 0.0,
        ledgerEntries: [
            {
                acc_transaction_id: '',
                acc_balance_and_income_line_id: '',
                acc_cost_center_id: '',
                acc_cost_center_name: '',
                acc_account_id: '',
                acc_account_name: '',
                dr_amount: '',
                cr_amount: '',
                ref_bill: '',
                bill_status: '',
                purpose: '',
                remarks: '',
            }
        ]
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getTransactions(filterOptions) {

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
            const {data, status} = await Api.get(`/acc/acc-transactions`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });

            transactions.value = data.value;
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

    async function storeTransaction(form) {

        if(!checkCreditAndDebitAmount(form)){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.post('/acc/acc-transactions', form);
                transaction.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "acc.transactions.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function showTransaction(transactionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-transactions/${transactionId}`);
            transaction.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateTransaction(form, transactionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/acc/acc-transactions/${transactionId}`,
                form
            );
            transaction.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.transactions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteTransaction(transactionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-transactions/${transactionId}`);
            notification.showSuccess(status);
            await getTransactions(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    function checkCreditAndDebitAmount(form){
        const messages = ref([]);
        let isHasError = false;
        form.ledgerEntries?.forEach((item,index) => {
            if((parseFloat(item.cr_amount) === 0 && parseFloat(item.dr_amount) === 0) || parseFloat(item.cr_amount) > 0 && parseFloat(item.dr_amount) > 0){
                let data = `Ledger Entries [line :${index + 1}] Either Credit Amount or Debit Amount must be non-zero and can't be zero at once.`;
                messages.value.push(data);
            }
            if((parseFloat(form.total_credit_amount) !== parseFloat(form.total_debit_amount)) && index === (form.ledgerEntries.length - 1)){
                let data = `The total debit amount must match the total credit amount.`;
                messages.value.push(data);
            }
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
                    customClass: "swal-width error-message-text",
                });
                isHasError = true;
            }
        });
        return isHasError;
    }

    return {
        transactions,
        transaction,
        getTransactions,
        storeTransaction,
        showTransaction,
        updateTransaction,
        deleteTransaction,
        bgColor,
        isLoading,
        isTableLoading,
        errors,
    };
}
