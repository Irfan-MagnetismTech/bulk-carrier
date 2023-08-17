import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function useCurrency() {
	const router = useRouter();
	const $loading = useLoading();
	const notification = useNotification();
	const currencies = ref([]);
	const currency = ref({
		code: '',
		name: '',
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getCurrency(page = 1) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const {data, status} = await Api.get('/dataencoding/currencies', {
				params: {
					page: page || 1,
				},
			});
			currencies.value = data.value;
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

	async function storeCurrency(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/dataencoding/currencies', form);
			currencies.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'dataencoding.currency.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);		
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showCurrency(currencyId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/dataencoding/currencies/${currencyId}`);
			currency.value = data.value;
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

	async function updateCurrency(form, currencyId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/dataencoding/currencies/${currencyId}`,
				form
			);
			notification.showSuccess(status);
			router.push({ name: 'dataencoding.currency.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteCurrency(currencyId) {
        if (!confirm('Are you sure you want to delete this currency?')) {
            return;
        }
        
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/dataencoding/currencies/${currencyId}`);
			notification.showSuccess(status);
            await getCurrency();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	function getCurrencyWithoutPaginate() {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		Api.get("dataencoding/currencies/without/paginate")
			.then((response) => {
				currencies.value = response.data.value;
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

	return {
		currencies,
		currency,
		getCurrency,
		storeCurrency,
		showCurrency,
		updateCurrency,
		deleteCurrency,
		getCurrencyWithoutPaginate,
		isLoading,
		errors,
	};
}
