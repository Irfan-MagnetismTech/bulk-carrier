import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';


export default function useInvoice() {
    const router = useRouter();
    const $loading = useLoading();
    const invoices = ref({});
    const customer = ref({});
    const invoiceCustomerList = ref([]);
    const invoiceContainers = ref([]);
    const tempInvoiceContainers = ref([]);
    const isInvoiceContainerModalOpen = ref(0);
    const invoiceNo = ref([]);
    const notification = useNotification();
    const errors = ref(null);
    const isLoading = ref(false);

    const formParams = ref( {
        voyage: null,
        voyage_id: null,
        customer_id: null,
        contract_id: '',
        customer_reference: '',
        customer_note: '',
        hrl_reference: '',
        hrl_internal_note: '',
        request_for: '',
        invoice_type: '',
        voyage_customer_id: '',
        is_model_open: true,
        sector: '',
        advance_invoices: [],
        debit_note: 0,
        sub_total: 0,
        total: 0,
        discount: 0,
        exchange_rate: 0.0,
        equivalent_in_bdt: 0.0,
        billing_party: '',
        billing_address: '',
        billing_emails: '',
        billing_style: ''
    });

    const invoice = ref({
        type: '',
        invoice_number: '',
        issue_date: '',
        currency: 'USD',
        voyage_id: '',
        customer_id: '',
        service_id: '',
        contract_id: '',
        contract_no: '',
        customer_reference: '',
        customer_note: '',
        hrl_reference: '',
        hrl_internal_note: '',
        amount: 0.0,
        vat: 0.0,
        ait: 0.0,
        discount: 0.0,
        round_off: 0.0,
        exchange_rate: 0.0,
        equivalent_in_bdt: 0.0,
        sub_total: 0.0,
        total: 0.0,
        selected_amount: 0.0,
        invoice_lines: [
            {
                id: Math.random(),
                description: '',
                selected_description: '',
                per: '',
                rate: '',
                quantity: '',
                sub_amount: 0.0,
                amount: 0.0,
            }
        ],
    });

    async function getInvoices(page, form = null) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/commercial/invoices', {
                params: {
                    page: page || 1,
                    voyage_id: form.voyage_id || null,
                    invoice_number: form.invoice_number || null,
                    contract_no: form.contract_no || null,
                    service_id: form.service_id || null,
                    customer_id: form.customer_id || null,
                    mailing_status: form.mailing_status || null,
                },
            });
            invoices.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            //notification.showError(status);
        } finally {
            NProgress.done();
            isLoading.value = false;
        }
    }

    async function showInvoice(invoiceId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/commercial/invoices/${invoiceId}`);
            invoice.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function downloadInvoice(invoiceId) {
        NProgress.start();
        isLoading.value = true;

        axios({
            url: '/commercial/download-customer-invoice/' + invoiceId,
            data: invoiceId,
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

            // const url = window.URL.createObjectURL(new Blob([response.data]));
            // const link = document.createElement('a');
            // link.href = url;
            // link.setAttribute('download', "Invoice-" + dateTime.toJSON().slice(0,10).split('-').reverse().join('-') + ".pdf");
            // document.body.appendChild(link);
            // link.click();
        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    async function sendInvoice(invoiceId) {
        if (!confirm('Are you sure you want to Send this invoice?')) {
            return;
        }
        //NProgress.start();

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(`/commercial/send-customer-invoice/${invoiceId}`);
            // invoice.value = data.value;
            // notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    function storeInvoice(form) {
        NProgress.start();
        isLoading.value = true;

        Api.post("commercial/invoices", form)
            .then(() => {
                invoice.value = {};
                router.push({ name: "commercial.advance.invoice" });
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    function updateInvoice(form) {
        NProgress.start();
        isLoading.value = true;

        Api.put(`commercial/invoices/${form.id}`, form)
            .then(() => {
                invoice.value = {};
                notification.showSuccess(202, "Invoice Updated Successfully");
                //router.push({ name: "commercial.advance.invoice" });
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    async function eligibleCustomerForInvoice(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/commercial/eligible-customer-for-invoice',form);
            invoices.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function generateInvoice(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/commercial/generate-invoice',form.value);
            invoices.value = {};
            router.push({ name: "commercial.invoice.list" });
        } catch (error) {
            const { data, status } = error.response;
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getCustomerDetails(customerId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/commercial/invoice/customer/' + customerId);
            customer.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getInvoiceCustomerList(voyage_id) {

        if(!voyage_id){
            notification.showError(422,'', "Please select voyage");
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        //NProgress.start();
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                'commercial/get-invoice-customer-list',
                { voyage_id }
            );
            invoiceCustomerList.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function getInvoiceByInvoiceNo(invoice_number) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                'commercial/invoice/get-invoice-by-invoice-no',
                { invoice_number }
            );
            invoiceNo.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getInvoiceContainers(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/commercial/get-invoiced-containers',form);
            invoiceContainers.value = data.value;
            tempInvoiceContainers.value = data.value;
            isInvoiceContainerModalOpen.value = true;
        } catch (error) {
            const { data, status } = error.response;
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function storeInvoiceNote(form) {
        NProgress.start();
        isLoading.value = true;

        let totalRevisedAmount = 0;

        try {
            let dataForm = {
                parent_invoice_id: form.parent_invoice_id,
                type: form.type,
                amount: form.amount,

                customer_reference: form.customer_reference,
                customer_note: form.customer_note,
                hrl_reference: form.hrl_reference,
                hrl_internal_note: form.hrl_internal_note,
                billing_emails: form.billing_emails,
                billing_party: form.billing_party,
                billing_address: form.billing_address,
                billing_style: form.billing_style,
                invoice_lines: [],
            }

            form.invoice_lines.forEach((line, lineIndex) => {
                if (line.revised_amount !== 0) {
                    line.adjusted_amount = line.revised_amount;
                    totalRevisedAmount += parseFloat(line.revised_amount);
                    dataForm.invoice_lines.push(line);
                }
            });

            dataForm.amount = totalRevisedAmount;

            if(dataForm.invoice_lines.length === 0) {
                notification.showError(422,'','Sorry! No new data found to update.');
                return;
            }
            //let temp = 0;
            dataForm.invoice_lines.forEach((line, lineIndex) => {
                dataForm.invoice_lines[lineIndex].invoice_containers.forEach((container, containerIndex) => {

                    if(container.amount === 0) {

                        alert('Sorry! Container amount can not be zero.');
                        dataForm.invoice_lines[lineIndex].invoice_containers.splice(containerIndex, 1);
                    }
                });
                //temp += parseFloat(line.adjusted_amount);
            });

            //console.log(dataForm);

            //dataForm.amount = temp;

            let formData = new FormData();
            formData.append('attachment', form.attachment);
            formData.append('data', JSON.stringify(dataForm));

            const { data, status } = await Api.post('/commercial/store-invoice-note',formData);
            invoices.value = {};
            notification.showSuccess(status,'Invoice Note created successfully.');
            router.push({ name: "commercial.invoice.list" });
        } catch (error) {
            const { data, status } = error.response;
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        formParams,
        invoice,
        customer,
        invoices,
        invoiceNo,
        getInvoices,
        updateInvoice,
        showInvoice,
        getInvoiceByInvoiceNo,
        getInvoiceContainers,
        downloadInvoice,
        sendInvoice,
        storeInvoice,
        getInvoiceCustomerList,
        storeInvoiceNote,
        eligibleCustomerForInvoice,
        invoiceContainers,
        getCustomerDetails,
        invoiceCustomerList,
        tempInvoiceContainers,
        isInvoiceContainerModalOpen,
        generateInvoice,
        isLoading,
        errors,
    };
}
