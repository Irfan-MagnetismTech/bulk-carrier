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
    const isTableLoading = ref(false);
	const notification = useNotification();

	const chartererInvoice = ref({
		business_unit: 'PSML',
		ops_charterer_profile_id: null,
		opsChartererProfile: null,
		ops_charterer_contract_id: null,
		opsChartererContract: null,
		ops_voyage_id: null,
		opsVoyage: null,
		contract_type: null,
		cargo_quantity: 0,
        bill_from: null,
        bill_till: null,
        total_days: null,
		total_amount: null,
		per_day_charge: 0,
        others_billable_amount: null,
		others_billable_amount_usd: null,
		sub_total_amount: null,
		sub_total_amount_usd: null,
		service_fee_deduction_amount: null,
		service_fee_deduction_amount_usd: null,
		discount_unit: null,
		discounted_amount: null,
		discounted_amount_usd: null,
		grand_total: null,
		grand_total_usd: null,
		opsChartererInvoiceServices: [
			{
				charge_or_deduct: 'deduct',
				particular: null,
				cost_unit: null,
				currency: null,
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
				particular: null,
				cost_unit: null,
				currency: null,
				quantity: 0,
				rate: 0,
				exchange_rate_bdt: 0,
				exchange_rate_usd: 0,
				amount: 0,
				amount_bdt: 0,
				amount_usd: 0,
			},
		],
		opsChartererInvoiceVoyages: [{
			'ops_voyage_id': null,
			'opsVoyage': null,
			'cargo_quantity': 0,
			'rate_per_mt': 0,
			'total_amount': 0,
		}],
	});


	const chartererInvoiceVoyageObject = {
		'ops_voyage_id': null,
		'opsVoyage': null,
		'cargo_quantity': 0,
		'rate_per_mt': 0,
		'total_amount': 0,
	};

	const serviceObject = {
		charge_or_deduct: 'deduct',
		particular: null,
		cost_unit: null,
		currency: null,
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
		particular: null,
		cost_unit: null,
		currency: null,
		quantity: 0,
		rate: 0,
		exchange_rate_bdt: 0,
		exchange_rate_usd: 0,
		amount: 0,
		amount_bdt: 0,
		amount_usd: 0,
	};

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

	async function getChartererInvoices(filterOptions) {
		//NProgress.start();
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
			const {data, status} = await Api.get(`/ops/charterer-invoices`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
			chartererInvoices.value = data.value;
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

	async function storeChartererInvoice(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			let formData = new FormData();

			formData.append('info', JSON.stringify(form));

			const { data, status } = await Api.post('/ops/charterer-invoices', formData);
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
			await getChartererInvoices(filterParams.value);
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
		isTableLoading,
		otherObject,
		isLoading,
		errors,
	};
}
