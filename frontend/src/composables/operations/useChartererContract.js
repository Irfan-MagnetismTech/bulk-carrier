import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';
import moment from 'moment';
import Swal from "sweetalert2";

export default function useChartererContract() {
	const router = useRouter();
	const chartererContracts = ref([]);
	const voyages = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const opsChartererLocalAgentObject = {
		port_code: '',
		agent_name: '',
		agent_billing_name: '',
		agent_billing_email: '',
	}

	const chartererContract = ref({
		contract_name: '',
		contract_type: '',
		business_unit: '',
		ops_vessel_id: null,
		vessel_owner: '',
		ops_charterer_profile_id: null,
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
		opsChartererContractsFinancialTerms: {
			credit_days: null,
			billing_cycle: '',
			valid_from: null,
			valid_till: null,
			per_day_charge: null,
			cleaning_fee: null,
			cancellation_fee: null,
			others_fee: null,
			ops_voyage_id: null,
			per_ton_charge: null,
			bunker_provider: '',
			ops_cargo_type_id: null,
			loading_point: '',
			final_unloading_point: '',
			approximate_load_amount: null,
			opsCargoTariff: null
		},
		opsChartererContractsLocalAgents: [{
			...opsChartererLocalAgentObject
		}]
	});
	const errors = ref(null);
	const isLoading = ref(false);	
	const indexPage = ref(null);
	const indexBusinessUnit = ref(null);
    const filterParams = ref(null);
	const isTableLoading = ref(false);

	async function getChartererContracts(filterOptions) {
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

		indexPage.value = filterOptions.page;
		indexBusinessUnit.value = filterOptions.business_unit;
        filterParams.value = filterOptions;

		try {
			const { data, status } = await Api.get('/ops/charterer-contracts', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				}
			});
			chartererContracts.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
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

	async function storeChartererContract(form) {
		//NProgress.start();

		let date1 = moment(form.opsChartererContractsFinancialTerms.valid_from);
		let date2 = moment(form.opsChartererContractsFinancialTerms.valid_till);
		if ( date2.diff(date1, "days") <= 0 ) {
			Swal.fire({
				icon: "",
				title: "Correct Please!",
				html: `Invalid Contract Validity.`,
				customClass: "swal-width",
			});
			return false;
		}

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
		
		let date1 = moment(form.opsChartererContractsFinancialTerms.valid_from);
		let date2 = moment(form.opsChartererContractsFinancialTerms.valid_till);
		if ( date2.diff(date1, "days") <= 0 ) {
			Swal.fire({
				icon: "",
				title: "Correct Please!",
				html: `Invalid Contract Validity.`,
				customClass: "swal-width",
			});
			return false;
		}

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
			// chartererContract.value = data.value;
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
			await getChartererContracts(filterParams.value);
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

	async function getChartererContractsByCharterOwner(chartererProfileId, opsVesselId  = '', filter = '') {
		try {
			const { data, status } = await Api.get(`/ops/get-charterer-contract-by-profile?ops_charterer_profile_id=${chartererProfileId}&ops_vessel_id=${opsVesselId}`);

			if(filter != '') {
				const filteredData = data.value.filter(item => item.status == filter);

				chartererContracts.value = filteredData;
			} else {
				chartererContracts.value = data.value;
			}
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//loader.hide();
			//isLoading.value = false;
			//NProgress.done();
		}
	}

	//get contract wise voyage
	async function getContractWiseVoyage(contractId) {
		try {
			const { data, status } = await Api.get(`/ops/get-voyage-by-contract?contract_id=${contractId}`);
			voyages.value = data.value;
			notification.showSuccess(status);
			return data.value;
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			//loader.hide();
			//isLoading.value = false;
			//NProgress.done();
		}
	}


	async function downloadChartererContractAttachment(chartererContactId) {
		axios({
            url: 'ops/export-particular-report?id=' + chartererContactId,
            method: 'GET',
            responseType: 'blob', // important
        }).then((response) => {

            //stream pdf file to new tab start
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download',  "- Attachment" + "");
            document.body.appendChild(link);
            link.click();
            //stream pdf file to new tab end
        }).catch((error) => {
            if (error.response.status === 422) {
                const reader = new FileReader();
                reader.onload = function() {
                    const data = JSON.parse(reader.result);
                    const message = data.message;
                    console.log("Response message: " + message);
                    notification.showError(error.response.status, '', message);
                }
                reader.readAsText(error.response.data);
            } else {
                notification.showError(error.response.status, '', error.response.statusText);
            }
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
	}
	
	return {
		chartererContracts,
		chartererContract,
		opsChartererLocalAgentObject,
		getChartererContracts,
		storeChartererContract,
		showChartererContract,
		updateChartererContract,
		deleteChartererContract,
		searchChartererContracts,
		getChartererContractsByCharterOwner,
		getContractWiseVoyage,
		downloadChartererContractAttachment,
		chartererContracts,
		voyages,
		isLoading,		
		isTableLoading,
		errors,
	};
}
