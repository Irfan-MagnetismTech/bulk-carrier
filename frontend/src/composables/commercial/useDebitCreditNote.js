import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useMoneyReceipt() {
    const router = useRouter();
    const receipts = ref({});
    //const receipt = ref({});
    const notification = useNotification();
    const error = ref([]);
    const isLoading = ref(false);

    const formParams = ref( {
        money_receipt_no: null,
    });
    const errors = ref(null);

    const receipt = ref({
        id: Math.random(),
        customer_code: null,
        customer_name: '',
        received_date: '',
        customer_id: null,
        invoice_id: null,
        money_receipts_no: '',
        collected_amount: 0.0,
        remarks: '',
        attachment: null,
        amount: 0.0,
        due_amount: 0.0,
        money_receipt_methods: [
            {
                id: Math.random(),
                money_receipt_id: '',
                payment_method: '',
                source_name: '',
                trx_no: '',
                dated: '',
                amount: 0.0,
                sub_amount: 0.0,
            }
        ],
    });

    function getReceipts() {
        NProgress.start();
        isLoading.value = true;
        Api.get("/money-receipts")
            .then((response) => {
                receipts.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    async function storeReceipt(form) {
        NProgress.start();
        isLoading.value = true;
        let formData = new FormData();
        formData.append('attachment', form.attachment);
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('/money-receipts', formData);
            receipt.value = data.value;
            notification.showSuccess(status,data.message);
            router.push({ name: "money.receipt.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function updateMoneyReceipt(form, receiptId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/money-receipts/${receiptId}`,
                form
            );
            receipt.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "money.receipt.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function showMoneyReceipt(receiptId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/money-receipts/${receiptId}`);
            receipt.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function deleteMoneyReceipt(receiptId) {
        if (!confirm('Are you sure you want to delete this money receipt?')) {
            return;
        }
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/money-receipts/${receiptId}`);
            notification.showSuccess(status);
            await getReceipts();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function downloadMoneyReceipt(receiptId) {
        NProgress.start();
        isLoading.value = true;

        axios({
            url: '/download-money-receipt/' + receiptId,
            data: receiptId,
            method: 'GET',
            responseType: 'blob', // important
        }).then((response) => {
            let dateTime = new Date();

            // const url = window.URL.createObjectURL(new Blob([response.data]));
            // const link = document.createElement('a');
            // link.href = url;
            // link.setAttribute('download', "Money-Receipt-" + dateTime.toJSON().slice(0,10).split('-').reverse().join('-') + ".pdf");
            // document.body.appendChild(link);
            // link.click();

            //stream pdf file to new tab start
            let fileURL = URL.createObjectURL(response.data);
            let a = document.createElement('a');
            a.href = fileURL;
            a.target = '_blank';
            a.click();
            //stream pdf file to new tab end


        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    return {
        formParams,
        receipt,
        receipts,
        getReceipts,
        storeReceipt,
        updateMoneyReceipt,
        showMoneyReceipt,
        deleteMoneyReceipt,
        downloadMoneyReceipt,
        isLoading,
        errors,
    };
}