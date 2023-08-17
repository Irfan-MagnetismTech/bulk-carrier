import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function useVoyageExpense() {
    const router = useRouter();
    const expenses = ref([]);
    const presets = ref([]);
    const entryType = ref('');
    const notification = useNotification();
	const isLoading = ref(false);
	const errors = ref(null);
    const categories = ref([]);
    const expenseInvoices = ref([]);
    const globalExpense = ref(null)
    const voyagPairName = ref(null)
    const expense = ref( {
        voyage_id: '',
        port: null,
        date: null,
        type: 'expense',
        entries: [
            // {
            //     voyage_expenditure_sub_head: '',
            //     voyage_expenditure_sub_head_id: '',
            //     invoice_no: '',
            //     invoice_date: '',
            //     currency: '',
            //     amount: '',
            //     remarks: '',
            // }
        ],
    });

    const expenseInvoice = ref();
    let headWiseExpenseFormParams = ref( {
        port: '',
        port_code_name: null,
        entries: [
            {
                expense_head: '',
                expense_head_id: '',
                voyage_id: '',
                invoice_date: '',
                invoice_no: '',
                currency: 'USD',
                amount: '',
                remarks: '',
                voyage_name: ''
            }
        ],
    });

    async function getExpenseCategories(category, port) {
        NProgress.start();
        isLoading.value = true;
        categories.value = [];

        try {
            const { data, status } = await Api.post(
                '/finance/search-expenditure-head', 
            {
                    subhead: category,
                    port: port
                }
            );
            categories.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
			notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }
    

    async function getExpenseInvoices(page, columns = null, searchKey = null, table = null, relation = null) {
        NProgress.start();
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/finance/expense-invoices', {
				params: {
					page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                    relation: relation || null,
				},
			});
            expenseInvoices.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getPortPresets(port, voyage)
    {
        // We can leverage voyage info to get vessel name to get vessel presets if there's any
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(
                '/finance/get-port-presets', 
            {
                    port: port,
                    voyage: voyage
                }
            );
            console.log(data.value);
            // assign response in arrays
            presets.value = data.value;
            entryType.value = data.type
            // expense.value.entries;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getVoyageExpense() {
        NProgress.start();
        isLoading.value = true;
        
        try {
            const { data, status } = await Api.get('/finance/port-expenditure-heads');
            expenses.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
			errors.value = notification.showError(status);
        } finally {
            NProgress.done();
			isLoading.value = false;
        }
    }

    async function getInvoiceDetails(expenseInvoiceId) {
        NProgress.start();
        isLoading.value = true;
        
        try {
            const { data, status } = await Api.get('/finance/expense-invoices/'+expenseInvoiceId);
            expenseInvoice.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status);
        } finally {
            NProgress.done();
            isLoading.value = false;
        }
    }

    async function updateSingleInvoice(id, form, port, summery, type) {
        NProgress.start();
        isLoading.value = true;

        console.log("summery",summery)

        let formData = new FormData();

        formData.append('attachment', summery.attachment)
        
        formData.append('summery', JSON.stringify(summery));
        formData.append('entries', JSON.stringify(form));
        formData.append('port', port);

        try {
            console.log('formdata', formData)
            const { data, status } = await Api.post('/finance/update-expense-invoices/'+id, formData);
            expense.value = data.value;
            notification.showSuccess(status);

            console.log('invoice', data.invoice)
            console.log('count', data.count)
            if(type == 'print') {
                if(data.count > 1) {
                    downloadExpenseInvoiceByGroup(data.invoice.entry_group)
                } else {
                    downloadExpenseInvoice(data.invoice.id)
                }
                router.push({ name: "finance.voyages.index" });
                
            } else {
                router.push({ name: "finance.voyages.index" });
            }
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function storeVoyageExpense(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/finance/voyage-expenses', form);
            expense.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.voyages.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    } 

    async function storeHeadWiseExpense(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/finance/head-wise-expenses', form);
            expense.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.voyages.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function storeExpenseEntry(form, port, summery, type) {
        NProgress.start();
        isLoading.value = true;

        let formData = new FormData();

        for (const [key, value] of Object.entries(summery)) {
            formData.append(value.invoice, value.attachment)
        }
        
        formData.append('summery', JSON.stringify(summery));
        formData.append('entries', JSON.stringify(form));
        formData.append('port', JSON.stringify(port));

        try {
            const { data, status } = await Api.post('/finance/save-expense', formData);
            expense.value = data.value;
            notification.showSuccess(status);

            console.log('invoice', data.invoice)
            console.log('count', data.count)
            if(type == 'print') {
                if(data.count > 1) {
                    downloadExpenseInvoiceByGroup(data.invoice.entry_group)
                } else {
                    downloadExpenseInvoice(data.invoice.id)
                }
                router.push({ name: "finance.voyages.index" });
                
            } else {
                router.push({ name: "finance.voyages.index" });
            }
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }

    }

    async function showVoyageExpense(voyageId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/finance/voyage-expenses/${voyageId}`);
            expense.value = data.value;
            globalExpense.value = data.globalHeads;
            voyagPairName.value = data.combined_name;
            notification.showSuccess(status);

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function updateVoyageExpense(form, headId) {
        NProgress.start();
        isLoading.value = true;
        try {
            const { data, status } = await Api.put(
                `/finance/port-expenditure-heads/${headId}`,
                form
            );
            expense.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.voyages.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function deleteVoyageExpense(headId) {
        if (!confirm('Are you sure you want to delete this voyage expense?')) {
            return;
        }
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/finance/port-expenditure-heads/${headId}`);
            notification.showSuccess(status);
            await getVoyageExpense();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function deleteExpenseInvoice(invoiceId) {
        if (!confirm("Are you sure you want to delete this Invoice's data?")) {
            return;
        }
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/finance/voyage-expenses/${invoiceId}`);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
			await getExpenseInvoices();
            isLoading.value = false;
            NProgress.done();

        }
         
    }

    async function downloadExpenseInvoice(invoiceId) {
        NProgress.start();
        isLoading.value = true;

        axios({
            url: '/finance/download-expense-invoice/' + invoiceId,
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
        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    async function downloadExpenseInvoiceByGroup(invoiceId) {
        NProgress.start();
        isLoading.value = true;

        axios({
            url: '/finance/download-expense-invoice-group/' + invoiceId,
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
        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    return {
        errors,
        notification,
        expense,
        expenses,
        presets,
        entryType,
        storeVoyageExpense,
        storeHeadWiseExpense,
        storeExpenseEntry,
        showVoyageExpense,
        headWiseExpenseFormParams,
        updateVoyageExpense,
        deleteVoyageExpense,
        isLoading,
        categories,
        getExpenseCategories,
        getPortPresets,
        getExpenseInvoices,
        expenseInvoices,
        expenseInvoice,
        getInvoiceDetails,
        downloadExpenseInvoice,
        downloadExpenseInvoiceByGroup,
        globalExpense,
        updateSingleInvoice,
        voyagPairName,
        deleteExpenseInvoice
    }
}