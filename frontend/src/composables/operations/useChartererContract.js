import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useChartererContract() {
	const router = useRouter();
	const chartererContracts = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const chartererContract = ref({
		contract_type: 'Voyage Wise',
		business_unit: '',
		ops_vessel_id: '',
		vessel_owner: '',
		ops_charterer_profile_id: '',
		charterer_code: '',
		country: '',
		address: '',
		billing_address: '',
		email: '',
		contact_no: '',
		bank_name: '',
		bank_branch_name: '',
		bank_account_no: '',
		bank_account_name: '',
		swift_code: '',
		routing_no: '',
		currency: '',
		port_code: '',
		agent_name: '',
		agent_billing_name: '',
		agent_billing_email: '',
		status: '',
		chartererContractsFinancialTerms: {
			credit_days: '',
			billing_cycle: '',
			valid_from: '',
			valid_till: '',
			per_day_charge: '',
			cleaning_fee: '',
			cancellation_fee: '',
			others_fee: '',
			ops_voyage_id: '',
			per_ton_charge: '',
			bunker_provider: '',
			ops_cargo_type_id: '',
			loading_point: '',
			final_unloading_point: '',
			approximate_load_amount: '',
		},
		chartererContractsAgents: {
			port_code: '',
			agent_name: '',
			agent_billing_name: '',
			agent_billing_email: '',
		}
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getChartererContracts(page, businessUnit) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/charterer-contracts', {
				params: {
					page: page || 1,
					business_unit: businessUnit
				}
			});
			chartererContracts.value = data.value;
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

	async function storeChartererContract(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			let formData = new FormData();

			// form.opsVoyageCertificates.map((element) => {
			// 	element.ops_maritime_certification_id = element.id
			// 	element.business_unit = form.business_unit
			// })

			// form.opsBunkers.map((element) => {
			// 	element.scm_material_id = element.id
			// })

			formData.append('attachment', form.attachment);
			formData.append('info', JSON.stringify(form));

			const { data, status } = await Api.post('/ops/charterer-contracts', formData);
			chartererContract.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.charterer-contracts.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showChartererContract(chartererContractId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/charterer-contracts/${chartererContractId}`);
			chartererContract.value = data.value;
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

	async function updateChartererContract(form, chartererContractId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			let formData = new FormData();
			formData.append('attachment', form.attachment);
			formData.append('info', JSON.stringify(form));
			formData.append('_method', 'PUT');

			const { data, status } = await Api.post(
				`/ops/charterer-contracts/${chartererContractId}`,
				formData
			);
			chartererContract.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.charterer-contracts.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteChartererContract(chartererContractId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/charterer-contracts/${chartererContractId}`);
			notification.showSuccess(status);
			await getChartererContracts();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchChartererContracts(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-charterer-contracts?name=${searchParam}`);
			chartererContracts.value = data.value;
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
		chartererContracts,
		chartererContract,
		getChartererContracts,
		storeChartererContract,
		showChartererContract,
		updateChartererContract,
		deleteChartererContract,
		searchChartererContracts,
		isLoading,
		errors,
	};
}
