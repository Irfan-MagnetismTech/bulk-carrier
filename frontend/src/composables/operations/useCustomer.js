import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useCustomer() {
	const router = useRouter();
	const customers = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	const customer = ref({
		code: '',
		legal_name: '',
		name: '',
		postal_address: '',
		city: '',
		post_code: '',
		country: '',
		tax_id: '',
		business_license_no: '',
		bin_gst_sst_type: '',
		bin_gst_sst_no: '',
		phone: '',
		company_reg_no: '',
		email_general: '',
		email_agreement: '',
		email_invoice: '',
		business_unit: ''
	});
	const errors = ref(null);
	const isLoading = ref(false);

	const indexPage = ref(null);
	const indexBusinessUnit = ref(null);
    const filterParams = ref(null);

	async function getCustomers(filterOptions) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		indexPage.value = filterOptions.page;
		indexBusinessUnit.value = filterOptions.business_unit;

		filterParams.value = filterOptions;

		try {
			const { data, status } = await Api.get('/ops/customers', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				 }
			});
			customers.value = data.value;
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

	async function storeCustomer(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/customers', form);
			customer.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.customers.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showCustomer(customerId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/customers/${customerId}`);
			customer.value = data.value;
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

	async function updateCustomer(form, customerId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/customers/${customerId}`,
				form
			);
			customer.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.customers.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteCustomer(customerId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/customers/${customerId}`);
			notification.showSuccess(status);
			await getCustomers(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	// Get ports by name or code
	async function getCustomersByNameOrCode(searchParam, businessUnit, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-customers?name_or_code=${searchParam}&business_unit=${businessUnit}`);
			customers.value = data.value;
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
		customers,
		customer,
		getCustomers,
		storeCustomer,
		showCustomer,
		updateCustomer,
		deleteCustomer,
		getCustomersByNameOrCode,
		isLoading,
		errors,
	};
}
