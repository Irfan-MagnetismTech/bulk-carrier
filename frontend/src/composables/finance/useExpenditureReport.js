import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useExpenditureReport() {
    const router = useRouter();
    const notification = useNotification();
	const isLoading = ref(false);
	const errors = ref(null);
    const expenseHeads = ref([]);
    const expenseEntries = ref([]);
    const formParams = ref( {
        port_name: '',
        service_id: '',
        date_from: '',
        date_to: '',
    });
    const report = ref(null)

    async function getExpenditureReport(formData)
    {
        NProgress.start();
		isLoading.value = true;
		try {
			const { data } = await Api.post(
				'finance/expenditure-report',
				{ port: formData.port_name, service: formData.service_id, start: formData.date_from, end: formData.date_to }
			);
			report.value = data.view;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    async function getProfitabilityReport(formData) {
        NProgress.start();
		isLoading.value = true;
		try {
			const { data } = await Api.post(
				'finance/budget-vs-expense-report',
				formData
			);
			report.value = data.view;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    async function getRevenuevsExpenseReport(formData) {
        NProgress.start();
		isLoading.value = true;
		try {
			const { data } = await Api.post(
				'finance/revenue-vs-expense-report',
				formData
			);
			report.value = data.view;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    function downloadSingleRevenueVsExpense(voyagePairId) {
		NProgress.start();
		isLoading.value = true;
        
		Api.get(`finance/dl-pdf-revenue-vs-expense-report/${voyagePairId}`, {
            responseType: 'blob'
        })
			.then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                
                fileLink.href = fileURL;
                fileLink.setAttribute('download', `Revenue and Expense Details ${voyagePairId}.pdf`);
                document.body.appendChild(fileLink);
              
                fileLink.click();

				console.log('File Downloaded.');
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}

	function downloadSingleRevenueVsExpenseExcel(voyagePairId) {
		NProgress.start();
		isLoading.value = true;
        
		Api.get(`finance/dl-excel-revenue-vs-expense-report/${voyagePairId}`, {
            responseType: 'blob'
        })
			.then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                
                fileLink.href = fileURL;
                fileLink.setAttribute('download', `Revenue and Expense Details ${voyagePairId}.xlsx`);
                document.body.appendChild(fileLink);
              
                fileLink.click();

				console.log('File Downloaded.');
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}

    async function downloadExpenditureReport(formData) {

        NProgress.start();
		isLoading.value = true;
		try {
			const { data } = await Api.post(
				'finance/download-expenditure-report',
				{ port: formData.port_name, service: formData.service_id, start: formData.date_from, end: formData.date_to },
                {responseType: 'blob'}
                
                ).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    let dateTime = new Date();
                    
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', `ExpenseReport.xlsx`);
                    document.body.appendChild(fileLink);
                  
                    fileLink.click();
    
                });
			report.value = data.view;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    async function downloadExpenseScheduleReport(formData) {

        NProgress.start();
		isLoading.value = true;
		try {
			const { data } = await Api.post(
				'finance/download-expense-schedule',
				{ port: formData.port_name, service: formData.service_id, start: formData.date_from, end: formData.date_to },
                {responseType: 'blob'}
                
                ).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    let dateTime = new Date();
                    
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', `Expense Schedule.pdf`);
                    document.body.appendChild(fileLink);
                  
                    fileLink.click();
    
                });
			report.value = data.view;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

	async function downloadProfitabilityPdfReport(formData) {

        NProgress.start();
		isLoading.value = true;
		try {
			const { data } = await Api.post(
				'finance/dl-revenue-vs-expense-pdf-report',
				formData,
                {responseType: 'blob'}
                
                ).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    let dateTime = new Date();
                    
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', `VoyageProfitabilty.pdf`);
                    document.body.appendChild(fileLink);
                  
                    fileLink.click();
    
                });
			report.value = data.view;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    return {
        errors,
        formParams,
        getExpenditureReport,
        getRevenuevsExpenseReport,
        downloadExpenditureReport,
        downloadExpenseScheduleReport,
        getProfitabilityReport,
        report,
        expenseHeads,
        expenseEntries,
		downloadSingleRevenueVsExpense,
		downloadSingleRevenueVsExpenseExcel,
		downloadProfitabilityPdfReport
    }
}