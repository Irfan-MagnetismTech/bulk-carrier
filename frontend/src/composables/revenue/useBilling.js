import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useBilling() {
    const router = useRouter();
    const bills = ref([]);
    const $loading = useLoading();
    const billNo = ref([]);
    const notification = useNotification();
    const bill = ref( {
        bill_no : '',
        bill_date : '',
        credit_days : '',
        due_date : '',
        customer_id : '',
        customer_code: '',
        customer_name: '',
        billing_name : '',
        billing_address : '',
        billing_contact : '',
        billing_email : '',
        billing_cc_emails : '',
        billing_from : '',
        billing_till : '',
        amount : '',
        vat_percentage: '',
        vat_amount: '',
        sub_total: '',
        grand_total: '',
        unpaid_bills: [],
        previous_unpaid_bills: [],
    });

    const errors = ref('');
    const isLoading = ref(false);
    const unpaidBills = ref([]);
    const customerBills = ref([]); // Individual customer's all bills

    async function getBills() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/revenue/customer-bills');
            bills.value = data.value;
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

    async function storeBill(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/revenue/customer-bills', form);
            bill.value = data.value;
            notification.showSuccess(status);
            router.go({ name: "revenue.billing.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showBill(billId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/customer-bills/${billId}`);
            bill.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateBill(form, billId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;
        
        try {
            const { data, status } = await Api.put(
                `/revenue/customer-bills/${billId}`,
                form
            );
            bill.value = data.value;
            notification.showSuccess(status);
            router.go({ name: "revenue.billing.index" });

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteBill(billId) {

        if (!confirm('Are you sure you want to delete this bill?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/revenue/customer-bills/${billId}`);
            notification.showSuccess(status);
            await getBills();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchBill(searchParam) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/search-bill-with-vehicle`,  {params: { searchParam: searchParam }});
            bills.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getCustomerBills(customerId) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/unpaid-customer-bills`,  {params: { customer_id: customerId }});
            customerBills.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
        }
    }
    
    async function getPendingBills(from, till, customerId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/revenue/pending-bills`,  {params: { billing_from: from, billing_till: till, customer_id: customerId }});
            unpaidBills.value = data;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function downloadBill(billId) {
        NProgress.start();
        isLoading.value = true;

        axios({
            url: '/revenue/download-customer-bill/' + billId,
            data: billId,
            method: 'GET',
            responseType: 'blob', // important
        }).then((response) => {
            let dateTime = new Date();

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

    async function getBillByBillNo(bill_no) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                '/common/get-bill-by-bill-no',
                { bill_no }
            );
            billNo.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        bills,
        bill,
        searchBill,
        billNo,
        getBills,
        customerBills,
        getCustomerBills,
        storeBill,
        showBill,
        updateBill,
        deleteBill,
        getBillByBillNo,
        isLoading,
        errors,
        unpaidBills,
        getPendingBills,
        downloadBill
    };
}
