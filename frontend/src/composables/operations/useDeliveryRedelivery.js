import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useDeliveryRedelivery() {
	const router = useRouter();
	const deliveryRedeliveries = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const deliveryRedelivery = ref({
		ops_vessel_id: '',
		ops_charterer_profile_id: '',
		note_type: '',
		effective_date: '',
		exchange_rate: '',
		currency: '',
		business_unit: '',
		opsBunkers: []

	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getDeliveryRedeliverys(page, businessUnit) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/delivery-redelivery', {
				params: {
					page: page || 1,
					business_unit: businessUnit
				}
			});
			deliveryRedeliveries.value = data.value;
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

	async function storeDeliveryRedelivery(form) {
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

			const { data, status } = await Api.post('/ops/delivery-redelivery', formData);
			deliveryRedelivery.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.delivery-redelivery.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showDeliveryRedelivery(deliveryRedeliveryId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/delivery-redelivery/${deliveryRedeliveryId}`);
			deliveryRedelivery.value = data.value;
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

	async function updateDeliveryRedelivery(form, deliveryRedeliveryId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			let formData = new FormData();
			formData.append('attachment', form.attachment);
			formData.append('info', JSON.stringify(form));
			formData.append('_method', 'PUT');

			const { data, status } = await Api.post(
				`/ops/delivery-redelivery/${deliveryRedeliveryId}`,
				formData
			);
			deliveryRedelivery.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.delivery-redelivery.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteDeliveryRedelivery(deliveryRedeliveryId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/delivery-redelivery/${deliveryRedeliveryId}`);
			notification.showSuccess(status);
			await getDeliveryRedeliverys();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchDeliveryRedeliverys(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-delivery-redelivery?name=${searchParam}`);
			deliveryRedeliveries.value = data.value;
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
		deliveryRedeliveries,
		deliveryRedelivery,
		getDeliveryRedeliverys,
		storeDeliveryRedelivery,
		showDeliveryRedelivery,
		updateDeliveryRedelivery,
		deleteDeliveryRedelivery,
		searchDeliveryRedeliverys,
		isLoading,
		errors,
	};
}
