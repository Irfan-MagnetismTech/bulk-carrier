import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useChartererInvoice() {
	const router = useRouter();
	const chartererInvoices = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const chartererInvoice = ref({
		business_unit: '',
		ops_charterer_profile_id: '',
		opsChartererProfile: null,
		ops_charterer_contract_id: '',
		opsChartererContract: null,
		ops_voyage_id: '',
		opsVoyage: null,
		contract_type: '',
		cargo_quantity: 0,
        bill_from: '',
        bill_till: '',
        total_days: '',
        total_amount: '',
        others_billable_amount: '',
		others_billable_amount_usd: '',
		sub_total_amount: '',
		sub_total_amount_usd: '',
		service_fee_deduction_amount: '',
		service_fee_deduction_amount_usd: '',
		discount_unit: '',
		discounted_amount: '',
		discounted_amount_usd: '',
		grand_total: '',
		grand_total_usd: '',
		opsChartererInvoiceServices: [
			{
				charge_or_deduct: 'deduct',
				particular: '',
				cost_unit: '',
				currency: '',
				quantity: 0,
				rate: 0,
				exchange_rate_bdt: 0,
				exchange_rate_usd: 0,
				amount: 0,
				amount_bdt: 0,
				amount_usd: 0,
			},
		],
		opsChartererInvoiceOthers: [
			{
				charge_or_deduct: 'charge',
				particular: '',
				cost_unit: '',
				currency: '',
				quantity: 0,
				rate: 0,
				exchange_rate_bdt: 0,
				exchange_rate_usd: 0,
				amount: 0,
				amount_bdt: 0,
				amount_usd: 0,
			},
		],
		opsChartereInvoiceVoyages: [{
			'ops_voyage_id': '',
			'opsVoyage': null,
			'cargo_quantity': 0,
			'rate_per_mt': 0,
			'total_amount': 0,
		}],
	});


	const chartererInvoiceVoyageObject = {
		'ops_voyage_id': '',
		'opsVoyage': null,
		'cargo_quantity': 0,
		'rate_per_mt': 0,
		'total_amount': 0,
	};

	const serviceObject = {
		charge_or_deduct: 'deduct',
		particular: '',
		cost_unit: '',
		currency: '',
		quantity: 0,
		rate: 0,
		exchange_rate_bdt: 0,
		exchange_rate_usd: 0,
		amount: 0,
		amount_bdt: 0,
		amount_usd: 0,
	};

	const otherObject = {
		charge_or_deduct: 'charge',
		particular: '',
		cost_unit: '',
		currency: '',
		quantity: 0,
		rate: 0,
		exchange_rate_bdt: 0,
		exchange_rate_usd: 0,
		amount: 0,
		amount_bdt: 0,
		amount_usd: 0,
	};


	const errors = ref(null);
	const isLoading = ref(false);

	async function getChartererInvoices(page, businessUnit) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/charterer-invoices', {
				params: {
					page: page || 1,
					business_unit: businessUnit
				}
			});
			chartererInvoices.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//NProgress.done();
			loader.hide();
			isLoading.value = false;
		}
	}

	async function storeChartererInvoice(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			let formData = new FormData();
			
			form.opsChartererInvoiceLines.map((element, index) => {
				formData.append('attachments['+index+']', element.attachment ?? null);
				element.attachment = null;
			})


			formData.append('info', JSON.stringify(form));

			const { data, status } = await Api.post('/ops/charterer-invoices', formData);
			notification.showSuccess(status);
			// router.push({ name: 'ops.charterer-invoices.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showChartererInvoice(chartererInvoiceId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/charterer-invoices/${chartererInvoiceId}`);
			chartererInvoice.value = data.value;
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

	async function updateChartererInvoice(form, chartererInvoiceId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			let formData = new FormData();
			
			form.opsChartererInvoiceLines.map((element, index) => {
				formData.append('attachments['+index+']', element.attachment ?? null);
				element.attachment = null;
			})


			formData.append('info', JSON.stringify(form));
			formData.append('_method', 'PUT');

			const { data, status } = await Api.post(
				`/ops/charterer-invoices/${chartererInvoiceId}`,
				formData
			);
			// chartererInvoice.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.charterer-invoices.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteChartererInvoice(chartererInvoiceId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/charterer-invoices/${chartererInvoiceId}`);
			notification.showSuccess(status);
			await getChartererInvoices();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchChartererInvoices(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-charterer-invoices?name=${searchParam}`);
			chartererInvoices.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false)
			//NProgress.done();
		}
	}

	return {
		chartererInvoices,
		chartererInvoice,
		getChartererInvoices,
		storeChartererInvoice,
		showChartererInvoice,
		updateChartererInvoice,
		deleteChartererInvoice,
		chartererInvoiceVoyageObject,
		searchChartererInvoices,
		serviceObject,
		otherObject,
		isLoading,
		errors,
	};
}
