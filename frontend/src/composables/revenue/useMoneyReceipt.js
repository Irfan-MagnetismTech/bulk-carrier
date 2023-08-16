import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMoneyReceipt() {
    const router = useRouter();
    const moneyReceipts = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const paymentReceiptModel = {
        payment_method: '',
        source_name: '',
        trx_no: '',
        dated: '',
        amount: ''
    }

    const receiptLineModel = {
        customer_bill_id: '',
        amount: ''
    }
    const moneyReceipt = ref( {
        sequence: '',
        issue_date: '',
        customer_id: '',
        amount: '',
        remarks: '',
        attachment: null,
        moneyReceiptMethods: [{
            ... paymentReceiptModel
        }],
        moneyReceiptLines: [{
            ... receiptLineModel
        }]
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getMoneyReceipts() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/revenue/money-receipts');
            moneyReceipts.value = data.value;
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

    async function storeMoneyReceipt(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('/revenue/money-receipts', formData);
            moneyReceipt.value = data.value;
            notification.showSuccess(status);
            router.go({ name: "revenue.money-receipt.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMoneyReceipt(moneyReceiptId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/money-receipts/${moneyReceiptId}`);
            moneyReceipt.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function editMoneyReceipt(moneyReceiptId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/money-receipts/${moneyReceiptId}/edit`);
            moneyReceipt.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }
    async function updateMoneyReceipt(form, moneyReceiptId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post(
                `/revenue/money-receipts-update/${moneyReceiptId}`,
                formData
            );
            moneyReceipt.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "revenue.money-receipt.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMoneyReceipt(moneyReceiptId) {

        if (!confirm('Are you sure you want to delete this moneyReceipt?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/revenue/money-receipts/${moneyReceiptId}`);
            notification.showSuccess(status);
            await getMoneyReceipts();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMoneyReceipt(searchParam) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/search-money-receipt`, {params: { searchParam: searchParam }});
            moneyReceipts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function downloadMoneyReceipt(receiptId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        axios({
            url: '/revenue/download-money-receipt/' + receiptId,
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
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        });
    }

    return {
        paymentReceiptModel,
        receiptLineModel,
        moneyReceipts,
        moneyReceipt,
        getMoneyReceipts,
        searchMoneyReceipt,
        storeMoneyReceipt,
        showMoneyReceipt,
        editMoneyReceipt,
        updateMoneyReceipt,
        deleteMoneyReceipt,
        downloadMoneyReceipt,
        isLoading,
        errors,
    };
}