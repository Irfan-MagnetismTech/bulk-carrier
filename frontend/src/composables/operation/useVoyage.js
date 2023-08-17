import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function useVoyage() {
    const router = useRouter();
	const voyages = ref([]);
	const $loading = useLoading();
	const voyageName = ref([]);
	const voyageCustomers = ref([]);
	const notification = useNotification();
	const lastVoyageId = ref([]);
	const rateType = ref('');
	const exchangeRate = ref('');
	const voyage = ref({
		sender: '',
        recipient: '',
        voyage: '',
		previous_voyage_id: null,
		previous_voyage_name: null,
		voyage_no: '',
        vessel_id: '',
        port_origin_name: null,
        port_origin: '',
        port_discharge_name: null,
        port_discharge: '',
		next_port_name: null,
		next_port: '',
        sailing_date: '',
        service_id: '',
		voyage_sectors: [],
		bound: '',
        export_rotation: '',
        import_rotation: '',
        document_no: '',
        message_reference_no: '',
        time_of_preparation: '',
        message_compilation_time: '',
        message_type: 'D:95B',
        departure_date: '',
        arrival_date: '',
        berthing_date: '',
        import_pilot_boarding_time: '',
        export_pilot_boarding_time: '',
        vessel: '',
        service: '',
        exchange_rate: '',
		status: '',
		load_type: 1,
		vvd: '',
		voyage_schedules: [{
			port_code: '',
			port_code_name: '',
			eta_date: '',
			etb_date: '',
			etd_date: '',
			actual_arrival_date: '',
			actual_berth_date: '',
			actual_departure_date: '',
			commence_discharge: '',
			completed_discharge: '',
			commence_load: '',
			completed_load: '',
		}],
	});
    const containers = ref([]);
	const errors = ref(null);
	const isLoading = ref(false);

	const advisory_customer = ref('');
	const advisory_customer_container = ref('');
	const advisory_agents = ref([]);
	const selectedCustomerAgents = ref({
		customer_id: [],
		agents: []
	});

	async function getLastVoyageId()
	{
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const {data, status} = await Api.get('/operation/voyages/last-id');
			lastVoyageId.value = data.value;
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally 
		{
			//NProgress.done();
			loader.hide();
			isLoading.value = false;
		}
	}

	async function getVoyages(page, form = null) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});

		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/operation/voyages', {
				params: {
					page: page || 1,
					voyage_id: form.voyage_id || null,
					vessel_name: form.vessel_name || null,
					service_id: form.service_id || null,
					port_origin: form.port_origin || null,
					port_discharge: form.port_discharge || null,
					etd_date: form.etd_date || null,
					eta_date: form.eta_date || null,
				},
			});
			voyages.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			//NProgress.done();
			loader.hide();
			isLoading.value = false;
		}
	}

	async function storeVoyage(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/operation/voyages', form);
			voyage.value = data.value;
			notification.showSuccess(status);
			await router.push({ name: "operation.voyages.index" });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function getAgents(form)
	{
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/operation/voyages/agents', form);
			advisory_agents.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			//NProgress.done();
			loader.hide();
			isLoading.value = false;
		}
	}

	async function showVoyage(voyageId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/operation/voyages/${voyageId}`);
			voyage.value = data.value;
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

	function showVoyageWithNo(voyageNo) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		Api.get(`operation/voyages/voyage-no/${voyageNo}`)
			.then((response) => {
				voyages.value = response.data.value;
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				loader.hide();
				isLoading.value = false;
				//NProgress.done();
			});
	}

	async function updateVoyage(form, voyageId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/operation/voyages/${voyageId}`,
				form
			);
			//voyage.value = data.value;
			notification.showSuccess(status);
			router.push({ name: "operation.voyages.index" });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteVoyage(voyageId) {
		if (!confirm('Are you sure you want to delete this voyage?')) {
			return;
		}
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/operation/voyages/${voyageId}`);
			notification.showSuccess(status);
			await getVoyages();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function clearVoyage(voyageId) {
		if (!confirm('This will clear all containers data related to this voyage.')) {
			return;
		}
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get( `/operation/voyages/clear/${voyageId}`);
			notification.showSuccess(status);
			await getVoyages();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status, null, data.message);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteVoyageContainer(containerId,voyageId) {
		if (!confirm('Are you sure you want to delete this voyage container?')) {
			return;
		}
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/operation/containers/${containerId}`);
			await getContainersByVoyage(voyageId);
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

    // Gets full details of voyage by voyageId (Temporary)
    function getVoyageDetails(voyageId) {
        //NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;
		Api.get(`operation/voyages/get-voyage-details/${voyageId}`)
			.then((response) => {
				voyage.value = response.data.value;
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				loader.hide();
				isLoading.value = false;
				//NProgress.done();
			});
    }

    /*
    * Gets all containers of voyage by voyageId
    * params: voyageId
    */
   async function getContainersByVoyage(voyageId) {
        //NProgress.start();
	   const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            const { data } = await Api.get(
				`operation/voyage-containers/${voyageId}`
			);
            containers.value = data.value.containers;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
			loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
   }

	async function getVoyageCustomerContainers(voyageCustomerId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;
		try {
			const { data } = await Api.get(
				`operation/voyage-customer-containers/${voyageCustomerId}`
			);
			containers.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

    function sendNotification(data) {
        //NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        Api.post(`operation/voyages/send-notification`, data)
            .then((res) => {
                console.log(res.data.value);
                console.log("Successfully sent notification.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
				loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

	function sendAdvisoryMail(data) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        Api.post(`operation/voyages/send-advisory-mail`, data)
            .then((res) => {
                console.log(res.data.value);
                console.log("Successfully sent notification.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
				loader.hide();
                isLoading.value = false;
                //NProgress.done();
        });
	}

	async function getCustomerAgentsAndContainers(data)
	{
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		Api.post(`operation/voyages/get-customer-agents-and-containers`, data)
			.then((response) => {
				// customerAgentsAndContainers.value = response.data.value;
				// console.table(response.data);
				advisory_agents.value = response.data.value.agents;
				advisory_customer.value = response.data.value.customer_info;
				advisory_customer_container.value = response.data.value.customers_self;
				selectedCustomerAgents.value.customer_id = []
				selectedCustomerAgents.value.agents = []

			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				loader.hide();
				isLoading.value = false;
				//NProgress.done();
			});
	}

	async function getCustomerAgentsAndContainersByAdvisoryType(data)
	{
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		Api.post(`operation/voyages/get-customer-agents-and-containers-by-advisory-type`, data)
			.then((response) => {
				advisory_agents.value = response.data.value.agents;
				advisory_customer.value = response.data.value.customer_info;
				advisory_customer_container.value = response.data.value.customers_self;
				selectedCustomerAgents.value.customer_id = []
				selectedCustomerAgents.value.agents = []
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				loader.hide();
				isLoading.value = false;
				//NProgress.done();
			});
	}

	async function getVoyageName(voyage_no, ...filters) {
		console.log('getVoyageName', voyage_no)
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;
		let customFilters = [...filters];

		try {
			const { data } = await Api.post(
				'operation/get/voyage/name',
				{ voyage_no, customFilters }
			);
			//voyages.value = data.value;
			voyageName.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function getVoyageCustomer(voyage_id) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'operation/get/voyage/customer',
				{ voyage_id }
			);
			voyageCustomers.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function getRateTypeOfVoyageCustomer(form){
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('operation/get/rate-type/voyage/customer', form);
			rateType.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function voyageExchangeRate(form){

		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('operation/voyage-exchange-rate', form);
			exchangeRate.value = data.value;
			notification.showSuccess(status);
			//rateType.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	return {
		voyages,
		voyageName,
		voyage,
		voyageCustomers,
        containers,
		voyageExchangeRate,
		lastVoyageId,
		advisory_agents,
		advisory_customer,
		advisory_customer_container,
		selectedCustomerAgents,
		getVoyages,
		getAgents,
		getLastVoyageId,
		storeVoyage,
		showVoyage,
		showVoyageWithNo,
		getVoyageCustomerContainers,
		updateVoyage,
		deleteVoyage,
		clearVoyage,
        getVoyageDetails,
        getContainersByVoyage,
        sendNotification,
		sendAdvisoryMail,
		getCustomerAgentsAndContainers,
		getCustomerAgentsAndContainersByAdvisoryType,
		getVoyageName,
		getVoyageCustomer,
		deleteVoyageContainer,
		getRateTypeOfVoyageCustomer,
		exchangeRate,
		isLoading,
		rateType,
		errors,
	};
}
